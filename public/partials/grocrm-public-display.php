<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.grocrm.com/
 * @since      1.0.0
 *
 * @package    Grocrm
 * @subpackage Grocrm/public/partials
 */
?>

<?php

    $header = get_option("grocrm_header");
    $subheader = get_option("grocrm_subheader");
    
    if (isset($header) && !empty($header)) {
        echo "<div class=\"grocrm-header\">$header</div>";
    }
    
    if (isset($subheader) && !empty($subheader)) {
        echo "<div class=\"grocrm-subheader\">$subheader</div>";
    }
?>

<form class="grocrm-form" method="post">
    

    <?php
        
        $fields = grocrm_fields();
        $field_keys = get_option("grocrm_field_keys");
        
        foreach ($field_keys as $key) {
            
            $label = $fields[$key]["label"];
            $required = $fields[$key]["required"];
            $input_type = $fields[$key]["input_type"];
            
            if (isset($_POST[$key]) && $this->grocrm_success != true) {
                $value = stripslashes($_POST[$key]);
            } else {
                $value = "";
            }
                        
            echo "<div class=\"grocrm-field\">";
            
            if ($required) {
                echo "<label for=\"$key\">$label*</label>";
            } else {
                echo "<label for=\"$key\">$label</label>";
            }
            
            // Handle Input Field Types
            if (in_array($input_type, ["button", "checkbox", "color", "date", "datetime-local", "email", "file", "hidden", "image", "month", "number", "password", "radio", "range", "reset", "search", "submit", "tel", "text", "time", "url", "week"])) {
                echo "<input type=\"text\" id=\"$key\" name=\"$key\" value=\"".esc_html($value)."\">";
            }
            
            if ($input_type == "select") {
                
                if ($key == "grocrm_country") {
                    $optionArray = $this->grocrm_api->getCountries();
                } elseif ($key == "grocrm_timezone") {
                    $optionArray = $this->grocrm_api->getTimezones();
                }
                    
                if (!empty($optionArray)) {
                    echo "<select id=\"$key\" name=\"$key\">";
                    
                    echo "<option value=\"\">".esc_html__("Select...", "grocrm")."</option>";
                    
                    foreach ($optionArray as $optionKey => $optionValue) {
                        echo "<option value=\"$optionKey\" ".selected($value, $optionKey).">$optionValue</option>";
                    }
                    
                    echo "</select>";
                }

            }
            
            if ($input_type == "textarea") {
                echo "<textarea type=\"text\" id=\"$key\" name=\"$key\">".esc_textarea($value)."</textarea>";
            }
    
            if (isset($this->grocrm_field_errors[$key])) {                          
                echo "<span class=\"grocrm-error\">".$this->grocrm_field_errors[$key]."</span>";
            }
            
            echo "</div>";
        }
    ?>
    
    <div class="grocrm_required_footer">* = <?php esc_html_e("required field", "grocrm"); ?></div>
    
    <div class="grocrm-submit">
        <?php
            
        $grocrm_submit = get_option("grocrm_submit");
        
        if (isset($grocrm_submit) && !empty($grocrm_submit)) {
            echo '<input type="submit" value="'.esc_attr($grocrm_submit).'">'; 
        } else {
            echo '<input type="submit" value="'.esc_html__('Submit', 'grocrm').'">'; 
        }
            
        ?>
        
    </div>
</form>