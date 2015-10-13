<?php

namespace SynapsePayRest;

/**
 * Simple static helper functions class for common tasks.
 */
class HelperFunctions{

	/**
	 * Creates a message for errors occuring at the library level.
	 * @param  String $key 	The missing parameter key.
	 * @return array      	An array object containg the error message.
	 */
	static function create_custom_error_message($key){
		$message = 'Please include the "' . $key . '" parameter';
		$response = array(
			'success' => false,
			'error' => array(
				'en' => $message 
			)
		);
		return $response;
	}
}