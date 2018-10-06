<?php
	class HomeController extends Controller
	{
		public function indexAction()
		{
			echo self::render(__METHOD__, 'Home');
		}
	}
?>