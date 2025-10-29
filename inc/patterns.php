<?php
/**
 * Fichier d'enregistrement automatique des patterns
 * 
 * Ce fichier permet d'enregistrer automatiquement tous les patterns
 * du thème enfant sans avoir à les déclarer manuellement.
 * 
 * @package Taulignan_U_Child
 * @since 1.0.0
 */

// Empêcher l'accès direct
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enregistre automatiquement tous les patterns du thème
 */
function taulignan_auto_register_patterns() {
    // Vérifier que la fonction register_block_pattern existe
    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    // Chemin vers le dossier patterns
    $patterns_dir = get_template_directory() . '/patterns/';
    
    // Vérifier que le dossier existe
    if ( ! is_dir( $patterns_dir ) ) {
        return;
    }

    // Récupérer tous les fichiers PHP du dossier patterns
    $pattern_files = glob( $patterns_dir . '*.php' );
    
    if ( empty( $pattern_files ) ) {
        return;
    }

    // Parcourir chaque fichier de pattern
    foreach ( $pattern_files as $pattern_file ) {
        // Lire le contenu du fichier
        $pattern_content = file_get_contents( $pattern_file );
        
        if ( $pattern_content === false ) {
            continue;
        }

        // Extraire les métadonnées du pattern
        $pattern_data = taulignan_parse_pattern_metadata( $pattern_content );
        
        if ( $pattern_data ) {
            // Enregistrer le pattern
            register_block_pattern(
                $pattern_data['slug'],
                array(
                    'title' => $pattern_data['title'],
                    'description' => $pattern_data['description'],
                    'categories' => $pattern_data['categories'],
                    'keywords' => $pattern_data['keywords'],
                    'viewportWidth' => $pattern_data['viewportWidth'],
                    'content' => $pattern_data['content']
                )
            );
        }
    }
}

/**
 * Parse les métadonnées d'un fichier de pattern
 * 
 * @param string $content Contenu du fichier de pattern
 * @return array|false Données du pattern ou false si erreur
 */
function taulignan_parse_pattern_metadata( $content ) {
    // Extraire le contenu HTML (après la balise PHP fermante)
    $html_pattern = '/\?>\s*(.*?)$/s';
    if ( ! preg_match( $html_pattern, $content, $matches ) ) {
        return false;
    }
    
    $html_content = trim( $matches[1] );
    
    // Extraire les métadonnées du commentaire PHP
    $metadata_pattern = '/\*\s*Title:\s*(.+?)(?:\r?\n|\r)/';
    preg_match( $metadata_pattern, $content, $title_match );
    $title = isset( $title_match[1] ) ? trim( $title_match[1] ) : '';
    
    $metadata_pattern = '/\*\s*Slug:\s*(.+?)(?:\r?\n|\r)/';
    preg_match( $metadata_pattern, $content, $slug_match );
    $slug = isset( $slug_match[1] ) ? trim( $slug_match[1] ) : '';
    
    $metadata_pattern = '/\*\s*Description:\s*(.+?)(?:\r?\n|\r)/';
    preg_match( $metadata_pattern, $content, $desc_match );
    $description = isset( $desc_match[1] ) ? trim( $desc_match[1] ) : '';
    
    $metadata_pattern = '/\*\s*Categories:\s*(.+?)(?:\r?\n|\r)/';
    preg_match( $metadata_pattern, $content, $cat_match );
    $categories = isset( $cat_match[1] ) ? array_map( 'trim', explode( ',', $cat_match[1] ) ) : array( 'featured' );
    
    $metadata_pattern = '/\*\s*Keywords:\s*(.+?)(?:\r?\n|\r)/';
    preg_match( $metadata_pattern, $content, $keywords_match );
    $keywords = isset( $keywords_match[1] ) ? array_map( 'trim', explode( ',', $keywords_match[1] ) ) : array();
    
    $metadata_pattern = '/\*\s*Viewport Width:\s*(\d+)/';
    preg_match( $metadata_pattern, $content, $viewport_match );
    $viewportWidth = isset( $viewport_match[1] ) ? intval( $viewport_match[1] ) : 1200;
    
    // Vérifier que les données essentielles sont présentes
    if ( empty( $title ) || empty( $slug ) || empty( $html_content ) ) {
        return false;
    }
    
    return array(
        'title' => $title,
        'slug' => $slug,
        'description' => $description,
        'categories' => $categories,
        'keywords' => $keywords,
        'viewportWidth' => $viewportWidth,
        'content' => $html_content
    );
}

/**
 * Hook pour enregistrer les patterns
 */
add_action( 'init', 'taulignan_auto_register_patterns' );

