<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.grocrm.com/
 * @since      1.0.0
 *
 * @package    Grocrm
 * @subpackage Grocrm/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->  
<header class="grocrm-header">
    <img alt="Gro CRM Logo" height="80" src="<?php echo plugin_dir_url( __FILE__ ) . "../images/gro_crm_logo.png" ?>">
    <h1><?php esc_html_e( 'Contact-to-Leads', 'grocrm'); ?></h1>
    <h3 style="padding-bottom: 20px;"><?php esc_html_e( 'Convert Wordpress forms into Gro CRM leads', 'grocrm'); ?></h3>
    <div class="grocrm-background-analytic" style="height:50px;"></div>
    <div style="background-color: #FDB12A; width: 100%; height: 40px;"></div>
</header>

<?php if ( !get_option("grocrm_api_key") || empty(get_option("grocrm_api_key"))) { ?>

<!-- API KEY LOGIN -->
<div class="grocrm-body">
    <div class="grocrm-container">
        
        <form method="POST" action="">
            <input type="hidden" name="grocrm_action" value="login"/>
            
            <!-- Start API Key Section -->
            <div class="grocrm-settings-section">
                <h2><?php esc_html_e('Login', 'grocrm'); ?></h2>
                <hr/>
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <label for="grocrm_api_key"><?php esc_html_e('API Key', 'grocrm'); ?></label>
                    </div>
                    <div class="grocrm-col-8">
                        <input type="password" id="grocrm_api_key" name="grocrm_api_key" placeholder="<?php esc_html_e('API Key', 'grocrm'); ?>">
                        
                        <?php if (!empty($this->login_error)) { ?>
                        <p class="grocrm-error"><?php echo $this->login_error; ?></p>
                        <?php } else { ?>
                        <p><?php esc_html_e('Starting out, we will need an API Key to access your Gro CRM account.', 'grocrm'); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div><!-- End API Key Section -->
            
            <!-- Submit Section -->
            <div style="padding-top: 50px;">
                <input type="submit" value="<?php esc_html_e('Connect', 'grocrm'); ?>" class="grocrm-btn">
            </div>
        </form>
    </div>
</div>

<?php } else { ?>

<!-- SETTINGS -->
<div class="grocrm-body">
    
    <input id="grocrm-tab1" type="radio" name="grocrm-tabs" checked>
    <label class="grocrm-tab" for="grocrm-tab1"><?php esc_html_e('Settings', 'grocrm'); ?></label>

    <input id="grocrm-tab2" type="radio" name="grocrm-tabs">
    <label class="grocrm-tab" for="grocrm-tab2"><?php esc_html_e('Account', 'grocrm'); ?></label>
   
    <!-- Settings Tab --> 
    <section id="grocrm-content1" class="grocrm-tab-content">
        <form method="POST" action="">
            <input type="hidden" name="grocrm_action" value="update"/>
            
            <!-- Start Options Section -->
            <div class="grocrm-settings-section">
                <h2><?php esc_html_e('Options', 'grocrm'); ?></h2>
                <hr/>
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <label for="grocrm_header"><?php esc_html_e('Header', 'grocrm'); ?></label>
                    </div>
                    <div class="grocrm-col-8">
                        <textarea id="grocrm_header" name="grocrm_header" rows="3"><?php echo esc_html(get_option("grocrm_header")); ?></textarea>
                        <p><?php esc_html_e('Insert your own text, HTML, or leave it blank.', 'grocrm'); ?></p>
                    </div>
                </div>
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <label for="grocrm_subheader"><?php esc_html_e('Sub-header', 'grocrm'); ?></label>
                    </div>
                    <div class="grocrm-col-8">
                        <textarea id="grocrm_subheader" name="grocrm_subheader" rows="3"><?php echo esc_html(get_option("grocrm_subheader")); ?></textarea>
                        <p><?php esc_html_e('Insert your own text, HTML, or leave it blank.', 'grocrm'); ?></p>
                    </div>
                </div>
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <label for="grocrm_submit"><?php esc_html_e('Submit button', 'grocrm'); ?></label>
                    </div>
                    <div class="grocrm-col-8">
                        <input type="text" id="grocrm_submit" name="grocrm_submit" value="<?php echo esc_attr(get_option("grocrm_submit")); ?>">
                    </div>
                </div>
                
                
                <?php
                    if (!class_exists('GroCRM_API')) {
                        $path = plugin_dir_path(__FILE__);
                        require_once($path . '../api/grocrm.php');
                    } 
                    
                    $grocrm_api_key = get_option("grocrm_api_key");        
                    $grocrm_api = new GroCRM_API($grocrm_api_key);
                    $grocrm_types = $grocrm_api->getTypes();                   
                ?>
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <label for="grocrm_default_type"><?php esc_html_e('Default type', 'grocrm'); ?></label>
                    </div>
                    <div class="grocrm-col-8">
                        <select id="grocrm_default_type" name="grocrm_default_type">
                            
                            <?php
                            foreach ($grocrm_types as $type) {
                                $id = $type["id"];
                                $value = $type["value"];
                                
                                echo "<option value=$id".selected(get_option("grocrm_default_type"), $id, false).">".$value."</option>";
                            }    
                            ?>
                        </select>
                        
                        <p><?php esc_html_e('Select the default type that you would like the contacts to be inserted as.', 'grocrm'); ?></p>
                    </div>
                </div>
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <label for="grocrm_default_tags"><?php esc_html_e('Default tags', 'grocrm'); ?></label>
                    </div>
                    <div class="grocrm-col-8">
                        <input type="text" id="grocrm_default_tags" name="grocrm_default_tags" placeholder="ex. website,contact-form" value="<?php echo esc_attr(implode(",", get_option("grocrm_default_tags"))); ?>">
                        <p><?php esc_html_e('Insert tags that you would like to be attached to these contacts. Comma separated. No spaces allowed.', 'grocrm'); ?></p>
                    </div>
                </div>
                
            </div><!-- End Options Section -->
                        
            <!-- Start Fields Section -->
            <div class="grocrm-settings-section">
                <h2><?php esc_html_e('Fields', 'grocrm'); ?></h2>
                <hr/>
                <table class="grocrm-table">
                    <tr>
                        <th><?php esc_html_e('Name', 'grocrm'); ?></th>
                        <th><?php esc_html_e('Required', 'grocrm'); ?></th>
                        <th><?php esc_html_e('Enabled', 'grocrm'); ?></th>
                    </tr>
                    
                    <?php
                        $grocrm_field_keys = get_option("grocrm_field_keys");
                                                
                        foreach (grocrm_fields() as $key => $value) {
                            
                            echo "<tr>";
                            
                            echo "<td>".$value['label']."</td>";
                            
                            if ($value['required']) {
                                echo "<td>".esc_html__('Yes', 'grocrm')."</td>";
                                echo "<td>-</td>";
                            } else {
                                echo "<td>".esc_html__('No', 'grocrm')."</td>";
                                echo "<td><input type=\"checkbox\" id=\"$key\" name=\"$key\" ".(in_array($key, $grocrm_field_keys) ? "checked" : "")."></td>";
                            }
                            
                            echo "</tr>";
                        }                    
                    ?>
                                        
                </table>
            </div><!-- End Fields Section -->
            
            <!-- Start CSS Section -->
            <div class="grocrm-settings-section">
                <h2><?php esc_html_e('CSS', 'grocrm'); ?></h2>
                <hr/>
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <label for="grocrm_css_disabled"><?php esc_html_e('Remove CSS', 'grocrm'); ?></label>
                    </div>
                    <div class="grocrm-col-8">
                        <input type="checkbox" id="grocrm_css_disabled" name="grocrm_css_disabled" <?php checked(get_option("grocrm_css_disabled")); ?>>
                        <p><?php esc_html_e('This will disable the contact form styles, so you may customize the form yourself.', 'grocrm'); ?></p>
                    </div>
                </div>
            </div><!-- End CSS Section -->
            
            <!-- Submit Section -->
            <div style="padding-top: 50px;">
                <input type="submit" value="<?php esc_html_e('Update Settings', 'grocrm'); ?>" class="grocrm-btn">
            </div>
        </form>
    </section>
    
    <!-- User Tab -->
    <section id="grocrm-content2" class="grocrm-tab-content">
        <form method="POST" action="">
            <input type="hidden" name="grocrm_action" value="logout"/>
            
            <div class="grocrm-settings-section">
                <h2><?php esc_html_e('User', 'grocrm'); ?></h2>
                <hr/>
                
                <?php $user = get_option("grocrm_user"); ?>
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <?php esc_html_e('First Name', 'grocrm'); ?>
                    </div>
                    <div class="grocrm-col-8">
                        <?php echo esc_html($user["first_name"]);?>
                    </div>
                </div>
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <?php esc_html_e('Last Name', 'grocrm'); ?>
                    </div>
                    <div class="grocrm-col-8">
                        <?php echo esc_html($user["last_name"]);?>
                    </div>
                </div>
                
                
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <?php esc_html_e('Username', 'grocrm'); ?>
                    </div>
                    <div class="grocrm-col-8">
                        <?php echo esc_html($user["username"]);?>
                    </div>
                </div>
                <div class="grocrm-row">
                    <div class="grocrm-col-4">
                        <?php esc_html_e('Email', 'grocrm'); ?>
                    </div>
                    <div class="grocrm-col-8">
                        <?php echo esc_html($user["email"]);?>
                    </div>
                </div>
            </div>
            
            <!-- Submit Section -->
            <div style="padding-top: 50px;padding-bottom: 50px;">
                <input type="submit" value="<?php esc_html_e('Logout', 'grocrm'); ?>" class="grocrm-btn">
            </div>
        </form>
    </section>
</div>

<?php } ?>
