<?php
	class UserAccountService
	{
		public static function login($email, $password) 
		{
			$connection = UserAccountService::getConnection();
	
			$queryResult = $connection->query("select *
											   from user
											   where email ='".$email."' 
											   and password = sha1('".$password."')");
			if ($queryResult == false) {
				throw new Exception('Невозможно подключиться к базе данных.');
			}
			
			if ($queryResult->num_rows > 1) {
				throw new Exception('Найдено несколько одинаковых аккаунтов.');
			}
			if ($queryResult->num_rows < 1) {
				throw new Exception('Неверный адрес электронной почты или пароль.');
			}
			
			return UserAccountService::createUserByQueryResult($queryResult);
		}
		
		public static function register($email, $firstName, $lastName, $password)
		{
			$connection = UserAccountService::getConnection();
	
			$queryResult = $connection->query("select * 
											   from user 
											   where email='".$email."'");
			
			if ($queryResult === false) {
				throw new Exception('Невозможно выполнить запрос к БД.');
			}
			if ($queryResult->num_rows > 1) {
				throw new Exception('Найдено более одного совпадения электронной почты в БД.');
			}
			if ($queryResult->num_rows == 1) {
				throw new Exception('Пользователь с таким адресом электронной почты уже существует.');
			}
			
			$queryResult = $connection->query("insert into user values 
											   (NULL,'".$email."', '".$firstName."', '".$lastName."', sha1('".$password."'))");
			echo $connection->error;
			if ($queryResult === false) {
				throw new Exception('Невозможно сохранить данные в БД.');
			}
			
			return true;
		}
		
		public static function resetPassword($email)
		{
			$newPassword = UserAccountService::getRandomSequence(GENERATED_PASSWORD_LENGTH);
	
			if ($newPassword === false) {
				throw new Exception('Невозможно сгенерировать новый пароль для пользователя '.$email.'.');
			}
			
			$connection = UserAccountService::getConnection();
			$queryResult = $connection->query("select *
											   from user
											   where email='".$email."'");
			if ($queryResult->num_rows < 1) {
				throw new Exception('Пользователя с электронной почтой "'.$email.'" не существует.');
			}
			$queryResult = $connection->query("update user
											   set password = sha1('".$newPassword."')
											   where email = '".$email."'");
			
			if (!$queryResult) {
				throw new Exception('Невозможно изменить существующий пароль для пользователя '.$email.'.');
			} else {
				return $newPassword;
			}
		}
		
		public static function notifyPassword($email, $newPassword)
		{
			$connection = UserAccountService::getConnection();
			$queryResult = $connection->query("select * 
											   from user
											   where email='".$email."'");
			
			if ($queryResult === false) {
				throw new Exception('Невозможно подключиться к БД, либо не найден пользователь с адресом электронной почты '.$email);
			}
			if ($queryResult->num_rows == 0) { 
				throw new Exception('Пользователь с адресом электронной почты '.$email.' не найден.');
			}
			
			$from = "From: ".RECOVERY_PASSWORD_EMAIL_FROM." \r\n";
			$subject = RECOVERY_PASSWORD_EMAIL_SUBJECT;
			$message = "Ваш текущий пароль для входа в систему был изменен на ".$newPassword.". \r\n";
			

			if (mail($email, $subject, $message, $from) == false) {
				throw new Exception('Не удалось отправить новый пароль по электронной почте.');
			}
			return true;
		}
		
		public function changePassword($userId, $oldPassword, $newPassword)
		{
			//login($username, $old_password);
			$connection = getConnection();
			$queryResult = $connection->query("update user 
											   set passwd = sha1('".$new_password."')
											   where id='".$userId."'");
			if ($queryResult === false) {
				throw new Exception('Пароль не может быть изменен.');
			} else {
				return true;
			}
		}
		
		public static function checkValidUser()
		{
			if (isset($_SESSION['validUserId'])) {
				//echo "Вы вошли в систему под именем ".$_SESSION['validUserId'].".<br />";
			} else {
				header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/Login', true, 301 );
				exit;
				
				header('HTTP/1.1 401 Unauthorized', true, 401);
				echo '401 Unauthorized';
				exit;
			}
		}
		
		private static function getRandomSequence($length)
		{
			$sequence = '';
			// possile characters set except few similar symbols: 
			// 0(digit)-O(letter), 
			// 1(one)-l(lower L)
			$possible_characters = 'abcdefghijkmnopqrstuvwxyz'.
								   'ABCDEFGHIJKLMNPQRSTUVWXYZ'.
								   '23456789';
			$possible_characters_array = str_split($possible_characters);
			
			shuffle($possible_characters_array);
			foreach(array_rand($possible_characters_array, $length) as $key) {
				$sequence .= $possible_characters_array[$key];
			}
			
			return $sequence;
		}
		
		private static function createUserByQueryResult($queryResult)
		{
			try
			{
				$queryData = $queryResult->fetch_array();
				$user = new UserAccount(
					$queryData['userid'],
					$queryData['email'],
					$queryData['firstName'],
					$queryData['lastName']
				);
				return $user;
			}
			catch (Exception $e)
			{
				throw new Exception('Ошибка создания объекта UserAccount: '.$e->getMessage());
			}
		}
		
		private static function getConnection()
		{
			$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			if ($connection === false) {
				throw new Exception('Невозможно подключиться к серверу БД.');
			} else {
				return $connection;
			}
		}
	}
?>