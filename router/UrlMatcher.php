<?php
	require_once('MatchedRoute.php');
	
	class UrlMatcher
	{
		private $routes = array();
		private $pregPatterns = 
		[
			'num' => '[0-9]+',
			'str' => '[a-zA-Z\.\-_%]+',
			'any' => '[a-zA-Z0-9\.\-_%]+'
		];
		
		public function register($route, $controllerAction)
		{
			$converted = $this->convertRoute($route);
			$this->routes[$converted] = $controllerAction;
		}
		
		public function match($uri)
		{
			if (array_key_exists($uri, $this->routes)) {
				return new MatchedRoute($this->routes[$uri]);
			}
			
			return $this->matchByPattern($uri);
		}
		
		
		private function convertRoute($route)
		{
			if ($this->containsPattern($route) == false) {
				return $route;
			}

			return preg_replace_callback('#\((\w+):(\w+)\)#', array($this, 'replaceRoute'), $route);
		}
		
		private function replaceRoute($match)
		{
			$name = $match[1];
			$userPattern = $match[2];

			return '(?<'.$name.'>'.strtr($userPattern, $this->pregPatterns).')';
		}
		
		private function matchByPattern($uri)
		{
			foreach($this->routes as $route => $controller) {
				if ($this->containsPattern($route)) {
					$pattern = '#^'.$route.'$#s';
					
					if (preg_match($pattern, $uri, $parameters)) {
						return new MatchedRoute($controller, $this->processParameters($parameters));
					}
				}
			}
		}
		
		private function processParameters($parameters)
		{
			foreach ($parameters as $key => $value) {
				if (is_int($key)) {
					unset($parameters[$key]);
				}
			}
			return $parameters;
		}
		
		private function containsPattern($uri)
		{
			return (strpos($uri, '(') !== false);
		}
		
	}
?>