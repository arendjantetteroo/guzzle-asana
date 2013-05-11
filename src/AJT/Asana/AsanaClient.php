<?php

namespace AJT\Asana;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
use Guzzle\Plugin\Backoff\BackoffPlugin;

/**
 * An Asana Client based on guzzle for Asana Api Key Authentication
 *
 * @link http://developer.asana.com/documentation
 * @package AJT\Asana
 */
class AsanaClient extends AbstractAsanaClient
{

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getDefaultParameters()
    {
        return array(
            'base_url' => 'https://app.asana.com/api/1.0',
            'debug' => false,
            'backoff' => true
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getRequiredParameters()
    {
        return array('api_key', 'base_url');
    }

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
        $configuration = static::buildConfig($config);

        $client = new self($configuration->get('base_url'), $configuration);

        $authPlugin = new CurlAuthPlugin($configuration->get('api_key'), '');
        $client->addSubscriber($authPlugin);

        static::loadDefinitions($client);
        static::loadStandardSettings($client, $configuration);

        return $client;
    }

	/**
     * Shortcut for executing Commands in the Definitions.
     * Removes the datafield from results
     *
     * @param string $method
     * @param array|null $args
     *
     * @return mixed|void
     *
     */
    public function __call($method, $args = null)
    {
        $result = parent::__call($method, $args);
        
        // Remove data field
        if (isset($result['data'])) {
        	return $result['data'];
        }
        return $result;
    }
}