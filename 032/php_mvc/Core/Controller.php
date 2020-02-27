<?php 

namespace Core;

abstract class Controller
{

	//parameters from the matched route @var array
	protected $route_params = [];


	//class constructor @param array $route_params Parameters from the route

	public function __construct($route_params)
	{
		$this->route_params = $route_params;
	}

	public function __call($name, $args)
	{
		$method = $name.'Action';

		if(method_exists($this, $method))
		{
			if($this->before() !== false)
			{
				call_user_func_array([$this, $method], $args);
				$this->after();
			}
			else
			{
				echo "method $method not found in Controller ".get_class($this);
			}
		}
	}

	protected function before()
	{

	}

	protected function after()
	{

	}
}















 ?>