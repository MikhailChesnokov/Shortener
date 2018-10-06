<?php
	class LinkController extends Controller
	{
		public function allAction()
		{
			session_start();
			UserAccountService::checkValidUser();
			
			$links = LinkService::getAll();
			
			echo self::render(__METHOD__, 'Мои ссылки',['links'=>$links]);
		}
		
		public function addLinkAction()
		{
			session_start();
			UserAccountService::checkValidUser();
			
			$exceptionText = null;
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				try
				{
					$initialLink = $_POST['link'];
					LinkService::addNew($initialLink);
					$this->redirectTo('id'.$_SESSION['validUserId'].'/MyLinks');
				}
				catch (Exception $e)
				{
					$exceptionText = 'Ошибка добавления ссылки. '.$e->getMessage();
				}
			}
			echo self::render(__METHOD__, 'Добавить ссылку',['exceptionText'=>$exceptionText,]);
		}
	}
?>