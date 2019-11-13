<?php

class CityManage {
	public static function create($name) {
		require "bootstrap.php";
		
		$city = new City();

		$city->setName($name);
				
		$entityManager->persist($city);
		$entityManager->flush();
	}
	
	public static function edit($id, $name) {
		require "bootstrap.php";
		
		$city = $entityManager->find('City', $id);
		if(!$city) {
			echo "There is no city with id $id";
			return;
		}

		$city->setName($name);
				
		$entityManager->flush();
	}	
	
	public static function del($id) {
		require "bootstrap.php";
		
		$city = $entityManager->find('City', $id);
		if(!$city) {
			echo "There is no city with id $id";
			return;
		}
		
		$complexes = $city->getComplexes();
		if($complexes) {
			foreach($complexes as $complex) {
				if($complex) {
					ComplexManage::del($complex->getId());
				}
			}
		}
		
		$entityManager->remove($city);
		
		$entityManager->flush();
	}
		
	public static function find($id) {
		require "bootstrap.php";
		return $entityManager->find("City", $id);
	}
	

}

class LocalityManage {
	public static function create($name) {
		require "bootstrap.php";
		$locality = new Locality();
		
		$locality->setName($name);
				
		$entityManager->persist($locality);
		$entityManager->flush();
	}
	
	public static function edit($id, $name) {
		require "bootstrap.php";
		
		$locality = $entityManager->find('Locality', $id);
		if(!$locality) {
			echo "There is no locality with id $id";
			return;
		}

		$locality->setName($name);
				
		$entityManager->flush();
	}
	
	
	public static function del($id) {
		require "bootstrap.php";
		
		$locality = $entityManager->find('Locality', $id);
		if(!$locality) {
			echo "There is no locality with id $id";
			return;
		}
		
		$entityManager->remove($locality);
		
		$entityManager->flush();
	}
	
	
	public static function find($id) {
		require "bootstrap.php";
		return $entityManager->find("Locality", $id);
	}
	
}

class ComplexManage {
	public static function create($name, $cityId) {
		require "bootstrap.php";
		$city = $entityManager->find('City', $cityId);
		if(!$city) {
			echo "There is no city with id $cityId";
			return;
		}
		
		$complex = new Complex();
		
		$complex->setName($name);
		$complex->setCity($city);
				
		$entityManager->persist($complex);
		$entityManager->flush();
	}
	
	public static function edit($id, $name, $cityId) {
		require "bootstrap.php";
		
		$complex = $entityManager->find('Complex', $id);
		if(!$complex) {
			echo "There is no complex with id $id";
			return;
		}
		$city = $entityManager->find('City', $cityId);
		if(!$city) {
			echo "There is no city with id $cityId";
			return;
		}

		$complex->setName($name);
		$complex->setCity($city);
				
		$entityManager->flush();
	}
	
	
	public static function del($id) {
		require "bootstrap.php";
		$complex = $entityManager->find('Complex', $id);
		if(!$complex) {
			echo "There is no complex with id $id";
			return;
		}
		$houses = $complex->getHouses();
		foreach($houses as $house) {
			if($house) {
				HouseManage::del($house->getId());
			}
		}
		
		
		$entityManager->remove($complex);
		
		$entityManager->flush();
	}
	
	
	public static function find($id) {
		require "bootstrap.php";
		return $entityManager->find("Complex", $id);
	}
	
}

class HouseManage {
	public static function create($name, $complexId) {
		require "bootstrap.php";
		
		$complex = $entityManager->find('Complex', $complexId);
		if(!$complex) {
			echo "There is no complex with id $complexId";
			return;
		}
		
		$house = new House();

		$house->setName($name);
		$house->setComplex($complex);
		
		$entityManager->persist($house);
		$entityManager->flush();
	}
	
	public static function edit($id, $name, $complexId) {
		require "bootstrap.php";
		
		$complex = $entityManager->find('Complex', $complexId);
		if(!$complex) {
			echo "There is no complex with id $complexId";
			return;
		}
		
		$house = $entityManager->find('House', $id);
		if(!$house) {
			echo "There is no house with id $id";
			return;
		}
		
		$house->setName($name);
		$house->setComplex($complex);
		
		$entityManager->flush();
	}
	
	
	public static function del($id) {
		require "bootstrap.php";
		
		$house = $entityManager->find('House', $id);
		if(!$house) {
			echo "There is no house with id $id";
			return;
		}
		$complex = $house->getComplex();
		$complex->getHouses()->removeElement($house);
		$house->setComplex(null);
		
		$flats = $house->getFlats();
		foreach($flats as $flat) {	
			if($flat) {
				FlatManage::del($flat->getId());
			}
		}
		$entityManager->remove($house);

		$entityManager->flush();
	}
	
	public static function find($id) {
		require "bootstrap.php";
		return $entityManager->find("House", $id);
	}
}
class FlatManage {
	public static function create($houseId, $flatTypeId, $square, $price) {
		require "bootstrap.php";
		
		$house = $entityManager->find('House', $houseId);
		if(!$house) {
			echo "There is no house with id $houseId";
			return;
		}
		$flatType = $entityManager->find("FlatType", $flatTypeId);
		if(!$flatType) {
			echo "There is no flat type with id $flatTypeId";
			return;
		}
		
		$flat = new Flat();

		$flat->setHouse($house);
		$flat->setFlatType($flatType);
		$flat->setSquare($square);
		$flat->setPrice($price);
		
		$entityManager->persist($flat);
		$entityManager->flush();
	}
	
	public static function edit($id, $houseId, $flatTypeId, $square, $price) {
		require "bootstrap.php";
		
		$house = $entityManager->find('House', $houseId);
		if(!$house) {
			echo "There is no house with id $houseId";
			return;
		}
		$flatType = $entityManager->find("FlatType", $flatTypeId);
		if(!$flatType) {
			echo "There is no flat type with id $flatTypeId";
			return;
		}
		
		$flat = $entityManager->find('Flat', $id);
		if(!$flat) {
			echo "There is no flat with id $id";
			return;
		}

		$flat->setHouse($house);
		$flat->setFlatType($flatType);
		$flat->setSquare($square);
		$flat->setPrice($price);
		
		$entityManager->flush();
	}
	
	public static function del($id) {
		require "bootstrap.php";
		
		$flat = $entityManager->find('Flat', $id);
		if(!$flat) {
			echo "There is no flat with id $id";
			return;
		}
		$house = $flat->getHouse();
		$house->getFlats()->removeElement($flat);
		$flatType = $flat->getFlatType();
		$flatType->getFlats()->removeElement($flat);
		
		
		$entityManager->remove($flat);

		$entityManager->flush();
	}
	

	public static function find($id) {
		require "bootstrap.php";
		return $entityManager->find("Flat", $id);
	}
}

class QueryRunner {
	public static function runDqlQuery($dql) {
		require "bootstrap.php";
		$query = $entityManager->createQuery($dql);
		return $query->getArrayResult();
	}
	
	public static function getAllComplexes() {		
		$dql = "SELECT c, ct, l FROM Complex c JOIN c.city ct  JOIN c.localities l";
		return QueryRunner::runDqlQuery($dql);
	}
	
	public static function getAllHouses() {		
		$dql = "SELECT h, c, ct, l FROM House h JOIN h.complex c JOIN c.city ct JOIN c.localities l ORDER BY c.id";
		return QueryRunner::runDqlQuery($dql);
	}
	
	public static function getAllFlats() {		
		$dql = "SELECT f, ft, h, c, ct, l FROM Flat f JOIN f.flatType ft JOIN f.house h JOIN h.complex c JOIN c.city ct JOIN c.localities l";
		return QueryRunner::runDqlQuery($dql);
	}
	
	public static function getAllCities() {		
		$dql = "SELECT l FROM City l";
		return QueryRunner::runDqlQuery($dql);
	}
	
	public static function getAllLocalities() {		
		$dql = "SELECT l, c, ct FROM Locality l JOIN l.complexes c JOIN c.city ct";
		return QueryRunner::runDqlQuery($dql);
	}
	
	public static function getAllComplexNamesAsArray() {
		$dql = "SELECT c FROM Complex c";
		$res = QueryRunner::runDqlQuery($dql);
		$resultAsArr = [];
		foreach($res as $row) {
			array_push($resultAsArr, $row['name']);
		}
		return $resultAsArr;
	}
	
	public static function getAllFlatTypesAsArray() {
		$dql = "SELECT c FROM FlatType c";
		$res = QueryRunner::runDqlQuery($dql);
		$resultAsArr = [];
		foreach($res as $row) {
			array_push($resultAsArr, $row['name']);
		}
		return $resultAsArr;
	}
	
	public static function getAllCitiesAsArray() {
		$dql = "SELECT ct FROM City ct ";
		$res = QueryRunner::runDqlQuery($dql);
		$resultAsArr = [];
		foreach($res as $row) {
			$city = $row['name'];
			if(!in_array($city, $resultAsArr)) {
				array_push($resultAsArr, $city);
			}
		}
		return $resultAsArr;
	}
	
	public static function getAllLocalitiesAsArray() {
		$dql = "SELECT lt FROM Locality lt";
		$res = QueryRunner::runDqlQuery($dql);
		$resultAsArr = [];
		foreach($res as $row) {
			$locality = $row['name'];
			if(!in_array($locality, $resultAsArr)) {
				array_push($resultAsArr, $locality);
			}
		}
		return $resultAsArr;
	}
}
?>