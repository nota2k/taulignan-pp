<?php

/**
 * Extensions ACF pour les blocs natifs WordPress
 * 
 * Ce fichier ajoute des champs ACF personnalisés aux blocs natifs
 * comme le bloc image, paragraphe, etc.
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajouter des champs ACF au bloc image natif
 */
function taulignan_extend_image_block()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_image_block_extension',
        'title' => 'Extension du bloc Image',
        'fields' => array(
            array(
                'key' => 'field_image_custom_number',
                'label' => 'Nombre personnalisé',
                'name' => 'image_custom_number',
                'type' => 'number',
                'instructions' => 'Entrez un nombre personnalisé pour cette image',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'Ex: 42',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
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
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'Votre texte ici...',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_image_custom_select',
                'label' => 'Option personnalisée',
                'name' => 'image_custom_select',
                'type' => 'select',
                'instructions' => 'Choisissez une option pour cette image',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'option1' => 'Option 1',
                    'option2' => 'Option 2',
                    'option3' => 'Option 3',
                ),
                'default_value' => '',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/image',
                ),
            ),
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'core/image',
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
        'description' => 'Champs personnalisés pour étendre le bloc image WordPress',
    ));
}

/**
 * Ajouter des champs ACF au bloc paragraphe natif
 */
function taulignan_extend_paragraph_block()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_paragraph_block_extension',
        'title' => 'Extension du bloc Paragraphe',
        'fields' => array(
            array(
                'key' => 'field_paragraph_custom_number',
                'label' => 'Nombre personnalisé',
                'name' => 'paragraph_custom_number',
                'type' => 'number',
                'instructions' => 'Entrez un nombre personnalisé pour ce paragraphe',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'Ex: 100',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'core/paragraph',
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
        'description' => 'Champs personnalisés pour étendre le bloc paragraphe WordPress',
    ));
}

/**
 * Ajouter des champs ACF au bloc heading natif
 */
function taulignan_extend_heading_block()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_heading_block_extension',
        'title' => 'Extension du bloc Titre',
        'fields' => array(
            array(
                'key' => 'field_heading_custom_number',
                'label' => 'Numéro de section',
                'name' => 'heading_custom_number',
                'type' => 'number',
                'instructions' => 'Numéro de section pour ce titre',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'Ex: 1',
                'prepend' => '§',
                'append' => '',
                'min' => 1,
                'max' => 99,
                'step' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'core/heading',
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
        'description' => 'Champs personnalisés pour étendre le bloc titre WordPress',
    ));
}

/**
 * Initialiser toutes les extensions de blocs
 */
function taulignan_init_block_extensions()
{
    // Attendre que ACF soit chargé
    if (function_exists('acf_add_local_field_group')) {
        taulignan_extend_image_block();
        taulignan_extend_paragraph_block();
        taulignan_extend_heading_block();
    }
}

// Hook pour initialiser les extensions
add_action('acf/init', 'taulignan_init_block_extensions');

/**
 * Rendre les champs ACF disponibles dans l'éditeur de blocs
 */
function taulignan_enable_acf_fields_in_blocks()
{
    if (function_exists('acf_get_field_groups')) {
        // Activer ACF pour tous les blocs
        add_filter('acf/settings/remove_wp_meta_box', '__return_false');
    }
}

add_action('init', 'taulignan_enable_acf_fields_in_blocks');

/**
 * Fonction utilitaire pour récupérer la valeur d'un champ ACF dans un bloc
 * 
 * @param string $block_name Nom du bloc (ex: 'core/image')
 * @param string $field_name Nom du champ ACF
 * @param int $post_id ID du post (optionnel)
 * @return mixed Valeur du champ ou false si non trouvé
 */
function taulignan_get_block_acf_field($block_name, $field_name, $post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Récupérer tous les blocs du post
    $blocks = parse_blocks(get_post_field('post_content', $post_id));

    // Chercher le bloc spécifique
    foreach ($blocks as $block) {
        if ($block['blockName'] === $block_name) {
            // Vérifier si le bloc a des champs ACF
            if (isset($block['attrs']['data'])) {
                $acf_data = $block['attrs']['data'];
                if (isset($acf_data[$field_name])) {
                    return $acf_data[$field_name];
                }
            }
        }
    }

    return false;
}

/**
 * Enregistrer le bloc ACF pour la date du séjour
 */
function register_date_sejour_block()
{
    if (!function_exists('acf_register_block_type')) {
        return;
    }

    acf_register_block_type(array(
        'name'              => 'date-sejour',
        'title'             => __('Date du Séjour'),
        'description'       => __('Affiche la date du séjour depuis le champ ACF date_sejour'),
        'render_template'   => get_template_directory() . '/inc/blocks/date-sejour.php',
        'category'          => 'formatting',
        'icon'              => 'calendar-alt',
        'keywords'          => array('date', 'séjour', 'acf'),
        'mode'              => 'edit',
        'supports'          => array(
            'align' => false,
            'mode'  => false,
        ),
    ));
}
add_action('acf/init', 'register_date_sejour_block');

/**
 * Enregistrer les champs ACF pour le bloc date-sejour
 */
function register_date_sejour_fields()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_date_sejour_block',
        'title' => 'Paramètres de la Date du Séjour',
        'fields' => array(
            array(
                'key' => 'field_date_sejour_format',
                'label' => 'Format d\'affichage',
                'name' => 'date_format',
                'type' => 'select',
                'instructions' => 'Choisissez le format d\'affichage de la date',
                'required' => 0,
                'choices' => array(
                    'j M Y' => '15 Jan 2024',
                    'j F Y' => '15 janvier 2024',
                    'd/m/Y' => '15/01/2024',
                    'm/d/Y' => '01/15/2024',
                    'Y-m-d' => '2024-01-15',
                ),
                'default_value' => 'j M Y',
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_date_sejour_icon',
                'label' => 'Afficher l\'icône',
                'name' => 'show_icon',
                'type' => 'true_false',
                'instructions' => 'Afficher l\'emoji 📅 avant la date',
                'required' => 0,
                'default_value' => 1,
            ),
            array(
                'key' => 'field_date_sejour_prefix',
                'label' => 'Préfixe personnalisé',
                'name' => 'custom_prefix',
                'type' => 'text',
                'instructions' => 'Texte à afficher avant la date (remplace l\'icône si renseigné)',
                'required' => 0,
                'default_value' => '',
                'placeholder' => 'Ex: Date du séjour :',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_date_sejour_icon',
                            'operator' => '!=',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/date-sejour',
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
        'description' => 'Paramètres pour le bloc d\'affichage de la date du séjour',
    ));
}
add_action('acf/init', 'register_date_sejour_fields');

/**
 * Enregistrer le bloc ACF pour la carte d'événement
 */
function register_card_event_block()
{
    if (!function_exists('acf_register_block_type')) {
        return;
    }

    acf_register_block_type(array(
        'name'              => 'card-event',
        'title'             => __('Carte d\'Événement'),
        'description'       => __('Affiche une carte d\'événement complète avec image, titre, date du séjour, extrait et bouton'),
        'render_template'   => get_template_directory() . '/inc/blocks/card-event.php',
        'category'          => 'widgets',
        'icon'              => 'calendar',
        'keywords'          => array('carte', 'événement', 'séjour', 'card'),
        'mode'              => 'edit',
        'supports'          => array(
            'align' => array('wide', 'full'),
            'mode'  => false,
        ),
    ));
}
add_action('acf/init', 'register_card_event_block');

/**
 * Enregistrer les champs ACF pour le bloc card-event
 */
function register_card_event_fields()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_card_event_block',
        'title' => 'Paramètres de la Carte d\'Événement',
        'fields' => array(
            array(
                'key' => 'field_card_style',
                'label' => 'Style de la carte',
                'name' => 'card_style',
                'type' => 'select',
                'instructions' => 'Choisissez le style d\'affichage de la carte',
                'required' => 0,
                'choices' => array(
                    'default' => 'Style par défaut',
                    'compact' => 'Style compact',
                    'featured' => 'Style mis en avant',
                    'minimal' => 'Style minimal',
                ),
                'default_value' => 'default',
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_show_excerpt',
                'label' => 'Afficher l\'extrait',
                'name' => 'show_excerpt',
                'type' => 'true_false',
                'instructions' => 'Afficher l\'extrait du contenu',
                'required' => 0,
                'default_value' => 1,
            ),
            array(
                'key' => 'field_excerpt_length',
                'label' => 'Longueur de l\'extrait',
                'name' => 'excerpt_length',
                'type' => 'number',
                'instructions' => 'Nombre de mots à afficher dans l\'extrait',
                'required' => 0,
                'default_value' => 20,
                'min' => 5,
                'max' => 100,
                'step' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_show_excerpt',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_button_text',
                'label' => 'Texte du bouton',
                'name' => 'button_text',
                'type' => 'text',
                'instructions' => 'Texte affiché sur le bouton de redirection',
                'required' => 0,
                'default_value' => 'Voir les détails',
                'placeholder' => 'Ex: Découvrir',
                'maxlength' => 50,
            ),
            array(
                'key' => 'field_date_format',
                'label' => 'Format de la date',
                'name' => 'date_format',
                'type' => 'select',
                'instructions' => 'Format d\'affichage de la date du séjour',
                'required' => 0,
                'choices' => array(
                    'j M Y' => '15 Jan 2024',
                    'j F Y' => '15 janvier 2024',
                    'd/m/Y' => '15/01/2024',
                    'm/d/Y' => '01/15/2024',
                    'Y-m-d' => '2024-01-15',
                ),
                'default_value' => 'j M Y',
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_finish',
                'label' => 'Événement terminé',
                'name' => 'finish',
                'type' => 'true_false',
                'instructions' => 'Cocher si l\'événement est terminé (affecte le style et le comportement)',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Terminé',
                'ui_off_text' => 'À venir',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/card-event',
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
        'description' => 'Paramètres pour le bloc de carte d\'événement',
    ));
}
add_action('acf/init', 'register_card_event_fields');

// Les champs ACF pour le Séjours Slider sont maintenant gérés via BlockSections
// Voir /sections/SejoursSlider.php
