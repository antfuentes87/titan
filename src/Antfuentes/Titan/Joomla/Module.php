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

	public function loadArticleByAlias($articleAlias, $routerId, $routerView, $routerCategoryParentId, $routerBase){
		//Create new instance of the Joomla\Database Class
		$db = new Joomla\Database;

		//Create new instance of the Joomla\Router class
		$router = new Joomla\Router;

		//Select all table names from database
		//Remove table prefix and build variables for each table name in the database
		//e.g. -- $this->content woud output the content table name with the correct table prefix
		$db->tables();

		//Set function variables to class variables
		
		//routerView can ether be article OR category OR categories
		$this->routerView = $routerView;
		
		//routerId can be one of two things
		//id of the article (if the routerView == article) OR category id (if the routerView == category OR categories)
		$this->routerId = $routerId;

		//articleAlias is the alias of the article
		//The alias is the title of the article but everything is converted to lowercase
		//All spaces converted into dashes
		//All speical characters are stripped as well
		$this->articleAlias = $articleAlias;

		//routerCategoryParentId is the categories parent id
		//If routerCategoryParentId is equal to 1 then use routerId
		$this->routerCategoryParentId = $routerCategoryParentId;
		
		//routerBase is the root url of the project
		//Where the Joomla files are located
		$this->routerBase = $routerBase;

		$query = $db->q("SELECT * FROM `$db->content` WHERE alias = '$this->articleAlias'");
		
		//Loop through results from above query
		//Create variables for each column for access in the required file
		foreach($query as $articleResultsKey => $articleResult){
			foreach ($articleResult as $articleColumn => $articleData) {
				$this->{$articleColumn} = $articleData;
			}
		}
		
		//Get the category alias by the cat id variable generated from above in the foreach loop
		$query = $db->q("SELECT alias FROM `$db->categories` WHERE id = '$this->catid'");
	
		//Assign a variable for the category alias
		$this->categoryAlias = $query[0]['alias'];
		
		//Build the routed path by the category alias and article alias
		//Stick the path inside of require
		require($router->buildArticleRoute($this->categoryAlias, $this->articleAlias));
	}
}
?>