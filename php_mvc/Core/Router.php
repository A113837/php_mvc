<?php  

class Router
{
 //associative array of routes (the routing table)

	protected $routes = [];


	public function add($route, $params)
	{
		$this->routes[$route] = $params;
	}

	public function getRoutes()
	{
		return $this->routes;
	}
}

?>