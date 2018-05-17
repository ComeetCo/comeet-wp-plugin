<div class="comeet-outer-wrapper">
<?php
if (isset($comeet_groups) && !empty($comeet_groups)) {
?>
    <div id="d" class="comeet-groups-list">
<?php
	foreach ($comeet_groups as $category) {
	?>
			<div class="comeet-g-r">
				<div class="comeet-u-1-2">
					<div class="comeet-list comeet-group-name">
						<?= $this->generate_page_titles(false, $category, false, $base) ?>
					</div>
				</div>
				<div class="comeet-u-1-2">
					<div class="comeet-list">
                        <ul class="comeet-positions-list">
						<?php
						if (isset($data)) {
                            foreach ($data as $post) {
                                if (isset($group_element)) {
                                    if ($this->check_comeet_is_category($post, $group_element, $category)) {
                                        $href = $this->generate_careers_url($base, $category, $post);
                                        ?>
                                        <li>
                                            <a class="comeet-position" href="<?= $href?>">
                                                <div class="comeet-position-name">
                                                    <!--<a href="<?= $href?>"><?=$post['name']?></a> -->
                                                    <?=$post['name']?>
                                                </div>
                                                <div class="comeet-position-meta">
                                        <?php
                                            if ($comeet_group == 0) {
                                                echo $post['department'];
                                            } else {
                                                echo $post['location']['name'];
                                            }
                                            if (!$post['employment_type'] == NULL || !$post['employment_type'] == "") {
                                                echo '  &middot;  ' . $post['employment_type'];
                                            }
                                            if (!$post['experience_level'] == NULL || !$post['experience_level'] == "") {
                                                echo '  &middot;  ' . $post['experience_level'];
                                            }
                                            ?>
                                                </div>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                            }
                        }
						?>
                        </ul>
					</div>
				</div>
			</div>
	<?php
	}
?>
</div>
<div class="comeet-social">
	<script type="comeet-social"></script>
</div>
<?php
} else {
	echo "We don't have any open positions at this time. Please visit again soon.";
}
?>
<?php
include('version-comments.php');
?>
</div>
