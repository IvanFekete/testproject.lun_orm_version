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
			
			if(array_key_exists('flat_id', $_POST)) {
				$id = (int)$_POST['flat_id'];
				$flat = FlatManage::find($id);
				$complex_name = $flat->getHouse()->getComplex()->getName();;
				$house_name = $flat->getHouse()->getName();
				$flat_type = $flat->getFlatType()->getId();
				$square = $flat->getSquare();
				$price = $flat->getPrice();
			}
			else {
				echo ErrorMessages::getUnexpectedErrorMessage();
			}
			
			
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
					
					FlatManage::edit($id, $house_id, $flat_type_id, $square, $price);
					
					header('Location: admin.php');
				}
			}
		?>

		<div class = 'container'>
			<h2 class = 'display-4'>Змінити інформацію про квартиру</h2>
			<p></p>
			<?php 
			echo "
			<form action = 'edit_flat.php' method = 'POST'>
				<input type = 'hidden' name = 'flat_id' value = '".$id."' />
				<h5>Комплекс:</h5>  
				<select name = 'complex_name' class = 'form-control'>";
						echo "<option value = '".$complex_name."'>".$complex_name."</option>\n";
				echo "
				</select>
				
				<h5>Будинок:</h5>  
				<select name = 'house_name' class = 'form-control'>
					
							<option value = '".$house_name."'>".$house_name."</option>\n;
						
					
					
				</select>
				
				<h5>Кількість кімнат:</h5>  
				<select name = 'flat_type' class = 'form-control'>";
						$flat_types = QueryRunner::getAllFlatTypesAsArray();
						foreach($flat_types as $cur_flat_type) {
							echo "<option value = '".$cur_flat_type."' ".
							($flat_type == $cur_flat_type  ? "selected = 'selected'" : "").">".$cur_flat_type."</option>\n";
						}
				echo "
				</select>
					
				<h5>Площа(кв. м):</h5>  <input type = 'text' class = 'form-control' name = 'square' value = '".$square."'/>
				<h5>Ціна(за всю квартиру, грн):</h5>  <input type = 'text' class = 'form-control' name = 'price' value = '".$price."'/>
				
				<p></p>
				<input type = 'submit' value = 'Змінити' class = 'btn btn-warning' />
				
			</form>";
			?>
		</div>
	</body>
</html>