<?php
	if(array_key_exists('id', $_POST) && $_POST['id'] == 'logout') {
		session_start();
		$_SESSION = array();
		session_destroy();		
	}
?>

<!DOCTYPE html>
<html lang = 'uk'>
	<head>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	
		<meta charset = 'utf-8' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Новобудови:Головна</title>
	</head>
	<body>
		<div class = 'container'>
			<h1 class = 'display-4'>
			Ви зараз на головній сторінці, для початку роботи оберіть 
				<a href = 'client.php'>Клієнт</a> або <a href = 'admin.php'>Адмін</a>.
			Щоб запустити запити, перейдіть на 	<a href = 'queries_page.php'>Запити</a>.
			</h1>
			
		</div>
	</body>
</html>