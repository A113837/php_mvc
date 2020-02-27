<?php  

namespace Core;

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

	 /**
     * Dispatch the route, creating the controller object and running the
     * action method
     *
     * @param string $url The route URL
     *
     * @return void
     */

     public function dispatch($url)
     {
     	if($this->match($url))
     	{
     		$url = $this->removeQueryStringVariables($url);


     		$controller = $this->params['controller'];
     		$controller = $this->convertToStudlyCaps($controller);
     		$controller = "App\Controllers\\$controller";

     		if(class_exists($controller))
     		{
     			$controller_object = new $controller($this->params);

     			$action = $this->params['action'];
     			$action = $this->convertToCamelCase($action);
     			
     			if(is_callable([$controller_object, $action]))
     			{
     				$controller_object->$action;
     			}
     			else
     			{
     				echo "method $action (in controller $controller) not found";
     			}
     		}
     		else
     		{
     			echo "Controller class $controller not found";

     		}
     	}
     	else
     	{
     		echo "no route matched.";
     	}
     }

      /**
     * Convert the string with hyphens to StudlyCaps,
     * e.g. post-authors => PostAuthors
     *
     * @param string $string The string to convert
     *
     * @return string
     */

      protected function convertToStudlyCaps()
      {
      	return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
      }

      /**
     * Convert the string with hyphens to camelCase,
     * e.g. add-new => addNew
     *
     * @param string $string The string to convert
     *
     * @return string
     */

      protected function convertToCamelCase($string)
      {
      	return lcfirst($this->convertToStudlyCaps($string));
      }

       /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     *   URL                           $_SERVER['QUERY_STRING']  Route
     *   -------------------------------------------------------------------
     *   localhost                     ''                        ''
     *   localhost/?                   ''                        ''
     *   localhost/?page=1             page=1                    ''
     *   localhost/posts?page=1        posts&page=1              posts
     *   localhost/posts/index         posts/index               posts/index
     *   localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable).
     *
     * @param string $url The full URL
     *
     * @return string The URL with the query string variables removed
     */

       protected function removeQueryStringVariables($url)
       {
	       	if($url != '')
	       	{
	       		$parts = explode('&', $url, 2);

	       		if(strpos($parts[0], '=') === false)
	       		{
	       			$url = $parts[0];
	       		}
	       		else
	       		{
	       			$url = '';
	       		}
	       	}

	       	return $url;
       }
}

?>