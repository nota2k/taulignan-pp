<?php
/**
 * Fichier de recensement et d'enregistrement des champs ACF
 * 
 * Ce fichier contient tous les groupes de champs ACF du thème
 * organisés par fonctionnalité pour une meilleure maintenance.
 * 
 * @package Taulignan_U_Child
 * @since 1.0.0
 */

// Empêcher l'accès direct
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Renommer complètement les produits WooCommerce en "Séjours"
 */
add_filter('woocommerce_register_post_type_product', 'custom_rename_products_backend');

function custom_rename_products_backend($args)
{
    // Modifier tous les libellés
    $args['labels']['name']               = 'Séjours';
    $args['labels']['singular_name']      = 'Séjour';
    $args['labels']['add_new']            = 'Ajouter un séjour';
    $args['labels']['add_new_item']       = 'Ajouter un nouveau séjour';
    $args['labels']['edit_item']          = 'Modifier le séjour';
    $args['labels']['new_item']           = 'Nouveau séjour';
    $args['labels']['view_item']          = 'Voir le séjour';
    $args['labels']['search_items']       = 'Rechercher des séjours';
    $args['labels']['not_found']          = 'Aucun séjour trouvé';
    $args['labels']['not_found_in_trash'] = 'Aucun séjour dans la corbeille';
    $args['labels']['all_items']          = 'Tous les séjours';
    $args['labels']['menu_name']          = 'Séjours';
    $args['labels']['name_admin_bar']     = 'Séjour';
    $args['labels']['item_updated']       = 'Séjour mis à jour';
    $args['labels']['item_published']     = 'Séjour publié';
    $args['labels']['item_published_privately'] = 'Séjour publié en privé';
    $args['labels']['item_reverted_to_draft'] = 'Séjour remis en brouillon';
    $args['labels']['item_scheduled']     = 'Séjour programmé';

    // S'assurer que le menu est visible
    $args['show_in_menu'] = true;
    $args['show_ui'] = true;
    $args['show_in_admin_bar'] = true;
    $args['show_in_nav_menus'] = true;

    // Vérifier les capacités
    $args['capability_type'] = 'product';
    $args['capabilities'] = array(
        'edit_post' => 'edit_product',
        'read_post' => 'read_product',
        'delete_post' => 'delete_product',
        'edit_posts' => 'edit_products',
        'edit_others_posts' => 'edit_others_products',
        'publish_posts' => 'publish_products',
        'read_private_posts' => 'read_private_products',
    );

    return $args;
}

/**
 * S'assurer que le menu principal "Séjours" est visible
 */
add_action('admin_menu', 'ensure_sejours_menu_visibility', 20);

function ensure_sejours_menu_visibility()
{
    global $menu, $submenu;

    // Rechercher et modifier le menu WooCommerce si nécessaire
    foreach ($menu as $key => $item) {
        if (isset($item[2]) && $item[2] === 'edit.php?post_type=product') {
            $menu[$key][0] = 'Séjours';
            $menu[$key][6] = 'dashicons-calendar-alt'; // Icône calendrier
            break;
        }
    }

    // Modifier le sous-menu si nécessaire
    if (isset($submenu['edit.php?post_type=product'])) {
        foreach ($submenu['edit.php?post_type=product'] as $key => $subitem) {
            if ($subitem[0] === 'Tous les produits') {
                $submenu['edit.php?post_type=product'][$key][0] = 'Tous les séjours';
            }
        }
    }
}