guzzle-asana
============

An Asana API client based on Guzzle PHP

## Installation

The library is available through Composer, so its easy to get it. 
Simply add this to your `composer.json` file:

    "require": {
        "ajt/guzzle-asana": "dev-master"
    }
    
And run `composer install`

## Features

* Version 1.0 API with API Key authentication

## Usage
    
To use the Asana API Client simply instantiate the client with the api key.
More information on the key available at http://developer.asana.com/documentation/#Authentication

```php
<?php

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;
$asana_token = ''; // Fill in your token here
$asana_client = AsanaClient::factory(array('api_key' => $asana_token));

// if you want to see what is happening, add debug=true to the factory call
$asana_client = AsanaClient::factory(array('api_key' => $asana_token, 'debug'=> true)); 
```

Invoke Commands using our `__call` method (auto-complete phpDocs are included)

```php
<?php 

$asana_client = AsanaClient::factory(array('api_key' => $asana_token));

$workspaces = $asana_client->getWorkspaces(array());

foreach($workspaces as $workspace){
	$id = $workspace['id'];
	print $workspace['name'] . "\n";
}
``` 

Or Use the `getCommand` method:

```php
<?php 

$asana_client = AsanaClient::factory(array('api_key' => $asana_token));

//Retrieve the Command from Guzzle
$command = $client->getCommand('GetWorkspaces', array());
$command->prepare();

$response = $command->execute();

$workspaces = $response['data'];

foreach($workspaces as $workspace){
	$id = $workspace['id'];
	print $workspace['name'] . "\n";
}
```

## Examples
Copy the apikey-dist.php to apikey.php (in the root directory) and add your apikey. 
Afterwards you can execute the examples in the examples directory. 
See the examples directory for a workspace with tasks usage example

You can look at the services.json for details on what methods are available and how to call them

## Todo


- [ ] Complete the service description
- [ ] Add tests
- [ ] Add some more examples
- [ ] Add some Response models

## Contributions welcome

## License

The Asana API client is available under an MIT License.
