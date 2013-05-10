<?php

namespace AJT\Asana;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Exception\HttpException;
use Guzzle\Plugin\Backoff\AbstractErrorCodeBackoffStrategy;

/**
 * Strategy used to retry HTTP requests based on the response code and Retry After header.
 *
 * Retries 429 Rate Limit by default and waits for the Retry-After period before resuming.
 */
class HttpBackoffWithRetryAfterStrategy extends AbstractErrorCodeBackoffStrategy
{
    /**
     * {@inheritdoc}
     */
    protected function getDelay($retries, RequestInterface $request, Response $response = null, HttpException $e = null)
    {
        if ($response) {
            //Short circuit the rest of the checks if it was successful
            if ($response->isSuccessful()) {
                return false;
            } else {
                if ($response->getStatusCode() == 429) {
                    // Get the Retry After header and return it, so the backoff plugin waits for this time
                    return $response->getRetryAfter();
                }
                // Not a 429, then return null so eventual other backoff plugins can take it
                return null;
            }
        }
    }
}
