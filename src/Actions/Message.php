<?php

namespace Nhn\Demo\Actions;

use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Support\MessageCollection;

class Message
{
    public function __construct(public string $folder)
    {
        // 
    }


    public function getMessages(): MessageCollection
    {
        $client = Client::account('default');
        $client->connect();

        $folder = $client->getFolder($this->folder);

        return $folder
            ->query()
            ->whereSince(today())
            ->get();
    }
}
