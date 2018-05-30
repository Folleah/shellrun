Example code:
```php
<?php
namespace Folleah\Shellrun;

require 'vendor/autoload.php';

// run php in interactive mode
$runtime = (new Command('php'))
    ->withArg('-a')
    ->runtime();

$runtime->write("echo 'test';");
$runtime->read();
/**
* Interactive mode enabled
* test
 */
```

