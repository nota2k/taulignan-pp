<?php

// Empêcher l'accès direct
if (! defined('ABSPATH')) {
    exit;
}

 

// Charger l'autoloader Composer
require_once __DIR__ . '/vendor/autoload.php';

// Définir les constantes du thème
define('APP_THEME_DIR', get_template_directory() . '/');
define('APP_THEME_URL', get_template_directory_uri());

// ============================================================================
// INCLUSIONS DES FICHIERS
// ============================================================================
// Register options and load additional functionality
add_action('init', 'app_init', 0);

function app_init()
{
    require_once APP_THEME_DIR . '/inc/acf-block-extensions.php';
    require_once APP_THEME_DIR . '/inc/acf-fields.php';
    require_once APP_THEME_DIR . '/inc/acf-image-block-extension.php';
    require_once APP_THEME_DIR . '/inc/auto-import-patterns.php';
    require_once APP_THEME_DIR . '/inc/custom-header.php';
    require_once APP_THEME_DIR . '/inc/custom-post-types.php';
    require_once APP_THEME_DIR . '/inc/customizer.php';
    require_once APP_THEME_DIR . '/inc/enqueues.php';
    require_once APP_THEME_DIR . '/inc/parallax-extension.php';
    require_once APP_THEME_DIR . '/inc/patterns-simple.php';
    require_once APP_THEME_DIR . '/inc/patterns.php';
    require_once APP_THEME_DIR . '/inc/template-functions.php';
    require_once APP_THEME_DIR . '/inc/template-tags.php';
    require_once APP_THEME_DIR . '/inc/sections.php';
    require_once APP_THEME_DIR . '/inc/shortcodes.php';
    require_once APP_THEME_DIR . '/inc/woocommerce.php';
    require_once APP_THEME_DIR . '/inc/register_style.php';
    
    // Interface d'administration pour la génération automatique de dates
    if (is_admin()) {
        require_once APP_THEME_DIR . '/inc/automatic-dates-admin.php';
    }
}

// ============================================================================
// CONFIGURATION DU THÈME
// ============================================================================
/**
 * Configuration principale du thème
 */
function taulignan_theme_setup()
{
    // Ajoute le support des images mises en avant
    add_theme_support('post-thumbnails');

    // Support FSE (Full Site Editing)
    add_theme_support('block-templates');
    add_theme_support('block-template-parts');
    add_theme_support('block-patterns');
    add_theme_support('editor-styles');
    add_editor_style('/dist/css/editor-style.css'); // make sure path reflects where the file is located


    // Support des éditeurs de blocs
    add_theme_support('responsive-embeds');

    // Support des couleurs et typographies personnalisées
    add_theme_support('custom-background');
    add_theme_support('custom-logo');
    add_theme_support('custom-header');

    // Support des formats de publication
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat'
    ));

    // Support de l'alignement large et pleine largeur
    add_theme_support('align-wide');

    // Support des menus de navigation
    add_theme_support('menus');

    // Support HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
}
add_action('after_setup_theme', 'taulignan_theme_setup');

/**
 * Enregistrement des menus de navigation
 */
function register_my_menus()
{
    register_nav_menus(
        array(
            'header-menu' => __('Header Menu')
        )
    );
}
add_action('init', 'register_my_menus');

// ============================================================================
// CHARGEMENT DES SCRIPTS
// ============================================================================
/**
 * Styles et scripts pour l'éditeur de blocs
 */
function taulignan_block_editor_assets()
{
    // Charger le CSS spécifique à l'éditeur (qui importe déjà main.css)
    wp_enqueue_style('taulignan-editor-style', get_stylesheet_directory_uri() . '/dist/css/editor-style.css', array('wp-edit-blocks'), '1.0.0');

    // Charger les scripts de l'éditeur avec les bonnes dépendances
    wp_enqueue_script('parallax-editor-js', get_stylesheet_directory_uri() . '/dist/js/parallax.js', array('wp-element', 'wp-hooks', 'wp-block-editor', 'wp-components', 'wp-i18n'), '1.0.0', true);
    wp_enqueue_script('acf-editor-js', get_stylesheet_directory_uri() . '/dist/js/acf.js', array('wp-element', 'wp-hooks', 'wp-block-editor', 'wp-components', 'wp-i18n'), '1.0.0', true);
}
add_action('enqueue_block_editor_assets', 'taulignan_block_editor_assets');


// ============================================================================
// DÉSACTIVATION DES STYLES PAR DÉFAUT
// ============================================================================

/**
 * Désactiver les styles de blocs par défaut de WordPress
 */
function taulignan_remove_wp_block_library_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // WooCommerce si présent
}
add_action('wp_enqueue_scripts', 'taulignan_remove_wp_block_library_css');

// Désactiver aussi dans l'éditeur
add_action('enqueue_block_editor_assets', 'taulignan_remove_wp_block_library_css', 100);

// ============================================================================
// MODIFICATIONS DES BLOCS
// ============================================================================

/**
 * Filtrer le template-part header pour ajouter la classe site-header
 */
function taulignan_add_header_class($block_content, $block)
{
    // Vérifier si c'est le template-part header
    if (isset($block['blockName']) && 'core/template-part' === $block['blockName']) {
        if (isset($block['attrs']['slug']) && 'header' === $block['attrs']['slug']) {
            // Essayer plusieurs patterns possibles
            $patterns = array(
                '/^<div class="wp-block-template-part"([^>]*)>/',
                '/<div class="wp-block-template-part"([^>]*)>/',
                '/^<div([^>]*class="[^"]*wp-block-template-part[^"]*"[^>]*)>/',
            );

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $block_content)) {
                    $block_content = preg_replace(
                        $pattern,
                        '<header id="masthead" class="wp-block-template-part site-header"$1>',
                        $block_content,
                        1
                    );
                    break;
                }
            }

            // Remplacer la dernière fermeture </div> par </header>
            $pos = strrpos($block_content, '</div>');
            if ($pos !== false) {
                $block_content = substr_replace($block_content, '</header>', $pos, 6);
            }
        }
    }
    return $block_content;
}
add_filter('render_block', 'taulignan_add_header_class', 10, 2);

/**
 * Fonction pour remplacer figure par div dans les blocs d'images
 */
function remplacer_figure_par_div($block_content, $block)
{
    if ($block['blockName'] !== 'core/image') {
        return $block_content;
    }

    // Remplacer <figure> par <div>
    $block_content = preg_replace('/<figure([^>]*)>/', '', $block_content);

    // Remplacer </figure> par </div>
    $block_content = str_replace('</figure>', '', $block_content);

    // Optionnel : remplacer figcaption par div avec classe
    $block_content = preg_replace('/<figcaption([^>]*)>/', '<div class="image-caption"$1>', $block_content);
    $block_content = str_replace('</figcaption>', '</div>', $block_content);

    return $block_content;
}
add_filter('render_block', 'remplacer_figure_par_div', 10, 2);

/**
 * Fonction pour ajouter automatiquement des classes aux groupes contenant des paragraphes
 */
function taulignan_add_group_classes($block_content, $block)
{
    // Vérifier si c'est un bloc groupe
    if ($block['blockName'] === 'core/group') {
        // Vérifier si le contenu contient des paragraphes
        if (strpos($block_content, 'wp-block-paragraph') !== false) {
            // Ajouter la classe has-paragraphs
            $block_content = str_replace(
                'class="wp-block-group',
                'class="wp-block-group has-paragraphs',
                $block_content
            );
        }

        // Vérifier si c'est un groupe de contenu principal
        if (
            strpos($block_content, 'wp-block-paragraph') !== false &&
            strpos($block_content, 'wp-block-heading') !== false
        ) {
            $block_content = str_replace(
                'class="wp-block-group',
                'class="wp-block-group content-box',
                $block_content
            );
        }
    }

    return $block_content;
}
add_filter('render_block', 'taulignan_add_group_classes', 10, 2);

// ============================================================================
// MODIFICATIONS DES GALERIES
// ============================================================================

/**
 * Modifier les galeries natives WordPress
 */
add_filter('post_gallery', 'galery_custom', 10, 2);
function galery_custom($output, $attr)
{
    global $post;

    // Récupère les IDs des images de la galerie
    $ids_array = explode(',', $attr['ids']);

    $output = '<div class="gallery">'; // Ajoute votre classe personnalisée

    foreach ($ids_array as $id) {
        $img_src = wp_get_attachment_image_src($id, 'full');

        // Vérifie si l'image est liée au média
        $link_to_media = isset($attr['link']) && $attr['link'] === 'file';

        $output .= '<div class="gallery-item">'; // Ajoute votre classe personnalisée
        if ($link_to_media) {
            $output .= '<a href="' . esc_url($img_src[0]) . '">';
        }
        $output .= '<img src="' . esc_url($img_src[0]) . '" alt="">';
        if ($link_to_media) {
            $output .= '</a>';
        }
        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
}

add_filter('post_gallery', function ($html, $attr, $instance) {
    if (isset($attr['class']) && $class = $attr['class']) {
        // Unset attribute to avoid infinite recursive loops
        unset($attr['class']);

        // Our custom HTML wrapper
        $html = sprintf(
            '<div class="%s">%s</div>',
            esc_attr($class),
            gallery_shortcode($attr)
        );
    }

    return $html;
}, 10, 3);

// ============================================================================
// FONCTIONNALITÉS DIVERSES
// ============================================================================

/**
 * Autoriser l'importation des SVG
 */
function taulignan_allow_svg_upload($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'taulignan_allow_svg_upload');

/**
 * Supprimer les balises <p> de Contact Form 7
 */
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Désactiver les patterns distants
 */
function desactiver_patterns_distants()
{
    // Empêcher le chargement des patterns depuis WordPress.org
    add_filter('should_load_remote_block_patterns', '__return_false');
}
add_action('init', 'desactiver_patterns_distants');

// ============================================================================
// FILTRES POUR GALERIES NATIVES
// ============================================================================

/**
 * Modifier le HTML des galeries natives pour utiliser Swiper
 */
function modifier_galeries_natives($output, $attr, $instance)
{
    // Vérifier si c'est bien une galerie
    if (empty($output)) {
        return $output;
    }

    // Ajouter la classe swiper au conteneur principal
    $output = preg_replace('/<div[^>]*class="[^"]*gallery[^"]*"[^>]*>/', '<div class="gallery swiper">', $output);

    // Remplacer les balises <figure> par <div class="swiper-wrapper">
    $output = preg_replace('/<figure[^>]*class="[^"]*gallery[^"]*"[^>]*>/', '<div class="swiper-wrapper">', $output);
    $output = preg_replace('/<\/figure>/', '</div>', $output);

    // Remplacer les balises <figure> des images individuelles par <div class="swiper-slide">
    $output = preg_replace('/<figure[^>]*class="[^"]*gallery-item[^"]*"[^>]*>/', '<div class="swiper-slide">', $output);
    $output = preg_replace('/<figure[^>]*class="[^"]*gallery-icon[^"]*"[^>]*>/', '<div class="swiper-slide">', $output);

    // Remplacer les <dl> et <dt> par des divs swiper
    $output = preg_replace('/<dl[^>]*class="[^"]*gallery[^"]*"[^>]*>/', '<div class="swiper-wrapper">', $output);
    $output = preg_replace('/<\/dl>/', '</div>', $output);
    $output = preg_replace('/<dt[^>]*>/', '<div class="swiper-slide">', $output);
    $output = preg_replace('/<\/dt>/', '</div>', $output);

    // Transformer les balises <a> en <div class="swiper-slide">
    $output = preg_replace('/<a([^>]*)>/', '<div class="swiper-slide"$1>', $output);
    $output = preg_replace('/<\/a>/', '</div>', $output);

    return $output;
}
add_filter('post_gallery', 'modifier_galeries_natives', 10, 3);

/**
 * Modifier le HTML des blocs de galerie Gutenberg
 */
function modifier_blocs_galerie_gutenberg($block_content, $block)
{
    // Vérifier si c'est un bloc de galerie
    if ($block['blockName'] === 'core/gallery') {
        // Remplacer les balises <figure> par <div class="swiper-wrapper">
        $block_content = preg_replace('/<figure[^>]*class="[^"]*wp-block-gallery[^"]*"[^>]*>/', '<div class="swiper-wrapper">', $block_content);
        $block_content = preg_replace('/<\/figure>/', '</div>', $block_content);

        // Remplacer les balises <figure> des images individuelles par <div class="swiper-slide">
        $block_content = preg_replace('/<figure[^>]*class="[^"]*wp-block-image[^"]*"[^>]*>/', '<div class="swiper-slide">', $block_content);

        // Ajouter la classe swiper au conteneur principal
        $block_content = preg_replace('/<ul[^>]*class="[^"]*wp-block-gallery[^"]*"[^>]*>/', '<div class="wp-block-gallery swiper">', $block_content);
        $block_content = preg_replace('/<\/ul>/', '</div>', $block_content);

        // Remplacer les <li> par des <div class="swiper-slide">
        $block_content = preg_replace('/<li[^>]*>/', '<div class="swiper-slide">', $block_content);
        $block_content = preg_replace('/<\/li>/', '</div>', $block_content);

        // Transformer les balises <a> en <div class="swiper-slide">
        $block_content = preg_replace('/<a([^>]*)>/', '<div class="swiper-slide"$1>', $block_content);
        $block_content = preg_replace('/<\/a>/', '</div>', $block_content);
    }

    return $block_content;
}
add_filter('render_block', 'modifier_blocs_galerie_gutenberg', 10, 2);

// ============================================================================
// PERSONNALISATION WOOCOMMERCE - PRODUCT DETAILS
// ============================================================================

/**
 * Supprimer les onglets des produits WooCommerce
 */
function remove_product_tabs($tabs)
{
    // Supprimer l'onglet Description
    unset($tabs['description']);

    // Supprimer l'onglet Informations supplémentaires
    unset($tabs['additional_information']);

    // Supprimer l'onglet Avis
    unset($tabs['reviews']);

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'remove_product_tabs', 98);

/**
 * Supprimer complètement les avis et évaluations
 */
function disable_woocommerce_reviews()
{
    // Désactiver les avis sur les produits
    add_filter('woocommerce_product_review_list_args', '__return_empty_array');

    // Supprimer les étoiles d'évaluation
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

    // Supprimer les avis de la page produit
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
}
add_action('init', 'disable_woocommerce_reviews');

/**
 * Personnaliser le breadcrumb WooCommerce - Retour vers la page précédente
 */
function custom_woocommerce_breadcrumb($crumbs, $breadcrumb)
{
    // Si on est sur une page produit, afficher le lien vers la page précédente
    if (is_product()) {
        // Créer un breadcrumb simple avec retour JavaScript vers la page précédente
        $crumbs = array(
            array('← Retour', 'javascript:history.back()')
        );
    }

    return $crumbs;
}
add_filter('woocommerce_get_breadcrumb', 'custom_woocommerce_breadcrumb', 10, 2);

/**
 * Personnaliser les arguments du breadcrumb - Style bouton de retour
 */
function custom_woocommerce_breadcrumb_defaults($defaults)
{
    $defaults['delimiter']   = '';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb back-to-previous">';
    $defaults['wrap_after']  = '</nav>';
    $defaults['before']      = '<a class="back-button" onclick="history.back(); return false;">';
    $defaults['after']       = '</a>';

    return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'custom_woocommerce_breadcrumb_defaults');

/**
 * Changer le texte "en stock" par "places disponibles"
 */
function change_stock_text($translated_text, $text, $domain)
{
    if ($domain == 'woocommerce') {
        switch ($translated_text) {
            case 'En stock':
            case 'en stock':
                $translated_text = 'Places disponibles';
                break;
            case '%s en stock':
                $translated_text = '%s places disponibles';
                break;
            case 'In stock':
                $translated_text = 'Places disponibles';
                break;
            case '%s in stock':
                $translated_text = '%s places disponibles';
                break;
        }
    }
    return $translated_text;
}
add_filter('gettext', 'change_stock_text', 20, 3);
add_filter('ngettext', 'change_stock_text', 20, 3);
/**
 * Corriger les warnings liés aux walkers WordPress
 */
function fix_walker_warnings()
{
    // Supprimer les erreurs liées aux walkers si elles ne sont pas critiques
    if (current_user_can('manage_options')) {
        // Masquer les warnings de walker en mode debug
        add_filter('wp_dropdown_cats', function ($output) {
            if (is_admin() && !wp_doing_ajax()) {
                return $output;
            }
            return $output;
        });
    }
}
add_action('init', 'fix_walker_warnings');

function group_price_to_formule()
{
    if (is_product()) {
        echo '<div class="sejour-field sejour-price">';
        

        echo '<ul class="formule-list">';
        $formule = get_field('formule');
        // Vérifier si c'est un array (répéteur/groupe) ou une string
        if (is_array($formule)) {
            // Si c'est un répéteur ou un array
            foreach ($formule as $item) {
                if (is_array($item)) {
                    // Si chaque item est aussi un array (sous-champs)
                    foreach ($item as $key => $value) {
                        if (!empty($value)) {
                            echo '<li class="formule-item">';
                            echo wp_kses_post($value);
                            echo '</li>';
                        }
                    }
                } else {
                    // Si c'est juste une liste de valeurs
                    echo '<div class="programme-item">' . wp_kses_post($item) . '</div>';
                }
            }
        } else {
            // Si c'est une string simple (textarea, wysiwyg)
            echo wp_kses_post($formule);
        }
        echo '</ul>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'group_price_to_formule', 1);

// ============================================================================
// FILTRES PERSONNALISÉS POUR QUERY LOOP
// ============================================================================

/**
 * Ajouter un filtre personnalisé pour le bloc Query Loop
 * Permet de filtrer les séjours selon le champ ACF event_finish
 * 
 * Usage dans l'éditeur de blocs :
 * 1. Ajouter un bloc Query Loop
 * 2. Sélectionner le bloc Query Loop
 * 3. Dans le panneau latéral, section "Paramètres" > "Avancé"
 * 4. Ajouter une classe CSS supplémentaire : "query-sejours-finis" ou "query-sejours-actifs"
 */
function taulignan_filter_query_loop_by_event_finish($query, $block, $page)
{
    // Récupérer les classes CSS du bloc
    $block_classes = isset($block->context['className']) ? $block->context['className'] : '';

    // Alternative : vérifier dans les attributs du bloc
    if (empty($block_classes) && isset($block->parsed_block['attrs']['className'])) {
        $block_classes = $block->parsed_block['attrs']['className'];
    }

    // Filtre pour les séjours finis (event_finish == 'oui')
    if (strpos($block_classes, 'query-sejours-finis') !== false) {
        $query['meta_query'] = array(
            array(
                'key' => 'event_finish',
                'value' => 'oui',
                'compare' => '='
            ),
        );

        // Tri par date de séjour (du plus récent au plus ancien)
        $query['meta_key'] = 'date_sejour';
        $query['orderby'] = 'meta_value';
        $query['order'] = 'DESC';
    }

    // Filtre pour les séjours actifs/à venir (event_finish != 'oui')
    if (strpos($block_classes, 'query-sejours-actifs') !== false) {
        $query['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => 'event_finish',
                'value' => 'oui',
                'compare' => '!='
            ),
            array(
                'key' => 'event_finish',
                'compare' => 'NOT EXISTS'
            ),
        );

        // Tri par date de séjour (du plus proche au plus lointain)
        $query['meta_key'] = 'date_sejour';
        $query['orderby'] = 'meta_value';
        $query['order'] = 'ASC';
    }

    return $query;
}
add_filter('query_loop_block_query_vars', 'taulignan_filter_query_loop_by_event_finish', 10, 3);

/**
 * Changer "Commande" par "Réservation" dans tous les textes d'email
 */
function taulignan_change_order_to_reservation( $translated_text, $text, $domain ) {
    if ( $domain === 'woocommerce' ) {
        // Traductions pour les emails
        $translations = array(
            'Order' => 'Réservation',
            'Your order' => 'Votre réservation',
            'Order details' => 'Détails de la réservation',
            'Order number' => 'Numéro de réservation',
            'Order date' => 'Date de réservation',
            'Payment method' => 'Mode de paiement',
            'Product' => 'Séjour',
            'Products' => 'Séjours',
            'Quantity' => 'Quantité',
            'Price' => 'Prix',
            'Subtotal' => 'Sous-total',
            'Total' => 'Total',
        );
        
        if ( isset( $translations[ $text ] ) ) {
            return $translations[ $text ];
        }
    }
    
    return $translated_text;
}
add_filter( 'gettext', 'taulignan_change_order_to_reservation', 20, 3 );

// Désactiver le préchargement des scripts WooCommerce
add_filter('woocommerce_block_assets_preload_hints', '__return_empty_array');

// Désactiver les préchargements inutiles
add_action('wp_enqueue_scripts', function () {
    // Supprimer les hints de préchargement
    remove_action('wp_head', 'wp_resource_hints', 2);
}, 999);

// Filtrer les préchargements spécifiques
add_filter('wp_resource_hints', function ($hints, $relation_type) {
    if ('preload' === $relation_type) {
        // Garder seulement les ressources critiques
        return array_filter($hints, function ($hint) {
            // Autoriser seulement les CSS et fonts
            return isset($hint['as']) && in_array($hint['as'], ['style', 'font']);
        });
    }
    return $hints;
}, 10, 2);

// Charger Stripe uniquement sur la page de paiement
add_action('wp_enqueue_scripts', function () {
    if (!is_checkout() && !is_cart()) {
        // Désenregistrer les scripts de paiement
        wp_dequeue_script('stripe');
        wp_dequeue_script('wc-stripe-payment-gateway');
        wp_dequeue_script('woocommerce-payments');
        wp_dequeue_script('ppcp-blocks');
    }
}, 100);

// Optimiser le chargement des blocs WooCommerce
add_filter('woocommerce_blocks_register_script_dependencies', function ($dependencies) {
    return [];
}, 10, 1);

// Désactiver les scripts du mini-cart si non utilisé
add_action('wp_enqueue_scripts', function () {
    if (!is_cart() && !is_checkout()) {
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_script('wc-blocks-checkout');
    }
}, 100);