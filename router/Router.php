<?php
	require_once('UrlMatcher.php');

	class Router
	{
		private $routes = array();
		private $matcher;
		private $generator;
		
		public function __construct()
		{
			$this->matcher = new UrlMatcher();
		}
		
		public function add($routeName, $pattern, $controllerAction)
		{
			/*
			$this->routes[$routeName] = 
			[
				'pattern' => $pattern,
				'controllerAction' => $controllerAction
			];
			*/
			$this->matcher->register($pattern, $controllerAction);
		}
		
		public function match($uri)
		{
			return $this->getMatcher()->match($uri);
		}
		
		private function getMatcher()
		{
			return $this->matcher;
		}
	}
?>