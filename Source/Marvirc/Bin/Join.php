<?php

namespace Marvirc\Bin;

use Hoa\Console;
use Hoa\File\Finder;
use Hoa\Irc;
use Hoa\Socket;
use Hoa\Websocket;

class Join extends Console\Dispatcher\Kit
{
    protected $options = [
        ['socket',         Console\GetOption::REQUIRED_ARGUMENT, 's'],
        ['username',       Console\GetOption::REQUIRED_ARGUMENT, 'u'],
        ['channel',        Console\GetOption::REQUIRED_ARGUMENT, 'c'],
        ['password',       Console\GetOption::REQUIRED_ARGUMENT, 'p'],
        ['channel-filter', Console\GetOption::REQUIRED_ARGUMENT, 'f'],
        ['websocket',      Console\GetOption::REQUIRED_ARGUMENT, 'w'],
        ['verbose',        Console\GetOption::NO_ARGUMENT,       'v'],
        ['help',           Console\GetOption::NO_ARGUMENT,       'h'],
        ['help',           Console\GetOption::NO_ARGUMENT,       '?']
    ];



    public function main()
    {
        $socket    = 'tcp://chat.freenode.org:6667';
        $username  = null;
        $channel   = null;
        $password  = null;
        $filter    = '/^$/';
        $websocket = null;
        $verbose   = false;

        while (false !== $c = $this->getOption($v)) {
            switch ($c) {

            case 's':
                $socket = 'tcp://' . $v;

              break;

            case 'u':
                $username = $v;

              break;

            case 'c':
                $channel = $v;

              break;

            case 'p':
                $password = $v;

              break;

            case 'f':
                $filter = $v;

              break;

            case 'w':
                $websocket = 'tcp://' . $v;

              break;

            case 'v':
                $verbose = $v;

              break;

            case 'h':
            case '?':
                return $this->usage();

              break;

            case '__ambiguous':
                $this->resolveOptionAmbiguity($v);

              break;
        }
        }

        if (empty($username) || empty($channel)) {
            return $this->usage();
        }

        $self      = $this;
        $group     = new Socket\Connection\Group();
        $client    = new Irc\Client(new Socket\Client($socket));
        $wsServer  = null;

        if (null !== $websocket) {
            $wsServer = new Websocket\Server(new Socket\Server($websocket));
            $wsServer->on('message', function ($bucket) use ($channel, $client) {
                $data = $bucket->getData();
                $client->say($data['message'], $channel);

                return;
            });
            $group[]  = $wsServer;
        }

        $group[] = $client;

        // Open.
        $client->on('open', function ($bucket) use ($username, $channel) {
            $bucket->getSource()->join($username, $channel);

            return;
        });

        // Join.
        $client->on('join', function ($bucket) {
            $data = $bucket->getData();
            echo '[', $data['channel'], '] Joined!', "\n";

            return;
        });

        $client->on('private-message', function ($bucket) use ($self) {
            $data = $bucket->getData();
            $bucket->getSource()->say(
                $self->getAnswer('PrivateMessage', $data, 'What?'),
                $data['from']['nick']
            );

            return;
        });

        $client->on('mention', function ($bucket) use ($self) {
            $data   = $bucket->getData();
            $answer = $self->getAnswer('Mention', $data, null);

            if (null !== $answer) {
                $bucket->getSource()->say(
                    $data['from']['nick'] . ': ' . $answer
                );
            }

            return;
        });

        $client->on('message', function ($bucket) use ($self) {
            $data   = $bucket->getData();
            $answer = $self->getAnswer('Message', $data, null);

            if (null !== $answer) {
                $bucket->getSource()->say(
                    $data['from']['nick'] . ': ' . $answer
                );
            }

            return;
        });

        // Kick.
        $verbose and
        $client->on('kick', function ($bucket) {
            $node = $bucket->getSource()->getConnection()->getCurrentNode();
            echo '[', $node->getChannel(), '] Kicked :-(.', "\n";

            return;
        });

        // Invite.
        $client->on('invite', function ($bucket) use ($filter) {
            $data = $bucket->getData();

            if (0 === preg_match($filter, $data['invitation_channel'])) {
                return;
            }

            $bucket->getSource()->join(
                $bucket->getSource()->getConnection()->getCurrentNode()->getUsername(),
                $data['invitation_channel']
            );

            return;
        });

        // Other messages.
        $verbose and
        $client->on('other-message', function ($bucket) {
            $data = $bucket->getData();
            $node = $bucket->getSource()->getConnection()->getCurrentNode();

            echo '[', $node->getChannel(), '] ';
            Console\Cursor::colorize('foreground(green)');
            echo $data['line'];
            Console\Cursor::colorize('normal');
            echo "\n";

            return;
        });

        // Error.
        $client->on('error', function ($bucket) {
            $data = $bucket->getData();
            $node = $bucket->getSource()->getConnection()->getCurrentNode();

            echo '[', $node->getChannel(), '] ';
            Console\Cursor::colorize('foreground(red)');
            echo $data['exception']->raise(true);
            Console\Cursor::colorize('normal');
            echo "\n";

            return;
        });

        // Message.
        $client->on('message', function ($bucket) use ($self) {
            $data   = $bucket->getData();
            $answer = null;

            if (0 !== preg_match('/!(?=\b)/', $data['message'])) {
                $answer = $self->getAnswer('Mention', $data, null);
            }

            if (null !== $answer) {
                $bucket->getSource()->say($answer);
            }

            return;
        });

        // Here we go.
        $group->run();

        return;
    }

    public function usage()
    {
        echo 'Usage   : join <options>', "\n",
             'Options :', "\n",
             $this->makeUsageOptionsList([
                 's'    => 'Socket (default: chat.freenode.org:6667).',
                 'u'    => 'Username.',
                 'c'    => 'Channel (with the leading #).',
                 'p'    => 'Password.',
                 'f'    => 'Channels that can be joined (a PCRE pattern, ' .
                           'with delimiters and options).',
                 'w'    => 'Socket for the WebSocket server (default: null, ' .
                           'so no server).',
                 'v'    => 'Verbose.',
                 'help' => 'This help.'
             ]);

        return;
    }

    public function getAnswer($messageType, array $data, $default = null)
    {
        static $_cache = [];

        if (!isset($_cache[$messageType])) {
            $path   = dirname(__DIR__) . DS . 'Action' . DS . $messageType;
            $finder = new Finder();
            $finder->in($path)
                   ->files()
                   ->name('#\.php$#');

            $collect = [];

            foreach ($finder as $entry) {
                $classname = 'Marvirc\Action\\' . $messageType . '\\' .
                             substr($entry->getBasename(), 0, -4);
                $collect[$classname] = $classname::getPattern();
            }

            $_cache[$messageType] = $collect;
        }

        foreach ($_cache[$messageType] as $classname => $pattern) {
            if (0 !== preg_match($pattern, $data['message'])) {
                return $classname::compute($data);
            }
        }

        return $default;
    }
}
