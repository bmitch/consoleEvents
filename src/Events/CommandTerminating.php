<?php

namespace Bmitch\ConsoleEvents\Events;

use Symfony\Component\Console\Input\InputInterface;

class CommandTerminating
{
    /**
     * The command that is terminating.
     * @var object
     */
    public $command;

    /**
     * The console input.
     *
     * @var string
     */
    public $input;

    /**
     * The command exit code.
     *
     * @var integer
     */
    public $exitCode;



    /**
     * Create a new event instance.
     * @param object         $command  The command that is terminating.
     * @param InputInterface $input    Input Interface.
     * @param integer        $exitCode Command exit code.
     */
    public function __construct($command, InputInterface $input, $exitCode)
    {
        $this->command = $command;
        $this->input = $input;
        $this->exitCode = $exitCode;
    }
}
