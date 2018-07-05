<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.grocrm.com/
 * @since      1.0.0
 *
 * @package    Grocrm
 * @subpackage Grocrm/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Grocrm
 * @subpackage Grocrm/public
 * @author     Gro CRM <support@grocrm.com>
 */
class Grocrm_Public {

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
		
	private $grocrm_api;
	
	private $grocrm_field_errors;
	
	private $grocrm_success;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		if (!class_exists('GroCRM_API')) {
            $path = plugin_dir_path(__FILE__);
            require_once($path . '../api/grocrm.php');
        }
        
        $grocrm_api_key = get_option("grocrm_api_key");
        
        $this->grocrm_api = new GroCRM_API($grocrm_api_key);
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        
        if (!get_option('grocrm_css_disabled')) {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/grocrm-public.css', array(), $this->version, 'all' );
        }
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

// 		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/grocrm-public.js', array( 'jquery' ), $this->version, false );

	}
		
	public function register_shortcodes() {
    	add_shortcode('grocrm_form', [$this, 'form_shortcode']);
	}
	
	public function form_shortcode($atts) {
    	ob_start();
        include_once 'partials/grocrm-public-display.php';
        return ob_get_clean();
    }
    
    public function validate_input() {
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $fields = grocrm_fields();
            $field_keys = get_option("grocrm_field_keys");
            $phoneRegex = '/^[+]?([\d]{0,3})?[\(\.\-\s]?(([\d]{1,3})[\)\.\-\s]*)?(([\d]{3,5})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/';
            
            if (empty($field_keys)) {
                $field_keys = [];
            }
            
            foreach ($field_keys as $key) {
                
                $label = $fields[$key]["label"];
                $required = $fields[$key]["required"];
                $input_type = $fields[$key]["input_type"];
                
                if ($required && empty($_POST[$key])) {   
                    $this->grocrm_field_errors[$key] = sprintf(
                        /* translators: %s: Name of a field such as First Name, Email, etc.. */
                        esc_html__('%s is required', 'grocrm'),
                        $label
                    );
                    continue;                  
                }
                
                if ($input_type == "email" && filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) == false) {
                    
                    $this->grocrm_field_errors[$key] = sprintf(
                        /* translators: %s: Name of a field such as First Name, Email, etc.. */
                        esc_html__( '%s must be a valid email address', 'grocrm'),
                        $label
                    );
                }
                
                if ($input_type == "tel" && !empty($_POST[$key]) && filter_var($_POST[$key], FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => $phoneRegex]]) == false) {
                    $this->grocrm_field_errors[$key] = sprintf(
                        /* translators: %s: Name of a field such as First Name, Email, etc.. */
                        esc_html__( '%s must be a valid phone number', 'grocrm'),
                        $label
                    );
                }
            }
        }
    }
    
    public function request_handler() {
        
        $this->validate_input();
                    
        if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($this->grocrm_field_errors)) {
            
            $defaultType = get_option("grocrm_default_type", 3);
            $defaultTags = get_option("grocrm_default_tags");
            
            if (empty($defaultTags)) {
                $defaultTags = null;
            }
            
            $parameters = [
                "type_id" => $defaultType,
                "tags" => $defaultTags
            ];
                        
            $parameters["first_name"] = $this->get_value("grocrm_first_name");
            $parameters["last_name"] = $this->get_value("grocrm_last_name");
            $parameters["email"] = $this->get_value("grocrm_email_address");
            $parameters["company"] = $this->get_value("grocrm_company");
            $parameters["title"] = $this->get_value("grocrm_title");
            $parameters["role"] = $this->get_value("grocrm_role");
            $parameters["url"] = $this->get_value("grocrm_url");
            $parameters["address"] = $this->get_value("grocrm_address");
            $parameters["address2"] = $this->get_value("grocrm_address2");
            $parameters["city"] = $this->get_value("grocrm_city");
            $parameters["state"] = $this->get_value("grocrm_state");
            $parameters["country_id"] = $this->get_value("grocrm_country");
            $parameters["timezone_id"] = $this->get_value("grocrm_timezone");
            $parameters["phone"] = $this->get_value("grocrm_phone");
            $parameters["mobile"] = $this->get_value("grocrm_mobile");
            
            $subject = $this->get_value("grocrm_subject");
            $message = $this->get_value("grocrm_message");
            
            if (!is_null($subject) || !is_null($message)) {
                $notes = "SUBJECT:\n";
                $notes .= $subject."\n\n";
                $notes .= "MESSAGE:\n";
                $notes .= $message;
                
                $parameters["notes"] = $notes;
            }
                         
            $this->grocrm_success = true;
                                    
            try {
                $this->grocrm_api->createContact($parameters);
            } catch (Exception $e) {
                // An error has occured
            }
        }
    }
    
    private function get_value($key) {
        
        if (isset($_POST[$key]) && !empty($_POST[$key])) {
            return stripslashes($_POST[$key]);
        }
        
        return null;
    }
}

