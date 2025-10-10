(function() {
    const { addFilter } = wp.hooks;
    const { Fragment, createElement: el } = wp.element;
    const { InspectorControls } = wp.blockEditor;
    const { PanelBody, ToggleControl, RangeControl } = wp.components;
    const { __ } = wp.i18n;

    // Ajouter les attributs personnalisés au bloc Group
    function addParallaxAttributes(settings, name) {
        if (name !== 'core/group') {
            return settings;
        }

        // Ajouter nos attributs personnalisés
        settings.attributes = Object.assign(settings.attributes, {
            parallaxEnabled: {
                type: 'boolean',
                default: false
            },
            parallaxSpeed: {
                type: 'number',
                default: 0.5
            }
        });

        return settings;
    }

    // Ajouter les contrôles dans l'inspecteur
    function addParallaxControls(BlockEdit) {
        return function(props) {
            if (props.name !== 'core/group') {
                return el(BlockEdit, props);
            }

            const { attributes, setAttributes } = props;
            const { parallaxEnabled, parallaxSpeed } = attributes;

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
                            title: __('Parallax', 'textdomain'),
                            initialOpen: false
                        },
                        el(ToggleControl, {
                            label: parallaxGroupL10n.enableParallax,
                            checked: parallaxEnabled,
                            onChange: function(value) {
                                setAttributes({ parallaxEnabled: value });
                            }
                        }),
                        parallaxEnabled && el(RangeControl, {
                            label: parallaxGroupL10n.parallaxSpeed,
                            value: parallaxSpeed,
                            onChange: function(value) {
                                setAttributes({ parallaxSpeed: value });
                            },
                            min: -1,
                            max: 10,
                            step: 0.1,
                            help: parallaxGroupL10n.speedHelp
                        })
                    )
                )
            );
        };
    }

    // Enregistrer les filtres
    addFilter(
        'blocks.registerBlockType',
        'parallax-group/add-attributes',
        addParallaxAttributes
    );

    addFilter(
        'editor.BlockEdit',
        'parallax-group/add-controls',
        addParallaxControls
    );
})();