# Console Events for Laravel Commands

## What is it? ##
This package allows you to have events triggered by your Aritsan Commands. The events available are:

`Bmitch\ConsoleEvents\Events\CommandStarting`
Triggered when an Aritsan Command is starting.

`Bmitch\ConsoleEvents\Events\CommandTerminating`
Triggered when an Aritsan Command is terminating.

## Why use it? ##
The main reason I created this package was for a use case where multiple commands were executed nightly and I wanted an easy way to log when they start and stopped. By hooking into these events it makes it easy.

## How to Install ##

### Add to composer ###
```
composer require bmitch/consoleevents
```

### Modify commands to extend custom class ###
In any command that you wish to trigger these events simple replace the:
```
use Illuminate\Console\Command;
```

with 
```
use Bmitch\ConsoleEvents\Command;
```

### Create and Register a Listener ###
You can create a listener within the `app/Listeners` folder like this:
```
<?php

namespace App\Listeners;

use Log;
use Bmitch\ConsoleEvents\Events\CommandStarting;

class CommandStartingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommandStarting  $commandRunning
     * @return void
     */
    public function handle(CommandStarting $commandRunning)
    {
        Log::info("Command {$commandRunning->command} starting");
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
    ];
```

_Then do the same with the `Bmitch\ConsoleEvents\Events\CommandTerminating` if you wish to hook into that event._

## Seeing the results ##
Run your command and check `laravel.log`. You should see an entry that was triggered by the `CommandStartingListener`. 

Something like:
```
[2016-11-23 22:42:33] local.INFO: Command foo:bar starting  
```

## Contributing ##

## License ##

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
