<?php

namespace App\Service;

use App\Helper\LoggerTrait;
use Nexy\Slack\Client;

class SlackClient
{
    use LoggerTrait;

    private $slack;

    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }

    public function sendMessage($message): void
    {
        $this->logInfo('Beaming a message to Slack', ['message' => $message]);

        $slackMessage = $this->slack->createMessage()
            ->from('Khan')
            ->withIcon(':ghost:')
            ->setText($message);
        $this->slack->sendMessage($slackMessage);
    }
}
