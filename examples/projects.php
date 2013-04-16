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

/*
 * Change this to the id of the workspace you want to add the project to
 *
 * You can use the get-workspaces.php file to get the workspace id's
 */
$workspace_id = 4785243965702; 

// Create a project
print "createProject\n";
$project = $asana_client->createProject(
	array(
		'name'  	  => 'Test Project',
		'notes' 		  => "testing notes",
		'workspace' 	  => $workspace_id,
	)
);
print_r($project);
$projectid = $project['id'];

// Get a project
print "getProject\n";
$project = $asana_client->getProject(
	array(
		'project-id'  	  => $projectid,
	)
);
print_r($project);

print "updateProject\n";
$project = $asana_client->updateProject(
	array(
		'name'  	 => 'Updated Test Project',
		'notes' 	 => "updated testing notes",
		'archived'   => true, // If you don't want to archive, don't include this field
		'project-id' => $projectid
	)
);
print_r($project);

print "Get all projects in this workspace: getProjectsInWorkspace\n";
$projects = $asana_client->getProjectsInWorkspace(
	array(
		'workspace-id' => $workspace_id
	)
);
print_r($projects);

print "Get all archived projects in Workspace: getProjectsInWorkspace\n";
$projects = $asana_client->getProjectsInWorkspace(
	array(
		'workspace-id' => $workspace_id,
		'archived' => true
	)
);
print_r($projects);

print "Get all active projects (over all workspaces): getArchivedProjects\n";
$projects = $asana_client->getProjects(
	array(
	)
);
print_r($projects);


print "Get all archived projects (over all workspaces): getArchivedProjects\n";
$projects = $asana_client->getProjects(
	array(
		'archived' => true
	)
);
print_r($projects);


print "DeleteProject\n";
$project = $asana_client->deleteProject(
	array(
		'project-id' => $projectid
	)
);
if(count($project) == 0){
	print "Task deleted\n";
}