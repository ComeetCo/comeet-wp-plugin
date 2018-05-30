<?php

function clean($string) {
    $fallback = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $fallback = preg_replace('/[^A-Za-z0-9\-]/', '', $fallback); // Removes special chars.
    return sanitize_title($string, $fallback);
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

//class for fetching and handling data before returning toe comeet.php and displaying.
class ComeetData {
    //cache comeet prefix.
    const TRANSIENT_PREFIX = 'comeet-';

    //getting comeet data - wrapper function for the cURL call
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

    //getting all data from comeet API - All data = all positions
    //please note the ?details=true flag
    static function get_api_data($options) {
        //Read main data for all positions
        $transient_prefix = 'comeet-all-data';
        $transient_data = get_transient($transient_prefix);
        if(isset($_GET['comeet_disable_cache'])) {
            $transient_data = false;
        }

        if($transient_data){
            Comeet::plugin_debug(['Data from Transient'], __LINE__, __FILE__);
            $all_data = $transient_data;
        } else {
            $comeet_post_url = "https://www.comeet.co/careers-api/2.0/company/" . $options['comeet_uid'] .
                "/positions?token=" . $options['comeet_token'] .
                '&details=true';
            $all_data = self::comeet_get_data($comeet_post_url);
            if (empty($all_data) || (isset($all_data['status']) && $all_data['status'] != 200)) {
                Comeet::plugin_debug(['Data from API - returned error', $all_data], __LINE__, __FILE__);
                $all_data = [];
            } else {
                set_transient($transient_prefix, $all_data, 1800);//cache is set for 30 minutes 60 * 30 = 1800
            }
            Comeet::plugin_debug(['Data from API '], __LINE__, __FILE__);
        }
        return $all_data;
    }

    //get data of specific position
    static function get_position_data($options, $comeet_pos) {
        if (empty($options['comeet_token']) || empty($options['comeet_uid'])) {
            return;
        }
        $post_data = self::get_api_data($options);
        Comeet::plugin_debug(['Specified position is: '.$comeet_pos], __LINE__, __FILE__);
        foreach($post_data as $data){
            if($data['uid'] == $comeet_pos){
                $response = $data;
                break;
            }
        }
        //debug function
        Comeet::plugin_debug(['Position data is: ', $response], __LINE__, __FILE__);
        return $response;
    }


    //fixing group data. Either leaving what it comes with,
    //or changing empty location/department to "other", so everything has some location/department
    static private function get_groups_data($options) {
        //Read main data for all positions
        $all_data = self::get_api_data($options);
        foreach ($all_data as $key => $job) {
            if (empty($job['department'])) {
                $all_data[$key]['department'] = 'Other';
            }

            if (empty($job['location']) || empty($job['location']['name'])) {
                if (empty($all_data[$key]['location'])) {
                    $all_data[$key]['location'] = array();
                }
                $all_data[$key]['location']['name'] = 'Other';
            }
        }
        $response = $all_data;
        return $response;
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

    //getting the opostie group element for pages that display positions by location or department
    static public function opposite_group_element($group_element) {
        return $group_element === 'department' ? 'location' : 'department';
    }


    static public function get_groups($options, $comeet_cat, $invert_group = false) {
        if (empty($options['comeet_token']) || empty($options['comeet_uid'])) {
            return [false, false, false];
        }
        $data = self::get_groups_data($options);
        //debug function
        Comeet::plugin_debug(['In get_groups function', $data], __LINE__, __FILE__);
        if (!empty($data)){
            $group_element = self::get_group_element($options, $comeet_cat, $data);
            if ($invert_group) {
                $group_element = self::opposite_group_element($group_element);
            }
            $groupa = array();

            foreach ($data as $h) {
                $groupa[] = self::get_group_value($h, $group_element);
            }
            $comeet_groups = array_unique($groupa);
            sort($comeet_groups);

            return [$comeet_groups, $data, $group_element];
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

        if (isset($array_value) && strtolower(clean($array_value)) == strtolower($value)) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, comeet_search($subarray, $key, $value));
        }
    }

    return $results;
}
?>