<?php
/*
 * Plugin Name: Comeet
 * Plugin URI: http://support.comeet.co/knowledgebase/wordpress-plug-in/
 * Description: Job listing page using the Comeet API.
 * Version: 1.6.9.1
 * Author: Comeet
 * Author URI: http://www.comeet.co
 * License: Apache 2
 */


/*

Copyright 2016 Comeet

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

*/

include_once(ABSPATH . 'wp-admin/includes/plugin.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$plugin_dir = trailingslashit(plugin_dir_path(__FILE__));
require_once($plugin_dir . 'includes/lib/comeet-data.php');

if (!function_exists('comeet_plugin_version')) {
    function comeet_plugin_version() {
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');

        }
        $plugin_data = get_plugin_data(__FILE__, false, false);

        return $plugin_data['Version'];
    }
}

if (!function_exists('comeet_plugin_version_arg')) {
    function comeet_plugin_version_arg() {
        return 'requestedby=wpplugin' . comeet_plugin_version();
    }
}

if (!class_exists('Comeet')) {

    class Comeet {
        public $version = '1.6.9.1';
        var $plugin_url;
        var $plugin_dir;
        var $db_opt = 'Comeet_Options';
        /*URL prefix.
         * This Prefix appears after the page slug in the URL
         * current URL structure: https://YOUR-URL.COM/CAREERS-PAGE/co/JOB-PARAMETERS
         * By changing this parameter you can alter the way the URL will look
         * Please take into account that once changed you will need to save the plugin settings again
         * Also, for good measure re-save permalinks.
        */
        var $comeet_prefix = 'co';

        private $isComeetContentPage;
        private $comeet_pos;
        private $post_data;
        private $socialGraphTitle;
        private $socialGraphImage;
        private $socialGraphDescription;
        private $socialGraphDefaultDescription = 'Job Opportunities';

        public function __construct() {
            $this->plugin_url = trailingslashit(plugin_dir_url(__FILE__));
            $this->plugin_dir = trailingslashit(plugin_dir_path(__FILE__));
            $plugin = plugin_basename( __FILE__ );
            add_action('init', array($this, 'add_rewrite_rules'));
            if (is_admin()) {
                add_action('admin_init', array($this, 'register_settings'));
                add_action('admin_menu', array($this, 'options_page'));
                add_action('admin_init', array($this, 'flush_permalinks'));
                add_action('updated_option', array($this, 'check_option'), 10, 3);
                add_filter( "plugin_action_links_$plugin", array($this, 'plugin_add_settings_link') );
            } else {
                add_filter('template_include', array($this, 'career_page_template'), 99);
                add_shortcode('comeet_data', array($this, 'comeet_content'));
                add_shortcode('comeet_page', array($this, 'comeet_custom_shortcode'));
                add_filter('the_content', array($this, 'filter_the_content'), 10);
                add_filter('template_redirect', array($this, 'override_404'), 10 );
            }
            add_action('the_posts', array($this, 'process_posts'), 10);
            add_filter('wpseo_og_og_title', array($this, 'filter_og_title'));
            add_action('wp_head', array($this, 'update_header'), 12);
            add_filter('wpseo_title', array($this, 'filter_title_simple'));
            add_filter('wpseo_opengraph_image', array($this, 'filter_image'));
            add_filter('wpseo_canonical', array($this, 'filter_url'));
            add_filter('wpseo_metadesc', array($this, 'getSocialGraphDescription'));
            add_filter('wpseo_opengraph_desc', array($this, 'getSocialGraphDescription'));
            register_deactivation_hook( $plugin, 'comeet_deactivation' );
        }


        public function add_careers_meta_tags() {
            echo '<meta name="application-name" itemprop="name" content="Comeet Jobs" />' . PHP_EOL;
            $options = $this->get_options();
            $post = get_post($options['post_id']);
            $url = get_permalink($post->ID);
            echo '<meta name="application-url" itemprop="url" content="' . $url . '" />' . PHP_EOL;
        }

        //function for adding json schema to header of page on individual job pages.
        public function add_job_posting_js_schema(){
            $positions_details = '';
            foreach($this->post_data['details'] as $detail){
                $positions_details .= "<b>".$detail['name']."</b><br />".$detail['value']."<br />";
            }
            ?>
            <script type="application/ld+json">{
                    "@context": "http://schema.org",
                    "@type": "JobPosting",
                    "title": "<?= $this->post_data['name']?>",
                    "url": "<?= $this->post_data['url_active_page']?>",
                    "datePosted": "<?= $this->post_data['time_updated']?>",
                    "employmentType": "<?= $this->post_data['employment_type']?>",
                    "hiringOrganization":
                    {
                        "@type": "Organization",
                        "name": "<?= $this->post_data['company_name']?>"
                    },
                    "jobLocation":
                    {
                        "@type": "Place",
                        "address":
                        {
                            "@type": "PostalAddress",
                            "addressLocality": "<?= $this->post_data['location']['city']?>",
                            "addressRegion": "<?= $this->post_data['location']['state']?>",
                            "addressCountry":
                            {
                                "@type": "Country",
                                "name": <?= $this->post_data['location']['country']?>
                            }
                        }
                    },
                    "image": "<?= $this->post_data['picture_url']?>",
                    "description": "<?= $positions_details?>"
                    }
                </script>
            <?php
        }

        public function has_shortcode($content) {
            return stripos($content, '[comeet_data') !== false ||
                stripos($content, '[comeet_page') !== false;
        }

        public function posts_has_shortcode($posts) {
            foreach ($posts as $post) {
                if ($this->has_shortcode($post->post_content)) {
                    return true;
                }
            }

            return false;
        }

        public function process_posts($posts) {
            $this->set_is_comeet_content_page($posts);

            if (!is_admin()) {
                // see https://stackoverflow.com/a/9558692/938389
                if ($this->posts_has_shortcode($posts)) {
                    add_action('wp_head', array($this, 'add_careers_meta_tags'));
                }
            }

            return $posts;
        }

        public function getSocialGraphDescription($pageSetDescription = null) {
            if (!$this->isComeetContentPage) {
                $res = $pageSetDescription;
            } else if ($this->socialGraphDescription) {
                $res = $this->socialGraphDescription;
            } else if ($pageSetDescription != null) {
                $res = $pageSetDescription;
            } else {
                $res = $this->socialGraphDefaultDescription;
            }

            return strip_tags($res);
        }

        function filter_image($imageUrl) {
            if (isset($this->socialGraphImage)) {
                $imageUrl = $this->socialGraphImage;
            }
            return $imageUrl;
        }

        function set_is_comeet_content_page($posts) {
            $this->isComeetContentPage = false;

            for ($c = 0; $c < count($posts); $c++) {
                if (has_shortcode($posts[$c]->post_content, 'comeet_data') || has_shortcode($posts[$c]->post_content, 'comeet_page')) {
                    $this->isComeetContentPage = true;
                    break;
                }
            }

            if ($this->isComeetContentPage) {
                global $wp_query;

                if (isset($wp_query->query_vars['comeet_pos'])) {
                    $this->comeet_pos = urldecode($wp_query->query_vars['comeet_pos']);
                } else {
                    $this->comeet_cat = (isset($wp_query->query_vars['comeet_cat'])) ? urldecode($wp_query->query_vars['comeet_cat']) : null;
                }
                $this->comeet_preload_data();
                //adding json schema to head ONLY if we are on individual job page.
                if(isset($wp_query->query_vars['comeet_pos'])){
                    add_action('wp_head', array($this, 'add_job_posting_js_schema'));
                }
            }

            return $posts;
        }

        function update_header() {
            if ($this->isComeetContentPage && (!is_plugin_active('wordpress-seo/wp-seo.php'))) : ?>
                <!-- COMEET PLUGIN -->
                <?php if (isset($this->title)) : ?>
                    <meta name="og:title" content="<?= $this->title ?>"/>
                <?php endif; ?>
                <?php if (isset($this->socialGraphImage)) : ?>
                    <meta property="og:image" content="<?= $this->socialGraphImage ?>"/>
                <?php endif; ?>
                <meta property="og:description" content="<?= $this->getSocialGraphDescription() ?>">
                <meta name="description" content="<?= $this->getSocialGraphDescription() ?>">
                <meta property="og:url" content="<?= $this->get_current_url(); ?>"/>
                <meta property="og:type" content="article" />
                <!-- COMEET PLUGIN -->
            <?php
            endif;
        }

        function career_page_template($template) {
            global $wp_query;
            $new_template = '';
            $options = $this->get_options();

            if (isset($wp_query->query_vars['comeet_pos'])) {
                $new_template = locate_template(array(
                    $options['comeet_positionpage_template'],
                    'comeet/' . $options['comeet_positionpage_template']
                ));
            } elseif (isset($wp_query->query_vars['comeet_cat'])) {
                $new_template = locate_template(array(
                    $options['comeet_subpage_template'],
                    'comeet/' . $options['comeet_subpage_template']
                ));
            }

            if ('' != $new_template) {
                return $new_template;
            }

            return $template;
        }

        public function install() {
            $this->get_options();
        }

        public function deactivate() {
        }

        public function check_for_keys() {
            if (is_admin()) {
                if ((empty($this->comeet_token) || empty($this->comeet_uid)) && empty($_POST['save'])) {
                    add_action('admin_notices', array($this, 'admin_keys_notice'));
                }
            }
        }

        public function admin_keys_notice() {
            $message = '';
            if (empty($this->comeet_token)) $message = 'Almost done! Just enter your <b>Comeet Token</b> in the ';
            if (empty($this->comeet_uid)) $message = 'Almost done! Just enter your <b>Comeet UID</b> in the ';
            echo '<div class="updated"><p>' . $message . ' <a href="' . admin_url('admin.php?page=comeet') . '">settings</a></p></div>';
        }

        public function check_for_curl() {
            if (is_admin()) {
                if (!in_array('curl', get_loaded_extensions())) {
                    if ($_GET['page'] == 'comeet') {
                        add_action('admin_notices', array($this, 'admin_curl_notice'));
                    }
                }
            }
        }

        public function check_for_comeetapi() {
            if (is_admin()) {
                if ((!empty($this->comeet_token) && !empty($this->comeet_uid))) {
                    add_action('admin_notices', array($this, 'admin_comeet_api_notice'));
                }
            }
        }

        public function admin_curl_notice() {
            $message = 'The Comeet plugin may not function properly as cURL is not enabled on the server.<br /><br />Ensure that Curl for php is enabled and that your server can execute http requests to www.comeet.co (used to retrieve the positions data).';
            echo '<div class="error"><p>' . $message . '</p></div>';
            echo '<div id="message" class="updated">
		<h3>How to enable cURL on your server?</h3>
		<p>If you are seeing this error message, the best way to resolve the problem is to ask your hosting provider or system admin to enable cURL on the server. If you are on your own, then the following tips may work:</p>
		<p><strong>Option 1 : Enable CURL via the php.ini</strong></p>

		<p>This is the main method on any windows install like WAMP, XAMPP etc.</p>

		<ol>
		<li>Locate  your PHP.ini file (normally located at in the bin folder of your apache install)</li>
		<li>Open the PHP.ini in notepad</li>
		<li>Search or find the following : �;extension=php_curl.dll�</li>
		<li>Uncomment this by removing the semi-colon �;� before it</li>
		<li>Save and Close PHP.ini</li>
		<li>Restart Apache</li>
		</ol>

		<p><strong>Option 2: Enabling CURL in WAMP</strong></p>

		<ol>
		<li>Left-click on the WAMP server icon in the bottom right of the screen</li>
		<li>PHP -> PHP Extensions -> php_curl</li>
		</ol>

		<p><strong>Option 3: enable CURL in Ubuntu</strong></p>

		<p>Run the following command:</p>

		<ol>
		<li>sudo apt-get install php5-curl</li>
		<li>sudo service apache2 restart</li>
		</ol>
		<p><i>Note: Each server is setup differently and depending on the setup these instructions may not work. But in most cases, these instructions should help.</i> </p>
		</div>';
        }

        public function admin_comeet_api_notice() {
            $apiurl = 'https://www.comeet.co/careers-api/2.0/company/' . $this->comeet_uid . '/positions?token=' . $this->comeet_token . '&' . comeet_plugin_version_arg();
            $request = wp_remote_get($apiurl);
            $response = $request['response'];

            if ($response['code'] != 200) {
                $jsonresponse = json_decode($request['body']);
                $message = $jsonresponse->message;
                if (strlen(trim($message)) != 0) {
                    echo '<div class="error"><p>Settings saved but there was an error retrieving positions data: ' . $message . '</p></div>';
                } else {
                    $message = 'Comeet - Unexpected error retrieving positions data. If the problem persists please contact us at: <a href="mailto:support@comeet.co" target="_blank">support@comeet.co</a>';
                    echo '<div class="error"><p>' . $message . '</p></div>';
                }
            }
        }

        /**
         * Gets plugin config options
         *
         * @access public
         * @return array
         */
        public function get_options() {
            $options = array(
                'comeet_token' => '',
                'comeet_uid' => '',
                'location' => '',
                'post_id' => '',
                'advanced_search' => 1,
                'comeet_color' => '278fe6',
                'comeet_bgcolor' => '',
                'comeet_stylesheet' => 'comeet-cards.css',
                'comeet_subpage_template' => 'page.php',
                'comeet_positionpage_template' => 'page.php',
                'comeet_auto_generate_location_pages' => '1',
                'comeet_auto_generate_department_pages' => '1'
            );

            $saved = get_option($this->db_opt);

            if (!empty($saved)) {
                foreach ($saved as $key => $option) {
                    $options[$key] = $option;
                }
            }

            if ($saved != $options) {
                update_option($this->db_opt, $options);
            }

            return $options;
        }

        /**
         * Registers the options page.
         */
        function options_page() {
            add_options_page('Comeet Settings', 'Comeet', 'manage_options', 'comeet', array($this, 'handle_options'));
        }

        function flush_permalinks() {
            if(isset($_GET['settings-updated'])){
                flush_rewrite_rules(true);
            }
        }

        function register_settings() {
            register_setting('comeet_options', $this->db_opt, array($this, 'validate_options'));
            $options = $this->get_options();
            // Fetch the integration settings
            $this->comeet_token = $options['comeet_token'];
            $this->comeet_uid = $options['comeet_uid'];
            $this->check_for_keys();
            $this->check_for_curl();
            $this->check_for_comeetapi();
            $this->add_settings_sections();
        }

        function add_rewrite_rules() {
            $options = $this->get_options();
            $post = get_post($options['post_id']);
            $post_parents = get_post_ancestors($post);

            if (!empty($post_parents)) {
                $parent_posts_slug = array();

                foreach ($post_parents as $parent_id) {
                    $parent = get_post($parent_id);
                    $parent_posts_slug[] = $parent->post_name;
                }
            }
            $regex = '/'.$this->comeet_prefix.'/([^/]+)/?(/all)?$';
            $query = '&comeet_cat=$matches[1]&comeet_all=$matches[2]';
            $query_all = '&comeet_cat=$matches[1]&comeet_pos=$matches[2]&comeet_all=$matches[4]';
            $regex_all = '/'.$this->comeet_prefix.'/([^/]+)/([^/]+)/([^/]+)/?(/all)?$';

            if (!empty($parent_posts_slug)) {
                $page_parents = (count($parent_posts_slug) > 1 ? implode('/', array_reverse($parent_posts_slug)) : reset($parent_posts_slug));
                $base = $page_parents . '/' . $post->post_name;
            } else {
                if(isset($post)){
                    $base = $post->post_name;
                } else {
                    $base = '/';
                }
            }
            add_rewrite_rule(
                $base . $regex_all,
                'index.php?pagename=' . $base . $query_all,
                'top'
            );
            add_rewrite_rule(
                $base . $regex,
                'index.php?pagename=' . $base . $query,
                'top'
            );
            //flushihg the rules, so they get rebuild and get added.
            flush_rewrite_rules();
        }


        function add_settings_sections() {
            // Comeet API required settings.
            add_settings_section(
                'comeet_api_settings',
                'Company Identifier',
                array($this, 'api_credentials_text'),
                'comeet'
            );

            add_settings_field(
                'comeet_uid',
                'Company UID',
                array($this, 'comeet_uid_input'),
                'comeet',
                'comeet_api_settings'
            );

            add_settings_field(
                'comeet_token',
                'Token',
                array($this, 'comeet_token_input'),
                'comeet',
                'comeet_api_settings'
            );

            add_settings_section(
                'comeet_api_blank',
                '',
                array($this, 'comeet_api_blank'),
                'comeet'
            );
            // Other fields as needed.
            add_settings_section(
                'comeet_other_settings',
                'Settings',
                array($this, 'other_text'),
                'comeet'
            );

            add_settings_field(
                'post_id',
                'Careers website page',
                array($this, 'job_page_input'),
                'comeet',
                'comeet_other_settings'
            );

            add_settings_field(
                'advanced_search',
                'Group Positions By',
                array($this, 'advanced_search_input'),
                'comeet',
                'comeet_other_settings'
            );
            add_settings_field(
                'thank_you_page',
                'Thank you page',
                array($this, 'thank_you_page_input'),
                'comeet',
                'comeet_other_settings'
            );
            add_settings_field(
                'comeet_stylesheet',
                'Style',
                array($this, 'comeet_stylesheet_input'),
                'comeet',
                'comeet_other_settings'
            );
            add_settings_field(
                'comeet_color',
                'Form Main Color',
                array($this, 'comeet_color_input'),
                'comeet',
                'comeet_other_settings'
            );
            add_settings_field(
                'comeet_bgcolor',
                'Form Background Color',
                array($this, 'comeet_bgcolor_input'),
                'comeet',
                'comeet_other_settings'
            );

            add_settings_field(
                'comeet_auto_generate_pages',
                'Auto-generate pages',
                array($this, 'comeet_auto_generate_pages'),
                'comeet',
                'comeet_other_settings'
            );

            add_settings_section(
                'comeet_other_blank',
                '',
                array($this, 'comeet_other_blank'),
                'comeet'
            );
            //Advanced Section
            add_settings_section(
                'comeet_advanced_settings',
                'Advanced',
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
                array($this, 'comeet_advanced_blank'),
                'comeet'
            );
        }

        function api_credentials_text() {
            echo '<div class="card" style="margin-bottom: 4em;"><p>To find these values, navigate in Comeet to Company Settings / Careers Website and make sure to enable the API. These settings are available to the company&#39;s admin. <a href="http://support.comeet.co/knowledgebase/careers-website/" target="_blank">Learn More</a></p>';
        }

        function comeet_advanced_text() {
            echo '<div class="card" style="margin-bottom: 4em;"><p>
      Use a different theme by specifying the templates that you would like to use.</br>
      Templates are PHP files that reside in your theme folder. <a target="_blank" href="https://developer.wordpress.org/themes/template-files-section/page-template-files/page-templates/">Learn more about page templates</a>
      </p>';
        }

        function comeet_api_blank() {
            echo '</div>';
        }

        function other_text() {
            echo '<div class="card">';
        }

        function comeet_token_input() {
            $options = $this->get_options();

            echo '<input type="text" id="comeet_token" name="' . $this->db_opt . '[comeet_token]" value="' . $options['comeet_token'] . '" size="25" style="width:200px" />';
        }

        function comeet_uid_input() {
            $options = $this->get_options();

            echo '<input type="text" id="comeet_uid" name="' . $this->db_opt . '[comeet_uid]" value="' . $options['comeet_uid'] . '" size="25"  style="width:200px" />';
        }

        function comeet_other_blank() {
            echo '</div>';
        }

        function comeet_advanced_blank() {
            echo '</div>';
        }

        function pages_input($post_id, $key, $select_text) {
            $page_opts = array();
            $page_opts[] = '<option value="-1"' .
                (empty($post_id) ? ' selected="selected"' : '') . ' style="text-decoration:underline;">' .
                $select_text .
                '</option>';
            $pages = get_pages(array('sort_column' => 'sort_column'));

            foreach ($pages as $page) {
                $page_opts[] = '<option value="' . $page->ID . '"' . ($post_id == $page->ID ? ' selected="selected"' : '') . '>' . $page->post_title . '</option>';
            }
            return '<div><select ' .
                'name="' . $this->db_opt . '[' . $key . ']" ' .
                'id="' . $key . '" '.
                'style="width:200px">' .
                implode("\n", $page_opts) .
                '</select></div>';
        }

        function job_page_input() {
            $options = $this->get_options();
            $post_id = trim($options['post_id']);
            echo $this->pages_input($post_id, 'post_id', '-- Create new page --');
            echo '<p class="description">Your careers website homepage will be at this page.</p>';
        }

        function thank_you_page_input() {
            $options = $this->get_options();
            $post_id = isset($options['thank_you_id']) ? trim($options['thank_you_id']) : '';
            echo $this->pages_input($post_id, 'thank_you_id', '-- Select a page --');
        }

        function advanced_search_input() {
            $options = $this->get_options();
            echo '<div><select name="' . $this->db_opt . '[advanced_search]" id="advanced_search" style="width:200px"><option value="0"' . ($options['advanced_search'] == 0 ? ' selected="selected"' : '') . '>Location</option><option value="1"' . ($options['advanced_search'] == 1 ? ' selected="selected"' : '') . '>Department</option></select></div>';
        }

        function comeet_color_input() {
            $options = $this->get_options();

            echo '<input type="text" id="comeet_color" name="' . $this->db_opt . '[comeet_color]" value="' . $options['comeet_color'] . '" size="25"  style="width:200px" />';
            echo '<p class="description">Optional. e.g. 278fe6</p>';
        }

        function comeet_bgcolor_input() {
            $options = $this->get_options();

            echo '<input type="text" id="comeet_bgcolor" name="' . $this->db_opt . '[comeet_bgcolor]" value="' . $options['comeet_bgcolor'] . '" size="25"  style="width:200px" />';
            echo '<p class="description">Optional. e.g. eeeeee</p>';
        }

        function comeet_auto_generate_pages() {
            $options = $this->get_options();
            echo '<input type="checkbox" id="comeet_auto_generate_posts_pages" name="' . $this->db_opt . '[comeet_auto_generate_posts_pages]" value="1" disabled="disabled" checked="checked" />&nbsp;';
            echo '<label for="comeet_auto_generate_posts_pages">For each position</label><br /><br />';

            if(isset($options['comeet_auto_generate_department_pages'])){
                ($options['comeet_auto_generate_department_pages'] == 1) ? $department_checked = 'checked="checked"' : $department_checked = '';
            } else {
                $department_checked = 'checked="checked"';
            }

            if(isset($options['comeet_auto_generate_location_pages'])){
                ($options['comeet_auto_generate_location_pages'] == 1) ? $locations_checked = 'checked="checked"' : $locations_checked = '';
            } else {
                $locations_checked = 'checked="checked"';
            }

            echo '<input type="checkbox" id="comeet_auto_generate_location_pages" name="' . $this->db_opt . '[comeet_auto_generate_location_pages]" value="1" '.$locations_checked.' />&nbsp;';
            echo '<label for="comeet_auto_generate_posts_pages">For each location</label><br /><br />';

            echo '<input type="checkbox" id="comeet_auto_generate_department_pages" name="' . $this->db_opt . '[comeet_auto_generate_department_pages]" value="1" '.$department_checked.' />&nbsp;';
            echo '<label for="comeet_auto_generate_posts_pages">For each department</label><br /><br />';
        }

        function comeet_subpage_input() {
            $options = $this->get_options();

            echo '<input type="text" id="comeet_subpage_template" name="' . $this->db_opt . '[comeet_subpage_template]" value="' . $options['comeet_subpage_template'] . '" size="25"  style="width:200px" />';
            echo '<p class="description">Optional. e.g. page.php</p>';
        }

        function comeet_positionpage_input() {
            $options = $this->get_options();

            echo '<input type="text" id="comeet_positionpage_template" name="' . $this->db_opt . '[comeet_positionpage_template]" value="' . $options['comeet_positionpage_template'] . '" size="25"  style="width:200px" />';
            echo '<p class="description">Optional. e.g. page.php</p>';
        }

        function comeet_stylesheet_input() {
            $options = $this->get_options();
            echo '<div>';
            echo '<select name="' . $this->db_opt . '[comeet_stylesheet]" id="comeet_stylesheet" style="width:200px">';
            echo '<option value="comeet-basic.css"' . ($options['comeet_stylesheet'] == 'comeet-basic.css' ? ' selected="selected"' : '') . '>Basic</option>';
            echo '<option value="comeet-cards.css"' . ($options['comeet_stylesheet'] == 'comeet-cards.css' ? ' selected="selected"' : '') . '>Cards</option>';
            echo '<option value="comeet-two-columns.css"' . ($options['comeet_stylesheet'] == 'comeet-two-columns.css' ? ' selected="selected"' : '') . '>Two columns</option>';

            echo '</select></div>';
        }

        function clear_cache() {
            global $wpdb;

            $prefix  = esc_sql('_transient_' . ComeetData::TRANSIENT_PREFIX . '%');
            $sql = $wpdb->prepare(
                "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%s'",
                $prefix
            );
            $transients = $wpdb->get_col($sql);

            foreach ($transients as $transient) {
                $key = str_replace('_transient_', '', $transient);
                delete_transient($key);
            }
        }

        function check_option($option, $old_value, $new_value) {
            if ($option !== 'Comeet_Options') {
                return;
            }

            if ($old_value['comeet_token'] !== $new_value['comeet_token'] ||
                $old_value['comeet_uid'] !== $new_value['comeet_uid']
            ) {
                $this->clear_cache();
            }
        }

        /**
         * Validates plugin settings form when submitted.
         *
         * @return array
         */
        function validate_options($input) {

            $valid['comeet_token'] = (isset($input['comeet_token'])) ? $input['comeet_token'] : "";
            $valid['comeet_uid'] = (isset($input['comeet_uid'])) ? $input['comeet_uid'] : "";
            $valid['location'] = (isset($input['location'])) ? $input['location'] : "";
            $valid['comeet_color'] = (isset($input['comeet_color'])) ? $input['comeet_color'] : "";
            $valid['comeet_bgcolor'] = (isset($input['comeet_bgcolor'])) ? $input['comeet_bgcolor'] : "";
            $valid['advanced_search'] = (isset($input['advanced_search'])) ? $input['advanced_search'] : "";
            $valid['comeet_stylesheet'] = (isset($input['comeet_stylesheet'])) ? $input['comeet_stylesheet'] : "";
            $valid['comeet_subpage_template'] = (isset($input['comeet_subpage_template'])) ? $input['comeet_subpage_template'] : "";
            $valid['comeet_positionpage_template'] = (isset($input['comeet_positionpage_template'])) ? $input['comeet_positionpage_template'] : "";
            $valid['thank_you_id'] = (isset($input['thank_you_id'])) ? $input['thank_you_id'] : "";

            $valid['comeet_auto_generate_location_pages'] = (isset($input['comeet_auto_generate_location_pages'])) ? $input['comeet_auto_generate_location_pages'] : "";
            $valid['comeet_auto_generate_department_pages'] = (isset($input['comeet_auto_generate_department_pages'])) ? $input['comeet_auto_generate_department_pages'] : "";


            if ($input['post_id'] == '-1') {
                // Create a new page for the job posts to appear.
                if ($post_id = $this->create_new_page()) {
                    $valid['post_id'] = $post_id;
                } else {
                    add_settings_error(
                        $this->db_opt . '[post_id]',
                        'post_id_error',
                        'Failed to create new job page.',
                        'error'
                    );
                }
            } else {
                $valid['post_id'] = $input['post_id'];
                global $wp_rewrite;
                $wp_rewrite->flush_rules( true );
            }
            return $valid;
        }

        /**
         * Displays the plugin settings form.
         *
         * @access public
         * @return void
         */
        public function handle_options() {
            $settings = $this->db_opt;
            include_once($this->plugin_dir . 'includes/comeet-options.php');
        }

        /**
         * Creates new WP Page to attach the job listings to.
         *
         * @access private
         * @return integer
         */
        private function create_new_page() {
            global $user_ID;
            $page = array(
                'post_type' => 'page',
                'post_content' => '[comeet_data]',
                'post_parent' => 0,
                'post_author' => $user_ID,
                'post_title' => 'Careers',
                'post_name' => 'careers',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed'
            );
            return wp_insert_post($page);
        }

        function comeet_preload_data() {
            if ($this->isComeetContentPage) {
                if (isset($this->comeet_pos)) {
                    $this->post_data = ComeetData::get_position_data($this->get_options(), $this->comeet_pos);
                    $this->socialGraphDescription = ComeetData::get_property_value($this->post_data['details'], 'Description');
                    $this->title = 'Job opportunity: '.$this->post_data['name'];
                    $this->socialGraphTitle = $this->title;
                    $this->socialGraphImage = $this->post_data['picture_url'];
                } else if (isset($this->comeet_cat)) {
                    $options = $this->get_options();
                    list($comeetgroups, $data, $group_element) = ComeetData::get_groups($options, $this->comeet_cat);
                    foreach ($data as $post) {
                        if (ComeetData::is_category($post, $group_element, $this->comeet_cat)) {
                            $this->title = $post[$group_element];
                            $this->socialGraphDescription = $this->socialGraphDefaultDescription . ' - ' . ComeetData::get_group_value($post, $group_element);
                            break;
                        }
                    }

                }
            }

        }

        function comeet_content($text) {
            $options = $this->get_options();
            $this->add_frontend_css();
            $this->add_frontend_scripts();
            $text .= $this->comeet_add_template();

            return $text;
        }

        function comeet_custom_shortcode($attr, $content = null) {
            $options = $this->get_options();
            $this->add_frontend_css();
            $this->add_frontend_scripts();
            $text = $this->comeet_add_template_custom_shortcode($attr, $content);

            return $text;
        }

        function extract_shortcode($content) {
            $start = stripos($content, '[comeet_');

            if ($start === false) {
                return '';
            }
            $end = stripos($content, ']', $start);

            if ($end === false) {
                return '';
            }
            return substr($content, $start, $end - $start + 1);
        }

        function filter_the_content($content) {
            global $wp_query;

            if ((is_single() || is_page()) &&
                in_the_loop() &&
                is_main_query() &&
                $this->has_shortcode($content) &&
                (isset($wp_query->query_vars['comeet_pos']) || isset($wp_query->query_vars['comeet_cat']))
            ) {
                return $this->extract_shortcode($content);
            }

            return $content;
        }

        function get_thank_you_url($options, $careers_page) {
            if (isset($options['thank_you_id']) && !empty($options['thank_you_id'])) {
                $post_id = $options['thank_you_id'];
                $post_id = trim($post_id);

                if ($post_id !== '-1') {
                    $post = get_post($post_id);

                    if (!empty($post)) {
                        return site_url() . '/' . $post->post_name;
                    }
                }
            }

            if (empty($careers_page)) {
                return site_url();
            }

            return site_url() . '/' . $careers_page->post_name . '/thankyou';
        }

        protected function add_frontend_scripts() {
            $options = $this->get_options();

            if (empty($options['comeet_token']) || empty($options['comeet_uid'])) {
                return;
            }
            wp_register_script("comeet_script", ($this->plugin_url . 'js/comeet.js'));
            $post = get_post($options['post_id']);
            $comeet_thankyou_url = $this->get_thank_you_url($options, $post);
            $data = array(
                "comeet_token" => $options['comeet_token'],
                "comeet_uid" => $options['comeet_uid'],
                "comeet_color" => $options['comeet_color'],
                "comeet_bgcolor" => $options['comeet_bgcolor'],
                "comeet_thankyou_url" => $comeet_thankyou_url
            );
            wp_localize_script("comeet_script", "comeetvar", $data);
            wp_enqueue_script("comeet_script");
        }

        protected function add_frontend_css() {
            $options = $this->get_options();
            $css_url = 'css/' . $options['comeet_stylesheet'];
            wp_enqueue_style('comeet_style', $this->plugin_url . $css_url, null, null, 'all');
        }

        function filter_title_simple($title) {
            if (isset($this->title)) {
                return $this->title;
            } else {
                return $title;
            }
        }

        function filter_title($title) {
            if (isset($this->title)) {
                return ["title" => $this->title];
            } else {
                return $title;
            }
        }

        function get_current_url() {
            $url = is_ssl() ? "https://" : "http://";
            $url .= $_SERVER["HTTP_HOST"];
            $url .= $_SERVER["REQUEST_URI"];
            return $url;
        }

        function filter_url($url) {
            if (isset($_SERVER["HTTP_HOST"])) {
                $url = $this->get_current_url();

            }
            return $url;
        }

        function filter_og_title($tag) {
            return $this->socialGraphTitle;

        }

        function get_template_path_or_die($template) {
            $paths = array(
                get_stylesheet_directory(),
                get_stylesheet_directory() . '/comeet',
                $this->plugin_dir . 'templates'
            );

            foreach ($paths as $path) {
                $fullpath = "$path/$template";

                if (file_exists($fullpath)) {
                    return $fullpath;
                }
            }
            echo '<div class="error">Error: Can not render page &ndash; no template found.</div>';
            die();
        }

        function comeet_add_template() {
            global $wp_query;

            $comeet_cat = null;
            $options = $this->get_options();

            if (isset($this->comeet_pos)) {
                $post_data = $this->post_data;
                $show_all_link = !empty($wp_query->query_vars['comeet_all']);
                $template = 'comeet-position-page.php';
            } else if (isset($wp_query->query_vars['comeet_cat'])) {
                if ($this->comeet_cat == 'thankyou') {
                    $template = 'comeet-thankyou-page.php';
                } else {
                    $show_all_link = !empty($wp_query->query_vars['comeet_all']);
                    list($comeetgroups, $data, $group_element) = ComeetData::get_groups($options, $this->comeet_cat, true);
                    $comeet_cat = $this->comeet_cat;
                    $comeet_group = $options['advanced_search'];
                    $sub_group = ComeetData::opposite_group_element($group_element);
                    $template = 'comeet-sub-page.php';
                }
            } else {
                list($comeetgroups, $data, $group_element) = ComeetData::get_groups($options, $this->comeet_cat);
                $comeet_group = $options['advanced_search'];
                $post = get_post($options['post_id']);
                $base = get_the_permalink($post->ID);
                $template = 'comeet-careers.php';
            }
            $template = $this->get_template_path_or_die($template);

            ob_start();
            include_once($template);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }

        function comeet_add_template_custom_shortcode($attr, $content) {
            global $wp_query;
            $options = $this->get_options();

            if (isset($wp_query->query_vars['comeet_pos'])) {
                $comeet_pos = urldecode($wp_query->query_vars['comeet_pos']);
            }

            if (isset($attr['name'])) {
                $comeet_cat = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $attr['name'])));
            }

            //location is 0 and department is 1. Default is location
            if (!isset($attr['type'])) {
                $comeet_group = 0;
            } else if ($attr['type'] == 'department') {
                $comeet_group = 1;
            } elseif ($attr['type'] == 'location') {
                $comeet_group = 0;
            }

            if (isset($comeet_pos)) {
                $template = 'comeet-position-page-custom.php';
            } elseif (isset($comeet_cat)) {
                if ($comeet_cat == 'thankyou') {
                    $template = 'comeet-thankyou-page.php';
                } else {
                    list($comeetgroups, $data, $group_element) = ComeetData::get_groups($options, $comeet_cat, true);
                    $sub_group = ComeetData::opposite_group_element($group_element);
                    $template = 'comeet-sub-page-custom.php';
                }
            } else {
                $template = 'blank.php';
            }
            $template = $this->get_template_path_or_die($template);
            //set the output
            ob_start();
            include($template);
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        }

        //404 cases handling
        function override_404() {
            if(isset($_GET['test_regex_values'])) {
                echo "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
                echo "<br />";
                print_r(get_query_var('pagename'));
                echo "<br />";
                print_r(get_query_var('comeet_cat'));
                echo "<br />";
                print_r(get_query_var('comeet_pos'));
                echo "<br />";
                print_r(get_query_var('comeet_all'));
                echo "<br />";
                die();
            }
            if (is_404()) {
                //getting the plugin options
                $options = get_option('Comeet_Options');
                //getting the page used to display the jpbs
                $post = get_post($options['post_id']);
                //getting the page name
                $post_name = $post->post_name;
                //getting the complete current URL that returned an error 404
                //$complete_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                global $wp;
                $request = $wp->request;
                if (preg_match("/".$this->comeet_prefix."\/([*]+)?/", home_url($request), $output_array)) {
                    //making sure the rewrite rules are added and flushing, just in case.
                    $this->add_rewrite_rules();
                    flush_rewrite_rules();
                    //getting full current URL
                    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
                    //checking if we have redirected before, if yes, we should see ?rd in the URL
                    if(!strstr($url, '?rd')){
                        //this page has been redirected by this function before
                        //trying to extract params from the URL
                        $url_parts_start = explode('/co/', $url);
                        $interesting_parts = $url_parts_start[1];
                        $interesting_parts_array = explode('/', $interesting_parts);
                        //checking how many parts are in the array to know if we have a job or department URL;
                        if(count($interesting_parts_array) > 4){
                            //specific job
                            //additional verification, that we have what we should
                            //The key item 1 should be the job id, so we check that it has a . in it (basic structure of the ID)
                            if(strstr($interesting_parts_array[1], '.')){
                                //the job id should have a . in it and one was detected
                                //creating Ugly URL
                                $build_url = '?pagename='.$post_name.'&comeet_cat='.$interesting_parts_array[0].'&comeet_pos='.$interesting_parts_array[1].'&comeet_all='.$interesting_parts_array[3];
                                $redirect_to = home_url().$build_url;
                                //redirecting
                                header('Location: ' . $redirect_to . '?rd');
                                die();
                            } else {
                                //no . detected in the job ID, one should be there...
                                //we go over each one of the elements we detected looking for a . to locate the id
                                $job_id_at = $this->check_for_id($interesting_parts_array);
                                //if an ID was found we can rebuild the ugly URL for redirection
                                if($job_id_at){
                                    //we think job id was detected
                                    //catrgory should always be before id so we get one before
                                    $comeet_cat = $interesting_parts_array[$job_id_at - 1];
                                    //getting the job ID
                                    $comeet_pos = $interesting_parts_array[$job_id_at];
                                    $comeet_all = '';
                                    //only if this exists do we try and get it's value... trying to avoif key not set error
                                    if(isset($interesting_parts_array[$job_id_at + 1]))
                                        $comeet_all = $interesting_parts_array[$job_id_at + 1];
                                    //build Ugly URL
                                    $build_url = '?pagename='.$post_name.'&comeet_cat='.$comeet_cat.'&comeet_pos='.$comeet_pos.'&comeet_all='.$comeet_all;
                                    $redirect_to = home_url().$build_url;
                                    //echo "Job ID detected at array key: ".$job_id_at."<br />";
                                    //echo $interesting_parts_array[$job_id_at];
                                    //redirect to ugly URL
                                    header('Location: ' . $redirect_to . '?rd');
                                    die();
                                } else {
                                    //no job id detected - 404 (non of the array items had a . in them)
                                    //So, 404 as we can't build an ugly URL
                                }
                            }
                        } else {
                            //depratment
                            //we should have only 2 parameters in the array (3 if there was a trailig slash, but the last one will be empty)
                            //if()
                            $comeet_cat = $interesting_parts_array[0];
                            $comeet_all = '';
                            if(isset($interesting_parts_array[1]))
                                $comeet_all = $interesting_parts_array[1];
                            $build_url = '?pagename='.$post_name.'&comeet_cat='.$comeet_cat.'&comeet_all='.$comeet_all;
                            $redirect_to = home_url().$build_url;
                            header('Location: ' . $redirect_to . '?rd');
                            die();
                        }
                    } else {

                        //first redirect - no ?rd detected in URL
                        //redirecting
                        //we found a match, so we can assume this error 404 was on a Careers page
                        //getting all the parts of the requested URL
                        $request_parts = explode('/', $request);
                        //checking if the first part matches the slug for the careers page
                        if ($request_parts[0] != $post_name) {
                            //if no match is found, we replace it and redirect to the correct page
                            $fixed_request = str_replace($request_parts[0], $post_name, $request);
                            //generate the full URL
                            $redirect_to = home_url($fixed_request);
                            //redirect - ?rd is added so we can detect a second redirect and stop issues from happening.
                            header('Location: ' . $redirect_to . '?rd');
                            die();
                        }
                    }
                }
            }
        }



        private function check_for_id($interesting_parts_array){
            $counter = 0;
            $job_id_at = false;
            foreach($interesting_parts_array as $parts){
                if(strstr($parts, '.')){
                    //. detected so we can assume this is the job ID
                    $job_id_at = $counter;
                }
                $counter++;
            }
            return $job_id_at;
        }

        //adding settings link to the plugin page
        function plugin_add_settings_link( $links ) {
            $settings_link = '<a href="options-general.php?page=comeet">' . __( 'Settings' ) . '</a>';
            array_push( $links, $settings_link );
            return $links;
        }

        //plugin deactivation
        function comeet_deactivation() {
            // clear the permalinks to remove our rewrite rules
            flush_rewrite_rules();
        }

        //getter funciton to get version
        public function get_version(){
            return $this->version;
        }

        public function generate_page_titles($sub = false ,$category, $show_all_link = false, $base  = false){
            $options = $this->get_options();
            if($sub){
                //this means that the sub page (this page) is showing jobs grouped by department
                $check_option = 'comeet_auto_generate_department_pages';
                if($options['advanced_search'] == 1){
                    //this means that the sub page (this page) is showing jobs grouped by location
                    $check_option = 'comeet_auto_generate_location_pages';
                }
                //cheking if to create a link or now.
                if(isset($options[$check_option])){
                    if($options[$check_option] == 1){
                        $category_link = '<a href="' . rtrim(get_the_permalink($options['post_id']), '/') . '/' . $this->comeet_prefix . '/' . strtolower(clean($category)) . (isset($show_all_link) && $show_all_link ? '/all' : '') . '">' . $category . '</a>';
                    } else {
                        $category_link = $category;
                    }
                } else {
                    //if this parameter isn't set, it will default to creating the link.
                    $category_link = '<a href="' . rtrim(get_the_permalink($options['post_id']), '/') . '/' . $this->comeet_prefix . '/' . strtolower(clean($category)) . (isset($show_all_link) && $show_all_link ? '/all' : '') . '">' . $category . '</a>';

                };
            } else {
                $check_option = 'comeet_auto_generate_location_pages';
                if($options['advanced_search'] == 1){
                    $check_option = 'comeet_auto_generate_department_pages';
                }
                //cheking if to create a link or now.
                if(isset($options[$check_option])){
                    if($options[$check_option] == 1){
                        $category_link = '<a href="' . rtrim($base,'/') . '/' . $this->comeet_prefix . '/' . strtolower(clean($category)) . '/all">' . $category . '</a>';
                    } else {
                        $category_link = $category;
                    }
                } else {
                    //if this parameter isn't set, it will default to creating the link.
                    $category_link = '<a href="' . rtrim($base,'/') . '/' . $this->comeet_prefix . '/' . strtolower(clean($category)) . '/all">' . $category . '</a>';
                };
            }
            return $category_link;
        }
    } //  End class

    $Comeet = new Comeet();

    if ($Comeet) {
        register_activation_hook(__FILE__, array(&$Comeet, 'install'));

        function comeet_add_query_vars($aVars) {
            $aVars[] = "comeet_cat"; // represents the name of the department or location in URL
            $aVars[] = "comeet_pos"; // represents the name of the position as shown in URL
            $aVars[] = "comeet_all"; // whether to show all link
            return $aVars;
        }

        // hook add_query_vars function into query_vars
        add_filter('query_vars', 'comeet_add_query_vars');

        function comeet_activation_redirect( $plugin ) {
            if( $plugin == plugin_basename( __FILE__ )) {
                exit( wp_redirect( admin_url( 'options-general.php?page=comeet' ) ) );
            }
        }
        add_action( 'activated_plugin', 'comeet_activation_redirect' );

    }


}
?>