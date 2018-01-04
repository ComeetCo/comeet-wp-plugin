<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
//deleting plugin options
$option_name = 'Comeet_Options';
delete_option($option_name);

?>