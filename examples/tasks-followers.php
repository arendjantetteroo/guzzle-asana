<?php
/**
 * Followers
 * 
 * 
 */

require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;

// Get the asana client with your asana api key
$asana_client = AsanaClient::factory(array('api_key' => $asana_api_key));

/*
 * Change this to the id of the workspace you want to add the task to
 *
 * You can use the get-workspaces.php file to get the workspace id's
 */
$workspace_id = 4785243965702; 

//Duedate in two days
$duedate = new DateTime();
$duedate->add(new DateInterval('P2D'));

// Create a task
print "createTask\n";
$task = $asana_client->createTask(
	array(
		'assignee'  	  => 'me',
		'assignee_status' => 'upcoming', 		// One off inbox, later, today or upcoming
		'name' 			  => 'Asana api test task',
		'completed' 	  => 0, 						// 0 for false, 1 for true
		'notes' 		  => "testing notes",
		'workspace' 	  => $workspace_id,
		'due_on' 		  => $duedate->format('Y-m-d'),   
		'followers' 	  => array(),
	)
);
print_r($task);
$taskid = $task['id'];

// Get the created task
print "getTask\n";
$task = $asana_client->getTask(array('task-id' => $taskid));
print_r($task);

print "addTaskFollowers\n";
$task = $asana_client->addTaskFollowers(array('task-id' => $taskid, 'followers' => array(1170851807722)));
print_r($task);

print "removeTaskFollowers\n";
$task = $asana_client->removeTaskFollowers(array('task-id' => $taskid, 'followers' => array(1170851807722)));
print_r($task);