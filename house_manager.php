<?php

class HouseManage {
	public static function create($name, $city) {
		require_once "bootstrap.php";
		
		$complex = new Complex();

		$complex->setName($name);
		$complex->setCity($city);
				
		$entityManager->persist($complex);
		$entityManager->flush();
	}
	
	
}

?>