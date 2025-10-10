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
add_filter('woocommerce_register_post_type_product', 'custom_rename_products_backend');

function custom_rename_products_backend($args)
{
    // On change les libellés (syntaxe array)
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

    return $args;
}
