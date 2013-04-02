<?php

require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Asana\AsanaClient;

// Get the asana client with your asana api key
$asana_client = AsanaClient::factory(array('api_key' => $asana_api_key));

// Get all users
print "getUsers\n";
$users = $asana_client->getUsers(array());
foreach ($users as $user) {
	print $user['id'] . ' - ' . $user['name'] . "\n";
}

// Get all users with emailadress
print "\ngetUsersWithEmail\n";
$users = $asana_client->getUsersWithEmail(array());
foreach ($users as $user) {
	print $user['id'] . ' - ' . $user['name'] . ' - ' . $user['email']. "\n";
}

// Get user
print "\ngetUser\n";
$users = $asana_client->getUsers(array());
if (count($users) > 0) {
	$userid = $users[0]['id'];
	print $userid . "\n";
	$user = $asana_client->getUser(array('user-id' => $userid));
	print $user['id'] . ' - ' . $user['name'] . ' - ' . $user['email'] . "\n";
	print "This user has " . count($user['workspaces']) . " workspaces\n";
	foreach ($user['workspaces'] as $workspace) {
		print $workspace['id'] . ' - ' . $workspace['name'] . "\n";
	}
}

// Get me
print "\ngetMe\n";
	$user = $asana_client->getUserMe(array());
	print $user['id'] . ' - ' . $user['name'] . ' - ' . $user['email'] . "\n";
	print "This user has " . count($user['workspaces']) . " workspaces\n";
	foreach ($user['workspaces'] as $workspace) {
		print $workspace['id'] . ' - ' . $workspace['name'] . "\n";
	}

// Get users in workspace
print "\nGetUsersInWorkspace\n";
if (isset($user['workspaces']) && count($user['workspaces'] > 0)) {
	print "Get users in Workspace: " . $user['workspaces'][0]['id'] . "\n";
	$users = $asana_client->getUsersInWorkspace(array('workspace-id' => $user['workspaces'][0]['id']));
	if (count($users) > 0) {
		foreach ($users as $user) {
			print $user['id'] . ' - ' . $user['name'] . "\n";
		}
	}
}

