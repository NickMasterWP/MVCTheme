<?php

namespace MVCTheme\Traits;

use MVCTheme\Classes\MVCCustomizerRepeater;

trait MVCCustomizerTrait {

    private $sectionCustomizer = [];
    private $optionsCustomizer = [];

    public function addSection($internal_name, $title) {
        $this->sectionCustomizer[] = [
            "internal_name" => $internal_name,
            "title" => $title
        ];
    }

    public function addOption($section_name, $internal_name, $title, $default, $type = "text", $choices = []) {
        $this->optionsCustomizer[$internal_name] = [
            "section_name" => $section_name,
            "title" => $title,
            "default" => $default,
            "type" => $type,
            "choices" => $choices
        ];
    }

    public function themeCustomize($customizer) {
        foreach ($this->sectionCustomizer as $section) {
            $customizer->add_section(
                'section_' . $section["internal_name"],
                [
                    'title' => $section["title"],
                    'description' => '',
                    'priority' => 10,
                ]
            );
        }

        foreach ($this->optionsCustomizer as $name => $option) {
            $settingName = "setting_" . $name;

            if ($option["type"] === "repeat") {
                $customizer->add_setting($settingName, [
                    'sanitize_callback' => [$this, 'customizerRepeaterSanitize'],
                    'default' => $option["default"]
                ]);
            } else {
                $customizer->add_setting($settingName, ['default' => $option["default"]]);
            }

            if ($option["type"] === "image") {
                $customizer->add_control(
                    new \WP_Customize_Image_Control(
                        $customizer,
                        $settingName,
                        [
                            'label' => $option["title"],
                            'section' => 'section_' . $option["section_name"],
                            'settings' => $settingName,
                        ]
                    )
                );
            } elseif ($option["type"] === "color") {
                $customizer->add_control(
                    new \WP_Customize_Color_Control(
                        $customizer,
                        $settingName,
                        [
                            'label' => $option["title"],
                            'section' => 'section_' . $option["section_name"],
                            'settings' => $settingName,
                        ]
                    )
                );
            } elseif ($option["type"] === "repeat") {
                $customizer->add_control(
                    new MVCCustomizerRepeater(
                        $customizer,
                        $settingName,
                        [
                            'label' => $option["title"],
                            'section' => 'section_' . $option["section_name"],
                            'settings' => $settingName,
                            'options' => $option["choices"]
                        ]
                    )
                );
            } else {
                $customizer->add_control($settingName, [
                    'label' => $option["title"],
                    'section' => 'section_' . $option["section_name"],
                    'type' => $option["type"],
                    'choices' => $option["choices"]
                ]);
            }
        }
    }

    public function getOption($settingName) {
        $val = get_theme_mod("setting_" . $settingName);

        if ($val === false && isset($this->optionsCustomizer[$settingName])) {
            $val = $this->optionsCustomizer[$settingName]["default"];
        }
        return $val;
    }

    public function theOption($settingName) {
        echo $this->getOption($settingName);
    }

    public function getOptions() {
        $result = [];
        foreach ($this->optionsCustomizer as $name => $value) {
            $result[$name] = $this->getOption($name);
        }
        return $result;
    }

    public function customizerRepeaterSanitize($input) {
        $input_decoded = json_decode($input, true);

        if (!empty($input_decoded)) {
            foreach ($input_decoded as $boxk => $box) {
                foreach ($box as $key => $value) {
                    $input_decoded[$boxk][$key] = wp_kses_post(force_balance_tags($value));
                }
            }
            return json_encode($input_decoded);
        }
        return $input;
    }
}