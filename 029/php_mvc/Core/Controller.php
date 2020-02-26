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
}














 ?>