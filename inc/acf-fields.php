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
 * Enregistrer tous les groupes de champs ACF
 */
function taulignan_register_acf_fields() {
    // Vérifier que ACF est installé et activé
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    // Groupe 1: Activités
    acf_add_local_field_group( array(
        'key' => 'group_666d1c73d1c97',
        'title' => 'Activités',
        'fields' => array(
            array(
                'key' => 'field_666d1c7629920',
                'label' => 'Accroche',
                'name' => 'activites_accroche',
                'type' => 'text',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_666d1c8d29921',
                'label' => 'Introduction',
                'name' => 'activites_introduction',
                'type' => 'text',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_666d1ca329922',
                'label' => 'Contenu',
                'name' => 'activites_contenu',
                'type' => 'wysiwyg',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
            ),
            array(
                'key' => 'field_666d2752a1aa4',
                'label' => 'Image culture 1',
                'name' => 'activites_image_culture_1',
                'type' => 'image',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'library' => 'all',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_66aa683053fa2',
                'label' => 'Image culture 2',
                'name' => 'activites_image_culture_2',
                'type' => 'image',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'library' => 'all',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_66aa684753fa3',
                'label' => 'Image culture 3',
                'name' => 'activites_image_culture_3',
                'type' => 'image',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'library' => 'all',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_666d25c9a69b2',
                'label' => 'Contenu Nature',
                'name' => 'activites_contenu_nature',
                'type' => 'wysiwyg',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
            ),
            array(
                'key' => 'field_666d2728a1aa3',
                'label' => 'Image nature 1',
                'name' => 'activites_image_nature_1',
                'type' => 'image',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'library' => 'all',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_66aa6e110d8bb',
                'label' => 'Image nature 2',
                'name' => 'activites_image_nature_2',
                'type' => 'image',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'library' => 'all',
                'preview_size' => 'medium',
            ),
            array(
                'key' => 'field_66aa6e200d8bc',
                'label' => 'Image nature 3',
                'name' => 'activites_image_nature_3',
                'type' => 'image',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'library' => 'all',
                'preview_size' => 'medium',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '510',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            0 => 'excerpt',
            1 => 'discussion',
            2 => 'comments',
            3 => 'revisions',
            4 => 'author',
            5 => 'categories',
            6 => 'tags',
            7 => 'send-trackbacks',
        ),
        'active' => true,
        'show_in_rest' => 0,
    ) );

    // Groupe 2: Chambres d'hôtes
    acf_add_local_field_group( array(
        'key' => 'group_666c6cc73757b',
        'title' => 'Chambres d\'hotes',
        'fields' => array(
            array(
                'key' => 'field_666ca833322a2',
                'label' => 'Présentation',
                'name' => 'chambres_presentation',
                'type' => 'wysiwyg',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
            ),
            array(
                'key' => 'field_666c72790df42',
                'label' => 'Details chambre 1',
                'name' => 'details_1',
                'type' => 'group',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_666c72790df44',
                        'label' => 'Superficie',
                        'name' => 'superficie',
                        'type' => 'text',
                        'required' => 0,
                    ),
                    // Ajoutez ici les autres sous-champs de la chambre 1
                ),
            ),
            // Ajoutez ici les autres champs pour les chambres
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'chambres-hotes', // ID ou slug de la page
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'show_in_rest' => 0,
    ) );

    // Groupe 3: Mariages
    acf_add_local_field_group( array(
        'key' => 'group_mariages',
        'title' => 'Mariages',
        'fields' => array(
            array(
                'key' => 'field_mariages_presentation',
                'label' => 'Présentation',
                'name' => 'mariages_presentation',
                'type' => 'wysiwyg',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
            ),
            array(
                'key' => 'field_mariages_galerie',
                'label' => 'Galerie photos',
                'name' => 'mariages_galerie',
                'type' => 'gallery',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'library' => 'all',
                'min' => '',
                'max' => '',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'insert' => 'append',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'mariages', // ID ou slug de la page
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'show_in_rest' => 0,
    ) );

    // Groupe 4: Séminaires
    acf_add_local_field_group( array(
        'key' => 'group_seminaires',
        'title' => 'Séminaires',
        'fields' => array(
            array(
                'key' => 'field_seminaires_presentation',
                'label' => 'Présentation',
                'name' => 'seminaires_presentation',
                'type' => 'wysiwyg',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
            ),
            array(
                'key' => 'field_seminaires_capacite',
                'label' => 'Capacité d\'accueil',
                'name' => 'seminaires_capacite',
                'type' => 'number',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'min' => '',
                'max' => '',
                'step' => '',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_seminaires_equipements',
                'label' => 'Équipements disponibles',
                'name' => 'seminaires_equipements',
                'type' => 'checkbox',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'projecteur' => 'Projecteur',
                    'ecran' => 'Écran',
                    'son' => 'Système audio',
                    'wifi' => 'WiFi',
                    'climatisation' => 'Climatisation',
                ),
                'default_value' => array(),
                'allow_custom' => 0,
                'save_custom' => 0,
                'layout' => 'vertical',
                'toggle' => 0,
                'return_format' => 'value',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'seminaires', // ID ou slug de la page
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'show_in_rest' => 0,
    ) );

    // Groupe 5: Contact et Informations générales
    acf_add_local_field_group( array(
        'key' => 'group_contact',
        'title' => 'Contact et Informations',
        'fields' => array(
            array(
                'key' => 'field_contact_telephone',
                'label' => 'Téléphone',
                'name' => 'contact_telephone',
                'type' => 'text',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_contact_email',
                'label' => 'Email',
                'name' => 'contact_email',
                'type' => 'email',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_contact_adresse',
                'label' => 'Adresse',
                'name' => 'contact_adresse',
                'type' => 'textarea',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 3,
                'new_lines' => 'br',
            ),
            array(
                'key' => 'field_contact_horaires',
                'label' => 'Horaires d\'ouverture',
                'name' => 'contact_horaires',
                'type' => 'wysiwyg',
                'required' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'tabs' => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'show_in_rest' => 0,
    ) );
}

/**
 * Hook pour enregistrer les champs ACF
 */
add_action( 'acf/include_fields', 'taulignan_register_acf_fields' );

/**
 * Fonction utilitaire pour récupérer les valeurs ACF
 * 
 * @param string $field_name Nom du champ ACF
 * @param mixed $post_id ID du post (optionnel)
 * @return mixed Valeur du champ
 */
function taulignan_get_acf_field( $field_name, $post_id = null ) {
    if ( function_exists( 'get_field' ) ) {
        return get_field( $field_name, $post_id );
    }
    return false;
}

/**
 * Fonction utilitaire pour afficher les valeurs ACF
 * 
 * @param string $field_name Nom du champ ACF
 * @param mixed $post_id ID du post (optionnel)
 */
function taulignan_the_acf_field( $field_name, $post_id = null ) {
    if ( function_exists( 'the_field' ) ) {
        the_field( $field_name, $post_id );
    }
}

/**
 * Fonction utilitaire pour récupérer et formater la date du séjour
 * 
 * @param int $post_id ID du post (optionnel)
 * @return string Date formatée ou message d'erreur
 */
function get_formatted_date_sejour($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Récupérer le champ ACF date_sejour
    $date_sejour = get_field('date_sejour', $post_id);
    
    if (!$date_sejour) {
        return '';
    }
    
    // Si c'est un array, prendre la première valeur
    if (is_array($date_sejour)) {
        $date_sejour = $date_sejour[0] ?? '';
    }
    
    // Convertir le format ACF (YYYYMMDD) en objet DateTime
    if ($date_sejour && strlen($date_sejour) === 8) {
        $date_obj = DateTime::createFromFormat('Ymd', $date_sejour);
        
        if ($date_obj) {
            return $date_obj;
        }
    }
    
    // Si le format n'est pas reconnu, retourner la valeur brute
    return $date_sejour;
}