<?php
/**
 * CRUD projects
 * 
 * Create, get update and delete a project
 */

require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;

// Get the asana client with your asana api key
$asana_client = AsanaClient::factory(array('api_key' => $asana_api_key));

print "Get all active projects (over all workspaces): getProjects\n";
$projects = $asana_client->getProjects(
	array(
	)
);
//print_r($projects);

$count = $completed = $total = $ratio = 0;
foreach($projects as $project){
	print "Get all tasks for project " . $project['name'] ." : getTasksForProject\n";
	$tasksForProject = $asana_client->getTasksForProject(
		array(
			'project-id' => $project['id'],
			'opt_fields' => 'completed'
		)
	);	
	foreach($tasksForProject as $task){
		if($task['completed'] == 1){
			$completed++;
		}
		$total++;
	}
	// Stop after 5 projects
	$count++;
	if($count > 5){
		break;
	}
}
if($total > 0){
	$ratio = number_format($completed/$total * 100,2);	
}

print "You have $completed tasks out of a total of $total task, a ratio of $ratio\n";

