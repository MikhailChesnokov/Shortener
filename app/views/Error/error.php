<div class="std">
<?php
	switch ($errorCode)
	{
		case 404:
			echo 'Ошибка 404. Запрошенная вами страница не найдена.';
			break;
		case 500:
			echo 'Ошибка 500. Внутренняя ошибка сервера.';
			break;
		default:
			echo 'Неизвестная ошибка.';
	}
?>
</div>