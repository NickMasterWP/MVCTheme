<?php

namespace MVCTheme\Core;

class MVCElementorBlock extends \Elementor\Widget_Base {

  protected $nameWidget;
  protected $title;
  protected $fields;
  protected $icon;
  protected $view_path;

    const TYPE_SWITCHER = "switcher";
    const TYPE_IMAGE = "image";
    const TYPE_TINY_MCE = "tiny_mce";
    const TYPE_SELECT = "select";
    const TYPE_SELECT2 = "select2";
    const TYPE_TEXT = "text";
    const TYPE_COLOR = "color";
    const TYPE_TEXTAREA = "textarea";
    const TYPE_REPEATER = "repeater";
    const TYPE_HTML = "html";
    const TYPE_ICON = "icon";

    public function __construct( $data = [], $args = null ) {
        $this->setupSettings();
        parent::__construct( $data, $args );
    }
  
  protected function setupSettings() {
  }
   
  
	public function get_name() {
      return $this->nameWidget;
	}

	public function get_title() {
		return $this->title;
	}
 
	public function get_icon() {
		return $this->icon;
	}
 
	public function get_categories() {
		return [ 'mvc_theme' ];
	}

  protected function add_field_elemenor($field_item, $element) {
    $type = \Elementor\Controls_Manager::TEXT;
    $input_type = "text";
    $options = [];
    $fields = [];
    $default = [];
    $params_extension = [];
    
    switch( $field_item[2] ) {
        case self::TYPE_SELECT:
        $type = \Elementor\Controls_Manager::SELECT;
        $input_type = "";
        $options = $field_item[3];
        break;
      case self::TYPE_SELECT2:
        $type = \Elementor\Controls_Manager::SELECT2;
        $input_type = "";
        $options = $field_item[3];
        break; 
      case self::TYPE_TEXTAREA:
        $type = \Elementor\Controls_Manager::TEXTAREA;
        $input_type = "textarea";
        break;
      case self::TYPE_IMAGE:
        $type = \Elementor\Controls_Manager::MEDIA;
        $default = [
            'url' => \Elementor\Utils::get_placeholder_image_src(),
        ];
        break;
      case self::TYPE_TINY_MCE:
        $type = \Elementor\Controls_Manager::WYSIWYG; 
        break;
        case self::TYPE_HTML:
        $type = \Elementor\Controls_Manager::CODE; 
        break; 
      case self::TYPE_ICON:
        $type = \Elementor\Controls_Manager::ICON; 
        break;
    case self::TYPE_COLOR:
        $type = \Elementor\Controls_Manager::TEXT;
        $params_extension = [
                'selectors' => [
                    '{{WRAPPER}} .your-class' => 'color: {{VALUE}}',
                ]
        ];
        break;
      case self::TYPE_SWITCHER:
        $type = \Elementor\Controls_Manager::SWITCHER;
        $params_extension = [
              'label_on' => 'Да',
              'label_off' => 'Нет',
              'return_value' => 'yes',
              'default' => 'yes',
          ];
        break;
        case self::TYPE_REPEATER:
        $type = \Elementor\Controls_Manager::REPEATER; 
        $repeater = new \Elementor\Repeater();
          
        foreach($field_item[3] as $field_repeat) {
            $repeater = $this->add_field_elemenor($field_repeat, $repeater); 
        }
        $fields = $repeater->get_controls();
        
        break;  
    }

    $params = [
        'label' => $field_item[1],
        'type' => $type,
        'default' => $default,
        'input_type' => $input_type,
        'placeholder' => '',
        'options' => $options
    ];

    if ($fields) {
      $params['fields'] = $fields;
    }

    $params = array_merge($params, $params_extension);


    $element->add_control(
      $field_item[0],
        $params
    ); 
    
    return $element;
  }

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( $this->get_title(), 'mvctheme' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        if ( count($this->fields) > 0 ) {
          foreach($this->fields as $field) {
              $this->add_field_elemenor($field, $this);
          }
        }

		$this->end_controls_section();

	}

    public function getData(array $param) : array {
        return [];
    }

	protected function render() {
		$args = $this->get_settings_for_display();
        $data = $this->getData($args);

        if (count($data)) {
            $args = array_merge($args, $data);
        }

        echo MVCView::partial( $this->view_path , $args);
	}

}
 