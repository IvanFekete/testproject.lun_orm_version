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
		<?php
			include 'db_manager.php';
			include 'data_formats.php';
			include 'error_messages.php';
			
			if(array_key_exists('house_name', $_POST)) {
				$error_message = ErrorMessages::getMessage($_POST['square'], $_POST['price']);
				if($error_message != '') {
					echo $error_message;
				}
				else {
					$complex_name = $_POST['complex_name'];
					$query = QueryRunner::runDqlQuery("SELECT c FROM Complex c WHERE c.name = '$complex_name'");
					$complex_id = $query[0]['id'];
					$house_name = $_POST['house_name'];
					$query = QueryRunner::runDqlQuery("SELECT h, c FROM House h JOIN h.complex c WHERE h.name = '$house_name' 
						AND c.name = '$complex_name'");
					$house_id = $query[0]['id'];
					$flat_type = $_POST['flat_type'];
					$query = QueryRunner::runDqlQuery("SELECT ft FROM FlatType ft WHERE ft.name = '$flat_type'");
					$flat_type_id = $query[0]['id'];
					$square = DataFormatter::toFloat($_POST['square']);
					$price = DataFormatter::toInt($_POST['price']);
					if(!in_array('all', $_POST['price_type'])) {
						$price = (int) $price * $square;
					}
					FlatManage::create($house_id, $flat_type_id, $square, $price);
					header('Location: admin.php');
				}
			}
		?>

		<div class = 'container'>
			<h2 class = 'display-4'>Додати квартиру</h2>
			<p></p>
			<form id = 'add_new_flat' name = 'add_new_flat' action = 'add_new_flat_next.php' method = 'POST'>
				<h5>Комплекс:</h5>  
				<select name = 'complex_name' class = 'form-control'>
					<?php 
						$complex_name = $_POST['complex_name'];
						echo "<option value = '".$complex_name."'>".$complex_name."</option>\n";
					?>
				</select>
				
				<h5>Будинок:</h5>  
				<select name = 'house_name' class = 'form-control'>
					<?php 
						$query = QueryRunner::runDqlQuery("SELECT c from Complex c WHERE c.name = '$complex_name' ");
						$complex_id = $query[0]['id'];
						$complex_houses = QueryRunner::runDqlQuery("SELECT h, c FROM House h JOIN h.complex c WHERE c.id = ".$complex_id);
						foreach($complex_houses as $row) {
							echo "<option value = '".$row['name']."'>".$row['name']."</option>\n";
						}
					
					?>
				</select>
				
				<h5>Кількість кімнат:</h5>  
				<select name = 'flat_type' class = 'form-control'>
					<?php 
						$flat_types = QueryRunner::getAllFlatTypesAsArray();
						foreach($flat_types as $flat_type) {
							echo "<option value = '".$flat_type."'>".$flat_type."</option>\n";
						}
					
					?>
				</select>
					
				<h5>Площа(кв. м):</h5>  <input type = 'text' class = 'form-control' name = 'square' />
				<h5>Ціна вказана:</h5>
				<p>
					За кв.м   <input type = 'radio' name = 'price_type[]' value = 'for_squared_meter' checked = 'true'/>
					За всю квартиру <input type = 'radio' name = 'price_type[]' value = 'all' />
				</p>
				<h5>Ціна(грн):</h5>  <input type = 'text' class = 'form-control' name = 'price' />
				
				<p></p>
				<input type = 'submit' value = 'Додати' class = 'btn btn-success' />
				
			</form>
		</div>
	</body>
</html>