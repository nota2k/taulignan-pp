<?php

// ============================================================================
// STYLES DE BLOCS PERSONNALISÉS
// ============================================================================

/**
 * Enregistrement des styles de blocs personnalisés
 */
function taulignan_register_block_styles()
{

	// Style pour le positionnement à gauche
	register_block_style(
		'core/group',
		array(
			'name'  => 'left-pos',
			'label' => __('Positionnement Gauche', 'taulignan'),
			'inline_style' => '
                .is-style-left-pos {
                    position: relative;
                    left: -3em;
                    z-index: 10;
                }
            '
		)
	);

	// Style pour le positionnement à droite
	register_block_style(
		'core/group',
		array(
			'name'  => 'right-pos',
			'label' => __('Positionnement Droite', 'taulignan'),
			'inline_style' => '
                .is-style-right-pos {
                    position: relative;
                    right: -3em;
                    z-index: 10;
                }
            '
		)
	);

	// Style pour le positionnement centré
	register_block_style(
		'core/group',
		array(
			'name'  => 'center-pos',
			'label' => __('Positionnement Centré', 'taulignan'),
			'inline_style' => '
                .is-style-center-pos {
                    position: relative;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 10;
                }
            '
		)
	);

	// Style pour le positionnement centré
	register_block_style(
		'core/group',
		array(
			'name'  => 'hide-on-mobile',
			'label' => __('Masquer sur mobile', 'taulignan')
		)
	);

	// Style pour le débordement à gauche
	register_block_style(
		'core/group',
		array(
			'name'  => 'overflow-left',
			'label' => __('Débordement Gauche', 'taulignan'),
			'inline_style' => '
                .is-style-overflow-left {
                    position: relative;
                    left: -100px;
                    z-index: 20;
                }
            '
		)
	);

	// Style pour le débordement à droite
	register_block_style(
		'core/group',
		array(
			'name'  => 'overflow-right',
			'label' => __('Débordement Droite', 'taulignan'),
		)
	);

	// Style pour 2 colonnes
	register_block_style(
		'core/group',
		array(
			'name'  => '2-columns',
			'label' => __('2 colonnes', 'taulignan'),
			'inline_style' => '
                .is-style-2-columns {
                    column-count: 2;
                    column-gap: 60px;
                }

                .is-style-2-columns::after {
                    content: "";
                    display: block;
                    clear: both;
                    width: 1px;
                    height: 100%;
                    background-color: var(--beige);
                    position: absolute;
                    right: 50%;
                    top: 0;
                }
            '
		)
	);

	// Style pour icône blanche
	register_block_style(
		'core/image',
		array(
			'name'  => 'icon-white',
			'label' => __('Icone blanche', 'taulignan'),
			'inline_style' => '
                .is-style-icon-white {
                    filter:brightness(1);
                }
            '
		)
	);
}
add_action('init', 'taulignan_register_block_styles');