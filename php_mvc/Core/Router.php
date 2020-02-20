<?php  

class Router
{
 //associative array of routes (the routing table)

	protected $routes = [];

	protected $params = [];


	public function add($route, $params = [])
	{
		// Convert the route to a regular expression: escape forward slashes
		$route = preg_replace('/\//', '\\/', $route);

		// Convert variables e.g. {controller}
		$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

		//convert variables with custom regular expression e.g {id: \d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
		


		 // Add start and end delimiters, and case insensitive flag
		$route = '/^'.$route.'$/i';

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

		// Match to the fixed URL format /controller/action
		//$reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";
		foreach($this->routes as $route => $params)
		{
			if(preg_match($route, $url, $matches))
			{
				//get named capture group values
				//$params = [];
				foreach($matches as $key => $match)
				{
					if(is_string($key))
					{
						$params[$key] = $match;
					}
				}

				$this->params = $params;
				return true;
			}

		}

		return false;
		
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