<?php
	class MatchedRoute
	{
		private $controller;
		private $action;
		private $params;
		
		public function __construct($controller, array $parameters = array())
		{
			list($this->controller, $this->action) = explode(':', $controller, 2);
			$this->params = $parameters;
		}
		
		public function getController()
		{
			return $this->controller;
		}
		
		public function getAction()
		{
			return $this->action;
		}
		
		public function getParameters()
		{
			return $this->params;
		}
	}
?>