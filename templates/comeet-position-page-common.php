<div>
    <?php
    if(isset($this->post_data)){
        $this->plugin_debug(['Template page: comeet-position-page-common.php', 'Data:', $this->post_data], __LINE__, __FILE__);
    }
    if(isset($post)){
        $this->plugin_debug(['Template page: comeet-position-page-common.php', 'Data:', $post], __LINE__, __FILE__);
    }
    ?>
</div>
<div class="comeet-outer-wrapper">
    <div>
        <?php
        if (empty($this->post_data) || (isset($this->post_data) && (isset($this->post_data['status'])) && ($this->post_data['status'] == 404))) {
            $careerurl = site_url() . (isset($post) ? '/' . $post->post_name : '');
            echo 'The position may have been closed or the link is incorrect. You will be redirected to the careers page, if nothing happens click <a href="' . $careerurl .'">here</a>.';
        } else {
            ?>
            <div id="<?php echo $this->post_data['uid']; ?>">
                <h2 class="comeet-position-name">
                    <?php echo $this->post_data['name'] ?>
                </h2>
                <div class="comeet-position-meta-single">
			<span class="comeet-position-location">
				<?php echo $this->post_data['location']['name']; ?>
			</span>
                    <?php if (!empty($this->post_data['employment_type'])) : ?>
                        <span class="comeet-position-employmenttype">
					&middot;  <?php echo $this->post_data['employment_type']; ?>
				</span>
                    <?php endif; ?>
                    <?php if (!empty($this->post_data['experience_level'])) : ?>
                        <span class="comeet-position-experiencelevel">
					&middot;  <?php echo $this->post_data['experience_level']; ?>
				</span>
                    <?php endif; ?>
                </div>
                <div class="comeet-position-info">
                    <?php if (!empty($this->post_data['picture_url'])) : ?>
                        <div class="position-image">
                            <img src="<?php echo $this->post_data['picture_url']; ?>" alt="" />
                        </div>
                    <?php endif; ?>
                    <?php if (isset($this->post_data['details'])) : ?>
                        <?php foreach ($this->post_data['details'] as $details): ?>
                            <?php if (isset($details['value']) && !empty($details['value']) && !empty(trim($details['value']))) : ?>
                                <?php $title = $this->get_position_title($details['name']); ?>
                                <?php $css = $this->get_position_css($details['name']); ?>
                                <?php $prop = $this->get_schema_prop($details['name']); ?>
                                <h4><?php echo $title; ?></h4>
                                <div class="comeet-position-<?php echo $css; ?> comeet-user-text">
                                    <?php echo $details['value'] ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="comeet-apply">
                <h4>Apply for this position</h4>
                <script type="comeet-applyform" data-position-uid="<?php echo $this->post_data['uid'] ?>"></script>
            </div>
            <div class="comeet-social">
                <script type="comeet-social" data-position-uid="<?php echo $this->post_data['uid'] ?>"></script>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    include('version-comments.php');
    ?>
</div>
