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
<div itemscope itemtype="http://schema.org/JobPosting" id="<?php echo $post_data['uid']; ?>">
	<h2 class="comeet-position-name" itemprop="name title">
		<?php echo $post_data['name'] ?>
	</h2>
	<div class="comeet-position-meta-single">
		<span class="comeet-position-location" itemprop="jobLocation">
			<?php echo $post_data['location']['name']; ?>
		</span>
		<?php if (!empty($post_data['employment_type'])) : ?>
			<span class="comeet-position-employmenttype" itemprop="employmentType">
				&middot;  <?php echo $post_data['employment_type']; ?>
			</span>
		<?php endif; ?>
		<?php if (!empty($post_data['experience_level'])) : ?>
			<span class="comeet-position-experiencelevel">
				&middot;  <?php echo $post_data['experience_level']; ?>
			</span>
		<?php endif; ?>
	</div>
	<div class="comeet-position-info">
		<?php if (!empty($post_data['employment_type'])) : ?>
			<div class="position-image">
				<img src="<?php echo $post_data['picture_url']; ?>" itemprop="image" alt="" />
			</div>
		<?php endif; ?>
		<?php if (isset($post_data['details'])) : ?>
		<?php foreach ($post_data['details'] as $details): ?>
			<?php $title = $details['name'] === 'Description' ? 'About The Position' : $details['name']; ?>
			<?php $css = preg_replace('/\W+/', '', strtolower(strip_tags($details['name']))); ?>
			<?php $prop = ComeetData::get_schema_prop($details['name']); ?>
			<?php $prop = empty($prop) ? '' : 'itemprop="' . $prop . '"'; ?>
			<h4><?php echo $title; ?></h4>
			<div class="comeet-position-<?php echo $css; ?> comeet-user-text" <?php echo $prop; ?>>
				<?php echo $details['value'] ?>
			</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
<div class="comeet-apply">
	<h4>Apply for this position</h4>
	<script type="comeet-applyform" data-position-uid="<?php echo $post_data['uid'] ?>"></script>
</div>
<div class="comeet-social">
	<script type="comeet-social" data-position-uid="<?php echo $post_data['uid'] ?>"></script>
</div>