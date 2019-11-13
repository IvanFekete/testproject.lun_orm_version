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
		
		<title>Новобудови:Запити</title>
	</head>
	<body>
		<div class = "container">
			<div class = 'row'>
				<h1 class = 'display-4'>Перейти на <a href = 'index.php'>головну</a>.</h1>
			</div>
			<?php
				include "sql_query.php";

				
				$queries = [
					new SqlQuery(
						"SELECT DISTINCT flat_types.id, flat_types.name
						FROM
							complexes INNER JOIN (
								houses INNER JOIN (
									flats INNER JOIN flat_types 
									ON flats.flatType_id = flat_types.id
								) ON flats.house_id = houses.id
							) ON houses.complex_id = complexes.id
						WHERE complexes.name = ?",
						"1. Знайти типи квартир, які містяться в будинках, що належать жк з назвою complex_name."
					),
					new SqlQuery(
						"SELECT DISTINCT COUNT(houses.id)
						FROM
							localities INNER JOIN (
								localities_complexes INNER JOIN(
									complexes INNER JOIN houses
									ON houses.complex_id = complexes.id
								) ON localities_complexes.complex_id = complexes.id
							) ON localities.id = localities_complexes.locality_id
						WHERE localities.name = ?",
						"2. Знайти кількість будинків, які належать комплексам, які знаходяться в локації locality_name."
					),
					new SqlQuery(
						"SELECT flats.*
						FROM
							cities INNER JOIN (
								complexes INNER JOIN (
									houses INNER JOIN flats
									ON flats.house_id = houses.id
								) ON houses.complex_id = complexes.id
							) ON cities.id = complexes.city_id
						WHERE
							cities.name = ? AND
							flats.price >= ? * flats.square AND 
							flats.price <= ? * flats.square",
						"3. Знайти квартири, що знаходяться у будинках, які знаходяться в комплексах, що знаходяться в місті 
							city_name і ціна за квадратний метр в яких знаходиться на проміжку [l;r]."
					),
					new SqlQuery(
						"SELECT DISTINCT complexes.id, complexes.name
						FROM
							complexes INNER JOIN houses ON houses.complex_id = complexes.id
						WHERE (SELECT COUNT(flats.id) 
								FROM flats
								WHERE flats.house_id = houses.id
							) >= 1 * ?",
						"4. Знайти ЖК, в яких є хоча б один будинок, в якому не менше ніж flats_number квартир."
					),
					new SqlQuery(
						"SELECT DISTINCT cities.id, cities.name
						FROM 
							cities INNER JOIN complexes
							ON complexes.city_id = cities.id
						WHERE
							(
								SELECT MAX(flats.price)
								FROM
									houses INNER JOIN flats
									ON flats.house_id = houses.id
								WHERE
									houses.complex_id = complexes.id
							) <= 1 * ?",
						"5.Знайти міста, в яких є комлпекси, максимальна ціна за квартиру не перевищує price."					
					),
					new SqlQuery(
						"SELECT DISTINCT y.id, y.name
						FROM complexes AS y
						WHERE
							NOT EXISTS (
								SELECT flats.flatType_id
								FROM
									houses INNER JOIN flats
									ON flats.house_id = houses.id
								WHERE
									houses.complex_id = y.id AND
									NOT (flats.flatType_id IN (
										SELECT DISTINCT flats.flatType_id
										FROM
											complexes AS x INNER JOIN (
												houses INNER JOIN flats
												ON flats.house_id = houses.id
											) ON x.id = houses.complex_id
										WHERE
										x.name = ?
									))
							)",
						"1. Знайти ЖК, в яких є тільки ті типи квартир, що і в ЖК complex_name."					
					),
					new SqlQuery(
						"SELECT DISTINCT complexes.*
						FROM 
							complexes INNER JOIN (
								houses INNER JOIN flats
								ON flats.house_id = houses.id
							) ON houses.complex_id = complexes.id
						WHERE
							NOT EXISTS (
								SELECT y.flatType_id
								FROM
									houses as x INNER JOIN flats as y
									ON y.house_id = x.id
								WHERE
									y.flatType_id = flats.flatType_id AND
									x.complex_id != complexes.id
							)",
						"2. Знайти назви ЖК, в яких є типи квартир, яких немає в інших ЖК."					
					),
					new SqlQuery(
						"SELECT
							x.name AS locality1,
							y.name AS localiity2
						FROM
							localities as x,
							localities as y
						WHERE
						x.id != y.id AND EXISTS (
							SELECT *
							FROM
								complexes
							WHERE EXISTS (
								SELECT *
								FROM localities_complexes
								WHERE
									localities_complexes.complex_id = complexes.id
									AND localities_complexes.locality_id = x.id
							)
							AND EXISTS (
								SELECT *
								FROM localities_complexes
								WHERE
									localities_complexes.complex_id = complexes.id
									AND localities_complexes.locality_id = y.id
							)
						  );
						",
						"3. Знайти пари локацій, для яких існує хоча б один комплекс, який належить обом локаціям одночасно"					
					),
				];
				if(isset($_GET['query_id'])) {
					$queryId = $_GET['query_id'];
				}
				else {
					$queryId = -1;
				}
			?>

			
			
			<h4><?= $queries[0]->getText()?></h4>
			<p><textarea cols = "50" rows = "15" readonly style = "font-family: Courier;"><?= $queries[0]->getSql()?></textarea></p>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "0" />
				<p>complex_name: <input type = "text" name = "complex_name"
				<?= ($queryId == 0 ? 'value = "'.$_GET['complex_name'].'"' : '')?> /></p>
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 0) {
					echo $queries[0]->getHtmlResults([$_GET['complex_name']]);
				}
			?>
			<br />
			
			<h4><?= $queries[1]->getText()?></h4>
			<p><textarea cols = "50" rows = "15" readonly style = "font-family: Courier;"><?= $queries[1]->getSql()?></textarea></p>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "1" />
				<p>locality_name: <input type = "text" name = "locality_name"
				<?= ($queryId == 1 ? 'value = "'.$_GET['locality_name'].'"' : '')?> /></p>
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 1) {
					echo $queries[1]->getHtmlResults([$_GET['locality_name']]);
				}
			?>
			<br />
			
			<h4><?= $queries[2]->getText()?></h4>
			<p><textarea cols = "50" rows = "15" readonly style = "font-family: Courier;"><?= $queries[2]->getSql()?></textarea></p>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "2" />
				<p>city_name: <input type = "text" name = "city_name"
				<?= ($queryId == 2 ? 'value = "'.$_GET['city_name'].'"' : '')?> />
				left: <input type = "text" name = "l"
				<?= ($queryId == 2 ? 'value = "'.$_GET['l'].'"' : '')?> />
				right: <input type = "text" name = "r"
				<?= ($queryId == 2 ? 'value = "'.$_GET['r'].'"' : '')?> /></p>
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 2) {
					echo $queries[2]->getHtmlResults([$_GET['city_name'], $_GET['l'], $_GET['r']]);
				}
			?>
			<br />
			
			<h4><?= $queries[3]->getText()?></h4>
			<p><textarea cols = "50" rows = "15" readonly style = "font-family: Courier;"><?= $queries[3]->getSql()?></textarea></p>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "3" />
				<p>flats_number: <input type = "text" name = "flats_number"
				<?= ($queryId == 3 ? 'value = "'.$_GET['flats_number'].'"' : '')?> /></p>
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 3) {
					echo $queries[3]->getHtmlResults([$_GET['flats_number']]);
				}
			?>
			<br />
			
			<h4><?= $queries[4]->getText()?></h4>
			<p><textarea cols = "50" rows = "15" readonly style = "font-family: Courier;"><?= $queries[4]->getSql()?></textarea></p>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "4" />
				<p>price: <input type = "text" name = "price"
				<?= ($queryId == 4 ? 'value = "'.$_GET['price'].'"' : '')?> /></p>
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 4) {
					echo $queries[4]->getHtmlResults([$_GET['price']]);
				}
			?>
			<br />
			
			<h1> Запити з множинним порівнянням </h1>
			
			<h4><?= $queries[5]->getText()?></h4>
			<p><textarea cols = "50" rows = "15" readonly style = "font-family: Courier;"><?= $queries[5]->getSql()?></textarea></p>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "5" />
				<p>complex_name: <input type = "text" name = "complex_name"
				<?= ($queryId == 5 ? 'value = "'.$_GET['complex_name'].'"' : '')?> /></p>
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 5) {
					echo $queries[5]->getHtmlResults([$_GET['complex_name']]);
				}
			?>
			<br />
			
			<h4><?= $queries[6]->getText()?></h4>
			<p><textarea cols = "50" rows = "15" readonly style = "font-family: Courier;"><?= $queries[6]->getSql()?></textarea></p>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "6" />
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 6) {
					echo $queries[6]->getHtmlResults();
				}
			?>
			<br />
			
			<h4><?= $queries[7]->getText()?></h4>
			<p><textarea cols = "50" rows = "15" readonly style = "font-family: Courier;"><?= $queries[7]->getSql()?></textarea></p>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "7" />
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 7) {
					echo $queries[7]->getHtmlResults();
				}
			?>
			<br />
			
			
			<h1>Написати запит руцями:</h1>
			<form action = "queries_page.php" method = "GET">
				<input type = "hidden" name = "query_id" value = "8" />
				<p><textarea name = 'sql' cols = "50" rows = "10" style = "font-family: Courier;"><?= ($queryId == 8 ? $_GET['sql'] : '')?></textarea>
				</p>
				<input type = "submit" class = "btn btn-success" value = "Виконати" />
			</form>
			<?php
				if($queryId == 8) {
					$query = new SqlQuery($_GET['sql']);
					echo $query->getHtmlResults();
				}
			?>
			<br />
		</div>
	</body>
</html>