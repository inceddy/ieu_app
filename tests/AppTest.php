<?php

use ieu\Container\Container;

//include __DIR__ . '/fixtures/SomeService.php';
//include __DIR__ . '/fixtures/SomeFactory.php';
//include __DIR__ . '/fixtures/SomeProviderWithOptions.php';

/**
 * @author  Philipp Steingrebe <philipp@steingrebe.de>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase {

	public function testContainerExtension()
	{
		$container = new ieu\Container\Container();
		$container->constant('hello', 'world');
		$container->value('foo', 'bar');

		$container2 = new ieu\Container\Container();
		$container2->constant('hello2', 'world2');
		$container2->value('foo2', 'bar2');

		$app = new ieu\App($container, $container2);

		// Test Constant (without provider)
		$app->config(['hello', 'hello2', function($hello, $hello2){
			$this->assertEquals('world', $hello);
			$this->assertEquals('world2', $hello2);
		}]);

		// Test value (with provider)
		$app->boot();
		$this->assertEquals('bar', $app['foo']);
		$this->assertEquals('bar2', $app['foo2']);

	}
}