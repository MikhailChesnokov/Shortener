<?php
	class Controller
	{
		protected function render($actionName, $pageTitle, array $args = array())
		{
			list($controllerName, $actionName) = explode('::',$actionName);
			$actionName = str_replace('Action','',$actionName);
			$controllerName = str_replace('Controller','',$controllerName);
			
			$rootDirectory = $_SERVER['DOCUMENT_ROOT'];

			$viewPath = $rootDirectory.'\\app\\views\\'.$controllerName.'\\'.$actionName.'.php';
			$templatePath = $rootDirectory.'\\app\\views\\PAGE_TEMPLATE.php';
			
			if (!is_file($templatePath)) {
				throw new \InvalidArgumentException(sprintf('Template page is not found in "%s"', $viewPath));
			}
			if (!is_file($viewPath)) {
				throw new \InvalidArgumentException(sprintf('View "%s" not found in "%s"', $actionName, $viewPath));
			}

			extract($args, EXTR_PREFIX_SAME, 'view');

			ob_start();
			ob_implicit_flush(0);

			try {
				require($templatePath);
			} catch (Exception $e) {
				ob_end_clean();
				throw $e;
			}

			return ob_get_clean();
		}
		
		protected function redirectTo($url, $params = null)
		{
			header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/'.$url, true, 301 );
			exit;
		}
	}
?>