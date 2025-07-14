=== Plugin Name ===
Requires at least: 5
Tested up to: 6.8.1
Stable tag: 4.0.1
License: Apache 2
License URI: http://www.apache.org/licenses/LICENSE-2.0

Simple integration with Spark Hires - Recruit API - Collects position data and creates a display

== Description ==
This plugin will allow you to quickly and easily integrate Recruit (formerly Comeet) with your WordPress site.
Once installed, you will need to enter your company UID and Token (obtained from Recruit) and that's it.
The plugin will fetch position data from the Recruit API, create a page showing all positions and create individual position pages.
Control over features, settings and styling can be done from the Recruit settings page:  Settings -> Recruit


== Frequently Asked Questions ==

= Where can I get the company UID and Token? =

You can get your company UID and Token by logging into the Recruit system, selecting the "settings" option on the top right (under the company name)
and then selecting "Careers Website" in the menu to the left.
Once the page loads you should see both options.
UID should look something like: E5.007
Token should look something like: 5E7236A0BCE5E7295111B55E70BCE

= Do I have to create a page for this to work? =

You can, but you don't have to. If you don't specify what page you want the plugin to use for the careers page, the plugin will create one for you named "Careers"
and will add the necessary shortcode to it.
The shortcode is: [comeet_data]

= What if I have more questions or need more help? =

You can to the developers resource [here](https://developers.comeet.com/reference/wordpress-plugin)



== Changelog ==

= 4.0.1 =
* fix to automatic redirect when plugin active + the settings link on the plugins page.

= 4.0.0 =
* Added automatic updating.
* Fixed bug in settings page.
* Merged 2 PRs.

= 3.0.3 =
* comeet.php - corrected data-section text to correctly describe the section.
* admin_section.php - added missing control for Social sharing title.
* comeet.js - added let to the comeet_init object.

= 3.0.1 =
* comeet-data.php - handling cases where "wp_remote_get" returns error.

= 3.0 =
* comeet.php
    - Added the new Advanced settings.
    - Moved the Sections and settings to an external file being included - for easier maintenance.
    - Moved styles to a CSS file being included only in admin.
    - Moved JS to a JS file being included only in admin.
    - Added all the new setting functions and validation + setting of the comeetvar js variable.
* comeet-admin-css.css - new CSS file.
* admin_sections.php - added new admin_section file being included into comeet.php.
* comeet-admin-js.js - added new JS file.
* comeet.js - added all the new parameters being used for advanced customization.

= 2.6.3 - 2023-03-02 =
* comeet.php - added a settings option allowing user to set the company URL. If set, the job Schema will have sameAs added.

= 2.6.2 - 2023-03-02 =
* comeet.php - updated the text and look/feel of the "Cookie consent manager" in the settings page.
* comeet-data.php - switched from using cURL to get the API response to using wp_remote_get - more inline with WordPress standards.

= 2.6 - 2023-03-01 =
* Better handling of Cookie consent services such as CookieBot and Termly.

= 2.5 - 2023-02-08 =
* Fixing error that occurs when the API returns an empty array (No available positions).
* Added "Clear cache" button to the options page.

= 2.4.6 - 2023-01-31 =
* Cookie consent fix - allowing the application form to work AFTER cookie consent.

= 2.4.5 - 2022-12-21 =
* Version bump - view fix.

= 2.4.5 - 2022-12-16 =
* Adding the ability for the user (Site admin) to decide how to handle missing positions.
* Default behaviour is to load the position URL with a response 200 and show a default built-in message.
* User now has 3 options: default, redirect to 404 page with a response code of 404, redirect to user selected page.
* In case of redirect, the redirect is a 301 redirect.

= 2.4.1 - 2022-09-12 =
* Removing parameter that was forcing all API calls to bypass API response caching.

= 2.4 - 2022-09-12 =
* comeet.php - Fixing issues that displayed warnings in special cases like multisite installs.
* comeet.php - Fixing issue with 'comee-deactivation' function.
* comeet-data.php - Fix for redeclaration of 'comeet_string_clean' in multisite by checking function_exists first.

= 2.3.9 - 2022-05-31 =
* comeet.php - Fix for Elementor Pro issue.

= 2.3.8 - 2022-05-31 =
* comeet-sub-page-custom.php - Backwards compatibility fix.

= 2.3.7 - 2022-05-23 =
* comeet-careers.php - Backwards compatibility fix.

= 2.3.6 - 2022-05-13 =
* comeet.php - If a user does not explicitly select a thank you page, none will be set.

= 2.3.5 - 2022-05-12 =
* comeet.php - Fixing for backwards compatibility of the "generate_page_titles" function.
* Updated position URLs to resolve SEO issues caused by multiple URLs for the same position.

= 2.3.4 - 2022-05-04 =
* comeet.php - Prevent job schema from being added to closed/non-existent positions.
* Updated Plugin URI to current documentation.

= 2.3.3 - 2022-03-18 =
* Fix for rare issue where template file in theme folder causes load failure.

= 2.3.2 - 2022-03-02 =
* comeet-data.php - Added CURLOPT_TIMEOUT to cURL call to prevent rare API stalls.

= 2.3.1 - 2021-12-14 =
* Fix for special character handling in job posting schema description (GitHub issue #9).

= 2.3 - 2021-06-12 =
* Version update to 2.3.
* comeet.php - Improved CSS caching and clearer label.
* comeet.js - Updated for cache handling.

= 2.2 - 2021-04-11 =
* comeet.php - Fix for meta description update with Yoast.

= 2.18.6 - 2021-09-22 =
* comeet.php / comeet.js - Fix for CSS-CACHE logic.

= 2.18.5 - 2021-09-22 =
* comeet.php / comeet-data.php - Fix for sub-brand selection with category names containing spaces.

= 2.18 - 2021-03-09 =
* comeet.php - Added CSS-URL and CSS-CACHE settings.
* comeet.js - Refactor for CSS-CACHE handling.

= 2.17.7 - 2021-08-12 =
* comeet.php - Added "directApply" to Job Posting schema.

= 2.17.6 - 2021-07-27 =
* Fix for unescaped `"` in job description JSON schema.

= 2.17.5 - 2021-06-28 =
* Fix for job description JSON schema.

= 2.17 - 2021-06-21 =
* Decreased plugin cache time from 30 minutes to 5 minutes.
* Fix for broken layout when showing closed position.

= 1.6.10.1 - 2018-05-25 =
* Minor security improvements (sanitization of user input).
* Fixed layout issue with embedded form in Elementor widgets.

= 1.6.10.0 - 2018-05-20 =
* Introduced setting to define custom thank you message after form submission.
* Refactored codebase to improve compatibility with WordPress 4.9+.
* Fixed issue with AJAX form submission not displaying errors correctly.

= 1.6.9.9 - 2018-05-17 =
* Corrected form label alignment on mobile devices.
* Fixed bug where some shortcode attributes were not being parsed correctly.

= 1.6.9.8 - 2018-05-14 =
* Added option to enable/disable default styles via settings panel.
* Improved default form layout for better responsiveness.

= 1.6.9.7 - 2018-05-10 =
* Fixed styling issue on the application form when using certain themes.
* Fixed PHP notice on plugin activation.

= 1.6.9.6 - 2018-05-07 =
* Various minor fixes and cleanup.

= 1.6.9.5 - 2018-05-03 =
* Added comeet-reset.css file with basic CSS rules to allow for a good display regardless of the theme being used.
* Changed comeet.php, enqueuing the new CSS file.

= 1.6.9.4 - 2018-05-03 =
* Added this changelog file.
* Fixed issue with Thank You page when thank you page was not set in the plugin settings.

