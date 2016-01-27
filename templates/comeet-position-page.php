<?php
	require_once($this->plugin_dir . 'includes/lib/comeet-data.php');
	
?>
<div class="all-jobs-link">
<?php
	$post = get_post(get_the_ID());
?>
<a href="<?php echo site_url() . '/' . $post->post_name; ?>">&larr; All Jobs</a>
</div>
<h2 class="comeet-position-name"><?php echo $post_data['name'] ?></h2>
<p class="comeet-position-meta-single">
<?php 
	echo $post_data['location']; 
	if (!$post_data['employment_type'] == NULL || !$post_data['employment_type'] =="") {echo '  &middot;  ' . $post_data['employment_type'];} 
	if (!$post_data['experience_level'] == NULL || !$post_data['experience_level'] =="") {echo '  &middot;  ' . $post_data['experience_level'];} 
 ?>
</p>
<div class="comeet-position-info">
	<h4>About The Position</h4>
	<?php
	if (!$post_data['employment_type'] == NULL || !$post_data['employment_type'] =="") {
		echo '<p class="position-image"><img src="' . $post_data['picture_url'] . '" /></p>';
		}
	?>
	<p class="comeet-position-description"><?php echo $post_data['description'] ?></p>
</div>
<div class="comeet-apply">
	<h4>Apply for this position<span class="email-resume-link"><a href="mailo:<?php echo $post_data['email_name'] . '?subject=' . $post_data['name'] ?>">email resume</a></span></h4>
	<script type="comeet-applyform" data-position-uid="<?php echo $post_data['position_uid'] ?>"></script>
</div>
<div class="comeet-social">
	<script type="comeet-social" data-position-uid="<?php echo $post_data['position_uid'] ?>"></script>
</div>	
