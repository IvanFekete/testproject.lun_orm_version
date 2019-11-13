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
		
		<form action = "admin.php" method = "POST">
			<div class = 'container'>
			<?php
				include 'error_messages.php';
				
				if(array_key_exists('complex_id', $_POST)) {
					$complex_id = $_POST['complex_id'];
					
					echo '<input type = "hidden" name = "id" value = "save_localities"/>';
					echo '<input type = "hidden" name = "complex_id" value = "' . $complex_id . '"/>';
					
					include "bootstrap.php";
					include "db_manager.php";
					
					$complex = $entityManager->find('Complex', $complex_id);
					echo '<h2> Локації комплексу "'. $complex->getName() .'"</h2>';
					echo '<table class = "table table-striped">
					<tr>
						<td>Назва</td>
						<td></td>
					</tr>';
					$localities = $complex->getLocalities();
					$allLocalities = QueryRunner::runDqlQuery('SELECT l FROM Locality l');
					
					function getCol($s) {
						return "<td>".$s."</td>";
					}
					foreach($allLocalities as $locality) {
						$checked = '';
						foreach($localities as $checkedLocality) {
							if($checkedLocality->getName() == $locality['name']) {
								$checked = 'checked';
								break;
							}
						}
						$checkbox = '<input name = "localities[]" value = "' . $locality['id'] 
						. '" type = "checkbox" ' . $checked . '/ > </p>';
						echo '<tr>'.getCol($locality['name']) . getCol($checkbox).'</tr>';
					}
					echo '</table>';
					
					echo '<input type = "submit" class = "btn btn-warning" value = "Зберегти" />';
				}
				else {
					ErrorMessages::getUnexpectedErrorMessage();
				}
				
			?>
			</div>
		</form>
	</body>
</html>