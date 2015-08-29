<?php
namespace framework;

class element{
	public function schema($schemas){
		$html = '';
		$schemas = json_decode($schemas);
		foreach($schemas as $schemaKey => $schema){
			if($schemaKey == 'itemtype' || $schemaKey == 'itemType'){
				$html .= ENCschema::itemScope.'  '.$schemaKey.'='.'"'.ENCschema::URL.$schema.'"';
			}else{
				$html .= $schemaKey.'='.'"'.$schema.'"';
			}
		}
		return $html;
	}
	
	public function attributes($attributes){
		$html = '';
		$attributes = json_decode($attributes);
		foreach($attributes as $attributeKey => $attribute){
			if($attributeKey <> '' AND $attribute <> ''){
				$html .= $attributeKey.'='.'"'.$attribute.'"';	
			}
			if($attributeKey <> '' AND $attribute == ''){
				$html .= $attributeKey;	
			}
			if($attributeKey == '' AND $attribute <> ''){
				$html .= $attribute;
			}
		}
		return $html;
	}
	
	public function open($element, $schemas = '', $attributes = ''){
		$html = '<';
		$html .= $element.' ';
		
		if($schemas <> ''){
			$html .= $this->schema($schemas);
		}
		if($attributes <> ''){
			$html .= $this->attributes($attributes);	
		}
		
		$html .= '>';
		echo "\n";
		
		return $html;
	}
	
	public function close($element){
		$html = '</';
		$html .= $element;
		$html .= '>';
		echo "\n";
		
		return $html;
	}
}
?>