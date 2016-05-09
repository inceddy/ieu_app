<?php

namespace ieu\App\Provider;
use ieu\Container\Provider;


/**
 * A provider for Twig.
 */

class TwigProvider extends Provider {

	private $loader;

	private $options;

	public function __construct() 
	{
		$this->options = [];

		if (!class_exists('Twig_Environment')) {
			throw new LogicException("The Twig package is not installed/available.");
		}

		$this->factory = ['Injector', [$this, 'factory']];
	}

	public function factory($injector)
	{
		if (!isset($this->loader)) {
			throw new LogicException("The Twig loader is not set");
		}

		if (is_string($this->loader)) {
			$this->setLoader($injector->get($this->loader));
		}

		return new Twig_Environment($this->loader, $this->options);
	}

	public function setOption($key, $value)
	{
		$this->options[$key] = $value;
		return $this;
	}

	public function setOptions(array $options)
	{
		foreach ($options as $key => $option) {
			$this->setOption($key, $option);
		}

		return $this;
	}

	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Sets the loader or the name of the loader-service for
	 * the Twig enviroment.
	 *
	 * @param string|Twig_Loader_Interface $loader the loader or 
	 *                                             the name of the loader-service
	 * @return self
	 * 
	 */
	
	public function setLoader($loader) 
	{
		$this->loader = $loader;
		return $this;
	}

	public function getLoader()
	{
		return $this->loader;
	}
}