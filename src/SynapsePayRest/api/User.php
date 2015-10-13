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
				print 'ergfhipur3wghiu3qhrgioerqhgioqg3hf';
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
}

?>