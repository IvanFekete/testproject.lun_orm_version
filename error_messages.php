<?php
	class ErrorMessages {
		public static function getUnexpectedErrorMessage() {
			return '<div class="alert alert-danger">
						<strong>Помилка!</strong> Виникла непередбачувана помилка. Перейти на 
							<a href = "admin.php">Адмін</a> або <a href = "client.php">Клієнт</a>.
					</div>';
		}
		
		public static function getAuthErrorMessage() {
			return '<div class="alert alert-danger">
						<strong>Помилка!</strong> Виникла помилка при авторизації. Перейти на 
							<a href = "admin.php">Адмін</a> або <a href = "client.php">Клієнт</a>.
					</div>';
		}
		
		public static function getMessage($square, $price) {
			$msg = "";
			if(!DataFormatter::isInteger($price)) {
				$msg .= '<div class="alert alert-danger">
						<strong>Помилка!</strong> Ціна має бути цілим додатнім числом.
					</div>';
			}
			if(!DataFormatter::isFloat($square)) {
				$msg .= '<div class="alert alert-danger">
						<strong>Помилка!</strong> Площа має бути додатнім числом.
					</div>';
			}
			return $msg;
		}
	}
?>