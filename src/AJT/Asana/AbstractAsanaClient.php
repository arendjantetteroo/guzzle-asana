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
 * @method array getAttachment(array $args = array()) get a specific attachment
 * @method array getAttachmentsForTask(array $args = array()) get all attachments for a specific task
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
 * @method array uploadAttachment(array $args = array()) Upload an attachment to a task
 *
 */
abstract class AbstractAsanaClient extends Client
{

    /**
     * Returns the default values for incoming configuration parameters
     *
     * @return array
     */
    public static function getDefaultParameters()
    {
        return array();
    }

    /**
     * Defines the configuration parameters that are required for client
     *
     * @return array
     */
    public static function getRequiredParameters()
    {
        return array();
    }

    /**
     * Builds array of configurations into final config
     *
     * @param array $config
     * @return Collection
     */
    public static function buildConfig($config = array())
    {
        $default  = static::getDefaultParameters();
        $required = static::getRequiredParameters();
        $config = Collection::fromConfig($config, $default, $required);

        return $config;
    }

    /**
     * Loads API method definitions
     *
     * @param \Guzzle\Service\Client $client
     */
    public static function loadDefinitions(Client $client)
    {
        $serviceDescriptions = ServiceDescription::factory(__DIR__ . '/services.json');
        $client->setDescription($serviceDescriptions);
    }

    /**
     * Load standard setting, like debug log plugin and backoff retry plugin
     * 
     * @param \Guzzle\Service\Client    $client
     * @param \Guzzle\Common\Collection $config
     */
    public static function loadStandardSettings(Client $client, Collection $config)
    {
        if($config->get('debug')){
            $client->addSubscriber(LogPlugin::getDebugPlugin());    
        }       
        if ($config->get('backoff')) {
            // Get a backoff plugin that takes the retry-after rate limit into account
            $backoffPlugin = new BackoffPlugin(new HttpBackoffWithRetryAfterStrategy());
            $client->addSubscriber($backoffPlugin);
        }
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

        return parent::__call($commandName, $args);
    }
}