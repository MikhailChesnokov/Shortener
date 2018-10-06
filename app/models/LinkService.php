<?php
	class LinkService
	{
		public static function getAll()
		{
			$userid = $_SESSION['validUserId'];
			
			$connection = LinkService::getConnection();
	
			$queryResult = $connection->query("select *
											   from link
											   where userid = '".$userid."'");
			if (!$queryResult) {
				throw new Exception('Невозможно подключиться к базе данных.');
			}
			
			$url_array = array();
			for ($count = 1; $row=$queryResult->fetch_array(); ++$count) {
				$url_array[$count] = $row;
			}
				
			return $url_array;
		}
		
		public static function addNew($initialLink)
		{
			$userid = $_SESSION['validUserId'];
			
			if (strstr($initialLink, 'https://') === false) {
				$initialLink = 'https://'.$initialLink;
			}
			
			if (!(@fopen($initialLink, 'r'))) {
				throw new Exception('Недоступный URL-адрес "'.$initialLink.'"');
			}
			
			$connection = LinkService::getConnection();
			if (!$connection) {
				throw new Exception('Невозможно подключиться к базе данных.');
			}
			
			$queryResult = $connection->query("select linkid
											   from link
											   where initialLink='".$initialLink."' and
											   userid='".$userid."'");
			if (!$queryResult) {
				throw new Exception('Ошибка при проверке похожих ссылок в БД.');
			}
			if ($queryResult->num_rows > 0) {
				throw new Exception('Такая ссылка уже существует.');
			}
			$queryResult=null;
			
			$queryResult = $connection->query("insert into link values
											   (NULL,'".$userid."', '".$initialLink."', 'no', 'TITLE', '2001-09-11', 0)");
			if (!$queryResult) {
				throw new Exception('Ошибка при первой вставке.');
			}
			$queryResult = $connection->query("select linkid
											   from link
											   where initialLink='".$initialLink."'");
			if (!$queryResult) {
				throw new Exception('Ошибка при поиске userid.');
			}
			$linkid = $queryResult->fetch_array()['linkid'];

			$linkShortener = new LinkShortener();
			$shortedLink = $linkShortener->translate($linkid);
			if (!$shortedLink) {
				throw new Exception('Ошибка трансляции ссылки.');
			}


			$queryResult = $connection->query("update link
											   set shortedLink='".$shortedLink."'
											   where linkid='".$linkid."'");
			
			if (!$queryResult) {
				throw new Exception('Ошибка при загрузке занных в БД.');
			}
		}
		
		public static function shortedToInitial($shortedLink) 
		{
			$connection = LinkService::getConnection();
			if (!$connection) {
				throw new Exception('Невозможно подключиться к БД');
			}
			$queryResult = $connection->query("select initialLink
											   from link
											   where shortedLink='".$shortedLink."'");
			if (!$queryResult) {
				throw new Exception('Ошибка при запросе на выборку.');
			}
			if ($queryResult->num_rows > 1) {
				throw new Exception('Найдено несколько вхождений.');
			}
			if ($queryResult->num_rows == 0) {
				return false;
			}
			return $queryResult->fetch_array()['initialLink'];
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