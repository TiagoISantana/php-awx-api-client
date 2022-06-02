## AWX API Client for PHP

###### Installation

Download the files and run composer

`composer install`

Or add this library using packagist

`composer require tisantan/php-awx-api-client`

###### Running example.php

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

$awx = new \AWX\AWXConnector('Bearer  **********************','http://ansible.host/api/v2/');

print_r($awx->listHosts());