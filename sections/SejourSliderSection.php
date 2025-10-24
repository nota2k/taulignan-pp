<?php

namespace Sections;

use BlockSections\Section;

/**
 * Section Sejours Slider avec 2 colonnes
 * Colonne gauche : contenu éditable
 * Colonne droite : bloc SejoursSlider
 */
class SejourSliderSection extends Section
{
    /**
     * Initialize the section.
     */
    function init()
    {
        // The section name, without "acf/app-" prefix. It will be added automatically
        // Full slug: "acf/app-sejours-slider-section"
        $this->name = 'sejours-slider-section';

        // Human readable section Title
        $this->title = __('Sejours Slider Section', 'taulignan');

        // The `$setting` for the section's root ACF block. See
        // https://www.advancedcustomfields.com/resources/acf_register_block_type/#settings
        $this->args = [
            'multiple' => true,
            'description' => __('Section complète avec slider de séjours (2 colonnes)', 'taulignan'),
            'keywords' => [
                'slider',
                'carousel',
                'sejours',
                'section',
            ],
            'category' => 'sejours-slider',
            'icon' => 'slides',
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
        $classes = ['section-sejours-slider', 'sejours-slider-section'];

        // Template pour InnerBlocks - structure complète avec groupe et 2 colonnes
        $template = [
            [
                'core/group',
                [
                    'className' => 'sejours-slider-container'
                ],
                [
                    // Titre
                    [
                        'core/heading',
                        [
                            'content' => 'Séjours à thèmes',
                            'level' => 2,
                            'className' => 'sejours-slider-heading'
                        ]
                    ],
                    // Bloc colonnes
                    [
                        'core/columns',
                        [
                            'className' => 'sejours-slider-columns'
                        ],
                        [
                            // Colonne 1 : Contenu texte et bouton
                            [
                                'core/column',
                                [
                                    'width' => '40%',
                                    'className' => 'sejours-slider-left-column'
                                ],
                                [
                                    // Paragraphe
                                    [
                                        'core/paragraph',
                                        [
                                            'content' => 'Découvrez nos séjours à thèmes organisés tout au long de l\'année.',
                                            'className' => 'sejours-slider-description'
                                        ]
                                    ],
                                    // Bouton
                                    [
                                        'core/buttons',
                                        [],
                                        [
                                            [
                                                'core/button',
                                                [
                                                    'text' => 'Voir tous les séjours',
                                                    'url' => '#',
                                                    'className' => 'sejours-slider-button'
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            // Colonne 2 : Slider de séjours
                            [
                                'core/column',
                                [
                                    'width' => '60%',
                                    'className' => 'sejours-slider-right-column'
                                ],
                                [
                                    [
                                        'acf/app-sejours-slider'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Classes supplémentaires
        if (!empty($block['className'])) {
            $classes[] = $block['className'];
        }
        if (!empty($block['align'])) {
            $classes[] = 'align' . $block['align'];
        }

        ?>

        <section class="<?php echo esc_attr(implode(' ', $classes)); ?>">

            <?php
            // InnerBlocks : permet l'édition dans l'éditeur et affiche le contenu sur le frontend
            $allowed_blocks = [
                'core/paragraph', 
                'core/button', 
                'core/buttons', 
                'core/heading', 
                'core/spacer', 
                'core/separator', 
                'core/columns', 
                'core/column', 
                'acf/app-sejours-slider'
            ];
            
            $this->inner_blocks(
                $block,
                $template,
                false, // locked = false pour permettre la modification
                $allowed_blocks
            );
            ?>

        </section><!-- .section-sejours-slider -->

<?php
    }
}
