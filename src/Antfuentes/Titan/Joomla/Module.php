<?php
namespace Antfuentes\Titan\Joomla;

use Antfuentes\Titan\Joomla\Database;

class module extends article{
	public function loadSections($dir, $section){
		$dir .= '/views/com_content/article/';
		$dir .= $section;
		$this->sections($dir, $section, $section);
	}
	
	public function getContentCatergoryAlias($alias){
		$db = new Database;
		$db->tables();
	
		$results = $db->q("SELECT * FROM `$db->categories` WHERE alias = '$alias'");
		$this->catid = $results[0]['id'];
		
		$results = $this->q("SELECT alias FROM `$db->categories` WHERE id = '$this->catid'");
		$this->alias = $results[0]['alias'];
		
		$results = $this->q("SELECT id FROM `$db->menu` WHERE alias = '$this->alias'");
		$this->itemId = $results[0]['id'];
		
		$results = $db->q("SELECT * FROM `$db->content` WHERE catid = '$this->catid' ORDER BY id DESC LIMIT 0,4");
		return $results;
	}
}
?>