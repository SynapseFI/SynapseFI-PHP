<?php

// include "Client.php";
require(dirname(__FILE__) . '/../../init.php');

use SynapsePayRest\Client;

$options = array('oauth_key'=>'', 'fingerprint'=>'8263af189ed09c4ae792a923b821f5c8', 'client_id'=>'RM7p64AfCh3cY74QV7CM', 'client_secret'=>'6Cymilqb2hfzlvEJyr8QD71lNJUQ4UZaJ8qUP4nr', 'development_mode'=>true, 'ip_address'=>'24.130.174.164');

$c = new Client($options);
$create_payload = array(
	"logins" => array(
		array(
			"email" => "phpTest@synapsepay.com",
			"password" => "test1234",
			"read_only" =>false
		)
	),
	"phone_numbers" => array(
		"901.111.1111"
	),
	"legal_names" => array(
		"PHP TEST USER"
	),
	"extra" => array(
		"note" => "Interesting user",
		"supp_id" => "122eddfgbeafrfvbbb",
		"is_business" => false
	)
);
$create_response = $c->user->create($create_payload);
print_r($create_response);
$users = $c->user->get();
$user = $users['users'][0];
$c->user->get($user['_id']);
$client = new Client($options, $user['_id']);
$refresh_payload = array('refresh_token' => $user['refresh_token']);
$rr = $client->user->refresh($refresh_payload);
print_r($rr);
$ssn_payload = array(
	"doc" => array(
		"birth_day" => 4,
		"birth_month" => 2,
		"birth_year" => 1940,
		"name_first" => "John",
		"name_last" => "doe",
		"address_street1" => "1 Infinite Loop",
		"address_postal_code" => "95014",
		"address_country_code" => "US",
		"document_value" => "3333",
		"document_type" => "SSN"
	)
);
$ssn_r = $client->user->add_doc($ssn_payload);
print_r($ssn_r);
$kba_payload = array(
	"doc" => array(
		"question_set_id" => $ssn_r['question_set']['id'],
		"answers" => array(
			array("question_id" => 1, "answer_id" => 1),
			array("question_id" => 2, "answer_id" => 1),
			array("question_id" => 3, "answer_id" => 1),
			array("question_id" => 4, "answer_id" => 1),
			array("question_id" => 5, "answer_id" => 1),
		)
	)
);
$kr = $client->user->answer_kba($kba_payload);
print_r($kr);
$nodes = $client->node->get();
print_r($nodes);
$synapse_node_payload = array(
	"type" => "SYNAPSE-US",
	"info" => array(
		"nickname" => "My Synapse Wallet"
	),
	"extra" => array(
		"supp_id" => "123sa"
	)
);
$ns = $client->node->add($synapse_node_payload);
print_r($ns);
$ac_node_payload = array(
	"type" => "ACH-US",
	"info" => array(
		"nickname" => "PHP Library Savings Account",
		"name_on_account" => "PHP Library",
		"account_num" => "72347235423",
		"routing_num" => "051000017",
		"type" => "PERSONAL",
		"class" => "CHECKING"
	),
	"extra" => array(
		"supp_id" => "123sa"
	)
);
$na = $client->node->add($ac_node_payload);
print_r($na);
$ac_verify_payload = array(
	"micro" => array(0.1,0.1)
);
$nav = $client->node->verify($na['nodes'][0]['_id'],$ac_verify_payload);
print_r($nav);
$log_node_payload = array(
	"type" => "ACH-US",
	"info" => array(
		"bank_id" => "synapse_good",
		"bank_pw" => "test1234",
		"bank_name" => "bofa"
	)
);
$nl = $client->node->add($log_node_payload);
print_r($nl);
$log_verify_payload = array(
	"access_token" => $nl['mfa']['access_token'],
	"mfa_answer" => "test_answer"
);
$nlv = $client->node->verify(null, $log_verify_payload);
print_r($nlv);
$nd = $client->node->delete($nlv['nodes'][0]['_id']);
print_r($nd);
$trans_payload = array(
	"to" => array(
		"type" => "ACH-US",
		"id" => $nav['_id']
	),
	"amount" => array(
		"amount" => 10.10,
		"currency" => "USD"
	),
	"extra" => array(
		"supp_id" => "1283764wqwsdd34wd13212",
		"note" => "Deposit to bank account",
		"webhook" => "http => //requestb.in/q94kxtq9",
		"process_on" => 1,
		"ip" => "192.168.0.1",
		"other" => array(
			"attachments" => array(
				"data => text/csv;base64,SUQsTmFtZSxUb3RhbCAoaW4gJCksRmVlIChpbiAkKSxOb3RlLFRyYW5zYWN0aW9uIFR5cGUsRGF0ZSxTdGF0dXMNCjUxMTksW0RlbW9dIEJlbHogRW50ZXJwcmlzZXMsLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0MzMxNjMwNTEsU2V0dGxlZA0KNTExOCxbRGVtb10gQmVseiBFbnRlcnByaXNlcywtMS4wMCwwLjAwLCxCYW5rIEFjY291bnQsMTQzMzE2MjkxOSxTZXR0bGVkDQo1MTE3LFtEZW1vXSBCZWx6IEVudGVycHJpc2VzLC0xLjAwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDMzMTYyODI4LFNldHRsZWQNCjUxMTYsW0RlbW9dIEJlbHogRW50ZXJwcmlzZXMsLTEuMDAsMC4wMCwsQmFuayBBY2NvdW50LDE0MzMxNjI2MzQsU2V0dGxlZA0KNTExNSxbRGVtb10gQmVseiBFbnRlcnByaXNlcywtMS4wMCwwLjAwLCxCYW5rIEFjY291bnQsMTQzMzE2MjQ5OCxTZXR0bGVkDQo0ODk1LFtEZW1vXSBMRURJQyBBY2NvdW50LC03LjAwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDMyMjUwNTYyLFNldHRsZWQNCjQ4MTIsS2FyZW4gUGF1bCwtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQzMTk5NDAzNixTZXR0bGVkDQo0NzgwLFNhbmthZXQgUGF0aGFrLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDMxODQ5NDgxLFNldHRsZWQNCjQzMTUsU2Fua2FldCBQYXRoYWssLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0Mjk3NzU5MzcsU2V0dGxlZA0KNDMxNCxTYW5rYWV0IFBhdGhhaywtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQyOTc3NTQzNCxTZXR0bGVkDQo0MzEzLFNhbmthZXQgUGF0aGFrLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDI5Nzc1MzY0LFNldHRsZWQNCjQzMTIsU2Fua2FldCBQYXRoYWssLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0Mjk3NzUyNTAsU2V0dGxlZA0KNDMxMSxTYW5rYWV0IFBhdGhhaywtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQyOTc3NTAxMyxTZXR0bGVkDQo0MjM1LFtEZW1vXSBCZWx6IEVudGVycHJpc2VzLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDI5MzMxODA2LFNldHRsZWQNCjQxMzYsU2Fua2FldCBQYXRoYWssLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0Mjg4OTA4NjMsU2V0dGxlZA0KNDAzMCxTYW5rYWV0IFBhdGhhaywtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQyODIxNTM5NixTZXR0bGVkDQo0MDE0LFtEZW1vXSBCZWx6IEVudGVycHJpc2VzLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDI4MTI1MzgwLENhbmNsZWQNCjM4MzIsU2Fua2FldCBQYXRoYWssLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0MjcxMDc0NzAsU2V0dGxlZA0KMzgyNixTYW5rYWV0IFBhdGhhaywtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQyNzAzNTM5MixTZXR0bGVkDQozODI1LFNhbmthZXQgUGF0aGFrLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDI3MDMyOTM3LFNldHRsZWQNCg==",
				"data => text/csv;base64,SUQsTmFtZSxUb3RhbCAoaW4gJCksRmVlIChpbiAkKSxOb3RlLFRyYW5zYWN0aW9uIFR5cGUsRGF0ZSxTdGF0dXMNCjUxMTksW0RlbW9dIEJlbHogRW50ZXJwcmlzZXMsLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0MzMxNjMwNTEsU2V0dGxlZA0KNTExOCxbRGVtb10gQmVseiBFbnRlcnByaXNlcywtMS4wMCwwLjAwLCxCYW5rIEFjY291bnQsMTQzMzE2MjkxOSxTZXR0bGVkDQo1MTE3LFtEZW1vXSBCZWx6IEVudGVycHJpc2VzLC0xLjAwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDMzMTYyODI4LFNldHRsZWQNCjUxMTYsW0RlbW9dIEJlbHogRW50ZXJwcmlzZXMsLTEuMDAsMC4wMCwsQmFuayBBY2NvdW50LDE0MzMxNjI2MzQsU2V0dGxlZA0KNTExNSxbRGVtb10gQmVseiBFbnRlcnByaXNlcywtMS4wMCwwLjAwLCxCYW5rIEFjY291bnQsMTQzMzE2MjQ5OCxTZXR0bGVkDQo0ODk1LFtEZW1vXSBMRURJQyBBY2NvdW50LC03LjAwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDMyMjUwNTYyLFNldHRsZWQNCjQ4MTIsS2FyZW4gUGF1bCwtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQzMTk5NDAzNixTZXR0bGVkDQo0NzgwLFNhbmthZXQgUGF0aGFrLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDMxODQ5NDgxLFNldHRsZWQNCjQzMTUsU2Fua2FldCBQYXRoYWssLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0Mjk3NzU5MzcsU2V0dGxlZA0KNDMxNCxTYW5rYWV0IFBhdGhhaywtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQyOTc3NTQzNCxTZXR0bGVkDQo0MzEzLFNhbmthZXQgUGF0aGFrLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDI5Nzc1MzY0LFNldHRsZWQNCjQzMTIsU2Fua2FldCBQYXRoYWssLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0Mjk3NzUyNTAsU2V0dGxlZA0KNDMxMSxTYW5rYWV0IFBhdGhhaywtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQyOTc3NTAxMyxTZXR0bGVkDQo0MjM1LFtEZW1vXSBCZWx6IEVudGVycHJpc2VzLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDI5MzMxODA2LFNldHRsZWQNCjQxMzYsU2Fua2FldCBQYXRoYWssLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0Mjg4OTA4NjMsU2V0dGxlZA0KNDAzMCxTYW5rYWV0IFBhdGhhaywtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQyODIxNTM5NixTZXR0bGVkDQo0MDE0LFtEZW1vXSBCZWx6IEVudGVycHJpc2VzLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDI4MTI1MzgwLENhbmNsZWQNCjM4MzIsU2Fua2FldCBQYXRoYWssLTAuMTAsMC4wMCwsQmFuayBBY2NvdW50LDE0MjcxMDc0NzAsU2V0dGxlZA0KMzgyNixTYW5rYWV0IFBhdGhhaywtMC4xMCwwLjAwLCxCYW5rIEFjY291bnQsMTQyNzAzNTM5MixTZXR0bGVkDQozODI1LFNhbmthZXQgUGF0aGFrLC0wLjEwLDAuMDAsLEJhbmsgQWNjb3VudCwxNDI3MDMyOTM3LFNldHRsZWQNCg=="
			)
		)
	),
	"fees" => array(
		array(
			"fee" => 1.00,
			"note" => "Facilitator Fee",
			"to" => array(
				"id" => "55d9287486c27365fe3776fb"
			)
		)
	)
);
$tg = $client->trans->get($ns['nodes'][0]['_id'], null);
print_r($tg);
$tc = $client->trans->create($ns['nodes'][0]['_id'],$trans_payload);
print_r($tc);
$update_payload = array(
	'comment' => 'test comment'
);
print 'NEXT';
$tu = $client->trans->update($ns['nodes'][0]['_id'], $tc['_id'], $update_payload);
print_r($tu);
$td = $client->trans->delete($ns['nodes'][0]['_id'], $tc['_id']);
print_r($td);