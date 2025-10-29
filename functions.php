<?php

/**
 * Functions.php - Thème enfant Taulignan
 * 
 * Ce fichier contient toutes les fonctionnalités personnalisées du thème
 */

// Empêcher l'accès direct
if (! defined('ABSPATH')) {
    exit;
}

// Désactiver les avertissements PHP pour éviter l'envoi prématuré des headers
// Cela empêche les erreurs de plugins tiers d'affecter l'affichage
// Note: À supprimer une fois que WooCommerce Payments est mis à jour
if (! defined('WP_DEBUG') || ! WP_DEBUG) {
    error_reporting(E_ERROR | E_PARSE);
}

// Filtrer les erreurs spécifiques de WordPress 6.7.0 liées aux traductions
add_filter('doing_it_wrong_trigger_error', function ($trigger, $function_name) {
    // Supprimer l'erreur pour _load_textdomain_just_in_time provenant de plugins
    if ($function_name === '_load_textdomain_just_in_time' && strpos(wp_debug_backtrace_summary(), 'woocommerce-payments') !== false) {
        return false;
    }
    return $trigger;
}, 10, 2);

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
    
    // Interface d'administration pour la génération automatique de dates
    if (is_admin()) {
        require_once APP_THEME_DIR . '/inc/automatic-dates-admin.php';
    }
}


// ============================================================================
// CONFIGURATION DU THÈME
// ============================================================================

/**
 * Fonction utilitaire pour charger les assets depuis le manifest
 */
function get_manifest_asset($manifest, $key)
{
    if (isset($manifest[$key]) && isset($manifest[$key]['file'])) {
        return get_template_directory_uri() . "/dist/" . $manifest[$key]['file'];
    }
    return false;
}

/**
 * Fonction utilitaire pour récupérer le manifest complet
 */
function get_theme_manifest()
{
    static $manifest = null;

    if ($manifest === null) {
        $manifest_path = get_theme_file_path() . "/dist/manifest.json";

        if (file_exists($manifest_path)) {
            $data = file_get_contents($manifest_path);
            $manifest = json_decode($data, true);
        } else {
            $manifest = false;
        }
    }

    return $manifest;
}

/**
 * Fonction utilitaire pour obtenir l'URL d'un asset depuis le manifest
 */
function get_asset_url($asset_key)
{
    $manifest = get_theme_manifest();

    if ($manifest && isset($manifest[$asset_key])) {
        return get_template_directory_uri() . "/dist/" . $manifest[$asset_key]['file'];
    }

    return false;
}

/**
 * Chargement des assets dans l'éditeur WordPress (admin)
 */
function load_admin_assets(): void
{
    if (!WP_DEBUG && is_dir(get_theme_file_path() . "/dist")) {
        $manifest_path = get_theme_file_path() . "/dist/manifest.json";

        if (file_exists($manifest_path)) {
            $data = file_get_contents($manifest_path);
            $manifest = json_decode($data, true);

            if ($manifest && is_array($manifest)) {
                // CSS de l'éditeur dans l'admin
                if ($css_editor = get_manifest_asset($manifest, 'css/editor-style')) {
                    wp_enqueue_style(
                        "taulignan-editor-admin",
                        $css_editor,
                        [],
                        '1.0.0'
                    );
                }

                // Scripts ACF dans l'admin
                if ($js_acf = get_manifest_asset($manifest, 'acf')) {
                    wp_enqueue_script(
                        "taulignan-acf-admin",
                        $js_acf,
                        ['jquery', 'wp-element', 'wp-hooks', 'wp-block-editor', 'wp-components', 'wp-i18n'],
                        '1.0.0',
                        true
                    );
                }
            }
        }
    }
}

add_action("admin_enqueue_scripts", "load_admin_assets");

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
    add_theme_support('editor-styles');

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
// STYLES DE BLOCS PERSONNALISÉS
// ============================================================================

/**
 * Enregistrement des styles de blocs personnalisés
 */
function taulignan_register_block_styles()
{
    // Style de bouton personnalisé
    register_block_style(
        'core/button',
        array(
            'name'  => 'chateau-button',
            'label' => 'Bouton Château',
            'style_handle' => 'taulignan-block-styles'
        )
    );

    // Style pour le positionnement à gauche
    register_block_style(
        'core/group',
        array(
            'name'  => 'left-pos',
            'label' => __('Positionnement Gauche', 'taulignan'),
            'inline_style' => '
                .is-style-left-pos {
                    position: relative;
                    left: -3em;
                    z-index: 10;
                }
            '
        )
    );

    // Style pour le positionnement à droite
    register_block_style(
        'core/group',
        array(
            'name'  => 'right-pos',
            'label' => __('Positionnement Droite', 'taulignan'),
            'inline_style' => '
                .is-style-right-pos {
                    position: relative;
                    right: -3em;
                    z-index: 10;
                }
            '
        )
    );

    // Style pour le positionnement centré
    register_block_style(
        'core/group',
        array(
            'name'  => 'center-pos',
            'label' => __('Positionnement Centré', 'taulignan'),
            'inline_style' => '
                .is-style-center-pos {
                    position: relative;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 10;
                }
            '
        )
    );

    // Style pour le positionnement centré
    register_block_style(
        'core/group',
        array(
            'name'  => 'hide-on-mobile',
            'label' => __('Masquer sur mobile', 'taulignan')
        )
    );

    // Style pour le débordement à gauche
    register_block_style(
        'core/group',
        array(
            'name'  => 'overflow-left',
            'label' => __('Débordement Gauche', 'taulignan'),
            'inline_style' => '
                .is-style-overflow-left {
                    position: relative;
                    left: -100px;
                    z-index: 20;
                }
            '
        )
    );

    // Style pour le débordement à droite
    register_block_style(
        'core/group',
        array(
            'name'  => 'overflow-right',
            'label' => __('Débordement Droite', 'taulignan'),
        )
    );

    // Style pour 2 colonnes
    register_block_style(
        'core/group',
        array(
            'name'  => '2-columns',
            'label' => __('2 colonnes', 'taulignan'),
            'inline_style' => '
                .is-style-2-columns {
                    column-count: 2;
                    column-gap: 60px;
                }

                .is-style-2-columns::after {
                    content: "";
                    display: block;
                    clear: both;
                    width: 1px;
                    height: 100%;
                    background-color: var(--beige);
                    position: absolute;
                    right: 50%;
                    top: 0;
                }
            '
        )
    );

    // Style pour icône blanche
    register_block_style(
        'core/image',
        array(
            'name'  => 'icon-white',
            'label' => __('Icone blanche', 'taulignan'),
            'inline_style' => '
                .is-style-icon-white {
                    filter:brightness(1);
                }
            '
        )
    );
}
add_action('init', 'taulignan_register_block_styles');

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
// INCLUSION DES PARTS PERSONNALISÉS
// ============================================================================

/**
 * Fonction pour inclure les template parts PHP dans les templates FSE
 */
function include_php_template_part($part_name)
{
    $part_file = get_template_directory() . '/parts/' . $part_name . '.php';
    if (file_exists($part_file)) {
        include $part_file;
    }
}


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

// ============================================================================
// FILTRAGE DES SÉJOURS PASSÉS
// ============================================================================

/**
 * Filtre pour les séjours passés dans les Query Loop
 */
function filter_sejours_passes_query($block_query, $block, $page)
{
    // Vérifier si c'est un bloc Query Loop avec l'attribut 'sejours-passes'
    $is_sejours_passes = false;

    if (is_object($block) && isset($block->parsed_block['attrs']['className'])) {
        $is_sejours_passes = strpos($block->parsed_block['attrs']['className'], 'sejours-passes') !== false;
    } elseif (is_array($block) && isset($block['attrs']['className'])) {
        $is_sejours_passes = strpos($block['attrs']['className'], 'sejours-passes') !== false;
    }

    // Si c'est un bloc pour les séjours passés
    if ($is_sejours_passes) {
        // S'assurer que c'est bien des produits
        $block_query['post_type'] = 'product';

        // Date d'aujourd'hui au format ACF (YYYYMMDD)
        $today = date('Ymd');

        // Ajouter la meta query pour filtrer par date
        if (!isset($block_query['meta_query'])) {
            $block_query['meta_query'] = array();
        }

        // Filtrer les séjours dont la date est antérieure à aujourd'hui
        $block_query['meta_query'][] = array(
            'key' => 'date_sejour',
            'value' => $today,
            'compare' => '<',
            'type' => 'DATE'
        );

        // Ajouter une relation AND pour la meta_query si nécessaire
        if (count($block_query['meta_query']) > 1) {
            $block_query['meta_query']['relation'] = 'AND';
        }

        // Configurer le tri par date ACF (plus récents en premier)
        $block_query['meta_key'] = 'date_sejour';
        $block_query['orderby'] = 'meta_value';
        $block_query['order'] = 'DESC';

        // S'assurer qu'on a des résultats
        $block_query['posts_per_page'] = $block_query['posts_per_page'] ?? 6;
    }

    return $block_query;
}
add_filter('query_loop_block_query_vars', 'filter_sejours_passes_query', 10, 3);

/**
 * Filtre pour personnaliser l'affichage de la date dans les blocs de séjours
 */
function customize_sejour_date_display($date, $format, $post_id)
{
    // Vérifier si c'est un produit avec un champ date_sejour
    if (get_post_type($post_id) === 'product') {
        $date_sejour = get_field('date_sejour', $post_id);

        if ($date_sejour) {
            // Si c'est un array, prendre la première valeur
            if (is_array($date_sejour)) {
                $date_sejour = $date_sejour[0] ?? '';
            }

            // Convertir le format ACF (YYYYMMDD) en objet DateTime
            if ($date_sejour && strlen($date_sejour) === 8) {
                $date_obj = DateTime::createFromFormat('Ymd', $date_sejour);

                if ($date_obj) {
                    // Retourner la date formatée avec l'emoji
                    return '📅 ' . $date_obj->format('j M Y');
                }
            }
        }
    }

    return $date;
}
add_filter('get_the_date', 'customize_sejour_date_display', 10, 3);


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
        // Afficher le prix du produit WooCommerce
        global $product;
        if ($product) {
            echo '<p class="price">' . $product->get_price_html() . '</p>';
        }

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

// ============================================================================
// PERSONNALISATION DES EMAILS WOOCOMMERCE
// ============================================================================

/**
 * Ajouter une option pour le numéro de téléphone dans les paramètres du thème
 */
function taulignan_add_theme_options() {
    // Ajouter une option pour le téléphone de contact
    add_option( 'taulignan_contact_phone', '' );
}
add_action( 'after_setup_theme', 'taulignan_add_theme_options' );

/**
 * Personnaliser le sujet des emails de confirmation
 */
function taulignan_custom_email_subject( $subject, $order, $email ) {
    if ( ! is_a( $order, 'WC_Order' ) ) {
        return $subject;
    }
    
    $order_number = $order->get_order_number();
    
    // Email de commande en cours de traitement
    if ( $email->id === 'customer_processing_order' ) {
        $subject = sprintf( '✓ Réservation reçue #%s - %s', $order_number, get_bloginfo( 'name' ) );
    }
    
    // Email de commande terminée
    if ( $email->id === 'customer_completed_order' ) {
        $subject = sprintf( '🎉 Réservation confirmée #%s - %s', $order_number, get_bloginfo( 'name' ) );
    }
    
    return $subject;
}
add_filter( 'woocommerce_email_subject_customer_processing_order', 'taulignan_custom_email_subject', 10, 3 );
add_filter( 'woocommerce_email_subject_customer_completed_order', 'taulignan_custom_email_subject', 10, 3 );

/**
 * Personnaliser le titre des emails
 */
function taulignan_custom_email_heading( $heading, $email ) {
    // Email de commande en cours de traitement
    if ( $email->id === 'customer_processing_order' ) {
        $heading = 'Réservation reçue !';
    }
    
    // Email de commande terminée
    if ( $email->id === 'customer_completed_order' ) {
        $heading = 'Réservation confirmée !';
    }
    
    return $heading;
}
add_filter( 'woocommerce_email_heading_customer_processing_order', 'taulignan_custom_email_heading', 10, 2 );
add_filter( 'woocommerce_email_heading_customer_completed_order', 'taulignan_custom_email_heading', 10, 2 );

/**
 * Personnaliser le texte "Produit" par "Séjour" dans les emails
 */
function taulignan_email_order_items_table( $order, $args ) {
    // Personnalisation déjà gérée dans le template email-order-details.php
}

/**
 * Ajouter des informations de séjour dans l'email
 */
function taulignan_add_sejour_info_to_email( $order, $sent_to_admin, $plain_text, $email ) {
    // Récupérer les items de la commande
    $items = $order->get_items();
    
    foreach ( $items as $item ) {
        $product = $item->get_product();
        if ( ! $product ) {
            continue;
        }
        
        // Récupérer la date du séjour si elle existe (champ ACF)
        $date_sejour = get_field( 'date_sejour', $product->get_id() );
        
        if ( $date_sejour ) {
            // Formater la date
            if ( is_array( $date_sejour ) ) {
                $date_sejour = $date_sejour[0] ?? '';
            }
            
            if ( $date_sejour && strlen( $date_sejour ) === 8 ) {
                $date_obj = DateTime::createFromFormat( 'Ymd', $date_sejour );
                if ( $date_obj ) {
                    // Stocker la date formatée comme meta de l'item
                    $item->add_meta_data( 'Date du séjour', $date_obj->format( 'd/m/Y' ), true );
                    $item->save();
                }
            }
        }
    }
}
add_action( 'woocommerce_email_before_order_table', 'taulignan_add_sejour_info_to_email', 10, 4 );

/**
 * Modifier le texte du bouton "View Order" dans les emails
 */
function taulignan_change_view_order_text( $text ) {
    return 'Voir ma réservation';
}
add_filter( 'woocommerce_email_order_details_button_text', 'taulignan_change_view_order_text' );

/**
 * Désactiver les améliorations d'email WooCommerce par défaut pour utiliser notre design
 */
function taulignan_disable_wc_email_improvements() {
    // Nous utilisons notre propre design, donc on garde les améliorations désactivées
    // pour avoir un contrôle total sur le design
    return false;
}
add_filter( 'pre_option_woocommerce_email_improvements_enabled', 'taulignan_disable_wc_email_improvements' );

/**
 * Personnaliser les couleurs des emails WooCommerce dans les paramètres
 */
function taulignan_email_settings_defaults( $settings ) {
    // Définir les couleurs par défaut selon le thème
    $custom_colors = array(
        'woocommerce_email_background_color' => '#F5F2E8',
        'woocommerce_email_body_background_color' => '#ffffff',
        'woocommerce_email_base_color' => '#B8A3D1',
        'woocommerce_email_text_color' => '#000000',
        'woocommerce_email_footer_text_color' => '#6e5b7c',
    );
    
    foreach ( $custom_colors as $key => $value ) {
        if ( ! get_option( $key ) ) {
            update_option( $key, $value );
        }
    }
    
    return $settings;
}
add_filter( 'woocommerce_email_settings', 'taulignan_email_settings_defaults' );

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

/**
 * Ajouter un message personnalisé dans le footer des emails
 */
function taulignan_email_footer_text() {
    return sprintf(
        '<p style="text-align: center; margin: 16px 0 0; color: #8B7355; font-size: 14px;">Belle journée à vous ! 🌿</p>'
    );
}
add_action( 'woocommerce_email_footer', 'taulignan_email_footer_text', 5 );

/**
 * Open a preview e-mail.
 *
 * @return null
 */
function previewEmail()
{

    if (is_admin()) {
        $default_path = WC()->plugin_path() . '/templates/';

        $files = scandir($default_path . 'emails');
        $exclude = array('.', '..', 'email-header.php', 'email-footer.php', 'plain');
        $list = array_diff($files, $exclude);
    ?><form method="get" action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php">
            <input type="hidden" name="order" value="2055">
            <input type="hidden" name="action" value="previewemail">
            <select name="file">
                <?php
                foreach ($list as $item) { ?>
                    <option value="<?php echo $item; ?>"><?php echo str_replace('.php', '', $item); ?></option>
                <?php } ?>
            </select><input type="submit" value="Go">
        </form><?php
                global $order;
                $order = new WC_Order($_GET['order']);
                wc_get_template('emails/email-header.php', array('order' => $order));


                wc_get_template('emails/' . $_GET['file'], array('order' => $order));
                wc_get_template('emails/email-footer.php', array('order' => $order));
            }
            return null;
        }

        add_action('wp_ajax_previewemail', 'previewEmail');


/**
 * Désactiver le preload agressif de WordPress/WooCommerce
 */
function taulignan_disable_resource_hints()
{
    // Retirer les resource hints (preload/prefetch) pour les scripts non critiques
    remove_action('wp_head', 'wp_resource_hints', 2);

    // Ou plus spécifiquement, désactiver pour WooCommerce
    add_filter('woocommerce_defer_transient_notices', '__return_true');
}
add_action('init', 'taulignan_disable_resource_hints');

/**
 * Désactiver le preload uniquement pour les scripts non utilisés
 */
function taulignan_remove_unused_preloads($urls, $relation_type)
{
    // Liste des scripts à ne PAS précharger
    $unused_scripts = [
        'mini-cart-contents-block',
        'blocks-checkout',
        'blocks-components',
        'admin/sanitize',
        'style-engine',
        'wordcount',
        'warning',
        'dom-ready',
        'autop',
        'a11y',
        'primitives',
        'navigation/view',
    ];

    if ($relation_type === 'preload') {
        foreach ($unused_scripts as $script) {
            foreach ($urls as $key => $url) {
                if (strpos($url['href'], $script) !== false) {
                    unset($urls[$key]);
                }
            }
        }
    }

    return $urls;
}
add_filter('wp_resource_hints', 'taulignan_remove_unused_preloads', 10, 2);