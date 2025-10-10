<?php

/**
 * Script pour créer automatiquement des attributs WooCommerce par mois
 * Version corrigée avec gestion d'erreurs améliorée
 */

// Configuration
$end_month = 12; // Décembre
$current_year = date('Y');
delete_option('weekend_attributes_script_executed_once');

function create_monthly_weekend_attributes($end_month, $current_year)
{
    global $wpdb;

    // Vérifier que WooCommerce est actif
    if (!function_exists('wc_create_attribute')) {
        return array(
            'success' => false,
            'message' => 'WooCommerce n\'est pas actif ou n\'est pas chargé.'
        );
    }

    // Générer tous les weekends groupés par mois
    $weekends_by_month = generate_weekends_by_month($end_month, $current_year);

    $created_attributes = 0;
    $created_terms = 0;
    $results = array();

    foreach ($weekends_by_month as $month_key => $data) {
        $month_name = $data['month_name'];
        $month_year = $data['month_year'];
        $weekends = $data['weekends'];

        // Créer le slug de l'attribut (ex: octobre-2025)
        $attribute_slug = sanitize_title($month_key);
        $attribute_label = $month_name . ' ' . $month_year;

        $attribute_existed = false;

        // Vérifier si l'attribut existe déjà en BDD directement
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT attribute_id, attribute_name FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s",
            $attribute_slug
        ));

        if (!$existing) {
            // L'attribut n'existe pas, on le crée directement en base de données
            $wpdb->insert(
                $wpdb->prefix . 'woocommerce_attribute_taxonomies',
                array(
                    'attribute_name' => $attribute_slug,
                    'attribute_label' => $attribute_label,
                    'attribute_type' => 'select',
                    'attribute_orderby' => 'menu_order',
                    'attribute_public' => 0
                ),
                array('%s', '%s', '%s', '%s', '%d')
            );

            if ($wpdb->last_error) {
                $results[] = '✗ Erreur BDD pour ' . $attribute_label . ': ' . $wpdb->last_error;
                continue;
            }

            $attribute_id = $wpdb->insert_id;

            // Vider le cache WooCommerce
            delete_transient('wc_attribute_taxonomies');

            // Enregistrer la taxonomie
            $taxonomy_name = wc_attribute_taxonomy_name($attribute_slug);
            register_taxonomy(
                $taxonomy_name,
                apply_filters('woocommerce_taxonomy_objects_' . $taxonomy_name, array('product')),
                apply_filters('woocommerce_taxonomy_args_' . $taxonomy_name, array(
                    'labels' => array(
                        'name' => $attribute_label,
                    ),
                    'hierarchical' => false,
                    'show_ui' => false,
                    'query_var' => true,
                    'rewrite' => false,
                ))
            );

            $created_attributes++;
        } else {
            $attribute_existed = true;
            $attribute_id = $existing->attribute_id;
            $taxonomy_name = wc_attribute_taxonomy_name($attribute_slug);
        }

        // Ajouter les weekends comme termes
        $terms_added = 0;
        $terms_skipped = 0;

        foreach ($weekends as $weekend) {
            // Vérifier si le terme existe déjà
            $existing_term = term_exists($weekend['label'], $taxonomy_name);

            if ($existing_term) {
                $terms_skipped++;
                continue;
            }

            // Créer le terme
            $term = wp_insert_term(
                $weekend['label'],
                $taxonomy_name,
                array('slug' => sanitize_title($weekend['label']))
            );

            if (!is_wp_error($term)) {
                $terms_added++;
                $created_terms++;
            } else {
                $results[] = '  ✗ Erreur terme "' . $weekend['label'] . '": ' . $term->get_error_message();
            }
        }

        // Message de résultat pour cet attribut
        if ($attribute_existed) {
            $results[] = sprintf(
                '→ %s : déjà existant, %d weekends ajoutés, %d déjà présents',
                $attribute_label,
                $terms_added,
                $terms_skipped
            );
        } else {
            $results[] = sprintf('✓ %s : créé avec %d weekends', $attribute_label, $terms_added);
        }
    }

    // Vider tous les caches
    delete_transient('wc_attribute_taxonomies');
    wp_cache_flush();

    return array(
        'success' => true,
        'attributes_created' => $created_attributes,
        'terms_created' => $created_terms,
        'details' => $results,
        'message' => sprintf(
            '%d attribut(s) créé(s) avec %d weekend(s) au total',
            $created_attributes,
            $created_terms
        )
    );
}

function generate_weekends_by_month($end_month, $year)
{
    // Mois en français
    $mois = array(
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    );

    $weekends_by_month = array();
    $current_date = new DateTime();
    $end_date = new DateTime("$year-$end_month-31");

    // Commencer au prochain vendredi
    $date = clone $current_date;
    while ($date->format('N') != 5) { // 5 = vendredi
        $date->modify('+1 day');
    }

    while ($date <= $end_date) {
        $friday = clone $date;
        $sunday = clone $date;
        $sunday->modify('+2 days');

        // Récupérer le mois du vendredi
        $month_num = (int)$friday->format('n');
        $month_name = $mois[$month_num];
        $month_year = $friday->format('Y');
        $month_key = strtolower($month_name) . '-' . $month_year;

        // Initialiser le tableau pour ce mois si nécessaire
        if (!isset($weekends_by_month[$month_key])) {
            $weekends_by_month[$month_key] = array(
                'month_name' => $month_name,
                'month_year' => $month_year,
                'weekends' => array()
            );
        }

        // Ajouter le weekend
        $weekends_by_month[$month_key]['weekends'][] = array(
            'start' => $friday->format('Y-m-d'),
            'end' => $sunday->format('Y-m-d'),
            'label' => $friday->format('d') . ' - ' . $sunday->format('d') . ' ' . $month_name
        );

        // Passer au vendredi suivant
        $date->modify('+7 days');
    }

    return $weekends_by_month;
}

// Exécution du script - S'EXÉCUTE UNE SEULE FOIS
$execution_flag = get_option('weekend_attributes_script_executed_once');

if ($execution_flag) {
    echo "⚠️ CE SCRIPT A DÉJÀ ÉTÉ EXÉCUTÉ\n\n";
    echo "Les attributs ont été créés le : " . date('d/m/Y à H:i:s', $execution_flag) . "\n\n";
    echo "Si vous voulez le réexécuter, vous devez d'abord supprimer le flag en exécutant :\n";
    echo "delete_option('weekend_attributes_script_executed_once');\n\n";
    echo "Ou supprimez cette option dans la table wp_options de votre base de données.\n";
    exit;
}

$result = create_monthly_weekend_attributes($end_month, $current_year);

if (!$result['success']) {
    echo 'Erreur : ' . $result['message'];
} else {
    echo $result['message'] . "\n\n";
    echo "Détails :\n";
    foreach ($result['details'] as $detail) {
        echo $detail . "\n";
    }
    echo "\n✓ Script terminé avec succès !";
    echo "\n→ Allez dans Produits > Attributs pour voir les nouveaux attributs";

    // Marquer définitivement le script comme exécuté
    update_option('weekend_attributes_script_executed_once', time(), false);

    echo "\n\n⚠️ Ce script ne pourra plus être réexécuté.";
    echo "\nPour le réexécuter, vous devrez supprimer l'option 'weekend_attributes_script_executed_once'";
}

/**
 * CORRECTIFS APPLIQUÉS :
 * 
 * 1. Insertion directe en base de données au lieu de wc_create_attribute()
 * 2. Vérification de l'existence de l'attribut avec wc_attribute_taxonomy_id_by_name()
 * 3. Nettoyage des caches transients
 * 4. Meilleure gestion des erreurs avec messages détaillés
 * 5. Enregistrement correct de la taxonomie
 * 
 * CAUSES POSSIBLES DE L'ERREUR "Échec de l'ajout" :
 * - Slug d'attribut déjà existant
 * - Problème de permissions base de données
 * - Cache WooCommerce non vidé
 * - Caractères spéciaux dans le nom
 * 
 * UTILISATION :
 * 
 * Méthode recommandée - Via Code Snippets :
 * 1. Installez le plugin "Code Snippets"
 * 2. Créez un nouveau snippet
 * 3. Collez ce code
 * 4. Exécutez-le une fois
 * 5. Désactivez le snippet après exécution
 */
