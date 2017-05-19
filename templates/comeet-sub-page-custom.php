<?php if (isset($comeetgroups) && count(comeet_search($data, $sub_group, $comeet_cat)) >0) { ?>
    <h2 class="comeet-group-name">
        <?php foreach ( $data as $post ) {
            if(strtolower(clean($post[$sub_group])) == $comeet_cat) {
                echo $post[$sub_group];
                break;
            }
        } ?>
    </h2>
    <div class="comeet-groups-list">
    <?php foreach ($comeetgroups as $category) : ?>	
        <div class="comeet-g-r">
            <div class="comeet-u-1-2">
                <div class="comeet-list comeet-group-name">
                    <?php echo '<a href="' . get_the_permalink($options['post_id']) . strtolower(clean($category)) . '">' . $category . '</a>'; ?>
                </div>
            </div>
            <div class="comeet-u-1-2">
                <div class="comeet-list">
                    <ul class="comeet-positions-list">
                    <?php
                    foreach ($data as $post) {
                        if (isset($group_element)) {
                            if ($post[$group_element] === $category && strtolower(clean($post[$sub_group])) === $comeet_cat) {
                                echo '<li class="comeet-position">';
								//echo '<div class="comeet-position-name"><a href="' . get_the_permalink($options['post_id']) . $comeet_cat . '/' . $post['position_uid'] . '/' . strtolower(clean($post['name'])) . '">' . $post['name'] . '</a></div>';
                                echo '<div class="comeet-position-name"><a href="' . get_the_permalink($options['post_id']) . strtolower(clean($category)) . '/' . $post['position_uid'] . '/' . strtolower(clean($post['name'])) . '">' . $post['name'] . '</a></div>';
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
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <div class="comeet-social">
        <script type="comeet-social"></script>
    </div>
<?php
} else {
    echo "No Data Found";
}
?>
