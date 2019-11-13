<?php

use Doctrine\ORM\Query\ResultSetMapping;

class SqlQuery {
	protected $text;
	protected $sql;
	
	public function __construct($sql, $text = "")
	{
		$this->sql = $sql;
		$this->text = $text;
	}
	
	public function getSql()
	{
		return $this->sql;
	}
	
	public function getText()
	{
		return $this->text;
	}
	
	public function execute($params = [])
	{
		require "bootstrap.php";
		$stmt = $entityManager->getConnection()->prepare($this->sql);
		for($i = 0; $i < count($params); $i++) {
			$stmt->bindValue($i + 1, $params[$i]);
		}
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	private static function genRow($arr)
	{
		$result = "";
		foreach ($arr as $x) {
			$result .= "<td>$x</td>";
		}
		return "<tr>$result</tr>";
	}

	public function getHtmlResults($params = []) {
		$results = $this->execute($params);
		
		if (count($results) == 0) {
			return "<p> Result is empty:(( </p>";
		}
		else {
			$tRows = '';
			$columns = array_keys($results[0]);
			$tRows .= self::genRow($columns);
			foreach ($results as $row) {
				$tRow = [];
				foreach ($columns as $column) {
					$tRow[] = $row[$column];
				}
				$tRows .= self::genRow($tRow);
			}
			
			return '<table class = "table table-striped">'.$tRows.'</table>';	
		}
	}
}