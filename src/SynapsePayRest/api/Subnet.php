<?php

namespace SynapsePayRest;

// use SynapsePayRest\HelperFunctions;

class Subnet{

  function __construct($client){
    $this->client = $client;
  }

  function create_subnet_path($node_id, $subnet_id=null){
    $path = '/users/' . $this->client->user_id . '/nodes/' . $node_id . '/subnets';
    if($subnet_id){
      $path = $path . '/' . $subnet_id;
    }
    return $path;
  }

  function create($node_id=null, $payload=null){
    if($payload and $node_id){
      $path = $this->create_subnet_path($node_id);
      $response = $this->client->post($path, $payload);
    }elseif($payload){
      $response = HelperFunctions::create_custom_error_message('node_id');
    }else{
      $response = HelperFunctions::create_custom_error_message('payload');
    }
    return $response;
  }

  function get($node_id=null, $subnet_id=null, $page=null, $per_page=null){
    $path = $this->create_subnet_path($node_id);
    if($node_id){
      if($page){
        $path = $path . '?page=' . $query;
        if($per_page){
          $path = $path . '&per_page=' . $per_page;
        }
      }elseif($per_page){
        $path = $path . '?per_page=' . $per_page;
      }
      $response = $this->client->get($path);
    }else{
      $response = HelperFunctions::create_custom_error_message('node_id');
    }
    return $response;
  }

  function update($node_id=null, $subnet_id=null, $payload=null){
    if(!$node_id){
      $response = HelperFunctions::create_custom_error_message('node_id');
    }elseif (!$subnet_id) {
      $response = HelperFunctions::create_custom_error_message('subnet_id');
    }elseif(!$payload){
      $response = HelperFunctions::create_custom_error_message('payload');
    }else{
      $path = $this->create_subnet_path($node_id, $subnet_id);
      $response = $this->client->patch($path, $payload);
    }
    return $response;
  }
}