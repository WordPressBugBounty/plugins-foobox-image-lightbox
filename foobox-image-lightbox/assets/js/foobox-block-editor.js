(function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.hooks || ! wp.element || ! wp.components || ! wp.compose || ! wp.blocks ) {
		return;
	}

	var __ = wp.i18n.__;
	var addFilter = wp.hooks.addFilter;
	var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
	var createElement = wp.element.createElement;
	var cloneElement = wp.element.cloneElement;
	var Children = wp.element.Children;
	var Fragment = wp.element.Fragment;
	var useEffect = wp.element.useEffect;
	var InspectorControls = ( wp.blockEditor || wp.editor ).InspectorControls;
	var BlockListBlock = ( wp.blockEditor || wp.editor ).BlockListBlock;
	var MediaUpload = ( wp.blockEditor || wp.editor ).MediaUpload;
	var MediaUploadCheck = ( wp.blockEditor || wp.editor ).MediaUploadCheck;
	var PanelBody = wp.components.PanelBody;
	var ToggleControl = wp.components.ToggleControl;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var Button = wp.components.Button;
	var Notice = wp.components.Notice;
	var config = window.FOOBOX_BLOCK_EDITOR || {};

	var BLOCKS = {
		'core/button': 'button',
		'core/image': 'image',
		'core/gallery': 'gallery',
		'core/group': 'content'
	};

	var TYPE_OPTIONS = [
		{ label: __( 'Auto-detect from URL', 'foobox-image-lightbox' ), value: 'auto' },
		{ label: __( 'Image', 'foobox-image-lightbox' ), value: 'image' },
		{ label: __( 'HTML content', 'foobox-image-lightbox' ), value: 'html' },
		{ label: __( 'Iframe', 'foobox-image-lightbox' ), value: 'iframe' },
		{ label: __( 'Video', 'foobox-image-lightbox' ), value: 'video' }
	];

	var attributes = {
		fooboxEnabled: {
			type: 'boolean',
			default: false
		},
		fooboxType: {
			type: 'string',
			default: 'auto'
		},
		fooboxStyle: {
			type: 'string',
			default: ''
		},
		fooboxTheme: {
			type: 'string',
			default: ''
		},
		fooboxEffect: {
			type: 'string',
			default: ''
		},
		fooboxWidth: {
			type: 'string',
			default: ''
		},
		fooboxHeight: {
			type: 'string',
			default: ''
		},
		fooboxMaxWidth: {
			type: 'string',
			default: ''
		},
		fooboxMaxHeight: {
			type: 'string',
			default: ''
		},
		fooboxGroup: {
			type: 'string',
			default: ''
		},
		fooboxCaptionTitle: {
			type: 'string',
			default: ''
		},
		fooboxCaptionDesc: {
			type: 'string',
			default: ''
		},
		fooboxCover: {
			type: 'string',
			default: ''
		},
		fooboxOverflow: {
			type: 'boolean',
			default: false
		},
		fooboxHtmlId: {
			type: 'string',
			default: ''
		},
		fooboxHideContent: {
			type: 'boolean',
			default: true
		}
	};

	function isSupported( blockName ) {
		return Object.prototype.hasOwnProperty.call( BLOCKS, blockName );
	}

	function cleanValue( value ) {
		return typeof value === 'string' ? value.trim() : '';
	}

	function cleanId( value ) {
		return cleanValue( value ).replace( /^#/, '' ).replace( /[^A-Za-z0-9_:.-]/g, '-' );
	}

	function addClassName( className, additions ) {
		var classes = cleanValue( className ).split( /\s+/ ).filter( Boolean );
		additions.split( /\s+/ ).forEach( function ( name ) {
			if ( name && classes.indexOf( name ) === -1 ) {
				classes.push( name );
			}
		} );
		return classes.join( ' ' );
	}

	function coreLightboxOverrideEnabled() {
		return !! config.overrideCoreLightbox;
	}

	function hasCoreLightboxEnabled( attrs ) {
		return !! (
			attrs &&
			attrs.lightbox &&
			typeof attrs.lightbox === 'object' &&
			attrs.lightbox.enabled === true
		);
	}

	function galleryWantsCoreLightbox( attrs ) {
		return !! ( attrs && attrs.linkTo === 'lightbox' );
	}

	function getGeneratedGalleryGroup( clientId ) {
		return 'foobox-gallery-' + ( cleanId( clientId || 'block' ).slice( 0, 8 ) || 'block' );
	}

	function valuesDiffer( current, next ) {
		if ( next && typeof next === 'object' ) {
			return JSON.stringify( current || {} ) !== JSON.stringify( next );
		}

		return current !== next;
	}

	function setChangedAttributes( props, next ) {
		var changed = {};
		var attrs = props.attributes || {};

		Object.keys( next ).forEach( function ( key ) {
			if ( valuesDiffer( attrs[ key ], next[ key ] ) ) {
				changed[ key ] = next[ key ];
			}
		} );

		if ( Object.keys( changed ).length ) {
			props.setAttributes( changed );
		}
	}

	function getSelectedMediaUrl( media ) {
		if ( ! media ) {
			return '';
		}

		if ( media.url ) {
			return media.url;
		}

		if ( media.source_url ) {
			return media.source_url;
		}

		if ( media.sizes && media.sizes.full && media.sizes.full.url ) {
			return media.sizes.full.url;
		}

		return '';
	}

	function setDataAttr( props, name, value ) {
		if ( value === true || value === false || cleanValue( value ) !== '' ) {
			props[ name ] = value;
		}
	}

	function getStyleOptions() {
		return config.styles || [
			{ label: __( 'Default', 'foobox-image-lightbox' ), value: '' },
			{ label: __( 'Rounded', 'foobox-image-lightbox' ), value: 'fbx-rounded' },
			{ label: __( 'Flat', 'foobox-image-lightbox' ), value: 'fbx-flat' },
			{ label: __( 'Metro', 'foobox-image-lightbox' ), value: 'fbx-metro' },
			{ label: __( 'Glass', 'foobox-image-lightbox' ), value: 'fbx-glass' }
		];
	}

	function getThemeOptions() {
		return config.themes || [
			{ label: __( 'Default', 'foobox-image-lightbox' ), value: '' },
			{ label: __( 'Light', 'foobox-image-lightbox' ), value: 'fbx-light' },
			{ label: __( 'Dark', 'foobox-image-lightbox' ), value: 'fbx-dark' }
		];
	}

	function getEffectOptions() {
		return config.effects || [
			{ label: __( 'Default', 'foobox-image-lightbox' ), value: '' },
			{ label: __( 'Zoom in', 'foobox-image-lightbox' ), value: 'fbx-effect-1' },
			{ label: __( 'Slide from right', 'foobox-image-lightbox' ), value: 'fbx-effect-2' },
			{ label: __( 'Flip horizontal', 'foobox-image-lightbox' ), value: 'fbx-effect-7' }
		];
	}

	function getRootProps( blockName, attrs ) {
		var props = {};
		var mode = BLOCKS[ blockName ];

		if ( mode === 'content' ) {
			if ( ! attrs.fooboxEnabled ) {
				return props;
			}

			props.id = cleanId( attrs.fooboxHtmlId );
			props.className = 'foobox-hidden-content';
			setDataAttr( props, 'data-width', attrs.fooboxWidth );
			setDataAttr( props, 'data-height', attrs.fooboxHeight );
			return props;
		}

		if ( ! attrs.fooboxEnabled ) {
			return props;
		}

		props.className = 'foobox';
		setDataAttr( props, 'data-foobox-trigger', mode );
		setDataAttr( props, 'data-style', attrs.fooboxStyle );
		setDataAttr( props, 'data-theme', attrs.fooboxTheme );
		setDataAttr( props, 'data-effect', attrs.fooboxEffect );
		setDataAttr( props, 'data-foobox-group', attrs.fooboxGroup );

		if ( attrs.fooboxOverflow ) {
			props[ 'data-overflow' ] = true;
		}

		return props;
	}

	function getLinkProps( blockName, attrs ) {
		var props = {};
		var type = attrs.fooboxType || 'auto';

		if ( ! attrs.fooboxEnabled ) {
			return props;
		}

		if ( type === 'html' || type === 'iframe' ) {
			props.target = 'foobox';
		}

		setDataAttr( props, 'data-width', attrs.fooboxWidth );
		setDataAttr( props, 'data-height', attrs.fooboxHeight );
		setDataAttr( props, 'data-max-width', attrs.fooboxMaxWidth );
		setDataAttr( props, 'data-max-height', attrs.fooboxMaxHeight );
		setDataAttr( props, 'data-foobox-group', attrs.fooboxGroup );
		setDataAttr( props, 'data-caption-title', attrs.fooboxCaptionTitle );
		setDataAttr( props, 'data-caption-desc', attrs.fooboxCaptionDesc );
		setDataAttr( props, 'data-cover', attrs.fooboxCover );

		if ( attrs.fooboxOverflow ) {
			props[ 'data-overflow' ] = true;
		}

		return props;
	}

	function mergeProps( target, source ) {
		var result = Object.assign( {}, target );
		Object.keys( source ).forEach( function ( key ) {
			if ( key === 'className' ) {
				result.className = addClassName( result.className, source.className );
				return;
			}
			if ( key === 'style' ) {
				result.style = Object.assign( {}, result.style || {}, source.style || {} );
				return;
			}
			if ( source[ key ] !== '' && source[ key ] !== null && typeof source[ key ] !== 'undefined' ) {
				result[ key ] = source[ key ];
			}
		} );
		return result;
	}

	function mapElementTree( element, callback ) {
		var mapped;
		var nextChildren;

		if ( ! wp.element.isValidElement( element ) ) {
			return element;
		}

		mapped = callback( element ) || element;

		if ( typeof mapped.props.children === 'undefined' ) {
			return mapped;
		}

		nextChildren = Children.map( mapped.props.children, function ( child ) {
			return mapElementTree( child, callback );
		} );

		return cloneElement( mapped, {}, nextChildren );
	}

	function addPropsToAnchors( element, props, onlyFirst ) {
		var hasUpdated = false;

		return mapElementTree( element, function ( child ) {
			var isAnchor = child.type === 'a' || ( child.props && child.props.tagName === 'a' );

			if ( ! isAnchor || ( onlyFirst && hasUpdated ) ) {
				return child;
			}

			hasUpdated = true;
			return cloneElement( child, mergeProps( child.props || {}, props ) );
		} );
	}

	function addPropsToRoot( element, props ) {
		if ( ! wp.element.isValidElement( element ) ) {
			return element;
		}

		return cloneElement( element, mergeProps( element.props || {}, props ) );
	}

	function syncImageCoreLightboxAttributes( props ) {
		useEffect( function () {
			var attrs = props.attributes || {};
			var next;

			if ( ! coreLightboxOverrideEnabled() || props.name !== 'core/image' || ! hasCoreLightboxEnabled( attrs ) ) {
				return;
			}

			next = {
				fooboxEnabled: true,
				linkDestination: 'media',
				lightbox: { enabled: false }
			};

			if ( attrs.url && ! attrs.href ) {
				next.href = attrs.url;
			}

			setChangedAttributes( props, next );
		} );
	}

	function syncGalleryChildAttributes( props ) {
		useEffect( function () {
			var attrs = props.attributes || {};
			var childAttrs;
			var children;
			var dispatch;
			var enabled;
			var galleryNext = {};
			var group;
			var wantsCoreLightbox;

			if ( props.name !== 'core/gallery' || ! wp.data || ! props.clientId ) {
				return;
			}

			wantsCoreLightbox = coreLightboxOverrideEnabled() && galleryWantsCoreLightbox( attrs );
			enabled = !! attrs.fooboxEnabled || wantsCoreLightbox;
			group = attrs.fooboxGroup || ( enabled ? getGeneratedGalleryGroup( props.clientId ) : '' );

			if ( wantsCoreLightbox ) {
				galleryNext.fooboxEnabled = true;
				galleryNext.linkTo = 'media';

				if ( ! attrs.fooboxGroup ) {
					galleryNext.fooboxGroup = group;
				}

				setChangedAttributes( props, galleryNext );
			}

			children = wp.data.select( 'core/block-editor' ).getBlocks( props.clientId );
			dispatch = wp.data.dispatch( 'core/block-editor' );

			if ( ! children || ! children.length || ! dispatch ) {
				return;
			}

			childAttrs = enabled ? {
				fooboxEnabled: true,
				fooboxStyle: attrs.fooboxStyle || '',
				fooboxTheme: attrs.fooboxTheme || '',
				fooboxEffect: attrs.fooboxEffect || '',
				fooboxGroup: group,
				linkDestination: 'media'
			} : {
				fooboxEnabled: false,
				fooboxStyle: '',
				fooboxTheme: '',
				fooboxEffect: '',
				fooboxGroup: ''
			};

			children.forEach( function ( child ) {
				var next = {};

				if ( child.name !== 'core/image' ) {
					return;
				}

				Object.keys( childAttrs ).forEach( function ( key ) {
					if ( child.attributes[ key ] !== childAttrs[ key ] ) {
						next[ key ] = childAttrs[ key ];
					}
				} );

				if ( enabled && ( wantsCoreLightbox || hasCoreLightboxEnabled( child.attributes ) ) ) {
					if ( valuesDiffer( child.attributes.lightbox, { enabled: false } ) ) {
						next.lightbox = { enabled: false };
					}
				}

				if ( enabled && ! child.attributes.href && child.attributes.url ) {
					next.href = child.attributes.url;
				}

				if ( Object.keys( next ).length ) {
					dispatch.updateBlockAttributes( child.clientId, next );
				}
			} );
		} );
	}

	function renderHelp( message, key ) {
		return createElement( 'p', { className: 'foobox-editor-help', key: key || 'help' }, message );
	}

	function renderCommonControls( attrs, setAttributes, blockName ) {
		var controls = [
			createElement( SelectControl, {
				key: 'style',
				label: __( 'Style', 'foobox-image-lightbox' ),
				value: attrs.fooboxStyle || '',
				options: getStyleOptions(),
				onChange: function ( value ) {
					setAttributes( { fooboxStyle: value } );
				}
			} ),
			createElement( SelectControl, {
				key: 'theme',
				label: __( 'Theme', 'foobox-image-lightbox' ),
				value: attrs.fooboxTheme || '',
				options: getThemeOptions(),
				onChange: function ( value ) {
					setAttributes( { fooboxTheme: value } );
				}
			} ),
			createElement( SelectControl, {
				key: 'effect',
				label: __( 'Opening effect', 'foobox-image-lightbox' ),
				value: attrs.fooboxEffect || '',
				options: getEffectOptions(),
				onChange: function ( value ) {
					setAttributes( { fooboxEffect: value } );
				}
			} ),
			createElement( TextControl, {
				key: 'group',
				label: __( 'Gallery group', 'foobox-image-lightbox' ),
				value: attrs.fooboxGroup || '',
				help: __( 'Use the same group name on items that should open together.', 'foobox-image-lightbox' ),
				onChange: function ( value ) {
					setAttributes( { fooboxGroup: value } );
				}
			} )
		];

		if ( blockName !== 'core/gallery' ) {
			controls.push(
				createElement( TextControl, {
					key: 'caption-title',
					label: __( 'Caption title', 'foobox-image-lightbox' ),
					value: attrs.fooboxCaptionTitle || '',
					onChange: function ( value ) {
						setAttributes( { fooboxCaptionTitle: value } );
					}
				} ),
				createElement( TextareaControl, {
					key: 'caption-desc',
					label: __( 'Caption description', 'foobox-image-lightbox' ),
					value: attrs.fooboxCaptionDesc || '',
					onChange: function ( value ) {
						setAttributes( { fooboxCaptionDesc: value } );
					}
				} )
			);
		}

		return controls;
	}

	function renderSizeControls( attrs, setAttributes, includeMax ) {
		var controls = [
			createElement( TextControl, {
				key: 'width',
				label: __( 'Width', 'foobox-image-lightbox' ),
				type: 'number',
				min: 0,
				value: attrs.fooboxWidth || '',
				onChange: function ( value ) {
					setAttributes( { fooboxWidth: value } );
				}
			} ),
			createElement( TextControl, {
				key: 'height',
				label: __( 'Height', 'foobox-image-lightbox' ),
				type: 'number',
				min: 0,
				value: attrs.fooboxHeight || '',
				onChange: function ( value ) {
					setAttributes( { fooboxHeight: value } );
				}
			} )
		];

		if ( includeMax ) {
			controls.push(
				createElement( TextControl, {
					key: 'max-width',
					label: __( 'Maximum image width', 'foobox-image-lightbox' ),
					type: 'number',
					min: 0,
					value: attrs.fooboxMaxWidth || '',
					onChange: function ( value ) {
						setAttributes( { fooboxMaxWidth: value } );
					}
				} ),
				createElement( TextControl, {
					key: 'max-height',
					label: __( 'Maximum image height', 'foobox-image-lightbox' ),
					type: 'number',
					min: 0,
					value: attrs.fooboxMaxHeight || '',
					onChange: function ( value ) {
						setAttributes( { fooboxMaxHeight: value } );
					}
				} )
			);
		}

		return controls;
	}

	function renderLightboxUrlMediaControl( setAttributes ) {
		if ( ! MediaUpload || ! MediaUploadCheck ) {
			return null;
		}

		return createElement(
			MediaUploadCheck,
			{ key: 'url-media-check' },
			createElement( MediaUpload, {
				mode: 'browse',
				multiple: false,
				onSelect: function ( media ) {
					setAttributes( { url: getSelectedMediaUrl( media ) } );
				},
				render: function ( mediaProps ) {
					return createElement( Button, {
						key: 'url-media',
						variant: 'secondary',
						onClick: mediaProps.open
					}, __( 'Select from media library', 'foobox-image-lightbox' ) );
				}
			} )
		);
	}

	function renderVideoControls( attrs, setAttributes ) {
		var controls = [];

		if ( attrs.fooboxType !== 'video' ) {
			return controls;
		}

		controls.push(
			createElement( TextControl, {
				key: 'cover',
				label: __( 'Poster image URL', 'foobox-image-lightbox' ),
				value: attrs.fooboxCover || '',
				help: __( 'Used as the poster image for self-hosted video links.', 'foobox-image-lightbox' ),
				onChange: function ( value ) {
					setAttributes( { fooboxCover: value } );
				}
			} )
		);

		if ( MediaUpload && MediaUploadCheck ) {
			controls.push(
				createElement(
					MediaUploadCheck,
					{ key: 'cover-media-check' },
					createElement( MediaUpload, {
						allowedTypes: [ 'image' ],
						onSelect: function ( media ) {
							setAttributes( { fooboxCover: media && media.url ? media.url : '' } );
						},
						render: function ( mediaProps ) {
							return createElement( Button, {
								variant: 'secondary',
								onClick: mediaProps.open
							}, attrs.fooboxCover ? __( 'Replace poster image', 'foobox-image-lightbox' ) : __( 'Choose poster image', 'foobox-image-lightbox' ) );
						}
					} )
				)
			);

			if ( attrs.fooboxCover ) {
				controls.push(
					createElement( Button, {
						key: 'cover-remove',
						variant: 'tertiary',
						isDestructive: true,
						onClick: function () {
							setAttributes( { fooboxCover: '' } );
						}
					}, __( 'Remove poster image', 'foobox-image-lightbox' ) )
				);
			}
		}

		return controls;
	}

	function renderTriggerControls( attrs, setAttributes ) {
		return [
			createElement( SelectControl, {
				key: 'type',
				label: __( 'Content type', 'foobox-image-lightbox' ),
				value: attrs.fooboxType || 'auto',
				options: TYPE_OPTIONS,
				onChange: function ( value ) {
					setAttributes( { fooboxType: value } );
				}
			} ),
			createElement( TextControl, {
				key: 'url',
				label: __( 'Lightbox URL or target', 'foobox-image-lightbox' ),
				value: attrs.url || '',
				help: __( 'Use an image/video URL, an iframe URL, or #content-id for hidden HTML content.', 'foobox-image-lightbox' ),
				onChange: function ( value ) {
					setAttributes( { url: value } );
				}
			} ),
			renderLightboxUrlMediaControl( setAttributes )
		].concat( renderVideoControls( attrs, setAttributes ), renderSizeControls( attrs, setAttributes, false ), renderCommonControls( attrs, setAttributes, 'core/button' ) );
	}

	function renderImageControls( attrs, setAttributes ) {
		var controls = [];

		if ( attrs.url && attrs.linkDestination !== 'media' ) {
			controls.push(
				createElement( Button, {
					key: 'media-link',
					variant: 'secondary',
					onClick: function () {
						setAttributes( {
							linkDestination: 'media',
							href: attrs.url
						} );
					}
				}, __( 'Link image to media file', 'foobox-image-lightbox' ) )
			);
		}

		controls.push(
			createElement( ToggleControl, {
				key: 'panning',
				label: __( 'Enable large image panning', 'foobox-image-lightbox' ),
				checked: !! attrs.fooboxOverflow,
				onChange: function ( value ) {
					setAttributes( { fooboxOverflow: value } );
				}
			} )
		);

		return controls.concat( renderSizeControls( attrs, setAttributes, true ), renderCommonControls( attrs, setAttributes, 'core/image' ) );
	}

	function renderGalleryControls( attrs, setAttributes ) {
		var controls = [];

		if ( attrs.linkTo !== 'media' ) {
			controls.push(
				createElement( Button, {
					key: 'gallery-media-link',
					variant: 'secondary',
					onClick: function () {
						setAttributes( { linkTo: 'media' } );
					}
				}, __( 'Link gallery images to media files', 'foobox-image-lightbox' ) )
			);
		}

		return controls.concat( renderCommonControls( attrs, setAttributes, 'core/gallery' ) );
	}

	function renderContentControls( attrs, setAttributes, clientId ) {
		var id = cleanId( attrs.fooboxHtmlId );

		return [
			! id && createElement( Notice, {
				key: 'missing-id',
				status: 'warning',
				isDismissible: false
			}, __( 'Add an HTML id, then point a FooBox button to #that-id.', 'foobox-image-lightbox' ) ),
			createElement( TextControl, {
				key: 'html-id',
				label: __( 'HTML id', 'foobox-image-lightbox' ),
				value: attrs.fooboxHtmlId || '',
				help: __( 'Example: demo-hidden-form. Button links should use #demo-hidden-form.', 'foobox-image-lightbox' ),
				onChange: function ( value ) {
					setAttributes( { fooboxHtmlId: cleanId( value ) } );
				}
			} ),
			createElement( Button, {
				key: 'generate-id',
				variant: 'secondary',
				onClick: function () {
					setAttributes( { fooboxHtmlId: 'foobox-content-' + clientId.slice( 0, 8 ) } );
				}
			}, __( 'Generate id', 'foobox-image-lightbox' ) ),
			createElement( ToggleControl, {
				key: 'hide',
				label: __( 'Hide on front end', 'foobox-image-lightbox' ),
				checked: attrs.fooboxHideContent !== false,
				onChange: function ( value ) {
					setAttributes( { fooboxHideContent: value } );
				}
			} )
		].concat( renderSizeControls( attrs, setAttributes, false ) );
	}

	function renderControlsForBlock( props ) {
		var attrs = props.attributes;
		var blockName = props.name;
		var mode = BLOCKS[ blockName ];
		var enabled = !! attrs.fooboxEnabled;
		var children = [
			createElement( ToggleControl, {
				key: 'enabled',
				label: mode === 'content' ? __( 'Use as hidden FooBox content', 'foobox-image-lightbox' ) : __( 'Enable FooBox', 'foobox-image-lightbox' ),
				checked: enabled,
				onChange: function ( value ) {
					var next = { fooboxEnabled: value };
					if ( value && mode === 'content' && ! attrs.fooboxHtmlId ) {
						next.fooboxHtmlId = 'foobox-content-' + props.clientId.slice( 0, 8 );
					}
					props.setAttributes( next );
				}
			} )
		];

		if ( ! enabled ) {
			children.push( renderHelp( mode === 'content' ? __( 'Build hidden forms, shortcode output, or custom layouts visually inside this group.', 'foobox-image-lightbox' ) : __( 'Turn this block into a FooBox trigger without writing HTML.', 'foobox-image-lightbox' ), 'disabled-help' ) );
			return children;
		}

		if ( blockName === 'core/button' ) {
			return children.concat( renderTriggerControls( attrs, props.setAttributes ) );
		}
		if ( blockName === 'core/image' ) {
			return children.concat( renderImageControls( attrs, props.setAttributes ) );
		}
		if ( blockName === 'core/gallery' ) {
			return children.concat( renderGalleryControls( attrs, props.setAttributes ) );
		}
		if ( blockName === 'core/group' ) {
			return children.concat( renderContentControls( attrs, props.setAttributes, props.clientId ) );
		}

		return children;
	}

	addFilter( 'blocks.registerBlockType', 'foobox/block-editor/attributes', function ( settings, name ) {
		if ( ! isSupported( name ) ) {
			return settings;
		}

		settings.attributes = Object.assign( {}, settings.attributes || {}, attributes );
		return settings;
	} );

	addFilter( 'editor.BlockEdit', 'foobox/block-editor/controls', createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			syncImageCoreLightboxAttributes( props );
			syncGalleryChildAttributes( props );

			if ( ! isSupported( props.name ) ) {
				return createElement( BlockEdit, props );
			}

			return createElement(
				Fragment,
				null,
				createElement( BlockEdit, props ),
				createElement(
					InspectorControls,
					config.inspectorGroup ? { group: config.inspectorGroup } : null,
					createElement(
						PanelBody,
						{
							title: __( 'FooBox', 'foobox-image-lightbox' ),
							initialOpen: !! props.attributes.fooboxEnabled
						},
						createElement( 'div', { className: 'foobox-editor-panel' }, renderControlsForBlock( props ) )
					)
				)
			);
		};
	}, 'withFooBoxControls' ) );

	addFilter( 'editor.BlockListBlock', 'foobox/block-editor/block-list-class', createHigherOrderComponent( function ( OriginalBlockListBlock ) {
		return function ( props ) {
			var className = props.className || '';

			if ( props.attributes && props.attributes.fooboxEnabled && isSupported( props.name ) ) {
				className = addClassName( className, props.name === 'core/group' ? 'is-foobox-hidden-content' : 'is-foobox-enabled' );
			}

			return createElement( OriginalBlockListBlock || BlockListBlock, Object.assign( {}, props, { className: className } ) );
		};
	}, 'withFooBoxBlockListClass' ) );

	addFilter( 'blocks.getSaveElement', 'foobox/block-editor/link-props', function ( element, blockType, attrs ) {
		var rootProps;
		var linkProps;

		if ( ! blockType || ! isSupported( blockType.name ) || ! attrs ) {
			return element;
		}

		if ( blockType.name === 'core/gallery' ) {
			return element;
		}

		rootProps = getRootProps( blockType.name, attrs );

		if ( blockType.name === 'core/group' && attrs.fooboxEnabled && attrs.fooboxHideContent !== false ) {
			rootProps.style = { display: 'none' };
		}

		element = addPropsToRoot( element, rootProps );

		if ( ! attrs.fooboxEnabled ) {
			return element;
		}

		if ( blockType.name === 'core/group' ) {
			return element;
		}

		linkProps = getLinkProps( blockType.name, attrs );

		if ( blockType.name === 'core/gallery' ) {
			return addPropsToAnchors( element, linkProps, false );
		}

		return addPropsToAnchors( element, linkProps, true );
	} );
})( window.wp );
