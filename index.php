<?php
	require_once('./consts.php');
	require_once('./router/Router.php');
	require_once('./router/functions.php');
	require_once('./app/controllers/Controller.php');
	require_once('./app/controllers/HomeController.php');
	require_once('./app/controllers/LinkController.php');
	require_once('./app/controllers/AuthenticationController.php');
	require_once('./app/controllers/RedirectorController.php');
	require_once('./app/controllers/ErrorController.php');
	require_once('./app/models/UserAccountService.php');
	require_once('./app/models/UserAccount.php');
	require_once('./app/models/LinkService.php');
	require_once('./app/models/Link.php');
	require_once('./app/models/LinkShorterer.php');
	
	try
	{
		$router = new Router();
		
		//			name				pattern							controller:action
		$router->add('home',			'/',							'HomeController:indexAction');
		
		$router->add('links',			'/id(id:num)/MyLinks',			'LinkController:allAction');
		$router->add('addLink',			'/id(id:num)/AddLink',			'LinkController:addLinkAction');
		
		$router->add('login',			'/Login',						'AuthenticationController:loginAction');
		$router->add('login',			'/Logout',						'AuthenticationController:logoutAction');
		$router->add('register',		'/Register',					'AuthenticationController:registerAction');
		$router->add('forgotPasswd',	'/ForgotPassword',				'AuthenticationController:repairPasswordAction');
		$router->add('changePasswd',	'/id(id:num)/ChangePassword',	'AuthenticationController:changePasswordAction');
		
		$router->add('error',			'/Error(code:num)',				'ErrorController:errorAction');
		
		$router->add('redirector',		'/(shorted:any)',				'RedirectorController:tryRedirectAction');
		
		
		$userURI = GET_PATH_INFO();
		$route = $router->match($userURI);
		
		if (!$route) {
			$route = new MatchedRoute('ErrorController:errorAction',['code'=>404]);
		}
		
		$class = $route->getController();
		$method= $route->getAction();
		$params= $route->getParameters();

		call_user_func_array([new $class, $method], $params);
	}
	catch (Exception $e)
	{
		header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error500', true, 500);
		exit;
	}
?>