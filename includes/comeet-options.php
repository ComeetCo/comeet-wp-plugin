<div class="wrap">
  <h2 style="min-width: 255px; max-width: 575px; margin-bottom: 2em;">
      <?php
      $plugin_basename = str_replace('includes/comeet-options.php', '', plugin_basename(__FILE__));
      ?>
      <img src="<?php echo plugins_url($plugin_basename.'img/comeet-logo.png') ?>" style="width:110px;margin-bottom:-5px;"/>
    Careers Website
    <?php $options = $this->get_options(); 
    $post = get_post($options['post_id']);

    ?>
	<?php
	if (isset($post->post_name)) { ?>
		<a class="button" href="<?php echo get_permalink( $post->ID );?>" target="_blank" style="float: right;"/>Open &#8599;</a>
	<?php }
	?>
  </h2>
  <form action="options.php" method="post">
    <?php
     settings_fields('comeet_options');
     do_settings_sections('comeet');
    ?>
    <div>
      <p>For more information visit <a href="https://developers.comeet.com/v1.0/reference#wordpress-plugin-1" target="_blank">our guide</a> or <a href="mailto:support@comeet.co" target="_blank">contact us</a>.</p>
        <p>
            <?php
            echo "Comeet Plugin version: ".Comeet::get_version()."<br />";
            echo "Wordpress version: ".get_bloginfo( 'version' )."<br />";
            echo "PHP version: ".phpversion()."<br />";
            ?>
        </p>
    </div>
    <div style="margin-top: 18px;">
      <?php submit_button(); ?>
    </div>
    
  </form>
</div>

