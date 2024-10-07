<?php

include_once 'config.php';

$name = filter_input(INPUT_GET, 'name', FILTER_VALIDATE_REGEXP, [
  'flags' => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => '/^[^<>:"\/|?]+$/'],
]);

$targets = filter_input(INPUT_GET, 'targets', FILTER_VALIDATE_REGEXP, [
  'flags' => FILTER_NULL_ON_FAILURE,
  'options' => ['regexp' => '/^[\da-zA-Z.:\/_ -]+$/'],
]);
