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
		
		<title>Новобудови:Адмін</title>
	</head>
	<body>
		<?php
			include 'error_messages.php';
			
			if(array_key_exists('login', $_POST) && array_key_exists('password', $_POST)) {
				if($_POST['login'] = 'admin' && $_POST['password'] == 'admin') {
					session_start();
					$_SESSION["loggedin"] = true;
					
					header('Location: admin.php');
				}
				else {
					echo ErrorMessages::getAuthErrorMessage();
				}
			}
			else {
				session_start();
				$_SESSION = array();
				session_destroy();
			}
		?>
		<div class = 'container'>
			<h2 class = 'display-4'>Авторизація</h2>
			<p></p>
			<form action = 'auth.php' method = 'POST'>
				<h5>Логін:</h5>  <input type = 'text' class = 'form-control' name = 'login' />
				<h5>Пароль:</h5>  <input type = 'password' class = 'form-control' name = 'password' />
				
				<input type = 'submit' value = 'Увійти' class = 'btn btn-success' />
			</form>
		</div>
	</body>
</html>