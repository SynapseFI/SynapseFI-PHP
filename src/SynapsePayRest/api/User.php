<?php

namespace SynapsePayRest;

class User{

	function __construct($client){
		$this->client = $client;
	}

	function create_user_path($user_id=null){
		$path = '/users';
		if($user_id){
			$path = $path . '/' . $user_id;
		}
		return $path;
	}

	function create($payload=null){
		if($payload){
			$path = $this->create_user_path();
			$response = $this->client->post($path, $payload);
			if(array_key_exists('_id',$response)){
				$this->client->update_headers(array('user_id' =>$response['_id']));
			}
		}else{
			$response = HelperFunctions::create_custom_error_message('payload');
		}
		return $response;
	}

	function get($user_id=null, $page=null, $per_page=null, $query=null){
		$path = $this->create_user_path($user_id);
		if($user_id){
			if($query){
				$path = $path . '?query=' . $query;
				if($page){
					$path = $path . '&page=' . $page;
				}
				if($per_page){
					$path = $path . '&per_page=' . $per_page;
				}
			}elseif($page){
				$path = $path . '?page=' . $page;
				if($per_page){
					$path = $path . '&per_page=' . $per_page;
				}
			}elseif($per_page){
				$path = $path . '?per_page=' . $per_page;
			}
		}
		$response = $this->client->get($path);
		if(array_key_exists('_id',$response)){
			$this->client->update_headers(array('user_id' => $response['_id']));
		}
		return $response;
	}

	function refresh($payload=null){
		if($payload){
			$path = '/oauth/'.$this->client->user_id;
			$response = $this->client->post($path, $payload);
			if(array_key_exists('oauth_key',$response)){
				$this->client->update_headers(array('oauth_key' =>$response['oauth_key']));
			}
		}else{
			$response = HelperFunctions::create_custom_error_message('payload');
		}
		return $response;
	}

	function update($payload=null){
		if($payload){
			$path = $this->create_user_path($this->client->user_id);
			$response = $this->client->patch($path, $payload);
		}else{
			$response = HelperFunctions::create_custom_error_message('payload');
		}
		return $response;
	}

	function add_doc($payload=null){
		if($payload){
			$path = $this->create_user_path($this->client->user_id);
			$response = $this->client->patch($path, $payload);
		}else{
			$response = HelperFunctions::create_custom_error_message('payload');
		}
		return $response;
	}

	function answer_kba($payload=null){
		if($payload){
			$path = $this->create_user_path($this->client->user_id);
			$response = $this->client->patch($path, $payload);
		}else{
			$response = HelperFunctions::create_custom_error_message('payload');
		}
		return $response;
	}

	function attach_file($file_path){
		if($file_path){
			$type = pathinfo($file_path, PATHINFO_EXTENSION);
			$mime_type = HelperFunctions::get_mime_type($type);
			if(!$mime_type){
				$message = 'File type currently not supported.';
				$response = array(
					'success' => FALSE,
					'error' => array(
						'en' => $message 
					)
				);
				return $response;
			}
			$file_url_path = str_replace(' ', '%20', $file_path);
			$data = $this->curl_get_contents($file_url_path);
			if($data === FALSE){
				$data = file_get_contents($file_url_path);
			}
			if($data === FALSE) {

				$message = 'Could not download/open file.';
				$response = array(
					'success' => FALSE,
					'error' => array(
						'en' => $message 
					)
				);
				return $response;
			}else{
				$base64 = 'data:' . $mime_type . ';base64,' . base64_encode($data);
				$payload = array(
					'doc' => array(
						'attachment' => $base64
					)
				);
				$path = $this->create_user_path($this->client->user_id);
				$response = $this->client->patch($path, $payload);
			}
		}else{
			$response = HelperFunctions::create_custom_error_message('file_path');
		}
		return $response;
	}

	function curl_get_contents($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

?>