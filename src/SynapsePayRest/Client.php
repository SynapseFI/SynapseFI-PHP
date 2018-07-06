<?php

namespace SynapsePayRest;


use SynapsePayRest\HttpClient;
use SynapsePayRest\HelperFunctions;


/**
 * Top-level class constructor.
 *
 * Handles all the SynapsePay API v3 Rest calls.
 *
 * @author Thomas Hipps <thomas@synapsepay.com>
 */
class Client{

	function __construct($options, $user_id=null){
		$this->options = $options;
		$this->client = new HttpClient($this->options, $user_id);
		$this->user = new User($this->client);
		$this->node = new Node($this->client);
		$this->trans = new Trans($this->client);
		$this->subnet = new Subnet($this->client);
		$this->subscription = new Subscription($this->client);
	    $this->publickey = new PublicKey($this->client);
	}
}
?>