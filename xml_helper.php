<?php
class XmlHelper {
	const TAB = '      ';
	
	
	private static function makeLine($line, $level) {
		$indents = '';
		for($i = 0; $i < $level; $i++) {
			$indents .= self::TAB;
		}
		return $indents . $line . PHP_EOL;
	}
	
	private static function getXmlFromArray($arr, $level) {
		$result = '';
		foreach($arr as $key=>$value) {
			if($key == 'id') continue;
			$result .= self::makeLine('<' . $key . '>', $level);
			if(is_array($value)) {
				$result .= self::getXmlFromArray($value, $level + 1);
			}
			else {
				$result .= self::makeLine(strval($value), $level + 1);
			}
			$result .= self::makeLine('</' . $key . '>', $level);
		}
		return $result;
	}
	
	public static function getXmlEntityFromArray($arr, $entityName) {
		$result = '';
		foreach($arr as $row) {
			$result .= self::makeLine('<' . $entityName . '>', 0).
				self::getXmlFromArray($row, 1) . self::makeLine('</' . $entityName . '>', 0); 
		}
		return $result;
	}
}