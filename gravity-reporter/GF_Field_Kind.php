<?php
/**
 * Created by PhpStorm.
 * User: LOGAN
 * Date: 9/12/2018
 * Time: 3:10 AM
 */

class GF_Field_Kind extends GF_Field
{

    public $type = 'kind';


    public function get_form_editor_field_title()
    {
        return esc_attr__('kind', 'gravityforms');
    }

    public function get_form_editor_button()
    {
        return array(
            'group' => 'advanced_fields',
            'text' => 'نوع'
        );
    }

    function get_form_editor_field_settings()
    {
        return array(
            'prepopulate_field_setting',
            'label_setting',
            'placeholder_setting'
        );
    }


    public function get_field_input($form, $value = '', $entry = null)
    {
        $form_id = absint($form['id']);
        $is_entry_detail = $this->is_entry_detail();
        $is_form_editor = $this->is_form_editor();


        $html_input_type = 'text';

        $id = (int)$this->id;
        $field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

        $value = esc_attr($value);
        $size = $this->size;
        $class_suffix = $is_entry_detail ? '_admin' : '';
        $class = $size . $class_suffix ;

        $tabindex = $this->get_tabindex();
        $disabled_text = $is_form_editor ? 'disabled="disabled"' : '';
        $placeholder_attribute = $this->get_field_placeholder_attribute();
        $required_attribute = $this->isRequired ? 'aria-required="true"' : '';
        $invalid_attribute = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';

        $input = "<input name='input_{$id}' id='{$field_id}' type='{$html_input_type}' value='{$value}' class='{$class} desibale_input_reporter'  {$tabindex}{$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$disabled_text} readonly='readonly' />";
//
        return sprintf("<div class='ginput_container ginput_container_text'>%s</div>", $input);
    }

    public function get_field_content($value, $force_frontend_label, $form)
    {
        return parent::get_field_content($value, $force_frontend_label, $form);
    }

}

GF_Fields::register(new GF_Field_Kind());