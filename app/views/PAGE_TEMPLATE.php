<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php echo $pageTitle; ?></title>

		<link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../../css/narrow-jumbotron.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	
	<div class="container">
		<div class="header clearfix">
			<nav>
				<ul class="nav nav-pills float-right">
				<?php 
				if (isset($_SESSION['validUserId']))
				$id = $_SESSION['validUserId'];
				if (!isset($id)) {
					echo 
				'<li class="nav-item">
					<a class="nav-link active" href="/Login">Вход<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/Register">Регистрация</a>
				</li>';
				} else {
					echo
				'
				<li class="nav-item">
					<a class="nav-link" href="/id'.$id.'/MyLinks">Мои ссылки</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/id'.$id.'/AddLink">Новая ссылка</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="/Logout">Выйти<span class="sr-only">(current)</span></a>
				</li>
				';}
				?>
				</ul>
			</nav>
			<h3 class="text-muted">Project name</h3>
		</div>

		<div>
		<?php
		if (isset($exceptionText))
			if ($exceptionText != null)
				echo 
			'<div class="alert alert-danger">
				<strong>Danger!</strong> '.$exceptionText.
			'</div>';
		require($viewPath);
		?>
		</div>

      <footer class="footer">
        <p>&copy; Company 2017</p>
      </footer>
    </div> 

  
	
	
	<script src="../../js/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="../../js/bootstrap.min.js"></script>
  </body>
</html>