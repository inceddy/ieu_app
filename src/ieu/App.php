<?php



namespace ieu;

use ieu\Http\Request;
use ieu\Http\RouterProvider;

class App extends Container\Container {

	public function __construct()
	{
		parent::__construct();

		// Insert reuqest service
		$this->service('Request', [Request::CLASS]);

		// Inser router provider
		$this->provider('Router', new RouterProvider);
	}

	/**
	 * Alias for controller services.
	 * The controller providers can be accessed by `controllernameController`.
	 *
	 * @param  string $name                      The name of the controller service
	 * @param  array  $dependenciesAndClassname  The dependencies and the classnmae of the controller
	 *
	 * @return self
	 * 
	 */
	
	public function controller($name, $dependenciesAndClassname) 
	{
		return $this->service($name . 'Controller', $dependenciesAndClassname);
	}


	public function run()
	{
		$this->boot();
		$this['Router']->handle();
		return $this;
	}
}