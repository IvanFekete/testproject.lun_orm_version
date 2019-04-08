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
		
		<title>Новостройки:Админ</title>
	</head>
	<body>
		<?php
			//include 'db_manager.php';
			include 'db_manager.php';
			include 'error_messages.php';
			
			if(array_key_exists('id', $_POST)) {
				$id = $_POST['id'];
				
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
				
				if($id == 'add_new_complex') {
					if(array_key_exists('name', $_POST) && array_key_exists('name', $_POST)) {
						ComplexManage::create($_POST['name'], $_POST['city']);
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
				
				if($id == 'edit_complex') {					
					if(array_key_exists('name', $_POST) && array_key_exists('city', $_POST) && array_key_exists('complex_id', $_POST)) {
						ComplexManage::edit($_POST['complex_id'], $_POST['name'], $_POST['city']);
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
				
			}
			
			function getCol($s) {
				return "<td>".$s."</td>";
			}
		?>
		<div class = 'container'>
			<div class = 'row'>
				<h1 class = 'display-4'>Перейти на <a href = 'client.php'>Клиент</a> или на <a href = 'index.php'>главную</a>.</h1>
			</div>


			<h2> ЖК </h2>
			
			<input type = 'button' class = 'btn btn-success' value = 'Добавить' 
				onclick = 'document.location.href = "add_new_complex.php"' />
			
			<form id = "delete_complex" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_complex" />
			</form>
			<script>
				$("#delete_complex").submit(function() {
					return confirm("Вы действительно хотите удалить выбранные новостройки?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Город</td>
					<td>Название</td>
					<td>Изменить</td>
					<td><input form = "delete_complex" type = "submit" class = "btn btn-danger" value = "Удалить" /></td>
				</tr>
				<?php
					$dql = "SELECT c FROM Complex c";
				
					$all = QueryRunner::runDqlQuery($dql);
					
					
					foreach($all as $row) {
						$name = $row['name'];
						$city = $row['city'];
						$update_button = "
						<form action = 'edit_complex.php' method = 'POST'>
							<input type = 'hidden' name = 'complex_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Изменить' />
						</form>";
						$delete_checkbox = "<input form = 'delete_complex' type = 'checkbox' name = 'delete_complexes[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($name).getCol($city).getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
			
		
			<h2> Дома </h2>
			
			
			<input type = 'button' class = 'btn btn-success' value = 'Добавить' 
				onclick = 'document.location.href = "add_new_house.php"' />
			<form id = "delete_house" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_house" />
			</form>
			<script>
				$("#delete_house").submit(function() {
					return confirm("Вы действительно хотите удалить выбранные дома?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Дом</td>
					<td>Комплекс</td>
					<td>Изменить</td>
					<td><input form = "delete_house" type = "submit" class = "btn btn-danger" value = "Удалить" /></td>
				</tr>
				<?php
					$dql = "SELECT h, c FROM House h JOIN h.complex c ORDER BY c.id";
				
					$all = QueryRunner::runDqlQuery($dql);
					
					foreach($all as $row) {
						$name = $row['name'];
						$complex_name = $row['complex']['name'];;
						$update_button = "
						<form action = 'edit_house.php' method = 'POST'>
							<input type = 'hidden' name = 'house_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Изменить' />
						</form>";
						$delete_checkbox = "<input form = 'delete_house' type = 'checkbox' name = 'delete_houses[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($name).getCol($complex_name).getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
		
		
			<h2> Квартиры </h2>
			
			<input type = 'button' class = 'btn btn-success' value = 'Добавить' 
				onclick = 'document.location.href = "add_new_flat.php"' />
			<input type = 'button' class = 'btn btn-success' value = 'Добавить типовую квартиру' 
				onclick = 'document.location.href = "add_new_typical_flat.php"' />
			
			<form id = "delete_flat" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_flat" />
			</form>
			<script>
				$("#delete_flat").submit(function() {
					return confirm("Вы действительно хотите удалить выбранные квартиры?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Город</td>
					<td>ЖК</td>
					<td>Название дома</td>
					<td>Количество комнат</td>
					<td>Площадь</td>
					<td>Цена</td>
					<td>Изменить</td>
					<td><input form = "delete_flat" type = "submit" class = "btn btn-danger" value = "Удалить" /></td>
				</tr>
				<?php
					$dql = "SELECT f, ft, h, c FROM Flat f JOIN f.flatType ft JOIN f.house h JOIN h.complex c";

				
					$allFlats = QueryRunner::runDqlQuery($dql);
					
					
					foreach($allFlats as $row) {
						$complex_name = $row['house']['complex']['name'];
						$house_name = $row['house']['name'];
						$complex_city = $row['house']['complex']['city'];
						$flat_type = $row['flatType']['name'];
						$square = $row['square'];
						$price = $row['price'];
						$update_button = "
						<form action = 'edit_flat.php' method = 'POST'>
							<input type = 'hidden' name = 'flat_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Изменить' />
						</form>";
						$delete_checkbox = "<input form = 'delete_flat' type = 'checkbox' name = 'delete_flats[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($complex_city).getCol($complex_name).getCol($house_name).
							getCol($flat_type).getCol($square."  кв.м").getCol($price." грн").
							getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
			</form>
		</div>
	</body>
</html>