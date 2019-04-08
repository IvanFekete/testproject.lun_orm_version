<?php
	include 'db_manager.php';
?>
<!DOCTYPE html>
<html lang = 'ru'>
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
		
		<title>Новостройки:Клиент</title>
	</head>
	<body>
		<div class = 'container'>
			<div class = 'row'>
				<h1 class = 'display-4'>Перейти на <a href = 'admin.php'>Админ</a> или на <a href = 'index.php'>главную</a>.</h1>
			</div>
			<div class = 'row'>
				<div class = 'col-sm-4'>				
					<form action = 'client.php' method = 'GET'>
						<h2>Город:</h2>
						
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
						
						<h2>Количество комнат:</h2>
						
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
						
						<input type = 'submit' class = 'btn btn-primary' value = 'Фильтр' />
					</form>
				</div>
				
				<div class = 'col-sm-8'>
					<table class = "table table-striped">
						<tr>
							<td>Город</td>
							<td>ЖК</td>
							<td>Название дома</td>
							<td>Количество комнат</td>
							<td>Площадь</td>
							<td>Цена</td>
						</tr>
						<?php
							$dql = "SELECT f, h, c, ft FROM Flat f JOIN f.house h JOIN h.complex c JOIN f.flatType ft";
							$city_statement = "";
							$flat_type_statement = "";
							
							if(array_key_exists('city', $_GET)) {
								foreach($_GET['city'] as $city_name) {
									if($city_statement != '') {
										$city_statement .= " OR ";
									}
									$city_statement .= "c.city = '".$city_name."'";
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
							
							
							if($city_statement != '' || $flat_type_statement != '') {
								$dql .= " WHERE ";
								if($city_statement != '') {
									$dql .= "(".$city_statement.")";									
									if($flat_type_statement != '') {
										$dql .= " AND (".$flat_type_statement.")";
									}
								}
								else if($flat_type_statement != '') {
									$dql .= "(".$flat_type_statement.")";
								}
								
								$dql .= " ORDER BY f.price";
							}
							
							
						
						
							$allFlats = QueryRunner::runDqlQuery($dql);
							
							function getCol($s) {
								return "<td>".$s."</td>";
							}
							
							foreach($allFlats as $row) {
								$complex_name = $row['house']['complex']['name'];
								$house_name = $row['house']['name'];
								$complex_city = $row['house']['complex']['city'];
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