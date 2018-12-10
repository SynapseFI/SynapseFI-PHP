<?php

namespace SynapsePayRest;

class HttpClient{
  protected $printToConsole = true;

  function __construct($options, $user_id=null){
    $this->client_id = $options['client_id'];
    $this->client_secret = $options['client_secret'];
    $this->oauth_key = $options['oauth_key'];
    $this->fingerprint = $options['fingerprint'];
    $this->ip_address = $options['ip_address'];
    $this->lang = 'en';
    $this->user_id = $user_id;
    if($options['development_mode']){
      $this->baseUrl = 'https://uat-api.synapsefi.com/v3.1';
    }else{
      $this->baseUrl = 'https://api.synapsefi.com/v3.1';
    }

    if (isset($options['printToConsole'])) {
      $this->printToConsole = $options['printToConsole'];
    }
  }

  function update_headers($options){
    if(array_key_exists('user_id', $options)){
      $this->user_id = $options['user_id'];
    }
    if(array_key_exists('oauth_key', $options)){
      $this->oauth_key = $options['oauth_key'];
    }
  }

  function create_headers($url, $json_str=null, $idempotent_key=null){
    $headers = array(
      'Content-Type:application/json',
      'X-SP-USER:' . $this->oauth_key . '|' . $this->fingerprint,
      'X-SP-GATEWAY:' . $this->client_id . '|' . $this->client_secret,
      'X-SP-USER-IP:' . $this->ip_address,
      'X-SP-LANG:' . $this->lang
    );
    if($idempotent_key){
      $headers[] = 'X-SP-IDEMPOTENCY-KEY:' . $idempotent_key;
    };
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

  function handle_response($response,$ch){
    $response = json_decode($response, true);
    $this->handleOutput($response);
    if($response == false || curl_error($ch)) {
      $err = curl_getinfo($ch);
      curl_close($ch);
      if($response != false){
        return $response;
      }

      if ($this->printToConsole) {
        print $this->handle_errors($err);
      } else {
        $this->handle_errors($err);
      }
    } else {
      curl_close($ch);
      return $response;
    }
  }

  function handle_errors($err){
    $this->handleOutput($err);

    if($err['http_code'] == 0){
      $err['http_code'] = 408;
    }

    switch ($err['http_code']){
      case 205: $text = 'Reset Content'; break;
      case 300: $text = 'Multiple Choices'; break;
      case 301: $text = 'Moved Permanently'; break;
      case 302: $text = 'Moved Temporarily'; break;
      case 305: $text = 'Use Proxy'; break;
      case 400: $text = 'Bad Request'; break;
      case 401: $text = 'Unauthorized'; break;
      case 402: $text = 'Payment Required'; break;
      case 403: $text = 'Forbidden'; break;
      case 404: $text = 'Not Found'; break;
      case 405: $text = 'Method Not Allowed'; break;
      case 406: $text = 'Not Acceptable'; break;
      case 407: $text = 'Proxy Authentication Required'; break;
      case 408: $text = 'Request timeout/Network connection error'; break;
      case 409: $text = 'Conflict'; break;
      case 410: $text = 'Gone'; break;
      case 411: $text = 'Length Required'; break;
      case 412: $text = 'Precondition Failed'; break;
      case 413: $text = 'Payload Too Large'; break;
      case 414: $text = 'Request-URI Too Large'; break;
      case 415: $text = 'Unsupported Media Type'; break;
      case 431: $text = 'Request header fields too large'; break;
      case 500: $text = 'Internal Server Error'; break;
      case 501: $text = 'Not Implemented'; break;
      case 502: $text = 'Bad Gateway'; break;
      case 503: $text = 'Service Unavailable'; break;
      case 504: $text = 'Gateway Timeout'; break;
      case 505: $text = 'HTTP Version not supported'; break;
      case 521: $text = 'Web server is down'; break;
      default:
          $text = 'Unknown Error';
      break;
    }

    return '{
      "error":{
        "en": '.$text.',
      }
      "error_code": "600",
      "http_code": '.$err["http_code"].',
      "success": false
    }';
  }

  function get($path){
    $url = $this->baseUrl . $path;

    if ($this->printToConsole) {
      print $url;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    $options = $this->create_headers($url);
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    return $this->handle_response($response,$ch);
  }

  function post($path, $json_object, $idempotent_key=null){
    $url = $this->baseUrl . $path;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);
    $json_str = json_encode($json_object);
    $options = $this->create_headers($url, $json_str, $idempotent_key);
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    return $this->handle_response($response,$ch);
  }

  function patch($path, $json_object){
    $url = $this->baseUrl . $path;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    $json_str = json_encode($json_object);
    $options = $this->create_headers($url, $json_str);
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    return $this->handle_response($response,$ch);
  }

  function delete($path){
    $url = $this->baseUrl . $path;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    $options = $this->create_headers($url);
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    return $this->handle_response($response,$ch);
  }

  private function handleOutput($payload)
  {
    if ($this->printToConsole) {
      print_r($payload);
    }
  }
}
