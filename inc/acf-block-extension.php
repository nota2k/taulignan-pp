<?php
/**
 * Extension ACF pour les blocs de groupe WordPress
 * Affiche le groupe de champs group_68aa9ae61a5e6 dans les réglages des blocs
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le script d'extension pour l'éditeur
 */
function taulignan_enqueue_acf_block_extension() {
    wp_enqueue_script(
        'taulignan-acf-block-extension',
        get_template_directory_uri() . '/js/acf-block-extension.js',
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-components', 'wp-element'),
        '1.0.0',
        true
    );
    
    // Localiser le script avec les données ACF
    wp_localize_script('taulignan-acf-block-extension', 'taulignanAcfData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('taulignan_acf_nonce'),
        'acfGroupKey' => 'group_68aa9ae61a5e6'
    ));
}
add_action('enqueue_block_editor_assets', 'taulignan_enqueue_acf_block_extension');

/**
 * Ajouter les attributs ACF aux blocs de groupe
 */
function taulignan_add_acf_attributes_to_group_blocks($args, $block_type) {
    if ($block_type === 'core/group') {
        if (!isset($args['attributes'])) {
            $args['attributes'] = array();
        }

        // Ajouter les attributs pour le groupe de champs ACF
        $args['attributes']['acfGroupKey'] = array(
            'type' => 'string',
            'default' => 'group_68aa9ae61a5e6'
        );
        
        // Attributs pour les champs ACF (à adapter selon vos besoins)
        $args['attributes']['acfField1'] = array(
            'type' => 'string',
            'default' => ''
        );
        
        $args['attributes']['acfField2'] = array(
            'type' => 'string',
            'default' => ''
        );
        
        $args['attributes']['acfField3'] = array(
            'type' => 'string',
            'default' => ''
        );
    }

    return $args;
}
add_filter('register_block_type_args', 'taulignan_add_acf_attributes_to_group_blocks', 10, 2);

/**
 * Fonction AJAX pour récupérer les champs ACF
 */
function taulignan_get_acf_fields() {
    // Vérifier le nonce
    if (!wp_verify_nonce($_POST['nonce'], 'taulignan_acf_nonce')) {
        wp_die('Nonce invalide');
    }

    // Vérifier que ACF est installé
    if (!function_exists('acf_get_field_groups')) {
        wp_send_json_error('ACF non installé');
    }

    // Récupérer le groupe de champs
    $field_group = acf_get_field_group('group_68aa9ae61a5e6');
    
    if (!$field_group) {
        wp_send_json_error('Groupe de champs non trouvé');
    }

    // Récupérer les champs du groupe
    $fields = acf_get_fields($field_group);
    
    wp_send_json_success(array(
        'fieldGroup' => $field_group,
        'fields' => $fields
    ));
}
add_action('wp_ajax_taulignan_get_acf_fields', 'taulignan_get_acf_fields');

/**
 * Appliquer les valeurs ACF au rendu des blocs
 */
function taulignan_apply_acf_values_to_blocks($block_content, $block) {
    // Vérifier si c'est un bloc de groupe avec des champs ACF
    if ($block['blockName'] !== 'core/group') {
        return $block_content;
    }

    // Récupérer les attributs ACF
    $acf_group_key = isset($block['attrs']['acfGroupKey']) ? $block['attrs']['acfGroupKey'] : '';
    
    if (empty($acf_group_key) || $acf_group_key !== 'group_68aa9ae61a5e6') {
        return $block_content;
    }

    // Récupérer les valeurs des champs ACF
    $acf_field1 = isset($block['attrs']['acfField1']) ? $block['attrs']['acfField1'] : '';
    $acf_field2 = isset($block['attrs']['acfField2']) ? $block['attrs']['acfField2'] : '';
    $acf_field3 = isset($block['attrs']['acfField3']) ? $block['attrs']['acfField3'] : '';

    // Si des valeurs ACF sont définies, les appliquer
    if (!empty($acf_field1) || !empty($acf_field2) || !empty($acf_field3)) {
        // Ajouter une classe CSS pour identifier les blocs avec ACF
        $block_content = str_replace(
            'class="wp-block-group',
            'class="wp-block-group has-acf-fields',
            $block_content
        );
        
        // Ajouter les valeurs ACF comme attributs data
        $data_attributes = '';
        if (!empty($acf_field1)) $data_attributes .= ' data-acf-field1="' . esc_attr($acf_field1) . '"';
        if (!empty($acf_field2)) $data_attributes .= ' data-acf-field2="' . esc_attr($acf_field2) . '"';
        if (!empty($acf_field3)) $data_attributes .= ' data-acf_field3="' . esc_attr($acf_field3) . '"';
        
        if (!empty($data_attributes)) {
            $block_content = str_replace(
                '<div class="wp-block-group',
                '<div class="wp-block-group"' . $data_attributes,
                $block_content
            );
        }
    }

    return $block_content;
}
add_filter('render_block', 'taulignan_apply_acf_values_to_blocks', 10, 2);

/**
 * Ajouter des styles CSS pour les blocs avec ACF
 */
function taulignan_add_acf_block_styles() {
    ?>
    <style id="taulignan-acf-block-styles">
        /* Styles pour les blocs avec champs ACF */
        .wp-block-group.has-acf-fields {
            position: relative;
        }
        
        .wp-block-group.has-acf-fields::before {
            content: "ACF";
            position: absolute;
            top: -20px;
            right: 0;
            background: #007cba;
            color: white;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 3px;
            z-index: 9999;
        }
        

        
        /* Styles pour les contrôles ACF dans l'éditeur */
        .acf-fields-panel {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 16px;
            margin-top: 16px;
        }
        
        .acf-field-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #1e1e1e;
        }
        
        .acf-field-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 16px;
        }
    </style>
    <?php
}
add_action('wp_head', 'taulignan_add_acf_block_styles');
