<?php
	include 'db_manager.php';
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
		
		<title>Новобудови:Клієнт</title>
	</head>
	<body>
		<div class = 'container'>
			<div class = 'row'>
				<h1 class = 'display-4'>Перейти на <a href = 'admin.php'>Адмін</a> або на <a href = 'index.php'>головну</a>.</h1>
			</div>
			<div class = 'row'>
				<div class = 'col-sm-4'>				
					<form action = 'client.php' method = 'GET'>
						<h2>Місто:</h2>
						
						<div class = 'container'>
							<?php
								$cities = QueryRunner::getAllCitiesAsArray();
								foreach($cities as $city) {
									$checked = "";
									if(array_key_exists('city', $_GET) && in_array($city, $_GET['city'])) {
										$checked = "checked";
									}
									echo "<p><input type = 'checkbox' name = 'city[]' value = '".
										$city."' ".$checked." />".$city."</p>";
								}
							?>
						</div>
						
						<h2>Локація:</h2>
						
						<div class = 'container'>
							<?php
								$localities = QueryRunner::getAllLocalitiesAsArray();
								foreach($localities as $locality) {
									$checked = "";
									if(array_key_exists('locality', $_GET) && in_array($locality, $_GET['locality'])) {
										$checked = "checked";
									}
									echo "<p><input type = 'checkbox' name = 'locality[]' value = '".
										$locality."' ".$checked." />".$locality."</p>";
								}
							?>
						</div>
						
						<h2>Кількість кімнат:</h2>
						
						<div class = 'container'>
							<?php						
								$flat_types = QueryRunner::getAllFlatTypesAsArray();
								foreach($flat_types as $flat_type) {
									$checked = "";
									if(array_key_exists('flat_type', $_GET) && in_array($flat_type, $_GET['flat_type'])) {
										$checked = "checked";
									}
									echo "<p><input type = 'checkbox' name = 'flat_type[]' value = '".
									$flat_type."' ".$checked."/>".$flat_type."</p>";
								}
							?>
						</div>
						
						<input type = 'submit' class = 'btn btn-primary' value = 'Фiльтр' />
					</form>
				</div>
				
				<div class = 'col-sm-8'>
					<table class = "table table-striped">
						<tr>
							<td>Місто</td>
							<td>ЖК</td>
							<td>Назва будинку</td>
							<td>Кількість кімнат</td>
							<td>Площа</td>
							<td>Ціна</td>
						</tr>
						<?php
							$dql = "SELECT f, h, c, ft, ct, l FROM Flat f JOIN f.house h JOIN h.complex c JOIN f.flatType ft JOIN c.city ct JOIN c.localities l";
							$city_statement = "";
							$flat_type_statement = "";
							$locality_statement = "";
							
							if(array_key_exists('city', $_GET)) {
								foreach($_GET['city'] as $city_name) {
									if($city_statement != '') {
										$city_statement .= " OR ";
									}
									$city_statement .= "ct.name = '".$city_name."'";
								}
							}
							
							if(array_key_exists('flat_type', $_GET)) {								
								foreach($_GET['flat_type'] as $flat_type) {
									$query = QueryRunner::runDqlQuery("SELECT ft FROM FlatType ft WHERE ft.name = '$flat_type'");
									$flat_type_id = $query[0]['id'];
									if($flat_type_statement != '') {
										$flat_type_statement .= " OR ";
									}
									$flat_type_statement .= "ft.id = ".$flat_type_id;
								}
							}
							
							if(array_key_exists('locality', $_GET)) {								
								foreach($_GET['locality'] as $locality_name) {
									$query = QueryRunner::runDqlQuery("SELECT l FROM Locality l WHERE l.name = '$locality_name'");
									$locality_id = $query[0]['id'];
									if($locality_statement != '') {
										$locality_statement .= " OR ";
									}
									$locality_statement .= "l.id = ".$locality_id;
								}
							}
							
							$statements = [];
							if($city_statement != '') {
								$statements[] = $city_statement;
							}
							if($flat_type_statement != '') {
								$statements[] = $flat_type_statement;
							}
							if($locality_statement != '') {
								$statements[] = $locality_statement;
							}
							if(!empty($statements)) {
								$dql .= " WHERE ";
								$dql .= "(".$statements[0].")";
								for($i = 1; $i < count($statements); $i++) {
									$dql .= " AND (".$statements[$i].")";
								}
								
							}
							
							$dql .= " ORDER BY f.price";
							
							
						
						
							$allFlats = QueryRunner::runDqlQuery($dql);
							
							function getCol($s) {
								return "<td>".$s."</td>";
							}
							
							foreach($allFlats as $row) {
								$complex_name = $row['house']['complex']['name'];
								$house_name = $row['house']['name'];
								$complex_city = $row['house']['complex']['city']['name'];
								$flat_type = $row['flatType']['name'];
								$square = $row['square'];
								$price = $row['price'];
								echo "<tr>".getCol($complex_city).getCol($complex_name).getCol($house_name).
									getCol($flat_type).getCol($square."  кв.м").getCol($price." грн")."</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>