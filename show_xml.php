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
			<h1 class = 'display-4'>
				Перейти на 
				<a href = 'client.php'>Клієнт</a>, 
				<a href = 'admin.php'>Адмін</a> або на 
				<a href = 'index.php'>головну</a>.
			</h1>
			<?php
				include 'xml_helper.php';
				include 'db_manager.php';
				
				if (array_key_exists('entity', $_GET)) {
					$entity = $_GET['entity'];
					if($entity == 'Complex') {
						$all = QueryRunner::getAllComplexes();
						$text = htmlentities(XmlHelper::getXmlEntityFromArray($all, 'complex'));
					}
					
					if($entity == 'House') {
						$all = QueryRunner::getAllHouses();
						$text = htmlentities(XmlHelper::getXmlEntityFromArray($all, 'house'));
					}
					
					if($entity == 'Flat') {
						$all = QueryRunner::getAllFlats();
						$text = htmlentities(XmlHelper::getXmlEntityFromArray($all, 'flat'));
					}
					
					if($entity == 'City') {
						$all = QueryRunner::getAllCities();
						$text = htmlentities(XmlHelper::getXmlEntityFromArray($all, 'city'));
					}
					
					if($entity == 'Locality') {
						$all = QueryRunner::getAllLocalities();
						$text = htmlentities(XmlHelper::getXmlEntityFromArray($all, 'locality'));
					}
					
					
					
					echo '<textarea cols = "100" rows = "20" readonly style = "font-family: Courier;">'.$text.'</textarea>';
				}
				else {
					echo 'Вибачте, але отримання даних без вказаної сутності неможливе. Спробуйте натиснути на відповідну кнопку в панелі адміністратора.';
				}
			?>
		</div>
	<body>
</html>