<?php
    
function grocrm_contact_form() {
    do_shortcode( '[grocrm_form]' );
}

function grocrm_fields() {
    return [
    	'grocrm_first_name' => [
    		'label' => esc_html__( 'First Name', 'grocrm'),
    		'required' => true,
    		'input_type' => 'text'
    	],
    	'grocrm_last_name' => [
    		'label' => esc_html__( 'Last Name', 'grocrm'),
    		'required' => true,
    		'input_type' => 'text'
    	],
    	'grocrm_email_address' => [
    		'label' => esc_html__( 'Email Address', 'grocrm'),
    		'required' => true,
    		'input_type' => 'email'
    	],
    	'grocrm_company' => [
    		'label' => esc_html__( 'Company', 'grocrm'),
    		'required' => false,
    		'input_type' => 'text'
    	],
    	'grocrm_title' => [
    		'label' => esc_html__( 'Title', 'grocrm'),
    		'required' => false,
    		'input_type' => 'text'
    	],
    	'grocrm_role' => [
    		'label' => esc_html__( 'Role', 'grocrm'),
    		'required' => false,
    		'input_type' => 'text'
    	],
    	'grocrm_url' => [
    		'label' => esc_html__( 'URL', 'grocrm'),
    		'required' => false,
    		'input_type' => 'url'
    	],
    	'grocrm_address' => [
    		'label' => esc_html__( 'Address', 'grocrm'),
    		'required' => false,
    		'input_type' => 'text'
    	],
    	'grocrm_address2' => [
    		'label' => esc_html__( 'Address 2', 'grocrm'),
    		'required' => false,
    		'input_type' => 'text'
    	],
    	'grocrm_city' => [
    		'label' => esc_html__( 'City', 'grocrm'),
    		'required' => false,
    		'input_type' => 'text'
    	],
    	'grocrm_state' => [
    		'label' => esc_html__('State', 'grocrm'),
    		'required' => false,
    		'input_type' => 'text'
    	],
    	'grocrm_country' => [
    		'label' => esc_html__( 'Country', 'grocrm'),
    		'required' => false,
    		'input_type' => 'select'
    	],
    	'grocrm_timezone' => [
    		'label' => esc_html__( 'Timezone', 'grocrm'),
    		'required' => false,
    		'input_type' => 'select'
    	],
    	'grocrm_phone' => [
    		'label' => esc_html__( 'Phone', 'grocrm'),
    		'required' => false,
    		'input_type' => 'tel'
    	],
    	'grocrm_mobile' => [
    		'label' => esc_html__( 'Mobile', 'grocrm'),
    		'required' => false,
    		'input_type' => 'tel'
    	],
    	'grocrm_subject' => [
    		'label' => esc_html__( 'Subject', 'grocrm'),
    		'required' => false,
    		'input_type' => 'text'
    	],
    	'grocrm_message' => [
    		'label' => esc_html__( 'Message', 'grocrm'),
    		'required' => false,
    		'input_type' => 'textarea'
    	],
    ];
}