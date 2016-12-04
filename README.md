# Console Events for Laravel Commands
[![Build Status](https://travis-ci.org/bmitch/consoleEvents.svg?branch=master)](https://travis-ci.org/bmitch/consoleEvents)

## What is it? ##
This package allows you to have events triggered by your Artisan Commands. The events available are:

`Bmitch\ConsoleEvents\Events\CommandStarting`
Triggered when an Artisan Command is starting.

`Bmitch\ConsoleEvents\Events\CommandTerminating`
Triggered when an Artisan Command is terminating.

## Why use it? ##
The main reason I created this package was for a use case where multiple commands were executed nightly and I wanted an easy way to log when they started and stopped. By hooking into these events it makes it easy.

## How to Install ##

### Add to composer ###
```
composer require bmitch/consoleevents
```

### Modify commands to extend custom class ###
In any command that you wish to trigger these events simply replace the:
```
use Illuminate\Console\Command;
```

with 
```
use Bmitch\ConsoleEvents\Command;
```

### Create and Register Listeners ###
Create two listeners within the `app/Listeners` folder like this:
```
<?php

namespace App\Listeners;

use Log;
use Bmitch\ConsoleEvents\Events\CommandStarting;

class CommandStartingListener
{
    /**
     * Handle the event.
     *
     * @param  CommandStarting  $commandStartingEvent
     * @return void
     */
    public function handle(CommandStarting $commandStartingEvent)
    {
        $name = $commandStartingEvent->command->getName();
        Log::info("Command {$name} starting");
    }
}
```
```
<?php

namespace App\Listeners;

use Log;
use Bmitch\ConsoleEvents\Events\CommandTerminating;

class CommandTerminatingListener
{
    /**
     * Handle the event.
     *
     * @param  CommandTerminating  $commandTerminatingEvent
     * @return void
     */
    public function handle(CommandTerminating $commandTerminatingEvent)
    {
        $command = $commandTerminatingEvent->command;
        $name = $command->getName();

        Log::info("Command {$name} stopping", [
            'commandName' => $name,
            'executionTime' => $command->getExecutionTime(),
            'exitCode' => $commandTerminatingEvent->exitCode,
        ]);
    }
}
```

Then register it within the `app\Providers\EventServiceProvider.php` class:

```
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    'Bmitch\ConsoleEvents\Events\CommandStarting' => [
        'App\Listeners\CommandStartingListener',
    ],
    'Bmitch\ConsoleEvents\Events\CommandTerminating' => [
        'App\Listeners\CommandTerminatingListener',
    ],
];
```

## Seeing the results ##
Run your command and check `laravel.log`. You should see an entry that was triggered by the `CommandStartingListener`. 

Something like:
```
[2016-12-02 00:16:11] local.INFO: Command foo:bar starting  
[2016-12-02 00:16:11] local.INFO: Command foo:bar stopping {"commandName":"foo:bar","executionTime":0.005375862121582,"exitCode":0} 
```

## Additional Methods ##
The `Bmitch\ConsoleEvents\Command` class automatically tracks how long it takes to execute and provides a `getExecutionTime()` method to make it easy to add this data when Logging data.

## Contributing ##
Please see [CONTRIBUTING.md](CONTRIBUTING.md)

## License ##

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
