<?php



namespace ieu;

use ieu\Http\Request;
use ieu\Http\RouterProvider;
use ieu\Http\Response;

class App extends Container\Container {

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
		
		if (!($response = $this['Router']->handle()) instanceof Response){
			$response = new Response('Error: Router does not deliver Response object!', 500);
		}

		$response->send();

		return $this;
	}
}