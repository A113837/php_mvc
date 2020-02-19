<?php  

class Router
{
 //associative array of routes (the routing table)

	protected $routes = [];

	protected $params = [];


	public function add($route, $params)
	{
		$this->routes[$route] = $params;
	}

	public function getRoutes()
	{
		return $this->routes;
	}

	/**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     *
     * @param string $url The route URL
     *
     * @return boolean  true if a match found, false otherwise
     */

	public function match($url)
	{
		/*foreach ($this->routes as $route => $params) {
			if($url == $route)
			{
				$this->params = $params;
				return true;
			}
		}
		return false;*/

		$reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

		if(preg_match($reg_exp, $url, $matches))
		{
			//Get named capture group values
			$params = [];

			foreach($matches as $key => $match)
			{
				if(is_string($key))
				{
					$params[$key] = $match;
				}
			}

			$this -> params = $params;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
     * Get the currently matched parameters
     *
     * @return array
     */
	public function getParams()
	{
		return $this->params;
	}
}

?>
