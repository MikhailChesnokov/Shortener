<?php
	class RedirectorController extends Controller
	{
		public function tryRedirectAction($shortedLink) {
			$exceptionText = null;
			try
			{
				$initialLink = LinkService::shortedToInitial($shortedLink);

				if (!$initialLink) {
					header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Error404', true, 301 );
				} else {
					header( 'Location: '.$initialLink, true, 301 );
				}
				exit;
			}
			catch (Exception $e)
			{
				$exceptionText = 'Ошибка перенаправления. '.$e-getMessage();
			}
			echo self::render(__METHOD__,'Перенаправление',['exceptionText'=>$exceptionText]);
		}
	}
?>