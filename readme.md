This plug-in is available to anyone who uses the Comeet recruiting platform and wants to showcase their career openings on a WordPress site. 

| IMPORTANT: Provided as-is, we do not provide support for installation of the plug-in on individual websites. We invite you to post your feature requests and report problems on this page.  |
| ------ |

If you wish to contribute to the plug-in’s evolution, we’d love to get your pull requests.

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



# Comeet

Comeet is a collaborative recruiting platform. It's [Careers API](http://support.comeet.co/knowledgebase/careers-api/) allows Comeet customers to create a customized careers website using the job openings data that resides in Comeet.

# Pre-requisites
 - You should have your Comeet company identifiers (UID and Token).
 - You should have permalinks enabled in your WordPress website.
 
# Installation

1. Download the  the Comeet plugin and upload to the /wp-content/plugins/ directory.
2. In WordPress, activate the plugin through the "Plugins" menu.
3. Visit the Comeet settings page, enter the required information and select or create a careers home page. If you are using an existing page please add the shortcode **\[comeet_data\]** to the page content. If you are creating the new page via the plugin, we already have you covered.
4. Visit Careers Page.


# Customization options

### Create a thank you page
After candidates apply they see a simple "thank you" message. You can create your own thank you page:

1. Create a page in WordPress with your thank you message
2. In WordPress, navigate to: Settings > Comeet. For the Thank you page setting select the page that you would like to use.
3. Click Save.


### Create custom content for each location or department
Follow these steps if you wish to create different content for the pages of the locations or departments:

1. Create a page for each location or department and add your content. These pages should be accessible from another page or menu, typically the main careers page. Make sure that these are top-level pages (without any parent).
2. In each page add the following shortcode where the list of positions should be rendered on the page. The "name" attribute would be the name of the location or department exactly as it appears on Comeet.

    For a location called "Los Angeles" the shortcode would be:
    **\[comeet_page name="Los Angeles"\]**
    For a department called "Marketing" the shortcode would be:
    **\[comeet_page name="Marketing"\]**
    
### Customize the pages of the website
By default the plugin renders the careers pages using the default template page of the website. This way the header, footer and other common components are consistent in all pages.

If you wish to use custom templates with a different structure or design then follow these steps:

1. Visit the plugin settings from within WordPress.
2. Under Advanced, specify the names of the template files the plugin should use. These template files should be available in the folder of the theme that the website uses.

### Filter positions by Brand
If you wish to filter the positions that appear on the site to show only positions for a specific Sub-brand of you company, follow these steps:

1. Visit the plugin settings from within WordPress.
2. Under "Sub-brand" , select the appropriate sub-brand field (the custom position category that indicated the brand of each position) and then select the sub-brand that you want displayed.
3. Click Save


### Customize the plug-in
If you wish to customize the structure of the content of the positions list or the position page, beyond styling using CSS, then you can customize the templates of the plug-in. To do that follow these steps:

1. Create a folder named "comeet" in the folder of your parent or child theme.
2. From the folder "templates" in the plug-in, copy the templates that you wish to customize to the new folder you created.
3. Change the copy of the templates as needed.

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

# Frequently asked questions

### How do I add the jobs list to an existing page?

1. Edit the existing careers page in "Text" mode (as opposed to "Visual" mode). Add the shortcode **\[comeet_data\]** where the list of jobs should be displayed. Click "Update" to save your changes.
2. Go to the settings of the Comeet plugin. For the "Careers website page" choose the page at which you added the shortcode. Click "Save Changes".

Visit the Careers Page.

### The list of positions is not shown on the careers page.

1. Go to the Comeet Plugin's settings in WordPress and click Save. Make sure that the settings are saved successfully.
2. Edit the careers page in "Text" mode (as opposed to "Visual" mode) and make sure that the shortcode \[comeet_data\] exists in the body of the page.
3. Edit the php.ini file and make sure allow_url_fopen is enabled.


### When I visit a page I get a "Page not found" message or the page is blank.
1. Ensure that you actually have some published positions in your Comeet account.
2. Visit the plugin's settings (Settings > Comeet) and make sure the correct **Careers website page** is selected. Visit this page to make sure it includes the short code: **\[comeet_data\]**. Another option is to create a new careers page by selecting **Create new page** and clicking **Save**.
3. Make sure you have the latest version of the plugin. This problem can arise from conflicts with other plugins and version 1.6.1 introduced a mechanism that handles such conflicts automatically.


### I don't see the positions' information when sharing a position on social media.

Make sure you're using the latest version of the Comeet plugin for WordPress. The plugin takes care of adding social information to every page, so positions look at their best when shared on Facebook, LinkedIn and other social media.
If you're using a plugin to manage sharing on your website then we recommend working with [YOAST](https://yoast.com/wordpress/plugins/seo/). It is a WordPress plugin for sharing and SEO and it is fully compatible with the Comeet plugin for WordPress.


### How does the plug-in work in regards to SEO optimization when promoting our positions?

The WordPress plugin is designed for SEO optimization of your positions:

- The positions page and single position pages are actual pages on your website that can be access by crawlers for indexing.
- Position pages include the social graph meta tags.
- Position pages support the Job Posting schema defined by [schema.org](http://schema.org/) (initiative by Google, Yahoo! and Bing). This support was added in version 1.4.1 of the plugin.
- The plugin works with other SEO plugins that run on the WordPress website. It was specifically tested to work with [Yoast](https://yoast.com/wordpress/plugins/seo/) – a popular SEO plugin for WordPress.

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

# License

Copyright 2018 Comeet


Licensed under the Apache License, Version 2.0 (the "License");
you may not use this plugin except in compliance with the License.
You may obtain a copy of the License at:
[http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and limitations under the License.

Have more questions? Contact us @ [support@comeet.co](mailto:support@commet.co)


 



