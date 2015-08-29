<?php
namespace framework;

class string{	
	public function explode($array, $explode){
		return explode($explode, $array);
	}
	
	public function breakExplode($array){
		$results = explode('{BREAK}', $array);
		return $results;
	}

	public function breakExplodeVars($array){
		$results = explode('{BREAK}', $array);
		foreach($results as $resultKey => $result){
			$this->{'text_'.$resultKey} = trim($result);
		}
	}
	public function formatDate($dateFormat, $dateKey){
		return date($dateFormat, strtotime($dateKey));
	}

	public function formatGmDate($dateFormat, $timestamp){
		return gmdate($dateFormat, $timestamp);
	}
	
	public function between($left, $right, $in){
	    preg_match('/'.$left.'(.*?)'.$right.'/s', $in, $match);
	    return empty($match[1]) ? NULL : $match[1];
	}
}
?>