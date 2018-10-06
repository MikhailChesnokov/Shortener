<div class="std">
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Первоначальная ссылка</th>
			<th>Сокращенная ссылка</th>
			<th>Посещения</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($links as $link) {
			echo
			'<tr>'.
				'<td>'.$link['initialLink'].'</td>'.
				'<td>'.$link['shortedLink'].'</td>'.
				'<td>'.$link['count'].'</td>'.
			'</tr>';
			}
		?>
	</tbody>
</table>
</div>