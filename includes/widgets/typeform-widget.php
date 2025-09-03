<?php

if (!defined('ABSPATH')) {
    exit;
}

class Typeform_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'typeform-embed';
    }
    
    public function get_title() {
        return __('Typeform Embed', 'typeform-embed-elementor');
    }
    
    public function get_icon() {
        return 'eicon-form-horizontal';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    public function get_script_depends() {
        return ['typeform-embed-widget'];
    }
    
    public function get_style_depends() {
        return ['typeform-embed-widget'];
    }
    
    protected function register_controls() {
        
        // Content Section - Typeform Settings
        $this->start_controls_section(
            'typeform_settings',
            [
                'label' => __('Typeform Settings', 'typeform-embed-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'custom_field_name',
            [
                'label' => __('Custom Field Name', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'typeform_id',
                'placeholder' => __('Enter custom field name', 'typeform-embed-elementor'),
                'description' => __('The custom field containing the Typeform ID or URL for this page/product', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'fallback_typeform_id',
            [
                'label' => __('Fallback Typeform ID/URL', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter Typeform ID or URL', 'typeform-embed-elementor'),
                'description' => __('This will be used if the custom field is empty', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'embed_type',
            [
                'label' => __('Embed Type', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'widget',
                'options' => [
                    'widget' => __('Widget (Inline)', 'typeform-embed-elementor'),
                    'popup' => __('Popup', 'typeform-embed-elementor'),
                    'slider' => __('Slider', 'typeform-embed-elementor'),
                    'popover' => __('Popover', 'typeform-embed-elementor'),
                    'sidetab' => __('Side Tab', 'typeform-embed-elementor'),
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Widget Settings
        $this->start_controls_section(
            'widget_settings',
            [
                'label' => __('Widget Settings', 'typeform-embed-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'embed_type' => 'widget',
                ],
            ]
        );
        
        $this->add_control(
            'widget_height',
            [
                'label' => __('Height', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1000,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-widget' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Button Settings (for popup, slider, popover, sidetab)
        $this->start_controls_section(
            'button_settings',
            [
                'label' => __('Button Settings', 'typeform-embed-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'embed_type' => ['popup', 'slider', 'popover', 'sidetab'],
                ],
            ]
        );
        
        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Open Form', 'typeform-embed-elementor'),
                'condition' => [
                    'embed_type' => ['popup', 'slider', 'popover'],
                ],
            ]
        );
        
        $this->add_control(
            'button_icon',
            [
                'label' => __('Button Icon', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'embed_type' => ['popup', 'slider', 'popover'],
                ],
            ]
        );
        
        $this->add_control(
            'button_icon_position',
            [
                'label' => __('Icon Position', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'before' => __('Before Text', 'typeform-embed-elementor'),
                    'after' => __('After Text', 'typeform-embed-elementor'),
                ],
                'condition' => [
                    'embed_type' => ['popup', 'slider', 'popover'],
                    'button_icon[value]!' => '',
                ],
            ]
        );
        
        
        $this->add_control(
            'button_full_width',
            [
                'label' => __('Full Width', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'embed_type' => ['popup', 'slider', 'popover'],
                ],
            ]
        );
        
        $this->add_control(
            'button_alignment',
            [
                'label' => __('Button Alignment', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'typeform-embed-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'typeform-embed-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'typeform-embed-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'condition' => [
                    'embed_type' => ['popup', 'slider', 'popover'],
                    'button_full_width!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_text_sidetab',
            [
                'label' => __('Side Tab Text', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Feedback', 'typeform-embed-elementor'),
                'condition' => [
                    'embed_type' => 'sidetab',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Advanced Settings
        $this->start_controls_section(
            'advanced_settings',
            [
                'label' => __('Advanced Settings', 'typeform-embed-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'opacity',
            [
                'label' => __('Opacity', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'size' => 100,
                ],
            ]
        );
        
        $this->add_control(
            'hide_headers',
            [
                'label' => __('Hide Headers', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'hide_footer',
            [
                'label' => __('Hide Footer', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'disable_tracking',
            [
                'label' => __('Disable Tracking', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'enable_close_on_escape',
            [
                'label' => __('Close on ESC key', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'embed_type' => ['popup', 'slider'],
                ],
                'description' => __('Allow closing with ESC key', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'auto_close',
            [
                'label' => __('Auto Close After Submit', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'embed_type' => ['popup', 'slider'],
                ],
                'description' => __('Automatically close after form submission', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'auto_close_delay',
            [
                'label' => __('Auto Close Delay (ms)', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'condition' => [
                    'auto_close' => 'yes',
                    'embed_type' => ['popup', 'slider'],
                ],
                'description' => __('Delay before closing after submission (in milliseconds)', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'keep_session',
            [
                'label' => __('Remember Progress', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'embed_type' => ['popup', 'slider'],
                ],
                'description' => __('Remember where the user left off when reopening the form. Progress is saved locally in the browser.', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'reset_on_submit',
            [
                'label' => __('Reset After Submit', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'keep_session' => 'yes',
                    'embed_type' => ['popup', 'slider'],
                ],
                'description' => __('Start fresh after form submission', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'close_button_position',
            [
                'label' => __('Close Button Position', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'allowed_dimensions' => ['top', 'right'],
                'condition' => [
                    'embed_type' => 'slider',
                ],
                'selectors' => [
                    '.tf-v1-slider .tf-v1-close' => 'top: {{TOP}}{{UNIT}} !important; right: {{RIGHT}}{{UNIT}} !important;',
                ],
                'description' => __('Adjust the position of the close button', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'lazy_load',
            [
                'label' => __('Lazy Load', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'embed_type' => 'widget',
                ],
            ]
        );
        
        $this->add_control(
            'enable_datalayer',
            [
                'label' => __('Send DataLayer to Typeform', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Send window.dataLayer content to Typeform as a hidden field', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'datalayer_field_name',
            [
                'label' => __('Hidden Field Name', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'datalayer',
                'placeholder' => __('Enter hidden field name', 'typeform-embed-elementor'),
                'description' => __('The name of the hidden field in Typeform that will receive the dataLayer content. This field must exist in your Typeform.', 'typeform-embed-elementor'),
                'condition' => [
                    'enable_datalayer' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'auto_resize',
            [
                'label' => __('Auto Resize', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'typeform-embed-elementor'),
                'label_off' => __('No', 'typeform-embed-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'embed_type' => 'widget',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section for Button
        $this->start_controls_section(
            'button_style',
            [
                'label' => __('Button Style', 'typeform-embed-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'embed_type' => ['popup', 'slider', 'popover'],
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Typography', 'typeform-embed-elementor'),
                'selector' => '{{WRAPPER}} .typeform-embed-button',
            ]
        );
        
        $this->start_controls_tabs('button_tabs');
        
        $this->start_controls_tab(
            'button_normal',
            [
                'label' => __('Normal', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_background_color',
            [
                'label' => __('Background Color', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'button_hover',
            [
                'label' => __('Hover', 'typeform-embed-elementor'),
            ]
        );
        
        $this->add_control(
            'button_hover_text_color',
            [
                'label' => __('Text Color', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_hover_background_color',
            [
                'label' => __('Background Color', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'button_padding',
            [
                'label' => __('Padding', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => __('Border', 'typeform-embed-elementor'),
                'selector' => '{{WRAPPER}} .typeform-embed-button',
            ]
        );
        
        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '4',
                    'right' => '4',
                    'bottom' => '4',
                    'left' => '4',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'label' => __('Box Shadow', 'typeform-embed-elementor'),
                'selector' => '{{WRAPPER}} .typeform-embed-button',
            ]
        );
        
        $this->add_control(
            'icon_style_heading',
            [
                'label' => __('Icon', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'button_icon_color',
            [
                'label' => __('Icon Color', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button .button-icon' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'button_icon_size',
            [
                'label' => __('Icon Size', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button .button-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .typeform-embed-button .button-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .typeform-embed-button .button-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'button_icon_spacing',
            [
                'label' => __('Icon Spacing', 'typeform-embed-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .typeform-embed-button .button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .typeform-embed-button .button-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function get_typeform_id() {
        $settings = $this->get_settings_for_display();
        $custom_field_name = $settings['custom_field_name'];
        $fallback = $settings['fallback_typeform_id'];
        
        // Get current post/product ID
        $post_id = get_the_ID();
        
        if ($post_id && $custom_field_name) {
            // Try to get the custom field value
            $typeform_id = get_post_meta($post_id, $custom_field_name, true);
            
            if (!empty($typeform_id)) {
                return $typeform_id;
            }
        }
        
        // Return fallback if no custom field value found
        return $fallback;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $typeform_id = $this->get_typeform_id();
        
        if (empty($typeform_id)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="typeform-embed-placeholder">';
                echo __('Please configure a Typeform ID in the widget settings or add a custom field to this page.', 'typeform-embed-elementor');
                echo '</div>';
            }
            return;
        }
        
        // Prepare data attributes
        $data_attrs = [
            'data-typeform-id' => esc_attr($typeform_id),
            'data-embed-type' => esc_attr($settings['embed_type']),
            'data-opacity' => esc_attr($settings['opacity']['size']),
            'data-hide-headers' => $settings['hide_headers'] === 'yes' ? 'true' : 'false',
            'data-hide-footer' => $settings['hide_footer'] === 'yes' ? 'true' : 'false',
            'data-disable-tracking' => $settings['disable_tracking'] === 'yes' ? 'true' : 'false',
            'data-enable-datalayer' => $settings['enable_datalayer'] === 'yes' ? 'true' : 'false',
        ];
        
        // Add dataLayer field name if enabled
        if ($settings['enable_datalayer'] === 'yes') {
            $data_attrs['data-datalayer-field-name'] = esc_attr($settings['datalayer_field_name']);
        }
        
        if ($settings['embed_type'] === 'widget') {
            $data_attrs['data-lazy'] = $settings['lazy_load'] === 'yes' ? 'true' : 'false';
            $data_attrs['data-auto-resize'] = $settings['auto_resize'] === 'yes' ? 'true' : 'false';
        }
        
        // Add popup/slider specific attributes
        if (in_array($settings['embed_type'], ['popup', 'slider'])) {
            $data_attrs['data-close-on-escape'] = $settings['enable_close_on_escape'] === 'yes' ? 'true' : 'false';
            $data_attrs['data-auto-close'] = $settings['auto_close'] === 'yes' ? 'true' : 'false';
            $data_attrs['data-keep-session'] = $settings['keep_session'] === 'yes' ? 'true' : 'false';
            if ($settings['keep_session'] === 'yes') {
                $data_attrs['data-reset-on-submit'] = $settings['reset_on_submit'] === 'yes' ? 'true' : 'false';
            }
            if ($settings['auto_close'] === 'yes') {
                $data_attrs['data-auto-close-delay'] = esc_attr($settings['auto_close_delay']);
            }
        }
        
        $attrs_string = '';
        foreach ($data_attrs as $key => $value) {
            $attrs_string .= $key . '="' . $value . '" ';
        }
        
        ?>
        <div class="typeform-embed-container" <?php echo $attrs_string; ?>>
            <?php if ($settings['embed_type'] === 'widget'): ?>
                <div class="typeform-embed-widget" id="typeform-widget-<?php echo $this->get_id(); ?>"></div>
            <?php elseif ($settings['embed_type'] === 'sidetab'): ?>
                <div class="typeform-embed-sidetab" 
                     data-button-text="<?php echo esc_attr($settings['button_text_sidetab']); ?>">
                </div>
            <?php else: ?>
                <?php
                // Add button classes based on settings
                $button_classes = ['typeform-embed-button'];
                
                if ($settings['button_full_width'] === 'yes') {
                    $button_classes[] = 'typeform-button-full-width';
                }
                
                $button_class_string = implode(' ', $button_classes);
                ?>
                <button class="<?php echo esc_attr($button_class_string); ?>" type="button">
                    <?php
                    // Render icon and text
                    if (!empty($settings['button_icon']['value'])) {
                        if ($settings['button_icon_position'] === 'before') {
                            \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true', 'class' => 'button-icon button-icon-before']);
                            echo '<span class="button-text">' . esc_html($settings['button_text']) . '</span>';
                        } else {
                            echo '<span class="button-text">' . esc_html($settings['button_text']) . '</span>';
                            \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true', 'class' => 'button-icon button-icon-after']);
                        }
                    } else {
                        echo esc_html($settings['button_text']);
                    }
                    ?>
                </button>
            <?php endif; ?>
        </div>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        var typeformId = settings.fallback_typeform_id || '';
        
        if (!typeformId) {
            if (elementor.config.is_editor_mode) {
                #>
                <div class="typeform-embed-placeholder">
                    <?php echo __('Please configure a Typeform ID in the widget settings or add a custom field to this page.', 'typeform-embed-elementor'); ?>
                </div>
                <#
            }
        } else {
            var dataAttrs = {
                'data-typeform-id': typeformId,
                'data-embed-type': settings.embed_type,
                'data-opacity': settings.opacity.size,
                'data-hide-headers': settings.hide_headers === 'yes' ? 'true' : 'false',
                'data-hide-footer': settings.hide_footer === 'yes' ? 'true' : 'false',
                'data-disable-tracking': settings.disable_tracking === 'yes' ? 'true' : 'false'
            };
            
            if (settings.embed_type === 'widget') {
                dataAttrs['data-lazy'] = settings.lazy_load === 'yes' ? 'true' : 'false';
                dataAttrs['data-auto-resize'] = settings.auto_resize === 'yes' ? 'true' : 'false';
            }
            
            var attrsString = '';
            for (var key in dataAttrs) {
                attrsString += key + '="' + dataAttrs[key] + '" ';
            }
            #>
            <div class="typeform-embed-container" {{{ attrsString }}}>
                <# if (settings.embed_type === 'widget') { #>
                    <div class="typeform-embed-widget"></div>
                <# } else if (settings.embed_type === 'sidetab') { #>
                    <div class="typeform-embed-sidetab" 
                         data-button-text="{{ settings.button_text_sidetab }}">
                    </div>
                <# } else { #>
                    <button class="typeform-embed-button" type="button">
                        {{ settings.button_text }}
                    </button>
                <# } #>
            </div>
        <# } #>
        <?php
    }
}