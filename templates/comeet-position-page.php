<?php if ($show_all_link) : ?>
	<?php $post = get_post(get_the_ID()); ?>
	<div class="all-jobs-link">
		<a href="<?php echo site_url() . '/' . $post->post_name; ?>">&larr; All Jobs</a>
	</div>
<?php endif; ?>
<?php include 'comeet-position-page-common.php' ?>