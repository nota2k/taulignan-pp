<?php
/**
 * Mini-script pour ajouter les weekends de juin 2025
 * 
 * Ce script crée l'attribut "juin-2025" et ajoute tous les samedis du mois
 * au format dd/MM/YYYY
 * 
 * UTILISATION RECOMMANDÉE :
 * 
 * Méthode 1 - Via Code Snippets (la plus simple) :
 * 1. Installez le plugin "Code Snippets"
 * 2. Créez un nouveau snippet
 * 3. Collez tout ce code
 * 4. Cliquez sur "Run Once" (Exécuter une fois)
 * 
 * Méthode 2 - Via functions.php :
 * Ajoutez temporairement add_june_2025_weekends(); dans functions.php
 * puis rechargez une page du site
 * 
 * Méthode 3 - Via WP-CLI :
 * wp eval-file wp-content/themes/taulignan-u-child/inc/add-june-2025-weekends.php
 */

// Charger WordPress si le script est exécuté directement
if (!defined('ABSPATH')) {
    // Essayer de charger WordPress
    $wp_load_paths = array(
        __DIR__ . '/../../../../../wp-load.php',
        __DIR__ . '/../../../../wp-load.php',
        dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/wp-load.php',
    );
    
    $loaded = false;
    foreach ($wp_load_paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            $loaded = true;
            break;
        }
    }
    
    if (!$loaded) {
        die('Erreur : Impossible de charger WordPress. Utilisez plutôt Code Snippets ou WP-CLI.');
    }
}

function add_june_2025_weekends() {
    global $wpdb;

    // Vérifier que WooCommerce est actif
    if (!function_exists('wc_attribute_taxonomy_name')) {
        return array(
            'success' => false,
            'message' => 'WooCommerce n\'est pas actif.'
        );
    }

    // Configuration
    $month_name = 'Juin';
    $month_year = '2025';
    $month_num = 6;
    $attribute_slug = 'juin-2025';
    $attribute_label = 'Juin 2025';

    // Générer tous les samedis de juin 2025
    $saturdays = array();
    $current_date = new DateTime('2025-06-01');
    $end_date = new DateTime('2025-06-30');

    // Trouver le premier samedi de juin
    while ($current_date->format('N') != 6) { // 6 = samedi
        $current_date->modify('+1 day');
    }

    // Collecter tous les samedis du mois
    while ($current_date <= $end_date) {
        if ($current_date->format('n') == $month_num) {
            $saturdays[] = array(
                'date' => $current_date->format('Y-m-d'),
                'label' => $current_date->format('d/m/Y')
            );
        }
        $current_date->modify('+7 days');
    }

    // Vérifier si l'attribut existe déjà
    $existing = $wpdb->get_row($wpdb->prepare(
        "SELECT attribute_id, attribute_name FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s",
        $attribute_slug
    ));

    if (!$existing) {
        // Créer l'attribut
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
                'message' => 'Erreur lors de la création de l\'attribut : ' . $wpdb->last_error
            );
        }

        // Vider le cache
        delete_transient('wc_attribute_taxonomies');
    }

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

    // Ajouter les samedis comme termes
    $terms_added = 0;
    $terms_skipped = 0;

    foreach ($saturdays as $saturday) {
        // Vérifier si le terme existe déjà
        $existing_term = term_exists($saturday['label'], $taxonomy_name);

        if ($existing_term) {
            $terms_skipped++;
            continue;
        }

        // Créer le terme
        $term = wp_insert_term(
            $saturday['label'],
            $taxonomy_name,
            array('slug' => sanitize_title($saturday['label']))
        );

        if (!is_wp_error($term)) {
            $terms_added++;
        }
    }

    // Vider les caches
    delete_transient('wc_attribute_taxonomies');
    wp_cache_flush();

    return array(
        'success' => true,
        'attribute' => $attribute_label,
        'terms_added' => $terms_added,
        'terms_skipped' => $terms_skipped,
        'saturdays' => $saturdays,
        'message' => sprintf(
            'Attribut "%s" créé avec %d samedi(s) ajouté(s) et %d déjà existant(s)',
            $attribute_label,
            $terms_added,
            $terms_skipped
        )
    );
}

// Exécuter le script
$result = add_june_2025_weekends();

// Afficher les résultats
echo '<div style="font-family: sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';
echo '<h1 style="color: #333;">🗓️ Ajout des weekends de Juin 2025</h1>';

if ($result['success']) {
    echo '<div style="background: #e7f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0;">';
    echo '<strong>✅ Succès !</strong><br>';
    echo esc_html($result['message']);
    echo '</div>';

    if (!empty($result['saturdays'])) {
        echo '<h3>📋 Samedis ajoutés :</h3>';
        echo '<ul style="line-height: 1.8;">';
        foreach ($result['saturdays'] as $saturday) {
            echo '<li><strong>' . esc_html($saturday['label']) . '</strong> (' . esc_html($saturday['date']) . ')</li>';
        }
        echo '</ul>';
    }

    echo '<div style="background: #fff3cd; border-left: 4px solid #ff9800; padding: 15px; margin: 20px 0;">';
    echo '<strong>⚠️ Important :</strong> Vous pouvez maintenant supprimer ce fichier pour des raisons de sécurité.';
    echo '</div>';
} else {
    echo '<div style="background: #ffebee; border-left: 4px solid #f44336; padding: 15px; margin: 20px 0;">';
    echo '<strong>❌ Erreur :</strong><br>';
    echo esc_html($result['message']);
    echo '</div>';
}

echo '<div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin: 20px 0;">';
echo '<strong>ℹ️ Prochaines étapes :</strong><br>';
echo '1. Allez dans <strong>Produits > Attributs</strong> pour vérifier<br>';
echo '2. Les samedis de juin 2025 sont maintenant disponibles comme variations<br>';
echo '3. Format des dates : <code>dd/MM/YYYY</code> (ex: 07/06/2025)';
echo '</div>';

echo '</div>';

