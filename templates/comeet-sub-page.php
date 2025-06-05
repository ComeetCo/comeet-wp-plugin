<?php if ($show_all_link) : ?>
<div class="all-jobs-link">
    <?php
    $options = $this->get_options();
    $post = get_post($options['post_id']);
    ?>
    <a href="<?php echo get_permalink( $post ) ?>">&larr; All Jobs</a>
</div>
<?php endif; ?>

<?php include 'comeet-sub-page-custom.php' ?>
