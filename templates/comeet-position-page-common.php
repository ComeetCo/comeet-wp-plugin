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
	echo $post_data['location']['name'];
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
    <?php if (isset($post_data['details'])) : ?>
    <?php foreach ($post_data['details'] as $details): ?>
        <?php $title = $details['name'] === 'Description' ? 'About The Position' : $details['name']; ?>
        <?php $css = preg_replace('/\W+/', '', strtolower(strip_tags($details['name']))); ?>
        <h4><?php echo $title; ?></h4>
        <div class="comeet-position-<?php echo $css; ?> comeet-user-text"><?php echo $details['value'] ?></div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="comeet-apply">
	<h4>Apply for this position</h4>
	<script type="comeet-applyform" data-position-uid="<?php echo $post_data['uid'] ?>"></script>
</div>
<div class="comeet-social">
	<script type="comeet-social" data-position-uid="<?php echo $post_data['uid'] ?>"></script>
</div>
