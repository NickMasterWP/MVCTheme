<?php 
class MVCGutenbergBlock {

	protected $name;
	protected $fields;
    protected $view_path;
	
	public function __construct($name, $view_path, $fields ) {
		$this->name = $name;
		$this->fields = $fields;
        $this->view_path = $view_path;

        add_action( 'init', [ $this,'add_gutenberg_blocks_scripts_dynamic'] );

	}

	public function add_gutenberg_blocks_scripts_dynamic() {

        wp_register_script(
            'mvctheme-'.$this->name,
            getTemplate_directory_uri() . '/assets/js/gutenberg/'.$this->name.'.js',
            array('wp-blocks','wp-editor'),
            MVC_THEME_VERSION
        );

        $attribtes = [];

        foreach ($this->fields as $field) {
            $type = "string";
            if ( $field[3] == "number ") {
                $type = "int";
            }
            $attribtes[ $field[0] ] = [
                "type" => $type,
                'default'   => $field[2],
            ];
        }

        register_block_type( 'mvctheme/'.$this->name, array(
            'api_version' => 2,
            'attributes'      => $attribtes,
            'editor_script' => 'mvctheme-'.$this->name,
            'render_callback' => [ $this, 'gutenberg_block_render_callback' ]
        ) );

    }


	public function gutenberg_block_render_callback( $attributes, $content  ) {
        $attributes["content"] = $content;

        return View::partial( $this->view_path , $attributes);
	}

}