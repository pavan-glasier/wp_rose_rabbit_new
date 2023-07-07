( function ( blocks, element, blockEditor, components ) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var MediaUpload = blockEditor.MediaUpload;
    var useBlockProps = blockEditor.useBlockProps;
    var BlockControls = blockEditor.BlockControls;
    var AlignmentToolbar = blockEditor.AlignmentToolbar;
    
    blocks.registerBlockType( 'supporthost-blocks/supporthost-06', {

        attributes: {
            mediaID: {
		        type: 'number',
	        },
            mediaURL: {
                type: 'string',
                source: 'attribute',
                selector: 'img',
                attribute: 'src',
            },
            name: {
                type: 'string',
                source: 'html',
                selector: '.supporthost-testimonial-name',
            },
            position: {
                type: 'string',
                source: 'html',
                selector: '.supporthost-testimonial-position',
            },
            content: {
                type: 'string',
                source: 'html',
                selector: '.supporthost-testimonial-content',
            },
            alignment: {
                type: 'string',
                default: 'center',
            },
        },
        
        edit: function ( props ) {
            var blockProps = useBlockProps();
            var name = props.attributes.name;
            var position = props.attributes.position;
            var content = props.attributes.content;
            var alignment = props.attributes.alignment;

            var mediaID = props.attributes.mediaID;
            var mediaURL = props.attributes.mediaURL;

            

            function onChangeName( newName ) {
                props.setAttributes( { name: newName } );
            }
            function onChangePosition( newPosition ) {
                props.setAttributes( { position: newPosition } );
            }
            function onChangeContent( newContent ) {
                props.setAttributes( { content: newContent } );
            }

            function onChangeAlignment( newAlignment ) {
                props.setAttributes( {
                    alignment:
                        newAlignment === undefined ? 'center' : newAlignment,
                } );
            }
            function onSelectImage( media ) {
                props.setAttributes( {
                    mediaURL: media.url,
                    mediaID: media.id,
                } );
            }
            return el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'supporthost-testimonial-image', style: { textAlign: alignment }, },
                    el( MediaUpload, {
                        onSelect: onSelectImage,
                        allowedTypes: 'image',
                        value: mediaID,
                        render: function ( obj ) {
                            return el(
                                components.Button,
                                {
                                    className: mediaID
                                        ? 'image-button'
                                        : 'button button-large',
                                    onClick: obj.open,
                                },
                                ! mediaID
                                    ? 'Upload Image'
                                    : el( 'img', { src: mediaURL } )
                            );
                        },
                    } )
                ),
                el(
                    BlockControls,
                    { key: 'controls' },
                    el( AlignmentToolbar, {
                        value: alignment,
                        onChange: onChangeAlignment,
                    } )
                ),
                el( RichText, {
                    key: 'richtext',
                    tagName: 'p',
                    style: { textAlign: alignment },
                    onChange: onChangeName,
                    value: name,
                    placeholder: 'Insert name here...'
                } ),
                el( RichText, {
                    key: 'richtext',
                    tagName: 'p',
                    style: { textAlign: alignment },
                    onChange: onChangePosition,
                    value: position,
                    placeholder: 'Insert position here...'
                } ),
                el( RichText, {
                    key: 'richtext',
                    tagName: 'p',
                    style: { textAlign: alignment },
                    onChange: onChangeContent,
                    value: content,
                    placeholder: 'Insert review here...'
                } ),
                
            );
        },

        save: function ( props ) {
            var blockProps = useBlockProps.save();

            return el(
                'div',
                blockProps,
                el( RichText.Content, {
                    tagName: 'p',
                    className:
                        'supporthost-testimonial-name supporthost-block-align-' +
                        props.attributes.alignment,
                    value: props.attributes.name,
                } ),
                el( RichText.Content, {
                    tagName: 'p',
                    className:
                        'supporthost-testimonial-position supporthost-block-align-' +
                        props.attributes.alignment,
                    value: props.attributes.position,
                } ),
                el( RichText.Content, {
                    tagName: 'p',
                    className:
                        'supporthost-testimonial-content supporthost-block-align-' +
                        props.attributes.alignment,
                    value: props.attributes.content,
                } ),
                el(
                    'div',
                    { className: 'supporthost-testimonial-image supporthost-block-align-' + props.attributes.alignment, },
                    el( 'img', { src: props.attributes.mediaURL } )
                  ),
            );
        },

    } );
} )( 
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components
 );