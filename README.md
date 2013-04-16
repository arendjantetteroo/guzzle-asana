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

* Complete version 1.0 API with API Key authentication

## Todo

- [x] Complete the service description
- [x] Add some more examples
- [ ] Add tests
- [ ] Add some Response models
- [ ] Add an oauth2 example for asana connect

## Usage
    
To use the Asana API Client simply instantiate the client with the api key.
More information on the key available at http://developer.asana.com/documentation/#Authentication

```php
<?php

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;
$asana_token = ''; // Fill in your token here
$asana_client = AsanaClient::factory(array('api_key' => $asana_token));

// if you want to see what is happening, add debug => true to the factory call
$asana_client = AsanaClient::factory(array('api_key' => $asana_token, 'debug' => true)); 
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

Or Use the `getCommand` method (in this case you need to work with the $response['data'] array:

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

Available examples with their included commands:
- get-users.php : getUsers, getUsersWithEmail, getUser, getMe, getUsersInWorkspace
- get-workspaces.php: getWorkspaces, getTasksForWorkspace
- tasks.php: createTask, getTask, updateTask, deleteTask, createSubTask, getSubTasks, getTaskStories, getStory, addTaskComment, getProjectsForTask
- tasks-and-projects.php: createTask, getProjectsInWorkspace, addProjectToTask, getProjectsForTask, removeProjectFromTask
- tasks-followers.php: createTask, getTask, addTaskFollowers, removeTaskFollowers
- tags.php: createTag, getTag, updateTag, getTagsInWorkspace, getTags
- projects.php: createProject, getProject, updateProject, getProjectsInWorkspace, getProjects, deleteProjects

You can look at the services.json for details on what methods are available and what parameters are available to call them

## Contributions welcome

Found a bug, open an issue, preferably with the debug output and what you did. 
Bugfix? Open a Pull Request and i'll look into it. 

## License

The Asana API client is available under an MIT License.
