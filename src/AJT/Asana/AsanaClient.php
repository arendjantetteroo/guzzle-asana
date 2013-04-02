<?php

namespace AJT\Asana;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;

use AJT\Asana\FixPostFieldToBodyPlugin;

/**
 * An Asana Client based on guzzle
 *
 * @link http://developer.asana.com/documentation
 * @package AJT\Asana
 *
 * @method array createTask(array $args = array())
 * @method array deleteTask(array $args = array())
 * @method array getWorkspaces(array $args = array())
 * @method array getTasksForWorkspace(array $args = array())
 * @method array getTask(array $args = array())
 * @method array getUsers(array $args = array())
 * @method array getUsersWithEmail(array $args = array())
 * @method array getUser(array $args = array())
 * @method array getUserMe(array $args = array())
 * @method array getUsersInWorkspace(array $args = array())
 * @method array renameWorkspace(array $args = array())
 * @method array updateTask(array $args = array())
 *  
 */
class AsanaClient extends Client
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
     * @return self
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'https://app.asana.com/api/1.0',
            'debug' => false
        );
        $required = array('api_key', 'base_url');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);
        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/services.json');
        $client->setDescription($description);

		$authPlugin = new CurlAuthPlugin($config->get('api_key'), '');
		$client->addSubscriber($authPlugin);

		if($config->get('debug')){
			$client->addSubscriber(LogPlugin::getDebugPlugin());	
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
        
        // Remove data field
        if (isset($result['data'])) {
        	return $result['data'];
        }
        return $result;
    }
}