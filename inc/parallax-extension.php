<?php
// ... votre code functions.php existant ...

/**
 * Extension Parallax pour les blocs Group de WordPress
 */
class ParallaxGroupExtension {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Enregistrer le script pour l'éditeur
        wp_register_script(
            'parallax-group-extension',
            get_template_directory_uri() . '/js/parallax-group-extension.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Enqueue le script dans l'éditeur
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
        
        // Modifier le rendu du bloc en front-end
        add_filter('render_block', array($this, 'render_parallax_group'), 10, 2);
        
        // Créer le fichier JS si il n'existe pas ou forcer la régénération
        $this->create_js_file_if_not_exists();
        
        // Forcer la régénération du fichier JS (temporaire, à supprimer après)
        $this->force_regenerate_js();
    }
    
    public function enqueue_editor_assets() {
        wp_enqueue_script('parallax-group-extension');
        
        // Localiser les textes
        wp_localize_script('parallax-group-extension', 'parallaxGroupL10n', array(
            'enableParallax' => __('Activer Parallax', 'textdomain'),
            'parallaxSpeed' => __('Vitesse Parallax', 'textdomain'),
            'speedHelp' => __('Valeur entre -1 (très lent) et 10 (très rapide)', 'textdomain')
        ));
    }
    
    public function render_parallax_group($block_content, $block) {
        // Vérifier que c'est un bloc Group
        if ($block['blockName'] !== 'core/group') {
            return $block_content;
        }
        
        // Récupérer les attributs personnalisés
        $attributes = $block['attrs'] ?? array();
        $parallax_enabled = $attributes['parallaxEnabled'] ?? false;
        $parallax_speed = $attributes['parallaxSpeed'] ?? 0.5;
        
        // Si parallax n'est pas activé, retourner le contenu original
        if (!$parallax_enabled) {
            return $block_content;
        }
        
        // Valider et nettoyer la vitesse
        $parallax_speed = $this->validate_speed($parallax_speed);
        
        // Ajouter le data-attribute et les classes nécessaires
        $block_content = $this->add_parallax_attributes($block_content, $parallax_speed);
        
        return $block_content;
    }
    
    private function validate_speed($speed) {
        // Convertir en float et limiter entre -1 et 10
        $speed = floatval($speed);
        return max(-1, min(10, $speed));
    }
    
    private function add_parallax_attributes($content, $speed) {
        // Chercher la div du groupe et ajouter les attributs
        $pattern = '/(<div[^>]*class="[^"]*wp-block-group[^"]*"[^>]*)/';
        
        $replacement = sprintf(
            '$1 data-parallax="true" data-speed="%s"',
            esc_attr($speed)
        );
        
        $content = preg_replace($pattern, $replacement, $content);
        
        // Ajouter une classe CSS pour le parallax
        $content = preg_replace(
            '/class="([^"]*wp-block-group[^"]*)"/',
            'class="$1 parallax"',
            $content
        );
        
        return $content;
    }
    
    private function create_js_file_if_not_exists() {
        $js_file = get_template_directory() . '/js/parallax-group-extension.js';
        
        if (!file_exists($js_file)) {
            $js_content = $this->get_js_content();
            
            // Créer le dossier si nécessaire
            wp_mkdir_p(dirname($js_file));
            
            // Écrire le fichier
            file_put_contents($js_file, $js_content);
        }
    }
    
    /**
     * Force la régénération du fichier JavaScript
     * À supprimer après la première utilisation
     */
    private function force_regenerate_js() {
        $js_file = get_template_directory() . '/js/parallax-group-extension.js';
        
        if (file_exists($js_file)) {
            // Supprimer le fichier existant
            unlink($js_file);
        }
        
        // Recréer le fichier avec les nouvelles valeurs
        $js_content = $this->get_js_content();
        wp_mkdir_p(dirname($js_file));
        file_put_contents($js_file, $js_content);
    }
    
    private function get_js_content() {
        return "(function() {
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
})();";
    }
}

// Activer l'extension
new ParallaxGroupExtension();