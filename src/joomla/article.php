<?php
namespace joomla;

use framework\html;

class article extends database{	
	public function deploy($element, $flag, $alias = '', $view = '', $schema = ''){
		$h = new html();
		if($flag == 0){
			$attr = '{"id":"'.$alias.'-'.$view.'"}';
			$h->b($element, 0, 1, $schema, $attr);
		}else{
			$h->b($element, 1, 1);
		}
		
	}

	public function content($id){		
		$this->tables();
		$query = "SELECT * FROM $this->content WHERE id = '$id' ORDER BY id DESC";
		$results = $this->q($query);
		$this->variables($results);
	}

	public function introtext($id){		
		$this->tables();
		$query = "SELECT introtext FROM $this->content WHERE id = '$id' ORDER BY id DESC";
		$results = $this->q($query);
		$this->variables($results);
	}
	
	public function single($colum, $id){		
		$this->tables();
		$query = "SELECT '$colum' FROM $this->content WHERE id = '$id' ORDER BY id DESC";
		$results = $this->q($query);
		$this->variables($results);
	}

	public function sectionContent($articleAlias, $sectionAlias){
		$this->tables();
		$results = $this->q("SELECT id FROM $this->categories WHERE alias = '$articleAlias'");
		$catid = $results[0]['id'];
		$results = $this->q("SELECT * FROM $this->content WHERE catid = '$catid' AND alias = '$sectionAlias'");
		$this->variables($results);
	}

	public function sections($dir, $articleAlias, $outerElement){
		$h = new html();
		$db = new database();

		$this->articleAlias = $articleAlias;
		$sections = scandir($dir);
		
		$h->b($outerElement, 0, 1, '', '{"id":"'.$this->articleAlias.'"}');
		foreach($sections as $sectionKey => $section){
			if($section <> '.' AND $section <> '..'){
				$searchSection = strpos($section, PHP_FILE_EXT);
				if($searchSection == false){
					$sectionExplode = explode('_', $section);
					$this->sectionId = $sectionExplode[0];
					$this->sectionType = $sectionExplode[1];
					$this->sectionAlias = $this->sectionId.'-'.$this->sectionType;
					if($this->sectionType == 'section'){
						$h->b('section', 0, 1, '', '{"id":"'.$this->sectionType.'-'.$this->sectionId.'"}');
								require($dir.'/'.$section.'/'.'section.php');
						$h->b('section', 1, 1);
					}elseif($this->sectionType == 'parallax'){
						require_once($dir.'/'.$section.'/'.'parallax.php');
						$h->b('section', 0, 1, '', '{"id":"'.$this->sectionType.'-'.$this->sectionId.'", "style":"background-image: url('.$parallaxBackground.');"}');
						$parallax = scandir($dir.'/'.$section);
						foreach ($parallax as $parallaxKey => $parallaxSection){
							if($parallaxSection <> '.' AND $parallaxSection <> '..'){
								$parallaxSearchSection = strpos($parallaxSection, PHP_FILE_EXT);
								if($parallaxSearchSection == false){
									$parallaxSectionExplode = explode('_', $parallaxSection);
									$this->parallaxSectionId = $parallaxSectionExplode[0];
									$this->parallaxSectionType = $parallaxSectionExplode[1];
									$this->parallaxSectionAlias = $this->parallaxSectionId.'-'.$this->parallaxSectionType;
									$h->b('section', 0, 1, '', '{"id":"'.$this->parallaxSectionType.'-'.$this->parallaxSectionId.'"}');
										require($dir.'/'.$section.'/'.$parallaxSection.'/'.'section.php');
									$h->b('section', 1, 1);
								}
							}
						}
						$h->b('section', 1, 1);
					}
				}
			}
		}
		$h->b($outerElement, 1, 1);
	}
}

?>