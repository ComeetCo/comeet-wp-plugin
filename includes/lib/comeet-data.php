<?php

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function comeet_get_data($comeeturl) {

	$cSession = curl_init(); 
	curl_setopt($cSession,CURLOPT_URL,$comeeturl);
	curl_setopt($cSession, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
	$result=curl_exec($cSession);
	curl_close($cSession);
	$result1 = json_decode($result,true);
	return $result1;
		
}

//fetch Data

	if(isset($comeet_pos)) {
		$transient_key = $comeet_pos;
		$post_data = get_transient( $transient_key );
		if ( $post_data == '' ) {
			$comeet_post_url = 'https://www.comeet.co/careers-api/1.0/company/' . $options["comeet_uid"] . '/positions/' . $comeet_pos . '?token=' . $options["comeet_token"];
			$post_data = comeet_get_data($comeet_post_url);	
			set_transient( $transient_key, $post_data, 60 * 5 * 1 );
		}
			//echo $post_data['status']; 	
	} else {
		$transient_key = 'comeet-careers-' . $options['comeet_uid'] . '-' . $options['comeet_token'];
		//delete_transient($transient_key);
		$data = get_transient( $transient_key );
		if ( $data == '' ) {
		//Read main data for all positions
		$cSession = curl_init(); 
		curl_setopt($cSession,CURLOPT_URL,"https://www.comeet.co/careers-api/1.0/company/" . $options['comeet_uid'] . "/positions?token=" . $options['comeet_token']);
		curl_setopt($cSession, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
		$result=curl_exec($cSession);
		curl_close($cSession);
		$result1 = array();
		$result1 = json_decode($result,true);
		if (!$result1['status'] == 400) {	
			//Add description details for each post
			foreach ($result1 as $key=>$job) {
				$cSession = curl_init(); 
				curl_setopt($cSession,CURLOPT_URL,$job['position_url']);
				curl_setopt($cSession, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
				$position=curl_exec($cSession);
				$positiond = json_decode($position,true);
				curl_close($cSession);
				if ($job['department'] === NULL || $job['department'] =="" ) {
					$result1[$key]['department'] = 'Other';
				}				
				if ($job['location'] === NULL || $job['location'] =="" ) {
					$result1[$key]['location'] = 'Other';
				}
				$result1[$key]['description'] = $positiond['description'];
				$result1[$key]['requirements'] = $positiond['requirements'];
			}
		}
		//Set transient data
		set_transient( $transient_key, $result1, 60 * 5 * 1 );
		$data = $result1;

		}
		if ($data['status'] == 400) {
			//echo $data['status'];
		} else {
		
		if($comeet_group==0) {
			$group_element = 'location';
		} else {
			$group_element = 'department';
		}
		
		function comeet_search($array, $key, $value)
		{
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
		if ($group_element == 'location') {
			if (count(comeet_search($data, $group_element, $comeet_cat)) >0) {
				$group_element = 'location';
			} else {
				$alt_element = 'department';
				if (count(comeet_search($data, $alt_element, $comeet_cat)) >0) {
					$group_element = 'department';
				}
			}
		} 
		if ($group_element == 'department') {
			if (count(comeet_search($data, $group_element, $comeet_cat)) >0) {
				$group_element = 'department';
			} else {
				$alt_element = 'location';
				if (count(comeet_search($data, $alt_element, $comeet_cat)) >0) {
					$group_element = 'location';
				}
			}
		} 
		
		$groupa = array();
		foreach ($data as $h) {
			$groupa[] = $h[$group_element];
		}
		$comeetgroups = array_unique($groupa);
		sort($comeetgroups);
		}
	}
?>