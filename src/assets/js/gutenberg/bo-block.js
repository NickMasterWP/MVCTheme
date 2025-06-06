( function ( blocks, element, serverSideRender, blockEditor ) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        components=wp.components,
        ServerSideRender = serverSideRender,
        useBlockProps = blockEditor.useBlockProps;
    var InnerBlocks = wp.editor.InnerBlocks,
        allowedBlocks = ['core/image', 'core/paragraph',  'core/columns','core/table', 'core/list', 'core/shortcode',  'core/heading',
        'yoast/faq-block', 'mvctheme/fc-list-images' , 'mvctheme/bo-block' , 'mvctheme/fc-form-subscribe' , 'mvctheme/bo-sets-table', 'mvctheme/hub-table-content', 'mvctheme/hub-post-link'  ];

    registerBlockType( 'mvctheme/bo-block', {
        apiVersion: 2,
        title: 'BO: Блок',
        icon: 'database',
        category: 'MVC_category',
        attributes: {
            bgText: {
                type: 'string'
            },
            colorText: {
                type: 'string'
            },
            colorBorderText: {
                type: 'string'
            },
            paddingText: {
                type: 'string'
            },
            marginText: {
                type: 'string'
            }
        },
        edit: function( props ) {

            var blockProps = useBlockProps();
            blockProps.className += " fc-admin-fullwidth fc-block ";
            blockProps.style = { background: props.attributes.bgText,
                border: "1px solid " + props.attributes.colorBorderText,
                padding: props.attributes.paddingText,
                margin: props.attributes.marginText,
                color: props.attributes.colorText
            };

            return el(
                wp.element.Fragment,
                null,
                el(
                    wp.editor.InspectorControls,
                    null,
                    el(
                        components.TextControl,
                        {
                            label: 'Цвет заливки ',
                            onChange: ( value ) => {
                                props.setAttributes( { bgText: value } );
                            },
                            value: props.attributes.bgText
                        }
                    ),
                    el(
                        components.TextControl,
                        {
                            label: 'Цвет границ ',
                            onChange: ( value ) => {
                                props.setAttributes( { colorBorderText: value } );
                            },
                            value: props.attributes.colorBorderText
                        }
                    ),
                    el(
                        components.TextControl,
                        {
                            label: 'Цвет шрифта ',
                            onChange: ( value ) => {
                                props.setAttributes( { colorText: value } );
                            },
                            value: props.attributes.colorText
                        }
                    ),
                    el(
                        components.TextControl,
                        {
                            label: 'Внутренний отступ',
                            onChange: ( value ) => {
                                props.setAttributes( { paddingText: value } );
                            },
                            value: props.attributes.paddingText
                        }
                    ),
                    el(
                        components.TextControl,
                        {
                            label: 'Внешний отступ',
                            onChange: ( value ) => {
                                props.setAttributes( { marginText: value } );
                            },
                            value: props.attributes.marginText
                        }
                    ),
                ),
                el('div',
                    blockProps,
                    el(InnerBlocks,{allowedBlocks:allowedBlocks,}) )

            );

        },

        save: function( props ) {

            var blockProps = useBlockProps.save();

            blockProps.style = { background: props.attributes.bgText,
                border: "1px solid " + props.attributes.colorBorderText,
                padding: props.attributes.paddingText,
                margin: props.attributes.marginText,
                color: props.attributes.colorText
            };

            return(
                el('div',blockProps,
                    el(InnerBlocks.Content,null) )
            );

        }

    } );


} )(
    window.wp.blocks,
    window.wp.element,
    window.wp.serverSideRender,
    window.wp.blockEditor
);