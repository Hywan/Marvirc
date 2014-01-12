# Marvirc

Marvirc is a dead **simple**, extremely **modular** and blazing **fast** IRC bot
(yup, that's it).

## Installation

Either you install Hoa, or you use Composer:

    $ composer install

## Usage

A CLI is able:

    $ marvirc join --socket    chat.freenode.org:6667 \
                   --username  Marvirc                \
                   --channel   '##marvirc-test'       \
                   --websocket 127.0.0.1:8889

That's all. Use `marvirc.bat` on Windows.

## Features

Marvirc has the following features.

### Based on Hoa

Hoa is a **modular**, **extensible** and **structured** set of PHP libraries.
Marvirc is based on the following awesome libraries: `Hoa\Irc`, `Hoa\Socket`,
`Hoa\Stream` and `Hoa\Core`. Moreover, Marvirc uses `Hoa\Websocket` for an
extreme modularity, see below.

Also, is works on Linux, BSD and even Windows (yup!).

### Custom actions on mentions, messages and private messages

Marvirc is able to react to mentions, messages and private messages. Actions are
attached to each category through simple classes. An action interface is
provided and asks to write only three methods: `getPattern`, `getUsage` and
`compute`. Based on the pattern, Marvirc will choose the appropriated actions to
run.

### Multi-channels

A single client instance can be present and interact on several channels at the
same time, including private discussions.

A list of channels where the bot can be invited can be provided. Thus, you can
control where the bot will interact when someone runs:

    > /invite Marvirc

The footprint of an instance is quite ridiculous. Thus, if you want to run
several clients, connected to several IRC servers, it is possible, discreet and
understated.

### Possession through WebSocket

A WebSocket server can run side-by-side with the IRC client. Every message
received by the WebSocket server is redirected verbatim on the IRC client. In
this way, every tools, such Git hooks, cron tasks, … is able to send a
notification on IRC, as soon as you have a WebSocket client. Fortunately for us,
a dead simple client exists, namely `hoa websocket:client` that uses a readline
(which works on a regular standard input, a pipe or a redirection). Thus:

    $ marvirc join --socket    chat.freenode.org:6667 \
                   --username  Marvirc                \
                   --channel   '##marvirc-test'       \
                   --websocket 127.0.0.1:8889
    $ echo 'You are awesome :-).' | hoa websocket:client --server 127.0.0.1:8889

And then, on `##marvirc-test`, you will see Marvirc saying `You are awesome
:-)`. Imagine a Git hook that notifies you about what you need… exciting aye?