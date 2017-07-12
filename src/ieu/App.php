<?php



namespace ieu;

use ieu\Http\Request;
use ieu\Http\RouterProvider;
use ieu\Http\Response;

use InvalidArgumentException;

class App extends Container\Container {

	public const OPTIONS_ARRAY = 0;
	public const OPTIONS_JSON = 1;

	public function __construct(...$containers)
	{
		parent::__construct(...$containers);

		// Insert reuqest as constant
		$this->constant('Request', Request::native());

		// Inser router provider
		$this->provider('Router', new RouterProvider);
	}

	/**
	 * Alias for controller services.
	 * The controller providers can be accessed by `controllernameController`.
	 *
	 * @param  string $name
	 *    The name of the controller service
	 * @param  array  $dependenciesAndClassname
	 *    The dependencies and the classnmae of the controller
	 *
	 * @return self
	 * 
	 */
	
	public function controller(string $name, $dependenciesAndClassname) 
	{
		return $this->service($name . 'Controller', $dependenciesAndClassname);
	}

	public function options(string $name, $options, int $type = self::OPTIONS_ARRAY)
	{
		switch ($type) {
			case self::OPTIONS_ARRAY:
				if (is_file($options)) {
					$options = include($options);
				}
				break;

			case self::OPTIONS_JSON:
				if (is_file($options)) {
					$options = file_get_contents($options);
				}

				$options = json_decode($options, true);
				break;

			default:
				throw new InvalidArgumentException('Unkown option type');				
		}

		$this->constant($name . 'Options', $options);

		return $this;
	}


	public function run()
	{
		$this->boot();
		
		if (null === $response = $this['Router']->handle($this['Request'])) {
			$response = new Response('Internal server error', 500);
		}

		$response->send();

		return $this;
	}
}