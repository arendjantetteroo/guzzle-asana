<?php

namespace AJT\Asana\Command;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Resource\Model;

use AJT\Asana\Plugin\ConvertPostFieldToBodyPlugin;

/**
 * Adds functionality to put commands:
 *
 * Needed to add a postfields to body converter. 
 * @todo There probably is a better way
 * 
 */
class PutCommand extends OperationCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $plugin = new ConvertPostFieldToBodyPlugin();
        $this->client->addSubscriber($plugin);

        parent::build();
    }
}