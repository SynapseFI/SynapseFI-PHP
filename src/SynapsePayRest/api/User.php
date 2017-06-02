<?php

namespace SynapsePayRest;
use Exception;

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

	function get($user_id=null, $page=null, $per_page=null, $query=null, $full_dehydrate=null){
		$path = $this->create_user_path($user_id);
		if(!$user_id){
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
		if($user_id && $full_dehydrate){
			$path = $path . '?full_dehydrate='.$full_dehydrate;
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
		try{
			$base64 = HelperFunctions::convert_to_base64($file_path);
			$payload = array(
				'doc' => array(
					'attachment' => $base64
				)
			);
			$path = $this->create_user_path($this->client->user_id);
			$response = $this->client->patch($path, $payload);
		}catch(Exception $e){
			$response = array(
				'success' => false,
				'error' => array(
					'en' => $e->getMessage() 
				)
			);
		}
		return $response;
	}
}

?>
