CAS Authentication
============

Use CAS as authentication provider for Kanboard.<br>
Plugin originally developed for École des Ponts ParisTech needs.

Requirements
------------

- Kanboard >= 1.2.5

Installation
------------

You have the choice between 2 methods:

1. Download the zip file and decompress everything under the directory `plugins/CASAuth`
2. Clone this repository into the folder `plugins/CASAuth`

Note: Plugin folder is case-sensitive.

You also need to install jasig's phpCas library. Easiest way is to do it through composer. <br>
Through a terminal, inside your Kanboard's root directory, launch following command :
```
composer require jasig/phpCas 
```

You will also need to create a phpCas.log file inside a log folder :
```
mkdir log
cd log
touch phpCas.log
```
Make sure that newly created file and folder have required permissions for the application to write into them.


Configuration
------------

You need to define new parameters in your `config.php` :

```php
define('CAS_HOSTNAME', 'cas.example.com');
// Your CAS server port, make sure not to put quotes around port number
define('CAS_PORT', 443);
define('CAS_URI', 'cas');
```

You also need to make sure that both HIDE_LOGIN_FORM and DISABLE_LOGOUT are set to `true` (in your `config.php`).

You should now be ready to go and going to your-kanboard-url.fr, when not logged in, should redirect you to your CAS login page.

Contributors
------

- Jamaïca Servier ([@jamaicv](https://github.com/jamaicv))
