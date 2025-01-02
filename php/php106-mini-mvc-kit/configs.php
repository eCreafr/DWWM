<?php

$DB_SERVER = getenv("MVC_SERVER") ?: "localhost";
$DB_DATABASE = getenv("MVC_DB") ?: "php106";
$DB_USER = getenv("MVC_USER") ?: "root";
$DB_PASSWORD = getenv("MVC_TOKEN") ?: "";
$DEBUG = getenv("MVC_DEBUG") ?: true;

return array(
    "DB_USER" => $DB_USER,
    "DB_PASSWORD" => $DB_PASSWORD,
    "DB_DSN" => "mysql:host=$DB_SERVER;dbname=$DB_DATABASE;charset=utf8",
    "DEBUG" => $DEBUG
);
