<?php
namespace Antfuentes\Titan\Framework;

class String{	
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

	public function breakByGroupSize($groupSize, $array, $dir, $html){
		$this->array = $array;
		$this->count = count($this->array);
		$this->counter = 0;
		if($groupSize == 1){
			foreach($this->array as $this->key => $this->content){
			    require($dir.'/'.$html.'.php');
			}
		}else{
			for ($z = 0; $z < $groupSize; $z++){
			    $this->key[] = $z;
			}

			$numGroups = count($array) / $groupSize;
			$numGroups = floor($numGroups);
			
			for($g = 0; $g < $numGroups; $g++){
			    require($dir.'/'.$html.'.php');
			    for ($i = 0; $i < $groupSize; $i++){
			        $this->key[$i] += $groupSize;
			    }    
			}
		}
		unset($this->key);
	}

	public function replaceString($search, $replaceWith, $string){
		$cleanString = str_replace($search, $replaceWith, $string);
		return $cleanString;
	}

	public function getFileExt($array){
		$results = explode('.', $array);
		return $results[1];

	}

	public function removeFileExt($filename){	
		// Explode filename by period(.)
		//$filename is generaly basename(__FILE__)
		$filenameExplode = $this->explode($filename, '.');

		//Set variable to first explode result
		$filenameNoExt = $filenameExplode[0];
		
		//return filenameNoExt	
		return $filenameNoExt;
	}

}
?>