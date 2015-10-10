#Modus/Config

The Modus/Config package is designed to provide a simple, basic configuration layer and DI container setup mechanism for any project.

##Installing

To install this package, run the following command:

```
composer require modus/config
```

##Dependencies
This module depends on the phpdotenv project. No other dependencies are installed.

##Usage
Using the configuration package is simple.

The package will automatically attempt to load up to three configuration files on each instantiation, based on the following rules:

1. config.php
2. The specific environment specified
3. local.php

For example, if the environment specified is "production", then Modus/Config will load config.php, then production.php, then local.php.

Modus/Config is smart enough to override the early files with later configuration files. So for example, if you load a file with an array key of 'database' set to null, and in a later configuration set that value to the name of your MySQL database, Modus/Config will compile a configuration that contains the correct value.

For example, imagine the two following configuration files:

```php
# config.php
return [
    'database' => null,
    'useMysql' => false,
    'userEmail' => 'user@example.com',
];
```

```
#local.php
return [
    'database' => 'myDb',
    'useMysql' => true,
    'storeEmail' => 'user2@example.com',
];
```

When Modus/Config evaluates them together, it will create the following configuration:

```php
#evaluated config
return [
    'database' => 'myDb',
    'useMysql' => true,
    'storeEmail' => 'user2@example.com',
    'userEmail' => 'user@example.com',
];
```

In addition, Modus/Config is recursive, so multidimensional arrays will be merged correctly.

##Environment Variables
Modus/Config allows you to optionally include the PHP Dotenv project, and define environment variables. This is useful for setting server-specific settings like passwords, keys, database names and server locations.
