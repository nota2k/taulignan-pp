<?php

namespace Sections;

use BlockSections\Section;

/**
 * The main theme layout
 */
class ThemeLayout extends Section
{
    /**
     * Initialize the section.
     */
    function init()
    {
        // The section name, wihtout "acf/app-" prefix. It will be added automatically
        // Full slug: "acf/app-example"
        $this->name = 'theme-layout';

        // Human readable section Title
        $this->title = __('Layout', 'app');

        // The `$setting` for the section's root ACF block. See
        // https://www.advancedcustomfields.com/resources/acf_register_block_type/#settings
        $this->args = [
            'multiple' => false,
            'description' => __('Layout for theme — Header and Footer', 'app'),
            'keywords' => [
                'wrapper',
                'layout',
                'main',
            ],
            'category' => 'theme',
            'icon' => 'welcome-widgets-menus',
        ];
    }

    /**
     * Render Section root element.
     *
     * @param $block
     * @param $content
     * @param $is_preview
     * @param $post_id
     * @param $wp_block
     * @param $context
     * @return void
     */
    function render($block, $content, $is_preview, $post_id, $wp_block, $context)
    {
        // Récupérer la configuration du background
        $background = get_field('background', $block['id']);

        // Fallback pour l'éditeur : récupérer directement depuis les données du bloc
        if (empty($background) && isset($block['data']['background'])) {
            $background = $block['data']['background'];
        }

        // Appliquer la classe au body si une valeur est définie
        if (! empty($background) && $background !== 'default') {
            add_filter('body_class', function ($classes) use ($background) {
                // Retirer les anciennes classes bg-* si elles existent
                $classes = array_filter($classes, function ($class) {
                    return ! str_starts_with($class, 'bg-');
                });

                // Ajouter la nouvelle classe
                $classes[] = 'bg-' . $background;
                return $classes;
            }, 20);
        }

        app_render_fragment('header');

        $this->inner_blocks(
            $block,
            locked: false,
            allowed_block_types: 'all',
        );

        app_render_fragment('footer');
    }
}
