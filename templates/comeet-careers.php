<?php
	require_once($this->plugin_dir . 'includes/lib/comeet-data.php');
?>
<?php 
if (isset($comeetgroups)) {
?>
    <div id="d" class="comeet-groups-list">
<?php
	foreach ($comeetgroups as $category) { 
	?>	
			<div class="comeet-g-r">
				<div class="comeet-u-1-2">
					<div class="comeet-list comeet-group-name">
						<?php echo '<a href="' . get_the_permalink() . strtolower(clean($category)) . '">' . $category . '</a>'; ?>
					</div>
				</div>
				<div class="comeet-u-1-2">
					<div class="comeet-list">
						<?php
						echo '<ul class="comeet-positions-list">';
						if (isset($data)) {
                            foreach ($data as $post) {
                                if (isset($group_element)) {
                                    if ($post[$group_element] == $category) {
                                        echo '<li class="comeet-position">';
                                        echo '<div class="comeet-position-name"><a href="' . get_the_permalink() . strtolower(clean($category)) . '/' . $post['position_uid'] . '/' . strtolower(clean($post['name'])) . '">' . $post['name'] . '</a></div>';
                                        echo '<div class="comeet-position-meta">';
                                        if ($comeet_group == 0) {
                                            echo $post['department'];
                                        } else {
                                            echo $post['location'];
                                        }
                                        if (!$post['employment_type'] == NULL || !$post['employment_type'] == "") {
                                            echo '  &middot;  ' . $post['employment_type'];
                                        }
                                        if (!$post['experience_level'] == NULL || !$post['experience_level'] == "") {
                                            echo '  &middot;  ' . $post['experience_level'];
                                        }
                                        echo '</div></li>';
                                    }
                                }
                            }
                        }
						echo '</ul>';
						?>
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
	echo "No Data Found";
}
?>
