<?php if (isset($comeetgroups) && !empty($comeetgroups) && count(comeet_search($data, $sub_group, $comeet_cat)) >0) { ?>
    <h2 class="comeet-group-name">
        <?php foreach ( $data as $post ) {
            if(ComeetData::is_category($post, $sub_group, $comeet_cat, true)) {
                echo ComeetData::get_group_value($post, $sub_group);
                break;
            }
        } ?>
    </h2>
    <div class="comeet-groups-list">
    <?php if (isset($group_element)) : ?>
    <?php foreach ($comeetgroups as $category) : ?>
    <?php
        $hasGroup = false;
        foreach ($data as $post) {
            if (ComeetData::is_category($post, $group_element, $category) &&
                ComeetData::is_category($post, $sub_group, $comeet_cat, true))
            {
                $hasGroup = true;
                break;
            }
        }

        if ($hasGroup) : ?>
        <div class="comeet-g-r">
            <div class="comeet-u-1-2">
                <div class="comeet-list comeet-group-name">
                    <?php echo '<a href="' . get_the_permalink($options['post_id']) . strtolower(clean($category)) . (isset($show_all_link) && $show_all_link ? '/all' : '') . '">' . $category . '</a>'; ?>
                </div>
            </div>
            <div class="comeet-u-1-2">
                <div class="comeet-list">
                    <ul class="comeet-positions-list">
                    <?php
                    foreach ($data as $post) {
                        if (ComeetData::is_category($post, $group_element, $category) &&
                            ComeetData::is_category($post, $sub_group, $comeet_cat, true))
                        {
                            echo '<li class="comeet-position">';
                            echo '<div class="comeet-position-name"><a href="' . get_the_permalink($options['post_id']) . strtolower(clean($category)) . '/' . $post['uid'] . '/' . strtolower(clean($post['name'])) . '">' . $post['name'] . '</a></div>';
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
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    </div>
    <div class="comeet-social">
        <script type="comeet-social"></script>
    </div>
<?php
} else {
    echo "Sorry, no open positions here at this time. Please visit again soon.";
}
?>
