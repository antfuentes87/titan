<?php
namespace framework;

class html extends element{	
	public function b($element, $flag, $return, $schema = '', $attributes = ''){
		if($flag == 0){
			if($return == 0){
				return $this->open($element, $schema, $attributes);	
			}else{
				echo $this->open($element, $schema, $attributes);	
			}
		}else{
			if($return == 0){
				return $this->close($element);
			}else{
				echo $this->close($element);
			}
		}
	}
	
	public function e($return, $string){
		if($return == 0){
			return $string;
		}else{
			echo $string;
		}
	}
}
?>