<?php

namespace SynapsePayRest;

class PublicKey{

	function __construct($client){
		$this->client = $client;
	}

	function create_public_key_path(){
		$path = '/client?issue_public_key=YES&scope=';
		return $path;
	}


	function get($scope=null){
		$path = $this->create_public_key_path();

		if($scope){
			$path = $path . $scope;
		}
		else{
			$path = $path . 'OAUTH|POST,USERS|POST,USERS|GET,USER|GET,USER|PATCH,SUBSCRIPTIONS|GET,SUBSCRIPTIONS|POST,SUBSCRIPTION|GET,SUBSCRIPTION|PATCH,CLIENT|REPORTS,CLIENT|CONTROLS';			
		}
		$response = $this->client->get($path);

		return $response;
	}

}

?>
