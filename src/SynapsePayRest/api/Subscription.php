<?php

namespace SynapsePayRest;

class Subscription{

	function __construct($client){
		$this->client = $client;
	}

	function create_subscription_path($subscription_id=null){
		$path = '/subscriptions';
		if($subscription_id){
			$path = $path . '/' . $subscription_id;
		}
		return $path;
	}

	function create($payload=null){
		if($payload){
			$path = $this->create_subscription_path();
			$response = $this->client->post($path, $payload);
		}else{
			$response = HelperFunctions::create_custom_error_message('payload');
		}
		return $response;
	}

	function get($subscription_id=null, $page=null, $per_page=null){
	    $path = $this->create_subscription_path($subscription_id);
	    if($page){
	        $path = $path . '?page=' . $query;
	        if($per_page){
	          $path = $path . '&per_page=' . $per_page;
	        }
	      }elseif($per_page){
	        $path = $path . '?per_page=' . $per_page;
	      }
	      $response = $this->client->get($path);
	   
	    return $response;
	  }

	function update($subscription_id=null, $payload=null){
	    if(!$subscription_id){
	      $response = HelperFunctions::create_custom_error_message('subscription_id');
	    }elseif(!$payload){
	      $response = HelperFunctions::create_custom_error_message('payload');
	    }else{
	      $path = $this->create_subscription_path($subscription_id);
	      $response = $this->client->patch($path, $payload);
	    }
	    return $response;
	}

}

?>
