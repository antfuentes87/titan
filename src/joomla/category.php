<?php
namespace joomla;

use framework\html;

class category extends database{
	public function categoryContent($id){
		$this->tables();
		$query = "SELECT * FROM $this->categories WHERE id = '$id' ORDER BY id DESC";
		$results = $this->q($query);
		$this->variables($results);
	}
	
	public function content($id, $itemId, $showLimit, $template){
		$this->tables();
		$query = "SELECT * FROM $this->content WHERE catid = '$id' ORDER BY id DESC";
		$results = $this->q($query);

		$total = count($results);
		$pages = @$_GET["page"];
		$pagesTotal = ceil($total / $showLimit);
		if($pages < 1){
			$pages = 1;
		}else{ 
			$pages;
		}
		
		$start = ($pages - 1) * ($showLimit);
		$resultsSlices = array_slice($results,$start,$showLimit);
		$this->pagesTotal = $pagesTotal;
		$this->showLimit = $showLimit;
		foreach($resultsSlices as $resultsKey => $resultSlice){
			foreach ($resultSlice as $column => $data){
				$this->{$column} = $data;
			}
			require($template.'.php');
		}
	}
	
	public function deploy($id, $itemId, $showLimit, $template, $alias, $view, $categoryTitle, $schema = ''){
		$html = new html();
		$attr = '{"id":"'.$alias.'-'.$view.'"}';
		$html->b('main', 0, 1, $schema, $attr);
			$html->b('h1', 0, 1);
				$html->e(1, $categoryTitle);
			$html->b('h1', 1, 1);
			$this->content($id, $itemId, $showLimit, $template);
		$html->b('main', 1, 1);
	}
}

?>