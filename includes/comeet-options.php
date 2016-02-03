<div class="wrap">
  <?php screen_icon(); ?>
  <h2><img src="<?php echo plugins_url('comeet-wp-plugin/img/comeet-logo.png') ?>" style="width:110px;margin-bottom:-5px;"/> Careers Website</h2>
  <?php $options = $this->get_options(); 
	$post = get_post($options['post_id']);
  ?>
  <a href="<?php echo site_url() . '/' .$post->post_name;?>" target="_blank"/>Go To Careers Website</a>
  <form action="options.php" method="post">
    <?php
     settings_fields('comeet_options');
     do_settings_sections('comeet');
    ?>
    <div style="margin-top: 18px;">
      <?php submit_button(); ?>
    </div>
    
  </form>
</div>
