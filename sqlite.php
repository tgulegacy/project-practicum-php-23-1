<?php
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$connection->exec(
	"INSERT INTO users (first_name, last_name) VALUES ('Ivan', 'Ivanov')"
);