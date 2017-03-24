<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.grocrm.com/
 * @since      1.0.0
 *
 * @package    Grocrm
 * @subpackage Grocrm/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Grocrm
 * @subpackage Grocrm/admin
 * @author     Gro CRM <support@grocrm.com>
 */
class Grocrm_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
        
    private $login_error;
    
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

        if ( 'settings_page_grocrm' == get_current_screen() -> id ) {
		    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/grocrm-admin.css', array(), $this->version, 'all' );
        }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
/*
        if ( 'settings_page_grocrm' == get_current_screen() -> id ) {
    		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/grocrm-admin.js', array( 'jquery' ), $this->version, false );
        }
*/
	}
	
	public function add_plugin_admin_menu() {
    	add_options_page( "Gro CRM Setup", "Gro CRM", "manage_options", $this->plugin_name, [$this, "display_plugin_setup_page"]);
	}
	
	public function add_action_links($links) {
    	$settings_link = [
        	'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
    	];
    	
    	return array_merge($settings_link, $links);
	}
	
    public function display_plugin_setup_page() {
    	include_once 'partials/grocrm-admin-display.php';
	}
    
    public function add_admin_body_class($classes) {
        
        if ( 'settings_page_grocrm' == get_current_screen() -> id ) {
            
            return "$classes grocrm-page";
        }
        
        return $classes;
    }
	
    public function request_handler() {
        
        if (isset($_POST['grocrm_action'])) {
            switch($_POST['grocrm_action']) {
                case 'login':
                    $this->login();
                    break;
                case 'logout':
                    $this->logout();
                    break;
                case 'update':
                    $this->update_settings();
                    break;
            }
        }
    }
    
    // Helper functions
    
    private function login() {
        
        if (!class_exists('GroCRM_API')) {
            $path = plugin_dir_path(__FILE__);
            require_once($path . '../api/grocrm.php');
        } 
        
        $apiKey = sanitize_text_field($_POST['grocrm_api_key']);
        $grocrm_api = new GroCRM_API($apiKey);
        
        try {
            
            $user = $grocrm_api->getUser();
            update_option("grocrm_user", $user);
            $this->update_text_field('grocrm_api_key');
            $this->login_error = null;
            
        } catch (Exception $e) {
            if ($e->getCode() == 401) {
                $this->login_error = esc_html_e( "Whoops, those credentials are not correct!", "grocrm");
            } else {
                $this->login_error = esc_html_e( "An error has occured, please try again later", "grocrm");
            }
        }
    }

    private function logout() {
        delete_option("grocrm_api_key");
        delete_option("grocrm_user");
    }
    
    private function update_settings() {
                
        $this->update_text_field('grocrm_header', true);
        $this->update_text_field('grocrm_subheader', true);
        $this->update_text_field('grocrm_submit');
        $this->update_text_field('grocrm_default_type');
        
        
        $defaultTags = preg_replace('/\s+/', '', sanitize_text_field($_POST["grocrm_default_tags"]));
        update_option( "grocrm_default_tags", $defaultTags);        
        
        $this->update_checkbox('grocrm_css_disabled');
        
        $this->update_fields();
    }
    
    private function update_text_field($name, $allowsHTML = false) {
        if ($allowsHTML) {
            $content = stripslashes($_POST[$name]);
            $content = str_replace("\r\n","<br/>", $content);
            update_option($name, $content);
        } else {
            update_option($name, sanitize_text_field($_POST[$name]));
        }
    }
    
    private function update_checkbox($name) {
        
        if (isset($_POST[$name]) && !empty($_POST[$name])) {
            update_option($name, true);
        } else {
            update_option($name, false);
        }
    }

    private function update_fields() {
        
        $field_keys = [];
        
        foreach (grocrm_fields() as $key => $value) {
                        
            if ($value['required']) {
                $field_keys[] = $key;
                continue;
            }
            
            if (isset($_POST[$key]) && !empty($_POST[$key])) {            
                $field_keys[] = $key;
            }
        }

        update_option("grocrm_field_keys", $field_keys);
    }
}