<?php

/**
 * Script pour créer automatiquement des variations de dates WooCommerce
 * Version modifiée : un seul attribut "Date" au lieu d'attributs par mois
 * 
 * Format des dates : Les variations enregistrent la date du VENDREDI
 * au format dd/MM/YYYY (ex: 13/10/2025) - vendredi qui précède le weekend
 */

/**
 * Fonction principale pour générer les dates d'un mois spécifique
 * et les ajouter à l'attribut "Date"
 */
function create_date_attributes_for_month($month, $year)
{
    global $wpdb;

    // Vérifier que WooCommerce est actif
    if (!function_exists('wc_create_attribute')) {
        return array(
            'success' => false,
            'message' => 'WooCommerce n\'est pas actif ou n\'est pas chargé.'
        );
    }

    // Validation des paramètres
    if ($month < 1 || $month > 12) {
        return array(
            'success' => false,
            'message' => 'Le mois doit être entre 1 et 12.'
        );
    }

    // Générer les vendredis du mois
    $fridays = generate_fridays_for_month($month, $year);

    if (empty($fridays)) {
        return array(
            'success' => false,
            'message' => 'Aucun vendredi trouvé pour ce mois.'
        );
    }

    // Créer ou récupérer l'attribut "Date"
    $attribute_slug = 'date';
    $attribute_label = 'Date';

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
            return array(
                'success' => false,
                'message' => 'Erreur BDD pour l\'attribut Date: ' . $wpdb->last_error
            );
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
    } else {
        $attribute_id = $existing->attribute_id;
        $taxonomy_name = wc_attribute_taxonomy_name($attribute_slug);
    }

    // Ajouter les dates comme termes
    $terms_added = 0;
    $terms_skipped = 0;
    $results = array();

    // Mois en français pour les messages
    $mois = array(
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    );
    $month_name = $mois[$month];

    foreach ($fridays as $friday_date) {
        // Vérifier si le terme existe déjà
        $existing_term = term_exists($friday_date['label'], $taxonomy_name);

        if ($existing_term) {
            $terms_skipped++;
            continue;
        }

        // Créer le terme
        $term = wp_insert_term(
            $friday_date['label'],
            $taxonomy_name,
            array('slug' => sanitize_title($friday_date['label']))
        );

        if (!is_wp_error($term)) {
            $terms_added++;
        } else {
            $results[] = '✗ Erreur terme "' . $friday_date['label'] . '": ' . $term->get_error_message();
        }
    }

    // Vider tous les caches
    delete_transient('wc_attribute_taxonomies');
    wp_cache_flush();

    return array(
        'success' => true,
        'attributes_created' => $existing ? 0 : 1,
        'terms_created' => $terms_added,
        'details' => $results,
        'message' => sprintf(
            '%d date(s) ajoutée(s) pour %s %d (%d déjà présente(s))',
            $terms_added,
            $month_name,
            $year,
            $terms_skipped
        )
    );
}

/**
 * Génère tous les vendredis d'un mois spécifique
 * Le vendredi est celui qui précède le weekend
 */
function generate_fridays_for_month($month, $year)
{
    $fridays = array();
    
    // Créer une date pour le premier jour du mois
    $date = new DateTime("$year-$month-01");
    
    // Trouver le premier vendredi du mois
    // On parcourt les jours jusqu'à trouver un vendredi qui est dans le mois
    while ((int)$date->format('n') == $month) {
        if ($date->format('N') == 5) { // 5 = vendredi
            break;
        }
        $date->modify('+1 day');
    }
    
    // Si on a dépassé le mois sans trouver de vendredi (théoriquement impossible)
    if ((int)$date->format('n') != $month) {
        return $fridays;
    }
    
    // Collecter tous les vendredis du mois
    while ((int)$date->format('n') == $month) {
        $fridays[] = array(
            'date' => $date->format('Y-m-d'),
            'label' => $date->format('d/m/Y') // Format: 13/10/2025 (vendredi)
        );
        
        // Passer au vendredi suivant (7 jours plus tard)
        $date->modify('+7 days');
    }
    
    return $fridays;
}

/**
 * NOTE : Ce script ne s'exécute PLUS automatiquement !
 * 
 * Pour générer les dates, utilisez maintenant l'interface d'administration :
 * Outils > Dates automatiques
 * 
 * Ou appelez directement la fonction : create_date_attributes_for_month($month, $year)
 * 
 * CHANGEMENTS PAR RAPPORT À L'ANCIENNE VERSION :
 * 
 * 1. Un seul attribut "Date" au lieu d'un attribut par mois
 * 2. Génération par mois spécifique au lieu de "jusqu'au mois X"
 * 3. Les dates correspondent au VENDREDI qui précède le weekend (au lieu du samedi)
 * 4. Format toujours : dd/MM/YYYY (ex: 13/10/2025)
 */
