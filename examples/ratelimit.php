<?php
/**
 * A simple ratelimit tester that gets all individual tasks for all projects,
 * prints a dot for each task and easily runs into the 100 calls per minute limit 
 * the Asana API has. (429 Rate Limit Enforced)
 * (if you have at least 100 tasks in Asana off course)
 *
 * Avoid this if possible, don't do this in a normal script but get all tasks from a project 
 * with getTasksForProject and using the opt_fields field to get all necessary fields you need
 * see the completedvstotalratio.php file for an example
 */

require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;

/**
 * If you set this to false and have lots of tasks, the client will stop on a 429 rate limit error
 * If you set this to true, a backoff plugin will retry the task after the retry-after time has passed
 * @var boolean
 */
$backoff = true;

// Get the asana client with your asana api key
$asana_client = AsanaClient::factory(array('api_key' => $asana_api_key, 'backoff' => $backoff));

print "Get all active projects (over all workspaces): getProjects\n";
$projects = $asana_client->getProjects(
	array(
	)
);
//print_r($projects);

$total = 0;
foreach($projects as $project){
	print "\nGet all tasks for project " . $project['name'] ."\n";
	$tasksForProject = $asana_client->getTasksForProject(
		array(
			'project-id' => $project['id']
		)
	);	
	foreach($tasksForProject as $task){
		$task = $asana_client->getTask(array('task-id' => $task['id']));
		$total++;
		print ".";
	}
	if($total >= 205){
		print "We are 2 times over the 100 per minute limit, let's stop now\n";
		break;
	}
}
print "Retrieved $total tasks in all your active projects, you might have more...\n";

