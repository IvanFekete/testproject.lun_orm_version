<?php
	session_start();
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: auth.php");
		exit;
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
		
		<title>Новобудови:Адмін</title>
	</head>
	<body>

		<div class = 'container'>
			<?php
					
				include 'db_manager.php';
				include 'error_messages.php';
				
				
				if(array_key_exists('city_id', $_POST)) {
					$id = $_POST['city_id'];
					$city = cityManage::find($id);
					$name = $city->getName();
					
					echo"
						<h2 class = 'display-4'>Змінити інформацію про локацію</h2>
						<p></p>

						<form action = 'admin.php' method = 'POST'>
						<input type = 'hidden' name = 'id' value = 'edit_city' />
						<input type = 'hidden' name = 'city_id' value = '".$id."' />
						<h5>Назва:</h5>  <input type = 'text' class = 'form-control' name = 'name' value = '".$name."' />";
						
						echo"
						<p></p>
						<input type = 'submit' value = 'Змінити' class = 'btn btn-warning' />";
				}
				else {
					echo ErrorMessages::getUnexpectedErrorMessage();
				}
			?>
			
		
				
			</form>
		</div>
	</body>
</html>