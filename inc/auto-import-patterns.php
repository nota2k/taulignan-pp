<?php
/**
 * Auto-import des patterns JSON
 * 
 * Ce fichier importe automatiquement tous les patterns JSON
 * stockés dans le dossier patterns-json/
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Import automatique des patterns JSON
 */
function taulignan_auto_import_patterns() {
    // Vérifier si la fonction register_block_pattern existe
    if (!function_exists('register_block_pattern')) {
        return;
    }
    
    // Chemin vers le dossier des patterns JSON
    $patterns_dir = get_template_directory() . '/patterns-json/';
    
    // Vérifier si le dossier existe
    if (!is_dir($patterns_dir)) {
        error_log('Taulignan: Dossier patterns-json non trouvé: ' . $patterns_dir);
        return;
    }
    
    // Scanner tous les fichiers JSON
    $json_files = glob($patterns_dir . '*.json');
    
    if (empty($json_files)) {
        error_log('Taulignan: Aucun fichier JSON trouvé dans: ' . $patterns_dir);
        return;
    }
    
    $imported_count = 0;
    $errors = array();
    
    foreach ($json_files as $json_file) {
        try {
            // Lire le contenu JSON
            $json_content = file_get_contents($json_file);
            
            if ($json_content === false) {
                $errors[] = 'Impossible de lire le fichier: ' . basename($json_file);
                continue;
            }
            
            // Décoder le JSON
            $pattern_data = json_decode($json_content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = 'Erreur JSON dans ' . basename($json_file) . ': ' . json_last_error_msg();
                continue;
            }
            
            // Vérifier que les données requises sont présentes
            if (empty($pattern_data['title']) || empty($pattern_data['content'])) {
                $errors[] = 'Données manquantes dans ' . basename($json_file) . ': title et content requis';
                continue;
            }
            
            // Préparer les arguments pour register_block_pattern
            $args = array(
                'title' => $pattern_data['title'],
                'content' => $pattern_data['content'],
            );
            
            // Ajouter les propriétés optionnelles
            if (!empty($pattern_data['slug'])) {
                $args['slug'] = $pattern_data['slug'];
            } else {
                // Générer un slug par défaut si aucun n'est fourni
                $args['slug'] = 'taulignan/' . sanitize_title($pattern_data['title']);
            }
            
            if (!empty($pattern_data['description'])) {
                $args['description'] = $pattern_data['description'];
            }
            
            if (!empty($pattern_data['categories'])) {
                $args['categories'] = $pattern_data['categories'];
            }
            
            if (!empty($pattern_data['keywords'])) {
                $args['keywords'] = $pattern_data['keywords'];
            }
            
            if (!empty($pattern_data['viewportWidth'])) {
                $args['viewportWidth'] = $pattern_data['viewportWidth'];
            }
            
            // Enregistrer le pattern avec le slug défini
            $result = register_block_pattern($args['slug'], $args);
            
            if ($result) {
                $imported_count++;
                error_log('Taulignan: Pattern importé avec succès: ' . $args['slug']);
            } else {
                $errors[] = 'Échec de l\'enregistrement du pattern: ' . $args['slug'];
            }
            
        } catch (Exception $e) {
            $errors[] = 'Exception lors de l\'import de ' . basename($json_file) . ': ' . $e->getMessage();
        }
    }
    
    // Log des résultats
    if ($imported_count > 0) {
        error_log("Taulignan: $imported_count patterns importés avec succès");
    }
    
    if (!empty($errors)) {
        error_log('Taulignan: Erreurs lors de l\'import des patterns: ' . implode(', ', $errors));
    }
}

// Hook pour l'import automatique
add_action('init', 'taulignan_auto_import_patterns', 10);

/**
 * Fonction utilitaire pour exporter un pattern en JSON
 * 
 * @param string $pattern_slug Le slug du pattern à exporter
 * @return array|false Les données du pattern ou false si échec
 */
function taulignan_export_pattern_to_json($pattern_slug) {
    // Récupérer le pattern enregistré
    $pattern = WP_Block_Patterns_Registry::get_instance()->get_registered($pattern_slug);
    
    if (!$pattern) {
        return false;
    }
    
    // Préparer les données pour l'export
    $export_data = array(
        'title' => $pattern['title'],
        'slug' => $pattern['name'],
        'content' => $pattern['content'],
        'description' => $pattern['description'] ?? '',
        'categories' => $pattern['categories'] ?? array(),
        'keywords' => $pattern['keywords'] ?? array(),
        'viewportWidth' => $pattern['viewportWidth'] ?? 1200,
    );
    
    return $export_data;
}

/**
 * Fonction pour sauvegarder un pattern en JSON
 * 
 * @param string $pattern_slug Le slug du pattern à sauvegarder
 * @param string $filename Le nom du fichier (sans extension)
 * @return bool True si succès, false sinon
 */
function taulignan_save_pattern_json($pattern_slug, $filename = '') {
    $pattern_data = taulignan_export_pattern_to_json($pattern_slug);
    
    if (!$pattern_data) {
        return false;
    }
    
    // Utiliser le slug comme nom de fichier par défaut
    if (empty($filename)) {
        $filename = $pattern_data['slug'];
    }
    
    // Chemin du fichier JSON
    $json_file = get_template_directory() . '/patterns-json/' . $filename . '.json';
    
    // Créer le dossier s'il n'existe pas
    $patterns_dir = dirname($json_file);
    if (!is_dir($patterns_dir)) {
        wp_mkdir_p($patterns_dir);
    }
    
    // Encoder en JSON avec formatage
    $json_content = json_encode($pattern_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // Sauvegarder le fichier
    $result = file_put_contents($json_file, $json_content);
    
    return $result !== false;
}
