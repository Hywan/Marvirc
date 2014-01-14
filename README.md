# Marvirc

Marvirc is a dead **simple**, extremely **modular** and blazing **fast IRC bot**
(yup, that's it).

## Installation

Either you install Hoa, or you use Composer:

    $ composer install

## Usage

Marvirc can be run from CLI with `marvirc`. By default, `marvirc welcome` will
run. It lists the available commands. The only one for now is `marvirc join`.
The `join` command has the following options:

  * `-s`/`--socket`, the socket URI (default: `chat.freenode.org:6667`),
  * `-u`/`--username`, username of Marvirc,
  * `-c`/`--channel`, the first channel to join, with the leading `#`,
  * `-p`/`--password`, the password of the first channel (default: `null`, i.e.
    no password),
  * `-w`/`--websocket`, the WebSocket URI (default: `null`, i.e. no server will
    be started),
  * `-v`/`--verbose`, be verbose or not,
  * `-h`/`-?`/`--help`, the help.

So, to join the channel `#hoaproject` on Freenode with the `FakeMarvirc`
username, you will write:

    $ marvirc join --socket    chat.freenode.org:6667 \
                   --username  FakeMarvirc            \
                   --channel   '#hoaproject'

That's all. On Windows, simply use `marvirc.bat`.

**Tips**: use `nohup` to detach Marvirc from your current SSH session:

    $ nohup marvirc join …

## Features

Marvirc has the following features.

### Based on Hoa

[Hoa](http://hoa-project.net/) is a **modular**, **extensible** and
**structured** set of PHP libraries. Marvirc is based on the following awesome
libraries: [`Hoa\Irc`](https://github.com/hoaproject/Irc),
[`Hoa\Socket`](https://github.com/hoaproject/Socket),
[`Hoa\Stream`](https://github.com/hoaproject/Stream) and
[`Hoa\Core`](https://github.com/hoaproject/Core). Moreover, Marvirc uses
[`Hoa\Websocket`](https://github.com/hoaproject/Websocket) for an extreme
modularity, see below.

Also, it works on Linux, BSD and even Windows (yup!).

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