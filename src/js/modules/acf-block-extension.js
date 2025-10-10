/**
 * Extension ACF pour les blocs de groupe WordPress
 * Affiche le groupe de champs group_68aa9ae61a5e6 dans les réglages des blocs
 */

(function() {
    'use strict';

    console.log('🎯 ACF Block Extension: Script chargé');

    const { addFilter } = wp.hooks;
    const { Fragment, createElement: el } = wp.element;
    const { InspectorControls } = wp.blockEditor;
    const { PanelBody, ToggleControl, TextControl, TextareaControl, SelectControl } = wp.components;
    const { __ } = wp.i18n;

    // Extension simplifiée pour les champs ACF de positionnement

    /**
     * Créer le panneau générique des champs ACF
     */
    function createGenericAcfPanel(attributes, setAttributes) {
        const { acfField1, acfField2, acfField3 } = attributes;

        return el(
            'div',
            {
                className: 'acf-fields-panel'
            },
            el(
                'h3',
                {
                    style: {
                        margin: '0 0 16px 0',
                        fontSize: '14px',
                        fontWeight: '600',
                        color: '#1e1e1e'
                    }
                },
                'Champs ACF'
            ),
            
            // Champ 1
            el(TextControl, {
                label: __('Champ ACF 1', 'taulignan'),
                value: acfField1,
                onChange: (value) => setAttributes({ acfField1: value }),
                help: __('Valeur pour le premier champ ACF', 'taulignan'),
                placeholder: 'Valeur...'
            }),
            
            // Champ 2
            el(TextControl, {
                label: __('Champ ACF 2', 'taulignan'),
                value: acfField2,
                onChange: (value) => setAttributes({ acfField2: value }),
                help: __('Valeur pour le deuxième champ ACF', 'taulignan'),
                placeholder: 'Valeur...'
            }),
            
            // Champ 3
            el(TextControl, {
                label: __('Champ ACF 3', 'taulignan'),
                value: acfField3,
                onChange: (value) => setAttributes({ acfField3: value }),
                help: __('Valeur pour le troisième champ ACF', 'taulignan'),
                placeholder: 'Valeur...'
            })
        );
    }

    /**
     * Ajouter les attributs ACF aux blocs de groupe
     */
    function addAcfAttributesToGroupBlocks(settings, name) {
        if (name !== 'core/group') {
            return settings;
        }

        // Ajouter nos attributs personnalisés
        if (!settings.attributes) {
            settings.attributes = {};
        }

        // Attribut pour identifier le groupe ACF
        settings.attributes.acfGroupKey = {
            type: 'string',
            default: 'group_68aa9ae61a5e6'
        };

        // Attributs pour les champs ACF (à adapter selon vos besoins)
        for (let i = 1; i <= 3; i++) {
            settings.attributes['acfField' + i] = {
                type: 'string',
                default: ''
            };
        }

        return settings;
    }



    /**
     * Ajouter les contrôles ACF dans l'inspecteur
     */
    function addAcfControlsToGroupBlocks(BlockEdit) {
        return function(props) {
            if (props.name !== 'core/group') {
                return el(BlockEdit, props);
            }

            const { attributes, setAttributes } = props;
            const { acfGroupKey } = props.attributes;

            // Debug - afficher les attributs dans la console
            console.log('🎯 ACF Extension - Attributs du bloc:', {
                acfGroupKey
            });

            // Vérifier si ce bloc utilise le bon groupe ACF
            if (acfGroupKey !== 'group_68aa9ae61a5e6') {
                console.log('🎯 ACF Extension - Groupe ACF non reconnu:', acfGroupKey);
                return el(BlockEdit, props);
            }

            return el(
                Fragment,
                {},
                el(BlockEdit, props),
                el(
                    InspectorControls,
                    {},
                    el(
                        PanelBody,
                        {
                            title: __('Champs ACF', 'taulignan'),
                            initialOpen: false,
                            icon: 'admin-generic'
                        },
                        createGenericAcfPanel(attributes, setAttributes)
                    )
                )
            );
        };
    }

    // Enregistrer les filtres
    addFilter(
        'blocks.registerBlockType',
        'taulignan-acf/add-attributes',
        addAcfAttributesToGroupBlocks
    );

    addFilter(
        'editor.BlockEdit',
        'taulignan-acf/add-controls',
        addAcfControlsToGroupBlocks
    );

    // Extension prête
    wp.domReady(function() {
        console.log('🎯 WordPress DOM ready, extension ACF de positionnement initialisée');
    });

    console.log('🎯 ACF Block Extension: Initialisée avec succès');
})();
