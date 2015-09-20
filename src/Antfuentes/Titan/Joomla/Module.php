<?php
namespace Antfuentes\Titan\Joomla;

use Antfuentes\Titan\Joomla;

class Module extends Article{
	public function loadSections($routerId, $categoryParentId, $dir, $articleAlias){
		$this->routerId = $routerId;
		$this->categoryParentId = $categoryParentId;
		$dir .= '/views/com_content/article/';
		$dir .= $articleAlias;
		$this->sections($routerId, $dir, $articleAlias, $articleAlias);
	}
	
	public function getContentCatergoryAlias($alias){
		$db = new Joomla\Database;
		$db->tables();
	
		$results = $db->q("SELECT * FROM `$db->categories` WHERE alias = '$alias'");
		$this->catid = $results[0]['id'];
		
		$results = $this->q("SELECT alias FROM `$db->categories` WHERE id = '$this->catid'");
		$this->alias = $results[0]['alias'];
		
		$results = $this->q("SELECT id FROM `$db->menu` WHERE alias = '$this->alias'");
		$this->itemId = $results[0]['id'];
		
		$results = $db->q("SELECT * FROM `$db->content` WHERE catid = '$this->catid' ORDER BY id DESC");
		return $results;
	}

	public function stateCityLoadCategoryByAlias($routerId, $categoryAlias){
		$db = new Joomla\Database;
		$db->tables();

		$this->routerId = $routerId;
		$this->categoryAlias = $categoryAlias;

		$query = $db->q("SELECT catid FROM `$db->content` WHERE id = '$this->routerId'");
		$categoryId = $query[0]['catid'];

		$query = $db->q("SELECT path FROM `$db->categories` WHERE id = '$categoryId'");
		$categoryPath = $query[0]['path'];
		$categoryPathExplode = explode('/', $categoryPath);

		$categoryState = $categoryPathExplode[0];
		$categoryCity = $categoryPathExplode[1];
		$category = $this->categoryAlias;

		$categoryModulePath = $categoryState;
		$categoryModulePath .= '/';
		$categoryModulePath .= $categoryCity;
		$categoryModulePath .= '/';
		$categoryModulePath .= $category;

		$query = $db->q("SELECT id FROM `$db->categories` WHERE path = '$categoryModulePath'");
		$categoryModuleId = $query[0]['id'];

		$query = $db->q("SELECT * FROM `$db->content` WHERE catid = '$categoryModuleId' ORDER BY id ASC");

		return $query;
	}

	public function loadArticleByAlias($articleAlias, $routerCategoryId, $routerBase){
		$db = new Joomla\Database;
		$router = new Joomla\Router;	
		$db->tables();

		$this->articleAlias = $articleAlias;
		$this->routerCategoryId = $routerCategoryId;
		$this->routerBase = $routerBase;

		$articleResults = $db->q("SELECT * FROM `$db->content` WHERE alias = '$this->articleAlias'");

		foreach($articleResults as $articleResultsKey => $articleResult){
			foreach ($articleResult as $articleColumn => $articleData) {
				$this->{$articleColumn} = $articleData;
			}
		}

		$categoryResults = $db->q("SELECT alias, parent_id FROM `$db->categories` WHERE id = '$this->catid'");
		$this->categoryAlias = $categoryResults[0]['alias'];
		$this->categoryParentId = $categoryResults[0]['parent_id'];

		require($router->buildArticleRoute($this->categoryAlias, $this->articleAlias));
	}
}
?>