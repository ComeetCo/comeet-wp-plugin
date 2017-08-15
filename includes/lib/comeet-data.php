<?php

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

if (!function_exists('is_iterable')) {
    // from https://stackoverflow.com/a/15655476/938389
    function is_iterable($var) {
        return $var !== null &&
            (
                is_array($var) ||
                $var instanceof Traversable ||
                $var instanceof Iterator ||
                $var instanceof IteratorAggregate
            );
    }
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
        $comeet_post_url = 'https://www.comeet.co/careers-api/2.0/company/' . $options["comeet_uid"] .
            '/positions/' . $comeet_pos .
            '?token=' . $options["comeet_token"] .
            '&details=true';
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
        $comeet_post_url = "https://www.comeet.co/careers-api/2.0/company/" . $options['comeet_uid'] .
            "/positions?token=" . $options['comeet_token'] .
            '&details=true';
        $result1 = self::comeet_get_data($comeet_post_url);

        if (!isset($result1['status']) || $result1['status'] != 400) {
            //Add description details for each post
            foreach ($result1 as $key => $job) {
                if (empty($job['department'])) {
                    $result1[$key]['department'] = 'Other';
                }

                if (empty($job['location']) || empty($job['location']['name'])) {
                    if (empty($result1[$key]['location'])) {
                        $result1[$key]['location'] = array();
                    }
                    $result1[$key]['location']['name'] = 'Other';
                }
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
                $groupa[] = self::get_group_value($h, $group_element);
            }
            $comeetgroups = array_unique($groupa);
            sort($comeetgroups);
            return [$comeetgroups, $data, $group_element];
        }
    }

    static public function get_group_value($item, $key) {
        if ($key === 'location') {
            if (isset($item['location']) && isset($item['location']['name'])) {
                return $item['location']['name'];
            }

            return '';
        }
        
        if (isset($item[$key])) {
            return $item[$key];
        }

        return '';
    }

    static public function is_category($item, $key, $category, $cleanCompare = false) {
        $value = self::get_group_value($item, $key);

        if ($cleanCompare) {
            $value = strtolower(clean($value));
        }
        return $category === $value;
    }

    /* http://schema.org/JobPosting */
    static public function get_schema_prop($name) {
        $props = array(
            'basesalary' => 'baseSalary',
            'salary' => 'baseSalary',
            'dateposted' => 'datePosted',
            'date' => 'datePosted',
            'educationrequirements' => 'educationRequirements',
            'education' => 'educationRequirements',
            'employmenttype' => 'employmentType',
            'experiencerequirements' => 'experienceRequirements',
            'requirements' => 'experienceRequirements',
            'hiringorganization' => 'hiringOrganization',
            'organization' => 'hiringOrganization',
            'incentivecompensation' => 'incentiveCompensation',
            'compensation' => 'incentiveCompensation',
            'industry' => 'industry',
            'jobbenefits' => 'jobBenefits',
            'benefits' => 'jobBenefits',
            'jobLocation' => 'jobLocation',
            'location' => 'jobLocation',
            'occupationalcategory' => 'occupationalCategory',
            'qualifications' => 'qualifications',
            'responsibilities' => 'responsibilities',
            'skills' => 'skills',
            'specialcommitments' => 'specialCommitments',
            'title' => 'title',
            'validthrough' => 'validThrough',
            'workhours' => 'workHours',
            'additionalType' => 'additionalType',
            'alternatename' => 'alternateName',
            'description' => 'description',
            'disambiguatingdescription' => 'disambiguatingDescription',
            'identifier' => 'identifier',
            'id' => 'identifier',
            'image' => 'image',
            'mainentityofpage' => 'mainEntityOfPage',
            'name' => 'name',
            'potentialaction' => 'potentialAction',
            'sameas' => 'sameAs',
            'url' => 'url'
        );
        $clean = preg_replace('/\W+/', '', strtolower(strip_tags($name)));

        if (isset($props[$clean])) {
            return $props[$clean];
        }

        return '';
    }

    static public function get_property_value($data, $key) {
        if (isset($data) && is_iterable($data)) {
            foreach ($data as $details) {
                if (isset($details['name']) && $details['name'] === $key) {
                    return $details['value'];
                }
            }
        }

        return false;
    }
}

function comeet_search($array, $key, $value) {
    $results = array();

    if (is_array($array)) {
        $array_value = ComeetData::get_group_value($array, $key);

        if (isset($array_value) && strtolower(clean($array_value)) == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, comeet_search($subarray, $key, $value));
        }
    }

    return $results;
}
?>