
## Initialization

```php
require(dirname(__FILE__) . '/../../init.php');

use SynapsePayRest\Client;

$options = array(
	'oauth_key'=> USER_OAUTH_KEY, # Optional,
	'fingerprint'=> USER_FINGERPRINT,
	'client_id'=> YOUR_CLIENT_ID,
	'client_secret'=> YOUR_CLIENT_SECRET,
	'development_mode'=> true, # true will ping sandbox.synapsepay.com
	'ip_address'=> USER_IP_ADDRESS
);


$user_id = USER_ID # optionals

$client = new Client($options, $user_id); // $user_id optional

```

## User API Calls

```php

// Create a User

$create_payload = array(
    "logins" => array(
        array(
            "email" => "phpTest@synapsepay.com",
            "password" => "test1234",
            "read_only" => false
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

$create_response = $client->user->create($create_payload);


// Get User

$user = $client->user->get($user_id);

**optional, full_dehydrate='yes will return extra info on user:
$user = $client->user->get($user_id, null, null, null, $full_dehydrate='yes');


// Get All Users

$users_response = $client->user->get();


// Get OAuth Key

$refresh_payload = array('refresh_token' => $user['refresh_token']);

$refresh_response = $client->user->refresh($refresh_payload);


// Add Base Document and Physical/Social/Virtual Documents

$add_documents_payload = array(
    "documents" => array(
        array(
            "email" => "test@test.com",
            "phone_number" => "901-942-8167",
            "ip" => "12134323",
            "name" => "Charlie Brown",
            "alias" => "Woof Woof",
            "entity_type" => "M",
            "entity_scope" => "Arts & Entertainment",
            "day" => 2,
            "month" => 5,
            "year" => 2009,
            "address_street" => "Some Farm",
            "address_city" => "SF",
            "address_subdivision" => "CA",
            "address_postal_code" => "94114",
            "address_country_code" => "US",
            "virtual_docs" => array(
                array(
                    "document_value" => "111-111-3333",
                    "document_type" => "SSN"
                )
            ),
            "physical_docs" => array(
                array(
                    "document_value" => "data:text/csv;base64,SUQs==",
                    "document_type" => "GOVT_ID"
                ),
                array(
                    "document_value" => "data:text/csv;base64,SUQs==",
                    "document_type" => "SELFIE"
                )
            ),
            "social_docs" => array(
                array(
                    "document_value" => "https://www.facebook.com/sankaet",
                    "document_type" => "FACEBOOK"
                )
            )
        )
    )
);

$add_documents_response = $client->user->add_doc($add_documents_payload);


// Update Existing Base Document

$new_govt_id_attachment = "data:img/png;base64,SUQs==";

$update_existing_docs_payload = array(
    'documents' => array(
        array(
            'id' => $base_document['id'],
            'email' => 'test3@test.com',
            'phone_number' => '555-5555',
            'physical_docs' => array(
                array(
                    'document_value' => $new_govt_id_attachment,
                    'document_type' => 'GOVT_ID'
                )
            )
        )
    )
);

$update_existing_docs_response = $client->user->update($update_existing_docs_payload);

```


## Node API Calls

```php

// Get Node

$node = $client->node->get($node_id);

**optional, full_dehydrate='yes will return transaction analysis on specific node:
$node = $client->node->get($node_id, null, null, null, $full_dehydrate='yes');


// Get All Nodes

$nodes = $client->node->get();


// Add SYNAPSE-US Node

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


// Add ACH-US Node through Account and Routing Number Details

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


// Verify ACH-US via Micro-Deposits

$ac_verify_payload = array(
	"micro" => array(0.1,0.1)
);

$nav = $client->node->verify($na['nodes'][0]['_id'],$ac_verify_payload);


// Add ACH node through account login

$login_node_payload = array(
	"type" => "ACH-US",
	"info" => array(
		"bank_id" => "synapse_good",
		"bank_pw" => "test1234",
		"bank_name" => "bofa"
	)
);

$node_login_response = $client->node->add($login_node_payload);


// Verify ACH-US via MFA

$login_verify_payload = array(
	"access_token" => $node_login_response['mfa']['access_token'],
	"mfa_answer" => "test_answer"
);

$node_login_verify_response = $client->node->verify(null, $login_verify_payload);


// Delete a Node

$node_delete_response = $client->node->delete($node_login_verify_response['nodes'][0]['_id']);

```

## Transaction API Calls

```php

// Create a Transaction

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
		"ip" => "192.168.0.1"
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

$create_response = $client->trans->create($ns['nodes'][0]['_id'],$trans_payload);


// Get a Transaction

$tg = $client->trans->get($ns['nodes'][0]['_id'], $create_response['_id']);


// Get All Transactions

$tg = $client->trans->get($ns['nodes'][0]['_id'], null);


// Update Transaction

$update_payload = array(
	'comment' => 'test comment'
);

$tu = $client->trans->update($ns['nodes'][0]['_id'], $create_response['_id'], $update_payload);


// Delete Transaction

$td = $client->trans->delete($ns['nodes'][0]['_id'], $create_response['_id']);


```
## Subnet API Calls

```php

// Create a Subnet

$subnet_payload = array(
    "nickname" => "subnet test php"
);

$create_response = $client->subnet->create($ns['nodes'][0]['_id'], $subnet_payload);


// Get a Subnet

$sg = $client->subnet->get($ns['nodes'][0]['_id'], $create_response['_id']);


// Get All Subnets

$sg = $client->subnet->get($ns['nodes'][0]['_id'], null);


// Update Subnet

$update_payload = array(
    'allowed' => 'LOCKED'
);

$su = $client->subnet->update($ns['nodes'][0]['_id'], $create_response['_id'], $update_payload);


```
## Subscription API Calls

```php

// Create a Subscription

$subscription_payload = array(
    "scope" => [
                "USERS|POST",
                "USER|PATCH",
                "NODES|POST",
                "NODE|PATCH",
                "TRANS|POST",
                "TRAN|PATCH"],
    "url" => "https://requestb.in/zp216zzp"
);

$create_response = $client->subscription->create($subscription_payload);


// Get a Subscription

$sg = $client->subscription->get($create_response['_id']);


// Get All Subscriptions

$sg = $client->subscription->get();


// Update Subscription

$update_payload = array(
    "url" => "https://requestb.in/somethingnew"
);


$subup = $client->subscription->update($create_response['_id'], $update_payload);

```
## Issue Public Key API Calls
```php

//Issue Public Key With Specific Scope

$pk_payload = "CLIENT|CONTROLS";

$pk = $client->publickey->get($pk_payload);


//Issue Public Key With All Scopes

$pk = $client->publickey->get();

```