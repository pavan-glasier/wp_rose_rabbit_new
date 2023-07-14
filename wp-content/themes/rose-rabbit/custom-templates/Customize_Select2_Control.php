<?php 
class Customize_Select2_Control extends WP_Customize_Control {
    public $type = 'select2';

    public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <select <?php $this->link(); ?> class="customize-control-select2">
                <?php
                foreach ($this->choices as $value => $label) {
                    $selected = ($this->value() === $value) ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr($value) . '"' . $selected . '>' . esc_html($label) . '</option>';
                }
                ?>
            </select>
        </label>
        <?php
    }
}
