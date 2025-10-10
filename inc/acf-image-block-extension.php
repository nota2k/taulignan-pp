<?php
/**
 * Extension avancée du bloc image avec ACF Pro
 * 
 * Cette version utilise les hooks ACF pour étendre spécifiquement
 * le bloc image natif WordPress avec des champs personnalisés
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajouter des champs ACF au bloc image natif
 * Version avancée avec hooks ACF
 */
function taulignan_extend_image_block_advanced() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Groupe de champs pour le bloc image
    acf_add_local_field_group(array(
        'key' => 'group_image_block_advanced',
        'title' => 'Champs personnalisés - Bloc Image',
        'fields' => array(
            array(
                'key' => 'field_image_custom_number',
                'label' => 'Nombre personnalisé',
                'name' => 'image_custom_number',
                'type' => 'number',
                'instructions' => 'Entrez un nombre personnalisé pour cette image (ex: numéro de page, référence, etc.)',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => 'acf-field-image-number',
                    'id' => 'image-custom-number',
                ),
                'default_value' => '',
                'placeholder' => 'Ex: 42',
                'prepend' => 'N°',
                'append' => '',
                'min' => 0,
                'max' => 9999,
                'step' => 1,
            ),
            array(
                'key' => 'field_image_custom_text',
                'label' => 'Texte personnalisé',
                'name' => 'image_custom_text',
                'type' => 'text',
                'instructions' => 'Ajoutez un texte personnalisé pour cette image',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => 'acf-field-image-text',
                    'id' => 'image-custom-text',
                ),
                'default_value' => '',
                'placeholder' => 'Votre texte ici...',
                'prepend' => '',
                'append' => '',
                'maxlength' => 100,
            ),
            array(
                'key' => 'field_image_custom_select',
                'label' => 'Catégorie d\'image',
                'name' => 'image_custom_select',
                'type' => 'select',
                'instructions' => 'Choisissez une catégorie pour cette image',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '33',
                    'class' => 'acf-field-image-category',
                    'id' => 'image-custom-category',
                ),
                'choices' => array(
                    'decoration' => 'Décoration',
                    'illustration' => 'Illustration',
                    'photo' => 'Photo',
                    'graphic' => 'Graphique',
                    'other' => 'Autre',
                ),
                'default_value' => '',
                'allow_null' => 1,
                'multiple' => 0,
                'ui' => 1,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => 'Sélectionner...',
            ),
            array(
                'key' => 'field_image_custom_color',
                'label' => 'Couleur d\'accent',
                'name' => 'image_custom_color',
                'type' => 'color_picker',
                'instructions' => 'Choisissez une couleur d\'accent pour cette image',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '33',
                    'class' => 'acf-field-image-color',
                    'id' => 'image-custom-color',
                ),
                'default_value' => '#6b764c',
                'enable_opacity' => 1,
                'return_format' => 'string',
            ),
            array(
                'key' => 'field_image_custom_boolean',
                'label' => 'Image mise en avant',
                'name' => 'image_custom_boolean',
                'type' => 'true_false',
                'instructions' => 'Cochez si cette image doit être mise en avant',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '33',
                    'class' => 'acf-field-image-featured',
                    'id' => 'image-custom-featured',
                ),
                'message' => 'Mettre cette image en avant',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Oui',
                'ui_off_text' => 'Non',
            ),
            array(
                'key' => 'field_image_custom_repeater',
                'label' => 'Mots-clés personnalisés',
                'name' => 'image_custom_keywords',
                'type' => 'repeater',
                'instructions' => 'Ajoutez des mots-clés personnalisés pour cette image',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'acf-field-image-keywords',
                    'id' => 'image-custom-keywords',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 10,
                'layout' => 'table',
                'button_label' => 'Ajouter un mot-clé',
                'sub_fields' => array(
                    array(
                        'key' => 'field_image_keyword_text',
                        'label' => 'Mot-clé',
                        'name' => 'keyword',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => 'Entrez un mot-clé',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => 50,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'core/image',
                ),
            ),
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/image',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Champs personnalisés avancés pour étendre le bloc image WordPress',
        'show_in_rest' => 1,
    ));
}

/**
 * Initialiser l'extension avancée du bloc image
 */
function taulignan_init_image_block_extension() {
    if (function_exists('acf_add_local_field_group')) {
        taulignan_extend_image_block_advanced();
    }
}

// Hook pour initialiser l'extension
add_action('acf/init', 'taulignan_init_image_block_extension');

/**
 * Rendre les champs ACF visibles dans l'éditeur de blocs
 */
function taulignan_show_acf_fields_in_image_block() {
    if (function_exists('acf_get_field_groups')) {
        // Activer ACF pour tous les blocs
        add_filter('acf/settings/remove_wp_meta_box', '__return_false');
        
        // Activer ACF dans l'éditeur de blocs
        add_filter('acf/settings/show_admin', '__return_true');
    }
}

add_action('init', 'taulignan_show_acf_fields_in_image_block');

/**
 * Fonction utilitaire pour récupérer la valeur d'un champ ACF dans un bloc image
 * 
 * @param string $field_name Nom du champ ACF
 * @param int $post_id ID du post (optionnel)
 * @return mixed Valeur du champ ou false si non trouvé
 */
function taulignan_get_image_block_field($field_name, $post_id = null) {
    return taulignan_get_block_acf_field('core/image', $field_name, $post_id);
}

/**
 * Fonction utilitaire pour récupérer la valeur d'un champ ACF dans un bloc image
 * Version avec recherche dans tous les blocs image
 * 
 * @param string $field_name Nom du champ ACF
 * @param int $post_id ID du post (optionnel)
 * @return array Tableau des valeurs trouvées
 */
function taulignan_get_all_image_block_fields($field_name, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $values = array();
    $blocks = parse_blocks(get_post_field('post_content', $post_id));
    
    foreach ($blocks as $block) {
        if ($block['blockName'] === 'core/image' && isset($block['attrs']['data'][$field_name])) {
            $values[] = $block['attrs']['data'][$field_name];
        }
    }
    
    return $values;
}

/**
 * Exemple d'utilisation dans un template
 * 
 * // Récupérer le nombre personnalisé d'une image
 * $custom_number = taulignan_get_image_block_field('image_custom_number');
 * if ($custom_number) {
 *     echo '<span class="image-number">N°' . $custom_number . '</span>';
 * }
 * 
 * // Récupérer tous les nombres personnalisés des images
 * $all_numbers = taulignan_get_all_image_block_fields('image_custom_number');
 * if (!empty($all_numbers)) {
 *     echo '<div class="image-numbers">';
 *     foreach ($all_numbers as $number) {
 *         echo '<span class="number">' . $number . '</span>';
 *     }
 *     echo '</div>';
 * }
 */
