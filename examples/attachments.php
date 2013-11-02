<?php
/**
 * Attachments example
 * 
 */

/**
 * ------ Config -------
 */

/*
 * Change this to the id of the workspace you want to add the task to
 *
 * You can use the get-workspaces.php file to get the workspace id's
 */
$workspace_id = 4785243965702; 

/**
 * If set to true, tasks will be deleted at the end
 */
$cleanup = false;

/**
 * If set to true, all api calls will be shown with debug output
 * @var boolean
 */
$debug = false;

/**
 * ------- End Config -------
 */


require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;

// Get the asana client with your asana api key
$asana_client = AsanaClient::factory(array('api_key' => $asana_api_key, 'debug' => $debug));

//Duedate in two days
$duedate = new DateTime();
$duedate->add(new DateInterval('P2D'));

// Create a task
print "createTask\n";
$task = $asana_client->createTask(
	array(
		'assignee'  	  => 'me',
		'assignee_status' => 'upcoming', 		// One off inbox, later, today or upcoming
		'name' 			  => 'Asana api attachment test task',
		'completed' 	  => 0, 						// 0 for false, 1 for true
		'notes' 		  => "testing notes",
		'workspace' 	  => $workspace_id,
		'due_on' 		  => $duedate->format('Y-m-d'),   
		'followers' 	  => array(),
	)
);
print_r($task);
$taskid = $task['id'];

// Upload the attachment to this task
$att = $asana_client->uploadAttachment(array('task-id' => $taskid, 'file' => 'asana-oauth-button-blue.png'));
print_r($att);

// Get attachments for task
$attachments = $asana_client->getAttachmentsForTask(array('task-id' => $taskid));
print_r($attachments);
foreach($attachments as $attachment){
	$attachment_full = $asana_client->getAttachment(array('attachment-id' => $attachment['id']));
	print_r($attachment_full);
}

if ($cleanup) {
	// Remove the created task
	print "Delete the main task with: deleteTask\n";
	$data = $asana_client->deleteTask(array('task-id' => $taskid));
	if(count($data) == 0){
		print "Task deleted\n";
	}
}