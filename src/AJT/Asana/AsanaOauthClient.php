<?php

namespace AJT\Asana;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * An Asana Oauth Client for oauth authentication using Asana connect
 *
 * @link http://developer.asana.com/documentation
 * @package AJT\Asana
 *
 * @method array getToken(array $args = array())
 * @method array refreshToken(array $args = array())
 *
 */
class AsanaOauthClient extends AbstractAsanaClient
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
        return array('base_url');
    }

    /**
     * Factory method to create a new AsanaOauthClient
     *
     * The following array keys and values are available options:
     * - base_url: Base URL of web service
     * 
     * @link http://developer.asana.com/documentation/#AsanaConnect for more information on the oauth method
     *
     * @param array|Collection $config Configuration data
     *
     * @return AsanaOauthClient
     */
    public static function factory($config = array())
    {
        $configuration = static::buildConfig($config);

        $client = new self($configuration->get('base_url'), $configuration);

        static::loadDefinitions($client);
        static::loadStandardSettings($client, $configuration);

        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/oauth.json');
        $client->setDescription($description);

        return $client;
    }

	/**
     * Shortcut for executing Commands in the Definitions.
     *
     * Handles token setting on commands and removes the data field from results
     * 
     * @param string $method
     * @param array|null $args
     *
     * @return mixed|void     *
     */
    public function __call($method, $args = null)
    {
        $result = parent::__call($method, $args);

        /**
         * If this is a token method, set the Authorization Bearer token and return the whole result
         * @todo: Check if the result was correct
         */
        if ($method == 'getToken' || $method == 'refreshToken') {
            $this->setDefaultHeaders(array(
             'Authorization' => 'Bearer '.$result['access_token'] 
            ));
            return $result;
        }
        
        // Remove the data field
        if (isset($result['data'])) {
        	return $result['data'];
        }
        return $result;
    }
}