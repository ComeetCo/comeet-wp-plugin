<?php

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

class ComeetData {

    const TRANSIENT_PREFIX = 'comeet-';

    static private function comeet_get_data($comeeturl) {
        $url = $comeeturl . '&' . comeet_plugin_version_arg();
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($cSession);
        curl_close($cSession);
        $result1 = json_decode($result, true);
        return $result1;
    }

    static function get_position_data($options, $comeet_pos) {
        if (empty($options['comeet_token']) || empty($options['comeet_uid'])) {
            return;
        }
        $comeet_post_url = 'https://www.comeet.co/careers-api/2.0/company/' . $options["comeet_uid"] . '/positions/' . $comeet_pos . '?token=' . $options["comeet_token"];
        $post_data = self::comeet_get_data($comeet_post_url);
        $transient_key = self::TRANSIENT_PREFIX . $comeet_pos;

        if (empty($post_data) || (isset($post_data['status']) && $post_data['status'] === 400)) {
            $post_data = get_transient($transient_key);
        } else {
            set_transient($transient_key, $post_data);
        }
        
        return $post_data;
    }

    static private function fetch_groups_data($options) {
        //Read main data for all positions
        $comeet_post_url = "https://www.comeet.co/careers-api/2.0/company/" . $options['comeet_uid'] . "/positions?token=" . $options['comeet_token'];
        $result1 = self::comeet_get_data($comeet_post_url);

        if (!isset($result1['status']) || $result1['status'] != 400) {
            //Add description details for each post
            foreach ($result1 as $key => $job) {
                $positiond = self::comeet_get_data($job['position_url']);

                if ($job['department'] === NULL || $job['department'] == "") {
                    $result1[$key]['department'] = 'Other';
                }

                if ($job['location'] === NULL || $job['location'] == "") {
                    $result1[$key]['location'] = 'Other';
                }
                $result1[$key]['description'] = $positiond['description'];
                $result1[$key]['requirements'] = $positiond['requirements'];
            }
        }
        return $result1;
    }

    static private function get_group_element($options, $comeet_cat, $data) {
        $comeet_group = $options['advanced_search'];

        if ($comeet_group == 0) {
            $group_element = 'location';
        } else {
            $group_element = 'department';
        }


        if ($group_element == 'location') {
            if (count(comeet_search($data, $group_element, $comeet_cat)) > 0) {
                $group_element = 'location';
            } else {
                $alt_element = 'department';

                if (count(comeet_search($data, $alt_element, $comeet_cat)) > 0) {
                    $group_element = 'department';
                }
            }
        }

        if ($group_element == 'department') {
            if (count(comeet_search($data, $group_element, $comeet_cat)) > 0) {
                $group_element = 'department';
            } else {
                $alt_element = 'location';

                if (count(comeet_search($data, $alt_element, $comeet_cat)) > 0) {
                    $group_element = 'location';
                }
            }
        }

        return $group_element;
    }

    static public function opposite_group_element($group_element) {
        return $group_element === 'department' ? 'location' : 'department';
    }

    static public function get_groups($options, $comeet_cat, $invert_group = false) {
        if (empty($options['comeet_token']) || empty($options['comeet_uid'])) {
            return [false, false, false];
        }
        $data = self::fetch_groups_data($options);
        $transient_key = self::TRANSIENT_PREFIX . 'careers-' . $options['comeet_uid'] . '-' . $options['comeet_token'];

        if (!empty($data)) {
            set_transient($transient_key, $data);
        } else {
            $data = get_transient($transient_key);
        }

        if (empty($data) || (isset($data['status']) && ($data['status'] == 400))) {
            //echo $data['status'];
        } else {
            $group_element = self::get_group_element($options, $comeet_cat, $data);

            if ($invert_group) {
                $group_element = self::opposite_group_element($group_element);
            }
            $groupa = array();

            foreach ($data as $h) {
                $groupa[] = $h[$group_element];
            }
            $comeetgroups = array_unique($groupa);
            sort($comeetgroups);
            return [$comeetgroups, $data, $group_element];
        }
    }
}

function comeet_search($array, $key, $value) {
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && strtolower(clean($array[$key])) == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, comeet_search($subarray, $key, $value));
        }
    }

    return $results;
}
?>