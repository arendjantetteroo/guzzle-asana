<?php
/**
 * CRUD tasks
 * 
 * Create, get update and delete a task
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
		'name' 			  => 'Updated Asana api test task 2',
		'assignee_status' => 'today', 		// One off inbox, later, today or upcoming
		'task-id' 		  => $taskid,
	)
);
print_r($updatedtask);

// Create a subtask
print "Create a subtask for this task $taskid: createSubTask\n";
$subtask = $asana_client->createSubTask(
	array(
		'assignee'  	  => 'me',
		'assignee_status' => 'upcoming', 		// One off inbox, later, today or upcoming
		'name' 			  => 'Asana api test subtask',
		'completed' 	  => 0, 						// 0 for false, 1 for true
		'notes' 		  => "testing notes for subtask",
		'workspace' 	  => $workspace_id,
		'due_on' 		  => $duedate->format('Y-m-d'),   
		'followers' 	  => array(),
		'parent-id'       => $taskid
	)
);
print_r($subtask);

// Create another task
print "createTask\n";
$task = $asana_client->createTask(
	array(
		'assignee'  	  => 'me',
		'assignee_status' => 'upcoming', 		// One off inbox, later, today or upcoming
		'name' 			  => 'Asana api test task as new parent',
		'completed' 	  => 0, 						// 0 for false, 1 for true
		'notes' 		  => "this task will be the new parent of the subtask",
		'workspace' 	  => $workspace_id,
		'due_on' 		  => $duedate->format('Y-m-d'),   
		'followers' 	  => array(),
	)
);
print_r($task);
$newtaskid = $task['id'];

// Set a new parent
print "Set a new parent ($newtaskid) for the created subtask $taskid: setSubTaskParent\n";
$asana_client->setSubTaskParent(array('parent' => $newtaskid, 'task-id' => $subtask['id']));

print "Remove parent by using 'null' with quotes for the created subtask $taskid: setSubTaskParent\n";
$asana_client->setSubTaskParent(array('parent' => 'null', 'task-id' => $subtask['id']));

// Get subtasks for this task
print "Get subtasks: getSubTasks\n";
$subtasks = $asana_client->getSubTasks(array('task-id' => $taskid));
print_r($subtasks);

// Task stories
print "Get task stories: getTaskStories\n";
$stories = $asana_client->getTaskStories(array('task-id' => $taskid));
print_r($stories);

print "Get single story: getStory\n";
if(count($stories) > 0){
	$storyid = $stories[0]['id'];
	$story = $asana_client->getStory(array('story-id' => $storyid));
	print_r($story);
}

print "Comment on task: addTaskComment\n";
$comment = $asana_client->addTaskComment(array('task-id' => $taskid, 'text' => "Hello, this is a comment"));
print_r($comment);

print "Get projects for this task: getProjectsForTask\n";
$projects = $asana_client->getProjectsForTask(array('task-id' => $taskid));
print_r($projects);

if ($cleanup) {
	// Task deletion
	print "Delete this subtask with a normal deleteTask\n";
	$data = $asana_client->deleteTask(array('task-id' => $subtask['id']));
	if(count($data) == 0){
		print "Subtask deleted\n";
	}

	// Task deletion
	print "Delete the new parent with a normal deleteTask\n";
	$data = $asana_client->deleteTask(array('task-id' => $newtaskid));
	if(count($data) == 0){
		print "New parent task deleted\n";
	}

	// Remove the created task
	print "Delete the main task with: deleteTask\n";
	$data = $asana_client->deleteTask(array('task-id' => $taskid));
	if(count($data) == 0){
		print "Task deleted\n";
	}
}