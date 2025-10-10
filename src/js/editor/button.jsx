const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { PanelBody, ToggleControl } = wp.components;
const { InspectorControls } = wp.blockEditor;

const addIconAttribute = ( settings, name ) => {
    if ( name !== 'core/button' ) {
        return settings;
    }

    settings.attributes = { ...settings.attributes,
		hasIcon: {
            type: 'boolean',
            default: false,
        }
	};

    return settings;
};

addFilter( 'blocks.registerBlockType', 'app/attribute/has-icon', addIconAttribute );

const withIconControl = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        if ( props.name !== 'core/button' ) {
            return (
                <BlockEdit { ...props } />
            );
        }

        const { hasIcon } = props.attributes;

        return (
            <Fragment>
				<InspectorControls group="styles">
					<PanelBody
						className="icon-block-support-panel"
						title={ __( 'Arrow' ) }
						initialOpen={ true }
					>
						<ToggleControl
							label={ __( 'Show Arrow' ) }
							checked={ hasIcon }
							onChange={ ( checked ) => {
								props.setAttributes( {
									hasIcon: checked,
								} );
							} }
						/>
					</PanelBody>
				</InspectorControls>
                <BlockEdit { ...props } className={ hasIcon ? 'has-icon' : '' } />
            </Fragment>
        );
    };
}, 'withIconControl' );

addFilter( 'editor.BlockEdit', 'app/with-icon-control', withIconControl );

const addIconExtraProps = ( saveElementProps, blockType, attributes ) => {
    if ( blockType.name !== 'core/button' ) {
        return saveElementProps;
    }

    if ( attributes.hasIcon ) {
		const className = saveElementProps.className ? `${ saveElementProps.className } has-icon` : 'has-icon';
		saveElementProps = { ...saveElementProps, className };
	}

    return saveElementProps;
};

addFilter( 'blocks.getSaveContent.extraProps', 'app/get-save-content/extra-props', addIconExtraProps );

const addMobileAttribute = ( settings, name ) => {
    if ( name !== 'core/button' ) {
        return settings;
    }

    settings.attributes = { ...settings.attributes,
		hideOnMobile: {
            type: 'boolean',
            default: false,
        }
	};

    return settings;
};

addFilter( 'blocks.registerBlockType', 'app/attribute/hide-mobile', addMobileAttribute );

const withMobileControl = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        if ( props.name !== 'core/button' ) {
            return (
                <BlockEdit { ...props } />
            );
        }

        const { hideOnMobile } = props.attributes;

        return (
            <Fragment>
				<InspectorControls group="styles">
					<PanelBody
						className="icon-block-support-panel"
						title={ __( 'Responsive' ) }
						initialOpen={ true }
					>
						<ToggleControl
							label={ __( 'Hide on mobile' ) }
							checked={ hideOnMobile }
							onChange={ ( checked ) => {
								props.setAttributes( {
									hideOnMobile: checked,
								} );
							} }
						/>
					</PanelBody>
				</InspectorControls>
                <BlockEdit { ...props } className={ hideOnMobile ? 'hidden-xs' : '' } />
            </Fragment>
        );
    };
}, 'withMobileControl' );

addFilter( 'editor.BlockEdit', 'app/with-mobile-control', withMobileControl );

const addMobileExtraProps = ( saveElementProps, blockType, attributes ) => {
    if ( blockType.name !== 'core/button' ) {
        return saveElementProps;
    }

    if ( attributes.hideOnMobile ) {
		const className = saveElementProps.className ? `${ saveElementProps.className } hidden-xs` : 'hidden-xs';
		saveElementProps = { ...saveElementProps, className };
	}

    return saveElementProps;
};

addFilter( 'blocks.getSaveContent.extraProps', 'app/get-save-content/extra-props', addMobileExtraProps );
