<?php
use Tgu\Aksenov\Blog\Post;
use Tgu\Aksenov\Blog\Person\Name;
use Tgu\Aksenov\Blog\Person\Person;
use Faker\Factory;

require_once __DIR__ . '/vendor/autoload.php';



// spl_autoload_register(function ($class) {
// 	$file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

// 	if (file_exists($file)) {
// 		require "$class.php";
// 	}
// });

$faker = Factory::create('ru_RU');

$post = new Post(
	new Person(
		new Name( $faker->firstName(),  $faker->lastName()),
		new DateTimeImmutable(),
	),
	$faker->realText,
);

print $post;