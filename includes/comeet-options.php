<div class="wrap">
  <h2 style="min-width: 255px; max-width: 575px; margin-bottom: 2em;">
      <?php
      $plugin_basename = str_replace('includes/comeet-options.php', '', plugin_basename(__FILE__));
      ?>
      <img src="<?php echo plugins_url($plugin_basename.'img/sparkhire-recruit.svg') ?>" style="width:250px;margin-bottom:-5px;"/>
     - Careers Website
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
      <p>For more information visit <a href="https://developers.comeet.com/reference/wordpress-plugin" target="_blank">our guide</a> or <a href="mailto:support@comeet.co" target="_blank">contact us</a>.</p>
        <p>
            <?php
            echo "Spark Hire Recruit Plugin version: ".Comeet::get_version()."<br />";
            echo "Wordpress version: ".get_bloginfo( 'version' )."<br />";
            echo "PHP version: ".phpversion()."<br />";
            ?>
        </p>
    </div>
      <div style="margin-top: 18px;">
          <?php submit_button(); ?>
          <p class="submit">
              <a href="<?php echo home_url( add_query_arg( NULL, NULL ) )."&comeet_disable_cache"?>" class="button button-primary" style="display: inline-block">Clear all position cache</a>
          </p>
      </div>
    
  </form>
</div>

