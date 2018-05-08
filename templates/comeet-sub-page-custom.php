<div class="comeet-outer-wrapper">
<?php if (isset($comeetgroups) && !empty($comeetgroups) && count(comeet_search($data, $sub_group, $comeet_cat)) > 0) { ?>
    <h2 class="comeet-group-name">
        <?php $this->sub_page_logic($data, $sub_group, $comeet_cat);?>
    </h2>
    <div class="comeet-groups-list">
    <?php if (isset($group_element)) { ?>
    <?php foreach ($comeetgroups as $category) { ?>
    <?php
        $hasGroup = $this->get_has_group($data, $group_element, $category, $sub_group, $comeet_cat);
        if ($hasGroup) { ?>
        <div class="comeet-g-r">
            <div class="comeet-u-1-2">
                <div class="comeet-list comeet-group-name">
                    <?php
                    (isset($show_all_link) && $show_all_link ?  $pass_show_all = $show_all_link : $pass_show_all = false);
                    echo $this->generate_page_titles(true, $category, $pass_show_all);
                    ?>
                </div>
            </div>
            <div class="comeet-u-1-2">
                <div class="comeet-list">
                    <ul class="comeet-positions-list">
                    <?php
                    foreach ($data as $post) {
                        if ($this->check_for_category($post, $group_element, $category, $sub_group, $comeet_cat)) {
                            echo '<li class="comeet-position">';
                            echo '<div class="comeet-position-name"><a href="' . $this->generate_sub_page_url($options, $category, $post) . '">' . $post['name'] . '</a></div>';
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
        <?php } ?>
    <?php   }?>
    <?php } ?>
    </div>
    <div class="comeet-social">
        <script type="comeet-social"></script>
    </div>
<?php
} else {
    echo "Sorry, no open positions here at this time. Please visit again soon.";
}
?>
<?php
include('version-comments.php')
?>
</div>
