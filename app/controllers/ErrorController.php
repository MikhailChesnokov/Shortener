<?php
	class ErrorController extends Controller
	{
		public function errorAction($errorCode)
		{	
			echo self::render(__METHOD__,'Ошибка',['errorCode'=>$errorCode]);
		}
	}
?>