import { pullLeft, pullRight } from '@wordpress/icons';

const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { __ } = wp.i18n;
const { BlockControls } = wp.blockEditor;
const { ToolbarGroup, ToolbarButton } = wp.components;

const addImagePositionAttribute = ( settings, name ) => {
	if ( name !== 'acf/app-images-text' ) {
		return settings;
	}

	settings.attributes = { ...settings.attributes,
		imagePosition: {
			type: 'string',
			default: 'left',
		}
	};

	return settings;
};

addFilter( 'blocks.registerBlockType', 'app/attribute/image-position', addImagePositionAttribute );

const withImagePositionControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if ( props.name !== 'acf/app-images-text' ) {
			return (
				<BlockEdit { ...props } />
			);
		}

		const { imagePosition } = props.attributes;

		return (
			<Fragment>
				<BlockControls>
					<ToolbarGroup>
						<ToolbarButton
							icon={ pullLeft }
							title={ __( 'Show media on left' ) }
							isActive={ imagePosition === 'left' }
							onClick={ () =>
								props.setAttributes( { imagePosition: 'left' } )
							}
						/>
						<ToolbarButton
							icon={ pullRight }
							title={ __( 'Show media on right' ) }
							isActive={ imagePosition === 'right' }
							onClick={ () =>
								props.setAttributes( { imagePosition: 'right' } )
							}
						/>
					</ToolbarGroup>
				</BlockControls>
				<BlockEdit { ...props } className={ imagePosition ? 'media-on-' + imagePosition : '' } />
			</Fragment>
		);
	};
}, 'withImagePositionControl' );

addFilter( 'editor.BlockEdit', 'app/with-image-position-control', withImagePositionControl );
