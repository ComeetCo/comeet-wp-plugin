<?php
	require_once($this->plugin_dir . 'includes/lib/comeet-data.php');
	
?>
<div class="all-jobs-link">
<?php
	$post = get_post(get_the_ID());
?>
<a href="<?php echo site_url() . '/' . $post->post_name; ?>">&larr; All Jobs</a>
</div>
<div><?php
if ($post_data['status'] == 404) {
	$careerurl=site_url() . '/' . $post->post_name;;
	echo '<meta http-equiv="refresh" content="1; url=' . $careerurl .'" />';
  echo 'This position was not found. You will be redirected to careers home. If not, click <a href="' . $careerurl .'">here</a>.';
exit;
	//wp_redirect( home_url() ); exit;
}
?>
</div>
<h2 class="comeet-position-name"><?php echo $post_data['name'] ?></h2>
<div class="comeet-position-meta-single">
<?php 
	echo $post_data['location']; 
	if (!$post_data['employment_type'] == NULL || !$post_data['employment_type'] == "") {echo '  &middot;  ' . $post_data['employment_type'];} 
	if (!$post_data['experience_level'] == NULL || !$post_data['experience_level'] == "") {echo '  &middot;  ' . $post_data['experience_level'];} 
 ?>
</div>
<div class="comeet-position-info">
	<h4>About The Position</h4>
	<?php
	if (!$post_data['employment_type'] == NULL || !$post_data['employment_type'] =="") {
		echo '<div class="position-image"><img src="' . $post_data['picture_url'] . '" /></div>';
		}
	?>
	<div class="comeet-position-description"><?php echo $post_data['description'] ?></div>
	<div class="comeet-position-requirements"><?php echo $post_data['requirements'] ?></div>
</div>
<div class="comeet-apply">
	<h4>Apply for this position</h4>
	<script type="comeet-applyform" data-position-uid="<?php echo $post_data['position_uid'] ?>"></script>
</div>
<div class="comeet-social">
	<script type="comeet-social" data-position-uid="<?php echo $post_data['position_uid'] ?>"></script>
</div>	
