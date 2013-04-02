<?php
/**
 * CRUD tasks
 * 
 * Create, get update and delete a task
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

// Update the earlier created task
print "updateTask\n";
$updatedtask = $asana_client->updateTask(
	array(
		'name' 			  => 'Updated Asana api test task',
		'assignee_status' => 'today', 		// One off inbox, later, today or upcoming
		'task-id' 		  => $taskid,
	)
);
print_r($updatedtask);

// Remove the created task
print "deleteTask\n";
$data = $asana_client->deleteTask(array('task-id' => $taskid));
if(count($data) == 0){
	print "Task deleted\n";
}