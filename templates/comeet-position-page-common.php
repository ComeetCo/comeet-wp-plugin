<div>
<?php
if (empty($post_data) || (isset($post_data) && (isset($post_data['status'])) && ($post_data['status'] == 404))) {
	$careerurl = site_url() . (isset($post) ? '/' . $post->post_name : '');
	echo '<meta http-equiv="refresh" content="1; url=' . $careerurl .'" />';
	echo 'The position may have been closed or the link is incorrect. You will be redirected to the careers page, if nothing happens click <a href="' . $careerurl .'">here</a>.';
	exit;
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
	<?php
	if (!$post_data['employment_type'] == NULL || !$post_data['employment_type'] =="") {
		echo '<div class="position-image"><img src="' . $post_data['picture_url'] . '" /></div>';
		}
	?>
	<h4>About The Position</h4>
	<div class="comeet-position-description comeet-user-text"><?php echo $post_data['description'] ?></div>
    <h4>Requirements</h4>
	<div class="comeet-position-requirements comeet-user-text"><?php echo $post_data['requirements'] ?></div>
</div>
<div class="comeet-apply">
	<h4>Apply for this position</h4>
	<script type="comeet-applyform" data-position-uid="<?php echo $post_data['position_uid'] ?>"></script>
</div>
<div class="comeet-social">
	<script type="comeet-social" data-position-uid="<?php echo $post_data['position_uid'] ?>"></script>
</div>
