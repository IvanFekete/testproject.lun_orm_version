<?php
include "db_manager.php"

class FlatTypeManage {
	public static function create($name) {
		require "bootstrap.php";
		
		$ft = new FlatType();

		$ft->setName($name);
				
		$entityManager->persist($ft);
		$entityManager->flush();
	}
}

$arr = [
'студія',
'1к',
'2к',
'3к',
'4к',
'5к',
'6к',
'5к дворівнева',
'6к дворівнева'
];

foreach($arr as $x) {
	FlatTypeManage::create($x);
}

$arr = [
'Київ',
'Харків',
'Одеса',
'Львів',
'Дніпро',
];

foreach($arr as $name) {
	CityManage::create($name);
}


