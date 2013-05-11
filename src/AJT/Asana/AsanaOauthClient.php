<?php

namespace AJT\Asana;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
use Guzzle\Plugin\Backoff\BackoffPlugin;

/**
 * An Asana Client based on guzzle
 *
 * @link http://developer.asana.com/documentation
 * @package AJT\Asana
 *
 * @method array addProjectComment(array $args = array())
 * @method array addProjectToTask(array $args = array())
 * @method array addTaskComment(array $args = array())
 * @method array addTaskFollowers(array $args = array())
 * @method array createProject(array $args = array())
 * @method array createTag(array $args = array())
 * @method array createTask(array $args = array())
 * @method array createSubTask(array $args = array()) Creating a new subtask
 * @method array deleteProject(array $args = array())
 * @method array deleteTask(array $args = array())
 * @method array getProject(array $args = array()) get a specific project
 * @method array getProjects(array $args = array()) get all projects (either archived or active)
 * @method array getProjectsInWorkspace(array $args = array()) get all projects in this workspace
 * @method array getProjectStories(array $args = array()) get all stories of a specific project
 * @method array getStory(array $args = array()) Showing a single story
 * @method array getSubTasks(array $args = array()) Showing subtasks of a specific task
 * @method array getTag(array $args = array())
 * @method array getTags(array $args = array())
 * @method array getTagsInWorkspace(array $args = array())
 * @method array getTasksInWorkspace(array $args = array())
 * @method array getTasksForTag(array $args = array())
 * @method array getTask(array $args = array())
 * @method array getProjectsForTask(array $args = array())
 * @method array getTaskStories(array $args = array()) get all stories of a specific task
 * @method array getUsers(array $args = array())
 * @method array getUsersWithEmail(array $args = array())
 * @method array getUser(array $args = array())
 * @method array getUserMe(array $args = array())
 * @method array getUsersInWorkspace(array $args = array())
 * @method array getWorkspaces(array $args = array())
 * @method array removeProjectFromTask(array $args = array())
 * @method array removeTaskFollowers(array $args = array())
 * @method array renameWorkspace(array $args = array())
 * @method array setSubTaskParent(array $args = array())
 * @method array updateProject(array $args = array()) Update project
 * @method array updateTag(array $args = array())
 * @method array updateTask(array $args = array())
 *
 */
class AsanaOauthClient extends Client
{

    /**
     * Factory method to create a new AsanaClient
     *
     * The following array keys and values are available options:
     * - base_url: Base URL of web service
     * - api_key: API key
     * 
     * @link http://developer.asana.com/documentation/#Authentication for more information on the api token
     *
     * @param array|Collection $config Configuration data
     *
     * @return AsanaClient
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'https://app.asana.com/api/1.0',
            'debug' => false,
            'backoff' => true
        );
        $required = array('base_url');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);
        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/oauth.json');
        $client->setDescription($description);

		if($config->get('debug')){
			$client->addSubscriber(LogPlugin::getDebugPlugin());	
		}		
        if ($config->get('backoff')) {
            // Get a backoff plugin that takes the retry-after rate limit into account
            $backoffPlugin = new BackoffPlugin(new HttpBackoffWithRetryAfterStrategy());
            $client->addSubscriber($backoffPlugin);
        }
        return $client;
    }

	/**
     * Shortcut for executing Commands in the Definitions.
     *
     * @param string $method
     * @param array|null $args
     *
     * @return mixed|void
     *
     */
    public function __call($method, $args = null)
    {
        $commandName = ucfirst($method);

        $result = parent::__call($commandName, $args);

        // This result has both data and tokens and needs to be returned whole
        if ($method == 'getToken' || $method == 'refreshToken') {
            $this->setDefaultHeaders(array(
             'Authorization' => 'Bearer '.$result['access_token'] 
            ));
            return $result;
        }
        
        // Remove data field
        if (isset($result['data'])) {
        	return $result['data'];
        }
        return $result;
    }
}