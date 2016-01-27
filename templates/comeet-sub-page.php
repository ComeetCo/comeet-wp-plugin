<?php
	require_once($this->plugin_dir . 'includes/lib/comeet-data.php');
?>
<div class="all-jobs-link">
<?php
	$post = get_post(get_the_ID());
?>
<a href="<?php echo site_url() . '/' . $post->post_name; ?>">&larr; All Jobs</a>
</div>
<?php
if (isset($comeetgroups) && count(comeet_search($data, $group_element, $comeet_cat)) >0) {
	
?>
<h2 class="comeet-group-name"><?php 
	foreach ( $data as $post ) {
		if(strtolower(clean($post[$group_element])) == $comeet_cat) {
			echo $post[$group_element];
			break;
		}
	}
 ?></h2>
<div class="comeet-list">
	<?php

	echo '<ul class="comeet-positions-list">';
	foreach ( $data as $post ) {
		if(strtolower(clean($post[$group_element])) == $comeet_cat) {
			echo '<li class="comeet-position">';
			echo '<p class="comeet-position-name"><a href="' . get_the_permalink() . $comeet_cat . '/' . $post['position_uid'] . '/' . strtolower(clean($post['name'])) . '">' . $post['name'] . '</a></p>';
			echo '<p class="comeet-position-meta">';
			if($comeet_group==0) { echo $post['department']; } else { echo $post['location']; } 
			if (!$post['employment_type'] == NULL || !$post['employment_type'] =="") {echo '  &middot;  ' . $post['employment_type'];} 
			if (!$post['experience_level'] == NULL || !$post['experience_level'] =="") {echo '  &middot;  ' . $post['experience_level'];} 
			echo '</p></li>';
		}
	}
	echo '</ul>';
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