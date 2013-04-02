<?php

require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;

// Get the asana client with your asana api key
$asana_client = AsanaClient::factory(array('api_key' => $asana_api_key, 'debug'=> true));

$workspace_id = 4785243965702; // Change this to the id of the workspace you want to add the task to

//Duedate in two days
$duedate = new DateTime();
$duedate->add(new DateInterval('P2D'));

// Create a task
print "createTask\n";
$task = $asana_client->createTask(
	array(
		'assignee'  	  => 'me',
		'assignee_status' => 'upcoming', 		// One off inbox, later, today or upcominb
		'name' 			  => 'Asana api test task',
		'completed' 	  => 0, 						// 0 for false, 1 for true
		'notes' 		  => "testing notes",
		'workspace' 	  => $workspace_id,
		'due_on' 		  => $duedate->format('Y-m-d'),   
		'followers' 	  => array(),
		'tags'			  => array('testing', 'api')
	)
);
print_r($task);
$taskid = $task['id'];

// Get the created task
print "getTask\n";
$task = $asana_client->getTask(array('task-id' => $taskid));
print_r($task);

