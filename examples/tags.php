<?php
/**
 * CRUD tags
 * 
 * Create, get update and delete a tag
 */

require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;

// Get the asana client with your asana api key
$asana_client = AsanaClient::factory(array('api_key' => $asana_api_key));

/*
 * Change this to the id of the workspace you want to add the tag to
 *
 * You can use the get-workspaces.php file to get the workspace id's
 */
$workspace_id = 4785243965702; 

// Create a tag
print "createTag\n";
$tag = $asana_client->createTag(
	array(
		'name'  	  => 'Test Tag',
		'notes' 		  => "testing notes for tag",
		'workspace' 	  => $workspace_id,
	)
);
print_r($tag);
$tagid = $tag['id'];

// Get a tag
// @todo: figure out why this fails
/*print "getTag\n";
$tag = $asana_client->getTag(
	array(
		'tag-id'  	  => $tagid,
	)
);
print_r($tag);

print "updateTag\n";
$tag = $asana_client->updateTag(
	array(
		'name'  	 => 'Updated Test tag',
		'notes' 	 => "updated testing notes for tag",
		'tag-id' => $tagid
	)
);
print_r($tag);
*/

print "Get all tags in this workspace: getTagsInWorkspace\n";
$tags = $asana_client->getTagsInWorkspace(
	array(
		'workspace-id' => $workspace_id
	)
);
print_r($tags);

print "Get all tags (over all workspaces): getTags\n";
$tags = $asana_client->getTags(
	array(
	)
);
print_r($tags);