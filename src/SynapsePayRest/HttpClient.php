<?php

namespace SynapsePayRest;

class HttpClient{
	

	function __construct($options, $user_id=null){
		$this->client_id = $options['client_id'];
		$this->client_secret = $options['client_secret'];
		$this->oauth_key = $options['oauth_key'];
		$this->fingerprint = $options['fingerprint'];
		$this->ip_address = $options['ip_address'];
		$this->lang = 'en';
		$this->user_id = $user_id;
		if($options['development_mode']){
			$this->baseUrl = 'https://sandbox.synapsepay.com/api/3';
		}else{
			$this->baseUrl = 'https://synapsepay.com/api/3';
		}
	}

	function update_headers($options){
		if(array_key_exists('user_id',$options)){
			$this->user_id = $options['user_id'];
		}
		if(array_key_exists('oauth_key', $options)){
			$this->oauth_key = $options['oauth_key'];
		}
	}

	function create_headers($url, $json_str=null){
		$headers = array(
			'Content-Type:application/json',
			'X-SP-USER:' . $this->oauth_key . '|' . $this->fingerprint,
			'X-SP-GATEWAY:' . $this->client_id . '|' . $this->client_secret,
			'X-SP-USER-IP:' . $this->ip_address,
			'X-SP-LANG:' . $this->lang
		);
		if($json_str){
			$options = array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_POSTFIELDS => $json_str,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_RETURNTRANSFER => 1,
				// CURLOPT_VERBOSE => true
			);
		}else{
			$options = array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_RETURNTRANSFER => 1,
				// CURLOPT_VERBOSE => true
			);
		}
		return $options;
	}

	function get($path){
		$url = $this->baseUrl . $path;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, false);
		$options = $this->create_headers($url);
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		if($response == false || curl_error($ch)) {
			$err = curl_getinfo($ch);
			curl_close($ch);
			if($response != false){
				return json_decode($response, true);
			}
			return $err;
		} else {
			curl_close($ch);
			return json_decode($response, true);
		}
	}

	function post($path, $json_object){
		$url = $this->baseUrl . $path;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, true);
		$json_str = json_encode($json_object);
		$options = $this->create_headers($url, $json_str);
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		if($response == false || curl_error($ch)) {
			$err = curl_getinfo($ch);                        
			curl_close($ch);
			if($response != false){
				return json_decode($response, true);
			}
			return $err;
		} else {
			curl_close($ch);
			return json_decode($response, true);
		}
	}

	function patch($path, $json_object){
		$url = $this->baseUrl . $path;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
		$json_str = json_encode($json_object);
		$options = $this->create_headers($url, $json_str);
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		if($response == false || curl_error($ch)) {
			$err = curl_getinfo($ch);                        
			curl_close($ch);
			if($response != false){
				return json_decode($response, true);
			}
			return $err;
		} else {
			curl_close($ch);
			return json_decode($response, true);
		}
	}

	function delete($path){
		$url = $this->baseUrl . $path;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		$options = $this->create_headers($url);
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		if($response == false || curl_error($ch)) {
			$err = curl_getinfo($ch);                        
			curl_close($ch);
			if($response != false){
				return json_decode($response, true);
			}
			return $err;
		} else {
			curl_close($ch);
			return json_decode($response, true);
		}
	}
}