<?php
if (isset($comeetgroups) && !empty($comeetgroups)) {
?>
    <div id="d" class="comeet-groups-list">
<?php
	foreach ($comeetgroups as $category) { 
	?>	
			<div class="comeet-g-r">
				<div class="comeet-u-1-2">
					<div class="comeet-list comeet-group-name">
						<?php echo '<a href="' . $base . $this->comeet_prefix . '/' . strtolower(clean($category)) . '/all">' . $category . '</a>'; ?>
					</div>
				</div>
				<div class="comeet-u-1-2">
					<div class="comeet-list">
						<?php
						echo '<ul class="comeet-positions-list">';
						if (isset($data)) {
                            foreach ($data as $post) {
                                if (isset($group_element)) {
                                    if (ComeetData::is_category($post, $group_element, $category)) {
                                        $href = $base . $this->comeet_prefix . '/' . strtolower(clean($category)) . '/' . $post['uid'] . '/' . strtolower(clean($post['name'])) . '/all';
                                        echo '<li class="comeet-position">';
                                        echo '<div class="comeet-position-name">';
                                        echo '<a href="' . $href . '">' . $post['name'] . '</a>';
                                        echo '</div>';
                                        echo '<div class="comeet-position-meta">';
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
	echo "We don't have any open positions at this time. Please visit again soon.";
}
?>