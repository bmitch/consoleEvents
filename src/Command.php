<?php

namespace Bmitch\ConsoleEvents;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Console\Command as LaravelCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends LaravelCommand
{

    /**
     * The Event Dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    public function __construct()
    {
        parent::__construct();

        $this->events = \App::make(Dispatcher::class);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $commandName = $this->getName();

        $this->events->fire(
            new Events\CommandStarting($commandName, $input)
        );

        $exitCode = parent::run($input, $output);

        $this->events->fire(
            new Events\CommandTerminating($commandName, $input, $exitCode)
        );

        return $exitCode;
    }

}
