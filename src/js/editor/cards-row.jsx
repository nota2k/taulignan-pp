import { alignLeft, alignCenter } from '@wordpress/icons';

const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { __ } = wp.i18n;
const { BlockControls } = wp.blockEditor;
const { ToolbarGroup, ToolbarButton } = wp.components;

const addHeadingAlignmentAttribute = ( settings, name ) => {
	if ( name !== 'acf/app-cards-row-head' ) {
		return settings;
	}

	settings.attributes = { ...settings.attributes,
		headingAlignment: {
			type: 'string',
			default: 'center',
		}
	};

	return settings;
};

addFilter( 'blocks.registerBlockType', 'app/attribute/heading-alignment', addHeadingAlignmentAttribute );

const withHeadingAlignmentControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if ( props.name !== 'acf/app-cards-row-head' ) {
			return (
				<BlockEdit { ...props } />
			);
		}

		const { headingAlignment } = props.attributes;

		return (
			<Fragment>
				<BlockControls>
					<ToolbarGroup>
						<ToolbarButton
							icon={ alignLeft }
							title={ __( 'Align left' ) }
							isActive={ headingAlignment === 'left' }
							onClick={ () =>
								props.setAttributes( { headingAlignment: 'left' } )
							}
						/>
						<ToolbarButton
							icon={ alignCenter }
							title={ __( 'Align center' ) }
							isActive={ headingAlignment === 'center' }
							onClick={ () =>
								props.setAttributes( { headingAlignment: 'center' } )
							}
						/>
					</ToolbarGroup>
				</BlockControls>
				<BlockEdit { ...props } className={ headingAlignment ? 'align-' + headingAlignment : '' } />
			</Fragment>
		);
	};
}, 'withHeadingAlignmentControl' );

addFilter( 'editor.BlockEdit', 'app/with-heading-alignment-control', withHeadingAlignmentControl );
