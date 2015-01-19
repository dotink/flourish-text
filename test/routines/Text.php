<?php namespace Dotink\Lab
{
	use Dotink\Flourish;
	use Dotink\Parody\Mime;

	return [
		'setup' => function($data, $shared) {
			needs($data['root'] . '/src/Text.php');
			needs($data['root'] . '/../flourish-core/src/UTF8.php');

			$shared->text = new Flourish\Text();
		},

		'tests' => [

			/**
			 *
			 */
			'Dashize' => function($data, $shared) {
				$data = [
					'this is a test' => 'this-is-a-test',
					'this_is_a_test' => 'this-is-a-test',
					'thisIsATest'    => 'this-is-a-test',
					'ThisIsATest'    => 'this-is-a-test'
				];

				foreach ($data as $original => $transformed) {
					$text = $shared->text->create($original)->dashize();

					assert('Dotink\Flourish\Text::compose')
						-> using($text)
						-> equals($transformed);
				}
			},

			/**
			 *
			 */
			'Underscorize' => function($data, $shared) {
				$data = [
					'this is a test' => 'this_is_a_test',
					'this-is-a-test' => 'this_is_a_test',
					'thisIsATest'    => 'this_is_a_test',
					'ThisIsATest'    => 'this_is_a_test'
				];

				foreach ($data as $original => $transformed) {
					$text = $shared->text->create($original)->underscorize();

					assert('Dotink\Flourish\Text::compose')
						-> using($text)
						-> equals($transformed);
				}
			},

			/**
			 *
			 */
			'Camelize' => function($data, $shared) {
				$data = [
					'this is a test' => 'thisIsATest',
					'this_is_a_test' => 'thisIsATest',
					'this-is-a-test' => 'thisIsATest',
					'thisIsATest'    => 'thisIsATest',
					'ThisIsATest'    => 'thisIsATest',
				];

				foreach ($data as $original => $transformed) {
					$text = $shared->text->create($original)->camelize();

					assert('Dotink\Flourish\Text::compose')
						-> using($text)
						-> equals($transformed);
				}
			},

		]
	];
}
