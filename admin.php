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
			include 'error_messages.php';
			
			if(array_key_exists('id', $_POST)) {
				$id = $_POST['id'];

				
				if($id == 'delete_cities') {
					if(array_key_exists('delete_cities', $_POST)) {
						$to_delete = $_POST['delete_cities'];
						foreach($to_delete as $city_id) {
							CityManage::del($city_id);
						}
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'delete_complex') {
					if(array_key_exists('delete_complexes', $_POST)) {
						$to_delete = $_POST['delete_complexes'];
						foreach($to_delete as $complex_id) {
							ComplexManage::del((int)$complex_id);
						}
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
					
				if($id == 'delete_flat') {
					if(array_key_exists('delete_flats', $_POST)) {
						$to_delete = $_POST['delete_flats'];
						foreach($to_delete as $flat_id) {
							FlatManage::del($flat_id);
						}
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'delete_house') {
					if(array_key_exists('delete_houses', $_POST)) {
						$to_delete = $_POST['delete_houses'];
						foreach($to_delete as $house_id) {
							HouseManage::del($house_id);
						}
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'delete_localities') {
					if(array_key_exists('delete_localities', $_POST)) {
						$to_delete = $_POST['delete_localities'];
						foreach($to_delete as $locality_id) {
							LocalityManage::del($locality_id);
						}
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				
				if($id == 'add_new_city') {
					if(array_key_exists('name', $_POST)) {
						CityManage::create($_POST['name']);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'add_new_complex') {
					if(array_key_exists('name', $_POST) && array_key_exists('name', $_POST)) {
						$city_name = $_POST['city_name'];
						$query = QueryRunner::runDqlQuery("Select ct from City ct WHERE ct.name = '$city_name'");
						$city_id = $query[0]['id'];
						ComplexManage::create($_POST['name'], $city_id);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'add_new_house') {
					if(array_key_exists('name', $_POST) && array_key_exists('complex_name', $_POST)) {
						$complex_name = $_POST['complex_name'];
						$query = QueryRunner::runDqlQuery("Select c from Complex c WHERE c.name = '$complex_name'");
						$complex_id = $query[0]['id'];
						HouseManage::create($_POST['name'], $complex_id);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'add_new_locality') {
					if(array_key_exists('name', $_POST)) {
						LocalityManage::create($_POST['name']);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				
				if($id == 'edit_city') {
					if(array_key_exists('name', $_POST) &&  array_key_exists('city_id', $_POST)) {				
						CityManage::edit($_POST['city_id'], $_POST['name']);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'edit_complex') {					
					if(array_key_exists('name', $_POST) && array_key_exists('city_name', $_POST) && array_key_exists('complex_id', $_POST)) {
						$city_name = $_POST['city_name'];
						$query = QueryRunner::runDqlQuery("Select ct from City ct WHERE ct.name = '$city_name'");
						$city_id = $query[0]['id'];
						ComplexManage::edit($_POST['complex_id'], $_POST['name'], $city_id);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'edit_house') {
					if(array_key_exists('name', $_POST) && array_key_exists('complex_name', $_POST) && array_key_exists('house_id', $_POST)) {				
						$query = QueryRunner::runDqlQuery("SELECT c FROM Complex c WHERE c.name = '".$_POST['complex_name']."'");
						$complex_id = $query[0]['id'];
						HouseManage::edit($_POST['house_id'], $_POST['name'], $complex_id);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'edit_locality') {
					if(array_key_exists('name', $_POST) &&  array_key_exists('locality_id', $_POST)) {				
						LocalityManage::edit($_POST['locality_id'], $_POST['name']);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				if($id == 'save_localities') {
					if(array_key_exists('complex_id', $_POST) &&  array_key_exists('localities', $_POST)) {				
						$complex_id = $_POST['complex_id'];
						$localities = $_POST['localities'];
						
						include 'bootstrap.php';
							
						$complex = $entityManager->find('Complex', $complex_id);
						$oldLocalities = $complex->getLocalities();
						foreach($oldLocalities as $locality) {
							$complex->removeLocality($locality);
						}
						foreach($localities as $locality_id) {
							$locality = $entityManager->find('Locality', $locality_id);
							$complex->addLocality($locality);
						}
						
						$entityManager->flush();
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
			}
			
			function getCol($s) {
				return "<td>".$s."</td>";
			}
		?>
		<div class = 'container'>
			<div class = 'row'>
				<h1 class = 'display-4'>Перейти на <a href = 'client.php'>Клієнт</a> або на <a href = 'index.php'>головну</a>.</h1>
			</div>
			
			<h2> ЖК </h2>
			
			<input type = 'button' class = 'btn btn-success' value = 'Додати' 
				onclick = 'document.location.href = "add_new_complex.php"' />
			<input type = 'button' class = 'btn btn-primary' value = 'Завантажити в XML' 
				onclick = 'document.location.href = "show_xml.php?entity=Complex"'/>
				
			<form id = "delete_complex" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_complex" />
			</form>
			<script>
				$("#delete_complex").submit(function() {
					return confirm("Ви дійсно хочете видалити вибрані новобудови?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Місто</td>
					<td>Назва</td>
					<td>Змінити</td>
					<td><input form = "delete_complex" type = "submit" class = "btn btn-danger" value = "Видалити" /></td>
					<td>Локації</td>
				</tr>
				<?php
					$all = QueryRunner::getAllComplexes();
					
					
					foreach($all as $row) {
						$name = $row['name'];
						$city = $row['city']['name'];
						$update_button = "
						<form action = 'edit_complex.php' method = 'POST'>
							<input type = 'hidden' name = 'complex_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Змінити' />
						</form>";
						$delete_checkbox = "<input form = 'delete_complex' type = 'checkbox' name = 'delete_complexes[]' value = '".$row['id']."'/>";
						$locality_button = 
						"<form action = 'localities.php' method = 'POST'>
							<input type = 'hidden' name = 'complex_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-primary' value = 'Локації' />
						</form>";
						echo "<tr>".getCol($city).getCol($name).getCol($update_button).getCol($delete_checkbox).getCol($locality_button)."</tr>";
					}
				?>
			</table>
			
		
			<h2> Будинки </h2>
			
			
			<input type = 'button' class = 'btn btn-success' value = 'Додати' 
				onclick = 'document.location.href = "add_new_house.php"' />
			<input type = 'button' class = 'btn btn-primary' value = 'Завантажити в XML' 
				onclick = 'document.location.href = "show_xml.php?entity=House"'/>
			<form id = "delete_house" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_house" />
			</form>
			<script>
				$("#delete_house").submit(function() {
					return confirm("Ви дійсно хочете видалити вибрані будинки?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Будинок</td>
					<td>Комплекс</td>
					<td>Змінити</td>
					<td><input form = "delete_house" type = "submit" class = "btn btn-danger" value = "Видалити" /></td>
				</tr>
				<?php
					$all = QueryRunner::getAllHouses();
					
					foreach($all as $row) {
						$name = $row['name'];
						$complex_name = $row['complex']['name'];;
						$update_button = "
						<form action = 'edit_house.php' method = 'POST'>
							<input type = 'hidden' name = 'house_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Змінити' />
						</form>";
						$delete_checkbox = "<input form = 'delete_house' type = 'checkbox' name = 'delete_houses[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($name).getCol($complex_name).getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
		
		
			<h2> Квартири </h2>
			
			<input type = 'button' class = 'btn btn-success' value = 'Додати' 
				onclick = 'document.location.href = "add_new_flat.php"' />
			<input type = 'button' class = 'btn btn-success' value = 'Додати типову квартиру' 
				onclick = 'document.location.href = "add_new_typical_flat.php"' />
			<input type = 'button' class = 'btn btn-primary' value = 'Завантажити в XML' 
				onclick = 'document.location.href = "show_xml.php?entity=Flat"'/>
				
			<form id = "delete_flat" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_flat" />
			</form>
			<script>
				$("#delete_flat").submit(function() {
					return confirm("Ви дійсно хочете видалити вибрані квартири?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Місто</td>
					<td>ЖК</td>
					<td>Назва будинку</td>
					<td>Кількість кімнат</td>
					<td>Площа</td>
					<td>Ціна</td>
					<td>Змінити</td>
					<td><input form = "delete_flat" type = "submit" class = "btn btn-danger" value = "Видалити" /></td>
				</tr>
				<?php
					$allFlats = QueryRunner::getAllFlats();
					
					foreach($allFlats as $row) {
						$complex_name = $row['house']['complex']['name'];
						$house_name = $row['house']['name'];
						$complex_city = $row['house']['complex']['city']['name'];
						$flat_type = $row['flatType']['name'];
						$square = $row['square'];
						$price = $row['price'];
						$update_button = "
						<form action = 'edit_flat.php' method = 'POST'>
							<input type = 'hidden' name = 'flat_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Змінити' />
						</form>";
						$delete_checkbox = "<input form = 'delete_flat' type = 'checkbox' name = 'delete_flats[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($complex_city).getCol($complex_name).getCol($house_name).
							getCol($flat_type).getCol($square."  кв.м").getCol($price." грн").
							getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
			</form>
			
			<h2> Міста </h2>
			<form action = "admin.php" method = "POST">
			
				<input type = "hidden" name = "id" value = "add_new_city" />
				<p> Назва
					<input type = 'text' name = 'name' />
					<input type = 'submit' class = 'btn btn-success' value = 'Додати' />
				</p>
			</form>
			
			<input type = 'button' class = 'btn btn-primary' value = 'Завантажити в XML' 
				onclick = 'document.location.href = "show_xml.php?entity=City"'/>
			
			<form id = "delete_cities" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_cities" />
			</form>
			<script>
				$("#delete_cities").submit(function() {
					return confirm("Ви дійсно хочете видалити вибрані міста?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Назва</td>
					<td>Змінити</td>
					<td><input form = "delete_cities" type = "submit" class = "btn btn-danger" value = "Видалити" /></td>
				</tr>
				<?php
					$all = QueryRunner::getAllCities();
					
					foreach($all as $row) {
						$name = $row['name'];
						$update_button = "
						<form action = 'edit_city.php' method = 'POST'>
							<input type = 'hidden' name = 'city_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Змінити' />
						</form>";
						$delete_checkbox = "<input form = 'delete_cities' 
						type = 'checkbox' name = 'delete_cities[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($name).getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
			
			<h2> Локації </h2>
			<form action = "admin.php" method = "POST">
			
				<input type = "hidden" name = "id" value = "add_new_locality" />
				<p> Назва
					<input type = 'text' name = 'name' />
					<input type = 'submit' class = 'btn btn-success' value = 'Додати' />
				</p>
			</form>
			<input type = 'button' class = 'btn btn-primary' value = 'Завантажити в XML' 
				onclick = 'document.location.href = "show_xml.php?entity=Locality"'/>
			
			<form id = "delete_localities" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_localities" />
			</form>
			<script>
				$("#delete_localities").submit(function() {
					return confirm("Ви дійсно хочете видалити вибрані локації?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Назва</td>
					<td>Змінити</td>
					<td><input form = "delete_localities" type = "submit" class = "btn btn-danger" value = "Видалити" /></td>
				</tr>
				<?php
					$all = QueryRunner::getAllLocalities();	
					
					foreach($all as $row) {
						$name = $row['name'];
						$update_button = "
						<form action = 'edit_locality.php' method = 'POST'>
							<input type = 'hidden' name = 'locality_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Змінити' />
						</form>";
						$delete_checkbox = "<input form = 'delete_localities' type = 'checkbox' name = 'delete_localities[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($name).getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
		</div>
		
		<form action = 'index.php' method = 'POST'>
				<input type = 'hidden' name = 'id' value = 'logout' />
				<input type = 'submit' class = 'btn btn-danger' value = 'Вийти з адмінпанелі' />
			</form>
	</body>
</html>