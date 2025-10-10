<?php
/**
 * Functions.php - Thème enfant Taulignan
 * 
 * Ce fichier contient toutes les fonctionnalités personnalisées du thème
 */

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
    // require_once APP_THEME_DIR . '/inc/automatic-dates.php';
    // require_once APP_THEME_DIR . '/inc/add-june-2025-dates.php';
}


// ============================================================================
// CONFIGURATION DU THÈME
// ============================================================================

/**
 * Fonction utilitaire pour charger les assets depuis le manifest
 */
function get_manifest_asset($manifest, $key) {
    if (isset($manifest[$key]) && isset($manifest[$key]['file'])) {
        return get_template_directory_uri() . "/dist/" . $manifest[$key]['file'];
    }
    return false;
}

/**
 * Fonction utilitaire pour récupérer le manifest complet
 */
function get_theme_manifest() {
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
function get_asset_url($asset_key) {
    $manifest = get_theme_manifest();
    
    if ($manifest && isset($manifest[$asset_key])) {
        return get_template_directory_uri() . "/dist/" . $manifest[$asset_key]['file'];
    }
    
    return false;
}

/**
 * Chargement des assets via le manifest.json
 */
function load_vitejs_assets(): void
{
    // Debug: afficher le mode actuel
    echo '<!-- WP_DEBUG status: ' . (defined('WP_DEBUG') && WP_DEBUG ? 'ENABLED' : 'DISABLED') . ' -->';
    
    if (defined('WP_DEBUG') && WP_DEBUG) {
        echo '<!-- WP_DEBUG is enabled - using Vite dev server -->';
        // Mode développement - charger depuis Vite dev server
        echo '<script type="module" src="http://localhost:5173/@vite/client"></script>';
        echo '<script type="module" src="http://localhost:5173/assets/main.js"></script>';
    } elseif (is_dir(get_theme_file_path() . "/dist")) {
        echo '<!-- Using compiled assets from manifest -->';
        $manifest_path = get_theme_file_path() . "/dist/manifest.json";
        
        if (file_exists($manifest_path)) {
            $data = file_get_contents($manifest_path);
            $manifest = json_decode($data, true);
            
            if ($manifest && is_array($manifest)) {
                // === CHARGEMENT DES STYLES CSS ===
                
                // CSS principal (main.css)
                if ($css_main = get_manifest_asset($manifest, 'css/main')) {
                    echo '<!-- Loading CSS Main: ' . $css_main . ' -->';
                    wp_enqueue_style(
                        "taulignan-main",
                        $css_main,
                        [],
                        '1.0.0'
                    );
                } else {
                    echo '<!-- CSS Main not found in manifest -->';
                }
                
                // CSS de l'éditeur (editor-style.css)
                if ($css_editor = get_manifest_asset($manifest, 'css/editor-style')) {
                    wp_enqueue_style(
                        "taulignan-editor",
                        $css_editor,
                        [],
                        '1.0.0'
                    );
                }
                
                // CSS Swiper depuis CDN
                wp_enqueue_style(
                    'swiper-cdn',
                    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
                    [],
                    '11.0.0'
                );
                
                // === CHARGEMENT DES SCRIPTS JS ===
                
                // jQuery (dépendance)
                wp_enqueue_script('jquery');
                
                // Chargement de Swiper depuis CDN
                wp_enqueue_script(
                    'swiper-cdn',
                    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
                    [],
                    '11.0.0',
                    true
                );
                
                // Chargement de tous les scripts JavaScript
                $scripts = [
                    'taulignan' => ['jquery'],
                    'navigation' => ['jquery'],
                    'parallax' => ['jquery', 'wp-element', 'wp-hooks', 'wp-block-editor', 'wp-components', 'wp-i18n'],
                    'customizer' => ['jquery', 'customize-preview'],
                    'acf' => ['jquery', 'wp-element', 'wp-hooks', 'wp-block-editor', 'wp-components', 'wp-i18n'],
                    'custom-swiper' => ['swiper-cdn']
                ];
                
                foreach ($scripts as $script_name => $dependencies) {
                    if ($js_file = get_manifest_asset($manifest, $script_name)) {
                        echo '<!-- Loading ' . $script_name . ' JS: ' . $js_file . ' -->';
                        wp_enqueue_script(
                            "taulignan-{$script_name}",
                            $js_file,
                            $dependencies,
                            '1.0.0',
                            true
                        );
                    } else {
                        echo '<!-- ' . $script_name . ' JS not found in manifest -->';
                    }
                }
            }
        }
    }
}

// add_action("wp_enqueue_scripts", "load_vitejs_assets"); // Désactivé - utilise ViteAssetsLoader à la place

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
function taulignan_theme_setup() {
    // Ajoute le support des images mises en avant
    add_theme_support( 'post-thumbnails' );
    
    // Support FSE (Full Site Editing)
    add_theme_support( 'block-templates' );
    add_theme_support( 'block-template-parts' );
    add_theme_support( 'block-patterns' );
    add_theme_support('editor-styles');
    add_editor_style('/dist/css/editor-style.css'); // make sure path reflects where the file is located


    // Support des éditeurs de blocs
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    
    // Support des couleurs et typographies personnalisées
    add_theme_support( 'custom-background' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'custom-header' );
    
    // Support des formats de publication
    add_theme_support( 'post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat'
    ) );
    
    // Support de l'alignement large et pleine largeur
    add_theme_support( 'align-wide' );
    
    // Support des menus de navigation
    add_theme_support( 'menus' );
    
    // Support HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ) );
}
add_action( 'after_setup_theme', 'taulignan_theme_setup' );

/**
 * Enregistrement des menus de navigation
 */
function register_my_menus() {
    register_nav_menus(
        array(
            'header-menu' => __( 'Header Menu' )
        )
    );
}
add_action( 'init', 'register_my_menus' );

// ============================================================================
// CHARGEMENT DES SCRIPTS
// ============================================================================

/**
 * Chargement des styles et scripts du thème
 */
// function taulignan_enqueue_assets() {
//     // Styles CSS compilés par Vite
// //     wp_enqueue_style('taulignan-main', get_stylesheet_directory_uri() . '/dist/css/main.css', array(), '1.0.0');
    
// //     // Scripts JavaScript compilés par Vite
// //     wp_enqueue_script('jquery');
// //     wp_enqueue_script('taulignan-js', get_stylesheet_directory_uri() . '/dist/js/taulignan.js', array('jquery'), '1.0.0', true);
// //     wp_enqueue_script('navigation-js', get_stylesheet_directory_uri() . '/dist/js/navigation.js', array('jquery'), '1.0.0', true);
// //     wp_enqueue_script('parallax-js', get_stylesheet_directory_uri() . '/dist/js/parallax.js', array('jquery', 'wp-element', 'wp-hooks', 'wp-block-editor', 'wp-components', 'wp-i18n'), '1.0.0', true);
// //     wp_enqueue_script('customizer-js', get_stylesheet_directory_uri() . '/dist/js/customizer.js', array('jquery', 'customize-preview'), '1.0.0', true);
// //     wp_enqueue_script('acf-js', get_stylesheet_directory_uri() . '/dist/js/acf.js', array('jquery', 'wp-element', 'wp-hooks', 'wp-block-editor', 'wp-components', 'wp-i18n'), '1.0.0', true);
// //     wp_enqueue_script('swiper-js', get_stylesheet_directory_uri() . '/dist/js/custom-swiper.js', array(), '1.0.0', true);
// // }
// add_action( 'wp_enqueue_scripts', 'taulignan_enqueue_assets' );

/**
 * Styles et scripts pour l'éditeur de blocs
 */
function taulignan_block_editor_assets() {
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
function taulignan_register_block_styles() {
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
add_action( 'init', 'taulignan_register_block_styles' );

// ============================================================================
// DÉSACTIVATION DES STYLES PAR DÉFAUT
// ============================================================================

/**
 * Désactiver les styles de blocs par défaut de WordPress
 */
function taulignan_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // WooCommerce si présent
}
add_action( 'wp_enqueue_scripts', 'taulignan_remove_wp_block_library_css' );

// Désactiver aussi dans l'éditeur
add_action( 'enqueue_block_editor_assets', 'taulignan_remove_wp_block_library_css', 100 );

// ============================================================================
// MODIFICATIONS DES BLOCS
// ============================================================================

/**
 * Filtrer le template-part header pour ajouter la classe site-header
 */
function taulignan_add_header_class( $block_content, $block ) {
    // Vérifier si c'est le template-part header
    if ( isset( $block['blockName'] ) && 'core/template-part' === $block['blockName'] ) {
        if ( isset( $block['attrs']['slug'] ) && 'header' === $block['attrs']['slug'] ) {
            // Essayer plusieurs patterns possibles
            $patterns = array(
                '/^<div class="wp-block-template-part"([^>]*)>/',
                '/<div class="wp-block-template-part"([^>]*)>/',
                '/^<div([^>]*class="[^"]*wp-block-template-part[^"]*"[^>]*)>/',
            );
            
            foreach ( $patterns as $pattern ) {
                if ( preg_match( $pattern, $block_content ) ) {
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
            $pos = strrpos( $block_content, '</div>' );
            if ( $pos !== false ) {
                $block_content = substr_replace( $block_content, '</header>', $pos, 6 );
            }
        }
    }
    return $block_content;
}
add_filter( 'render_block', 'taulignan_add_header_class', 10, 2 );

/**
 * Fonction pour remplacer figure par div dans les blocs d'images
 */
function remplacer_figure_par_div($block_content, $block) {
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
function taulignan_add_group_classes($block_content, $block) {
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
        if (strpos($block_content, 'wp-block-paragraph') !== false && 
            strpos($block_content, 'wp-block-heading') !== false) {
            $block_content = str_replace(
                'class="wp-block-group',
                'class="wp-block-group content-box',
                $block_content
            );
        }
    }
    
    return $block_content;
}
add_filter('render_block', 'taulignan_add_group_classes', 10, 2 );

// ============================================================================
// MODIFICATIONS DES GALERIES
// ============================================================================

/**
 * Modifier les galeries natives WordPress
 */
add_filter('post_gallery', 'galery_custom', 10, 2);
function galery_custom($output, $attr) {
    global $post;

    // Récupère les IDs des images de la galerie
    $ids_array = explode(',', $attr['ids']);

    $output = '<div class="gallery">'; // Ajoute votre classe personnalisée

    foreach($ids_array as $id) {
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
// ATTRIBUTS PERSONNALISÉS POUR LES BLOCS
// ============================================================================

/**
 * Ajoute un attribut personnalisé 'isDecorative' à tous les blocs Image
 */
function example_add_attribute_to_image_blocks( $args, $block_type ) {
    // Only add the attribute to Image blocks.
    if ( $block_type === 'core/image' ) {
        if ( ! isset( $args['attributes'] ) ) {
            $args['attributes'] = array();
        }

        $args['attributes']['isDecorative'] = array(
            'type'    => 'boolean',
            'default' => false,
        );
    }

    return $args;
}
add_filter( 'register_block_type_args', 'example_add_attribute_to_image_blocks', 10, 2 );

// ============================================================================
// FONCTIONNALITÉS DIVERSES
// ============================================================================

/**
 * Autoriser l'importation des SVG
 */
function taulignan_allow_svg_upload($mimes) {
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
function desactiver_patterns_distants() {
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
function modifier_galeries_natives($output, $attr, $instance) {
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
function modifier_blocs_galerie_gutenberg($block_content, $block) {
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

/**
 * Debug pour vérifier le chargement de Swiper
 */
function debug_swiper_loading() {
    if (current_user_can('manage_options')) { // Seulement pour les administrateurs
        ?>
        <script>
        console.log('=== DEBUG SWIPER ===');
        console.log('Swiper disponible:', typeof Swiper !== 'undefined');
        console.log('TaulignanSwiperModule disponible:', typeof window.TaulignanSwiperModule !== 'undefined');
        console.log('Éléments .swiper trouvés:', document.querySelectorAll('.swiper').length);
        document.querySelectorAll('.swiper').forEach((el, index) => {
            console.log(`Galerie ${index + 1}:`, el, 'Initialisé:', el.hasAttribute('data-swiper-initialized'));
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'debug_swiper_loading');

// ============================================================================

// TESTS ET DÉBOGAGE (À SUPPRIMER APRÈS UTILISATION)
// ============================================================================

// Test temporaire - SUPPRIMER APRÈS TEST
add_action('init', function() {
    if (function_exists('register_block_pattern')) {
        error_log('Taulignan: Fonction register_block_pattern disponible');
    } else {
        error_log('Taulignan: Fonction register_block_pattern NON disponible');
    }
}, 5);

// ============================================================================
// INCLUSION DES PARTS PERSONNALISÉS
// ============================================================================

/**
 * Fonction pour inclure les template parts PHP dans les templates FSE
 */
function include_php_template_part($part_name) {
    $part_file = get_template_directory() . '/parts/' . $part_name . '.php';
    if (file_exists($part_file)) {
        include $part_file;
    }
}

/**
 * Shortcode pour afficher les informations de séjours
 */
function infosejours_shortcode($atts) {
    // Vérifier qu'on est sur une page produit et que ACF est disponible
    if (!is_product() || !function_exists('get_field')) {
        return '';
    }
    
    ob_start();
    ?>

    <?php 
    // Programme du séjour
    $programme = get_field('programme'); 
    if ($programme) : 
    ?>
    <div class="sejour-field sejour-programme">
        <p class="field-title">Programme du séjour</p>
        <div class="field-content">
            <ul class="programme-list">
            <?php
            // Vérifier si c'est un array (répéteur/groupe) ou une string
            if (is_array($programme)) {
                // Si c'est un répéteur ou un array
                foreach ($programme as $item) {
                    if (is_array($item)) {
                        // Si chaque item est aussi un array (sous-champs)
                        foreach ($item as $key => $value) {
                            if (!empty($value)) {
                                echo '<li class="programme-item">';
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
                echo wp_kses_post($programme);
            }
            ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <?php 
    // Prix inclus
    $prix_inclus = get_field('prix_inclus'); 
    if ($prix_inclus) : 
    ?>
    <div class="sejour-field sejour-inclus">
        <h3 class="field-title">Le prix comprend</h3>
        <div class="field-content">
            <?php 
            if (is_array($prix_inclus)) {
                echo '<ul class="prix-list">';
                foreach ($prix_inclus as $item) {
                    echo '<li class="prix-item">' . wp_kses_post($item) . '</li>';
                }
                echo '</ul>';
            } else {
                echo wp_kses_post($prix_inclus);
            }
            ?>
        </div>
    </div>
    <?php endif; ?>

    <?php 
    // Prix non inclus
    $prix_non_inclus = get_field('prix_non_inclus'); 
    if ($prix_non_inclus) : 
    ?>
    <div class="sejour-field sejour-non-inclus">
        <h3 class="field-title">Non inclus</h3>
        <div class="field-content">
            <?php 
            if (is_array($prix_non_inclus)) {
                echo '<ul class="prix-list">';
                foreach ($prix_non_inclus as $item) {
                    echo '<li class="prix-item">' . wp_kses_post($item) . '</li>';
                }
                echo '</ul>';
            } else {
                echo wp_kses_post($prix_non_inclus);
            }
            ?>
        </div>
    </div>
    <?php endif; ?>

    <?php 
    // Informations pratiques
    $infos_pratiques = get_field('informations_pratiques'); 
    if ($infos_pratiques) : 
    ?>
    <div class="sejour-field sejour-infos">
        <h3 class="field-title">Informations pratiques</h3>
        <div class="field-content">
            <?php echo wp_kses_post($infos_pratiques); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php 
    // Galerie supplémentaire
    $galerie = get_field('galerie_supplementaire'); 
    if ($galerie && is_array($galerie)) : 
    ?>
    <div class="sejour-field sejour-galerie">
        <h3 class="field-title">Plus d'images</h3>
        <div class="galerie-grid">
            <?php foreach ($galerie as $image) : ?>
                <?php if (isset($image['sizes']['medium'])) : ?>
                <div class="galerie-item">
                    <img src="<?php echo esc_url($image['sizes']['medium']); ?>" 
                         alt="<?php echo esc_attr($image['alt']); ?>" 
                         loading="lazy">
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php
    return ob_get_clean();
}
add_shortcode('infosejours', 'infosejours_shortcode');

// ============================================================================
// PERSONNALISATION WOOCOMMERCE - PRODUCT DETAILS
// ============================================================================

/**
 * Supprimer les onglets des produits WooCommerce
 */
function remove_product_tabs($tabs) {
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
function disable_woocommerce_reviews() {
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
function custom_woocommerce_breadcrumb($crumbs, $breadcrumb) {
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
function custom_woocommerce_breadcrumb_defaults($defaults) {
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
function change_stock_text($translated_text, $text, $domain) {
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
function filter_sejours_passes_query($block_query, $block, $page) {
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
function customize_sejour_date_display($date, $format, $post_id) {
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
 * Fonction de debug pour diagnostiquer le champ date_sejour
 */
function debug_date_sejour_field() {
    if (current_user_can('manage_options') && isset($_GET['debug_date_field'])) {
        $products = get_posts(array(
            'post_type' => 'product',
            'posts_per_page' => 3,
            'meta_query' => array(
                array(
                    'key' => 'date_sejour',
                    'compare' => 'EXISTS'
                )
            )
        ));
        
        echo '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 1px solid #ccc;">';
        echo '<h3>Debug Champ date_sejour</h3>';
        
        foreach ($products as $product) {
            $date_field = get_field('date_sejour', $product->ID);
            echo '<h4>' . $product->post_title . '</h4>';
            echo '<p><strong>Type:</strong> ' . gettype($date_field) . '</p>';
            echo '<p><strong>Valeur brute:</strong> ';
            var_dump($date_field);
            echo '</p>';
            
            if (is_array($date_field)) {
                echo '<p><strong>Premier élément:</strong> ' . ($date_field[0] ?? 'Aucun') . '</p>';
            }
            echo '<hr>';
        }
        
        echo '</div>';
    }
}
add_action('wp_footer', 'debug_date_sejour_field');

/**
 * Corriger les warnings liés aux walkers WordPress
 */
function fix_walker_warnings() {
    // Supprimer les erreurs liées aux walkers si elles ne sont pas critiques
    if (current_user_can('manage_options')) {
        // Masquer les warnings de walker en mode debug
        add_filter('wp_dropdown_cats', function($output) {
            if (is_admin() && !wp_doing_ajax()) {
                return $output;
            }
            return $output;
        });
    }
}
add_action('init', 'fix_walker_warnings');


function add_date_sejour_to_product_page() {
    if (is_product()) {
        echo '<div class="sejour-field sejour-date">';
        echo '<p class="date">' . get_field('date_sejour') . '</p>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'add_date_sejour_to_product_page', 1);

function group_price_to_formule() {
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

