<?php
	class AuthenticationController extends Controller
	{
		public function loginAction()
		{
			if (!isset($_POST['email']) || !isset($_POST['password'])) {
				echo self::render(__METHOD__, 'Вход');
				exit;
			}
			
			session_start();
			
			$email = $_POST['email'];
			$password = $_POST['password'];

			$exceptionText = null;
			if (!isset($email) ||
				!isset($password)) {
				$exceptionText = 'Одно или несколько полей ввода не заполнены.';
			} else  {
				try
				{
					$user = UserAccountService::login($email, $password);
					$_SESSION['validUserId'] = $user->getId();
					$this->redirectTo('id'.$user->getId().'/MyLinks');
				}
				catch (Exception $e) {
					$exceptionText = 'Ошибка входа. '.$e->getMessage();
				}
			}
			echo self::render(__METHOD__, 'Вход', ['exceptionText'=>$exceptionText]);
		}
		
		public function logoutAction()
		{
			session_start();
			unset($_SESSION['validUserId']);
			$this->redirectTo('');
		}
		
		public function registerAction()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				echo self::render(__METHOD__, 'Регистрация');
				exit;
			}
			session_start();
			
			$email = $_POST['email'];
			$firstName = $_POST['firstName'];
			$lastName = $_POST['lastName'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			
			$exceptionText = null;
			try
			{	
				UserAccountService::register($email, $firstName, $lastName, $password);
				//$_SESSION['validUserId'] = $user->id;
			} 
			catch (Exception $e)
			{
				$exceptionText = 'Ошибка регистрации. '.$e->getMessage();
			}
			
			echo self::render(__METHOD__, 'Регистрация', ['exceptionText'=>$exceptionText]);
		}
		
		public function repairPasswordAction()
		{
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				echo self::render(__METHOD__, 'Восстановление пароля');
				exit;
			}
			
			$email = $_POST['email'];
			
			$exceptionText = null;
			try
			{
				$newPassword = UserAccountService::resetPassword($email);
				UserAccountService::notifyPassword($email, $newPassword);
			}
			catch (Exception $e)
			{
				$exceptionText = 'Ошибка при восстановлении пароля. '.$e->getMessage();
			}

			echo self::render(__METHOD__, 'Восстановление пароля',['exceptionText'=>$exceptionText]);
		}
		
		public function changePasswordAction()
		{
			session_start();
			UserAccountService::checkValidUser();
			
			$oldPassword = $_POST['oldPassword'];
			$newPassword = $_POST['newPassword'];
			$newPassword2 =$_POST['newPassword2'];
			
			$exceptionText;
			try 
			{
				/*
				if (!filled_out($_POST)) {
					throw new Exception('Вы не заполнили форму корректно.');
				}
				
				if ($new_passwd != $new_passwd2) {
					throw new Exception('Введенные пароли не совпадают.');
				}
				
				if ((strlen($passwd) < MIN_PASSWORD_LENGTH) ||
					(strlen($passwd) > MAX_PASSWORD_LENGTH)) {
					throw new Exception('Новый пароль должен иметь длину минимум 6 и максимум 16 символов.');
				}
				*/
				UserAccountService::changePassword($_SESSION['validUserId'], $old_passwd, $new_passwd);
			}
			catch (Exception $e) 
			{
				$exceptionText = 'Ошибка при смене пароля. '.$e->getMessage();
			}

			
			echo self::render(__METHOD__, 'Восстановление пароля', $exceptionText);
		}
	}
?>