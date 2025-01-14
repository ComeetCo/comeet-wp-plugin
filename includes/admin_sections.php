<?php
// Comeet API required settings - UID and Token.
add_settings_section(
	'comeet_api_settings',
	'',
	array($this, 'api_credentials_text'),
	'comeet'
);
//UID
add_settings_field(
	'comeet_uid',
	'Company UID',
	array($this, 'comeet_uid_input'),
	'comeet',
	'comeet_api_settings'
);
//TOKEN
add_settings_field(
	'comeet_token',
	'Token',
	array($this, 'comeet_token_input'),
	'comeet',
	'comeet_api_settings'
);
//Closing UID/TOKEN section
add_settings_section(
	'comeet_api_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);

//Start Settings Section
add_settings_section(
	'comeet_other_settings',
	'',
	array($this, 'other_text'),
	'comeet'
);
//Select Career website page
add_settings_field(
	'post_id',
	'Careers website page',
	array($this, 'job_page_input'),
	'comeet',
	'comeet_other_settings'
);
//Auto generate pages
add_settings_field(
	'comeet_auto_generate_pages',
	'Auto-generate pages',
	array($this, 'comeet_auto_generate_pages'),
	'comeet',
	'comeet_other_settings'
);
//Set Thank you page.
add_settings_field(
	'thank_you_page',
	'Thank you page',
	array($this, 'thank_you_page_input'),
	'comeet',
	'comeet_other_settings'
);
//Display style
add_settings_field(
	'comeet_stylesheet',
	'Display style',
	array($this, 'comeet_stylesheet_input'),
	'comeet',
	'comeet_other_settings'
);
//Select Grouping type - Department/Location
add_settings_field(
	'advanced_search',
	'Group positions by',
	array($this, 'advanced_search_input'),
	'comeet',
	'comeet_other_settings'
);
//Company URL option
add_settings_field(
	'comeet_company_website_url',
	'Company website URL',
	array($this, 'comeet_company_website_url'),
	'comeet',
	'comeet_other_settings'
);

add_settings_field(
	'comeet_social_fields_employees',
	'Company employees',
	array($this, 'comeet_employees_field'),
	'comeet',
	'comeet_other_settings',
	['class' => 'comeet_social_options']
);
//Apply as employee option
add_settings_field(
	'comeet_apply_as_employee	',
	'',
	array($this, 'comeet_apply_as_employee'),
	'comeet',
	'comeet_other_settings'
);

add_settings_field(
    'comeet_social_fields_employees_2',
    'Social Title',
    array($this, 'comeet_show_title_field'),
    'comeet',
    'comeet_other_settings',
    ['class' => 'comeet_social_options']
);

//End Settings section
add_settings_section(
	'comeet_other_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);

add_settings_section(
	'comeet_form_fields',
	'',
	array($this, 'comeet_widget_fields'),
	'comeet'
);

add_settings_field(
	'comeet_form_fields_email',
	'Email',
	array($this, 'comeet_email_field'),
	'comeet',
	'comeet_form_fields'
);

add_settings_field(
	'comeet_form_fields_phone',
	'Phone',
	array($this, 'comeet_phone_field'),
	'comeet',
	'comeet_form_fields'
);

add_settings_field(
	'comeet_form_fields_resume',
	'Upload resume',
	array($this, 'comeet_resume_field'),
	'comeet',
	'comeet_form_fields'
);

add_settings_field(
	'comeet_form_fields_linkedin',
	'LinkedIn',
	array($this, 'comeet_linkedin_field'),
	'comeet',
	'comeet_form_fields'
);

add_settings_field(
	'comeet_form_fields_profile',
	'Required profile',
	array($this, 'comeet_profile_field'),
	'comeet',
	'comeet_form_fields'
);

add_settings_field(
	'comeet_form_fields_website',
	'Website',
	array($this, 'comeet_website_field'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_website_show']
);

add_settings_field(
	'comeet_form_fields_website_required',
	'',
	array($this, 'comeet_website_field_required'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_website_required']
);

add_settings_field(
	'comeet_form_fields_coverletter',
	'Cover letter',
	array($this, 'comeet_coverletter_field'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_coverletter_show']
);

add_settings_field(
	'comeet_form_fields_coverletter_required',
	'',
	array($this, 'comeet_coverletter_field_required'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_coverletter_required']
);

add_settings_field(
	'comeet_form_fields_portfolio',
	'Portfolio',
	array($this, 'comeet_portfolio_field'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_portfolio_show']
);

add_settings_field(
	'comeet_form_fields_portfolio_required',
	'',
	array($this, 'comeet_portfolio_field_required'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_portfolio_required']
);

add_settings_field(
	'comeet_form_fields_personalnote',
	'Personal note',
	array($this, 'comeet_personalnote_field'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_personalnote_show']
);

add_settings_field(
	'comeet_form_fields_personalnote_required',
	'',
	array($this, 'comeet_personalnote_field_required'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_personalnote_required']
);

/*add_settings_field(
	'comeet_social_fields_title',
	'Show a title?',
	array($this, 'comeet_show_title_field'),
	'comeet',
	'comeet_form_fields',
	['class' => 'comeet_social_options']
);*/

/*add_settings_field(
	'comeet_social_fields_override_share_url',
	'Override the URL to share:',
	array($this, 'comeet_social_fields_override_share_url'),
	'comeet',
	'comeet_form_fields',
);*/


add_settings_section(
	'comeet_form_fields_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);

//start widget fields
add_settings_section(
	'comeet_widget_fields_handling',
	'',
	array($this, 'comeet_widget_fields_handling_box'),
	'comeet'
);

add_settings_field(
	'comeet_social_show_on_careers',
	'Social sharing widget',
	array($this, 'comeet_show_social_on_careers'),
	'comeet',
	'comeet_widget_fields_handling',
);

add_settings_field(
	'comeet_social_show_on_position',
	'',
	array($this, 'comeet_show_social_on_positions'),
	'comeet',
	'comeet_widget_fields_handling',
);

add_settings_field(
	'comeet_social_fields_linkedin',
	'LinkedIn',
	array($this, 'comeet_linkedin_social_field'),
	'comeet',
	'comeet_widget_fields_handling',
	['class' => 'comeet_social_options']
);

add_settings_field(
	'comeet_social_fields_facebook',
	'Facebook',
	array($this, 'comeet_linkedin_social_field'),
	'comeet',
	'comeet_widget_fields_handling',
	['class' => 'comeet_social_options']
);

add_settings_field(
	'comeet_social_fields_twitter',
	'Twitter',
	array($this, 'comeet_linkedin_social_field'),
	'comeet',
	'comeet_widget_fields_handling',
	['class' => 'comeet_social_options']
);

add_settings_field(
	'comeet_social_fields_pinterest',
	'Pinterest',
	array($this, 'comeet_pinterest_field'),
	'comeet',
	'comeet_widget_fields_handling',
	['class' => 'comeet_social_options']
);

add_settings_field(
	'comeet_social_fields_whatsapp',
	'WhatsApp',
	array($this, 'comeet_whatsapp_field'),
	'comeet',
	'comeet_widget_fields_handling',
	['class' => 'comeet_social_options']
);

add_settings_section(
	'comeet_widget_fields_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);


add_settings_section(
	'comeet_advanced_styles',
	'',
	array($this, 'comeet_advanced_styles'),
	'comeet'
);

add_settings_field(
	'comeet_css_url',
	'CSS URL',
	array($this, 'comeet_css_url'),
	'comeet',
	'comeet_advanced_styles'
);
add_settings_field(
	'comeet_css_cache',
	'CSS URL cache',
	array($this, 'comeet_css_cache'),
	'comeet',
	'comeet_advanced_styles'
);

add_settings_field(
	'comeet_color',
	'Main color',
	array($this, 'comeet_color_input'),
	'comeet',
	'comeet_advanced_styles'
);

add_settings_field(
	'comeet_bgcolor',
	'Background color',
	array($this, 'comeet_bgcolor_input'),
	'comeet',
	'comeet_advanced_styles'
);

add_settings_field(
	'comeet_labels_position',
	'Label location ',
	array($this, 'comeet_labels_position'),
	'comeet',
	'comeet_advanced_styles'
);

add_settings_field(
	'comeet_button_color',
	'Submit button color',
	array($this, 'comeet_button_color'),
	'comeet',
	'comeet_advanced_styles'
);

add_settings_field(
	'comeet_button_text',
	'Submit button text',
	array($this, 'comeet_button_text'),
	'comeet',
	'comeet_advanced_styles'
);

add_settings_field(
	'comeet_font_size',
	'Font size',
	array($this, 'comeet_font_size'),
	'comeet',
	'comeet_advanced_styles'
);

add_settings_field(
	'comeet_button_font_size',
	'Submit button font size',
	array($this, 'comeet_button_font_size'),
	'comeet',
	'comeet_advanced_styles'
);

add_settings_field(
	'comeet_social_fields_title',
	'Social buttons color',
	array($this, 'comeet_social_button_color'),
	'comeet',
	'comeet_advanced_styles',
);


add_settings_section(
	'comeet_advanced_styles_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);


//Start Cookie consent section
add_settings_section(
	'comeet_cookie_consent_handling',
	'Advanced',
	array($this, 'comeet_cookie_consent_handling_box'),
	'comeet'
);
//Select what happen if position is missing
/* add_settings_field(
	 'comeet_cookie_option',
	 'Activate tracking script',
	 array($this, 'cookie_consent_option'),
	 'comeet',
	 'comeet_cookie_consent_handling'
 );*/
//End Cookie Consent section
add_settings_section(
	'comeet_consent_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);

//Start 404 handling section
add_settings_section(
	'comeet_404_handling',
	'',
	array($this, 'comeet_404_handling_box'),
	'comeet'
);
//Select what happen if position is missing
add_settings_field(
	'comeet_404_option',
	'Missing position action',
	array($this, 'error_404_action'),
	'comeet',
	'comeet_404_handling'
);
//error_404_page_input
add_settings_field(
	'error_404_page',
	'404 redirect page',
	array($this, 'error_404_page_input'),
	'comeet',
	'comeet_404_handling'
);
//End Cookie consent section
add_settings_section(
	'comeet_404_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);

//Start Branding section
add_settings_section(
	'comeet_branding',
	'',
	array($this, 'comeet_branding_box'),
	'comeet'
);
//Select Sub Brand field
add_settings_field(
	'comeet_category_branding',
	'Sub-brand field',
	array($this, 'comeet_get_categories'),
	'comeet',
	'comeet_branding'
);
/*//End Sub Brand section
add_settings_section(
	'comeet_branding_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);*/

/*Added section for branding ability*/

add_settings_field(
	'comeet_category_branding',
	'Sub-brand field',
	array($this, 'comeet_get_categories'),
	'comeet',
	'comeet_branding'
);

add_settings_field(
	'comeet_category_value_branding',
	'Select sub-brand',
	array($this, 'comeet_set_category_values'),
	'comeet',
	'comeet_branding'
);

add_settings_section(
	'comeet_branding_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);



//Advanced Section
add_settings_section(
	'comeet_advanced_settings',
	'',
	array($this, 'comeet_advanced_text'),
	'comeet'
);
add_settings_field(
	'comeet_subpage_template',
	'Template for locations / departments',
	array($this, 'comeet_subpage_input'),
	'comeet',
	'comeet_advanced_settings'
);
add_settings_field(
	'comeet_positionpage_template',
	'Template for the position page',
	array($this, 'comeet_positionpage_input'),
	'comeet',
	'comeet_advanced_settings'
);
add_settings_section(
	'comeet_advanced_blank',
	'',
	array($this, 'comeet_other_blank'),
	'comeet'
);


