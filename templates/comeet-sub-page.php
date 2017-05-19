<div class="all-jobs-link">
    <?php
    //$post = get_post(get_the_ID());
    $options = $this->get_options();
    $post = get_post($options['post_id']);
    ?>
    <a href="<?php echo site_url() . '/' . $post->post_name; ?>">&larr; All Jobs</a>
</div>

<?php include 'comeet-sub-page-custom.php' ?>