<?php

require_once __DIR__.'/vendor/autoload.php';

$awx = new \AWX\AWXConnector('Bearer  *************************','http://ansible.host/api/v2/');

print_r($awx->listHosts());