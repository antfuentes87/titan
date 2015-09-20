<?php
namespace Antfuentes\Titan\Joomla;

use Antfuentes\Titan\Framework;

class Article extends Database{

	//Use this to make variables out of the Catergory Path based on the Article Id
	public function getCatergoryPath($articleId){
		//Include String Class
		$string = new Framework\String;
		
		//Grab table names from Database Class
		$this->tables();
		
		//Grab the cat id based off the article id
		$results = $this->q("SELECT catid FROM $this->content WHERE id = '$articleId'");
		$articleCatId = $results[0]['catid'];
		
		//Grab the path based off the catid that was based off the article id
		$results = $this->q("SELECT path FROM $this->categories WHERE id = '$articleCatId'");
		$catPath = $results[0]["path"];
		
		//Explode the path by forward slash
		$results = $string->explode($catPath, '/');
		
		//Loop through the exploded path
		//Create a current object variable and assign it $result (which is each part of the $results array)
		foreach($results as $key => $result){
			$this->{'path_'.$key} = trim($result);
		}
	}

	public function deploy($element, $flag, $alias = '', $view = '', $schema = ''){
		$h = new Html();
		if($flag == 0){
			$attr = '{"id":"'.$alias.'-'.$view.'"}';
			$h->b($element, 0, 1, $schema, $attr);
		}else{
			$h->b($element, 1, 1);
		}
	}

	public function content($id){		
		$this->tables();
		$query = "SELECT * FROM $this->content WHERE id = '$id' AND state = 1 ORDER BY id DESC";
		$results = $this->q($query);
		$this->variables($results);
	}

	public function introtext($id){		
		$this->tables();
		$query = "SELECT introtext FROM $this->content WHERE id = '$id' AND state = 1 ORDER BY id DESC";
		$results = $this->q($query);
		$this->variables($results);
	}
	
	public function single($colum, $id){		
		$this->tables();
		$query = "SELECT '$colum' FROM $this->content WHERE id = '$id' AND state = 1 ORDER BY id DESC";
		$results = $this->q($query);
		$this->variables($results);
	}

	public function singleArticle($dir, $articleAlias, $routerId,  $file = 'default'){
		$path = $dir;
		$path .= '/';
		$path .= $articleAlias;
		$path .= '/';
		$path .= $file;
		$path .= '.php';

		$this->articleAlias = $articleAlias;
		$this->routerId = $routerId;

		require($path);
	}

	public function sectionContent($articleAlias, $sectionAlias){
		$this->tables();
		$results = $this->q("SELECT id FROM $this->categories WHERE alias = '$articleAlias'");
		$catid = $results[0]['id'];
		$results = $this->q("SELECT * FROM $this->content WHERE catid = '$catid' AND state = 1 AND alias = '$sectionAlias'");
		$this->variables($results);
	}

	public function stateCitySectionContent($routerId, $articleAlias, $sectionAlias){
		//Rebuild required variables back into the class for later use
		$this->routerId = $routerId;
		$this->articleAlias = $articleAlias;
		$this->sectionAlias = $sectionAlias;

		//Select all table names from database
		//Remove table prefix and build variables for each table name in the database
		//e.g. -- $db->content woud output the content table name with the correct table prefix
		$this->tables();

		//Select catid from content table where any id matching the Router Id
		//The router id is the article id
		//The article is located in the Static category
		$query = $this->q("SELECT catid FROM $db->content WHERE id = '$this->routerId'");

		//Create a varaible to store the catid from the above query
		$categoryId = $query[0]['catid'];

		//Select path from categories table where any id mataching the category id variable created above
		$query = $this->q("SELECT path FROM $db->categories WHERE id = '$categoryId'");

		//Create a variable to store the path from the above query
		//The catergory path will be the State / City / Static
		$categoryPath = $query[0]['path'];

		//Explode the category path variable above by a foward slash
		$categoryPathExplode = explode('/', $categoryPath);

		//Create a variable to store the first part of the exploded category path explode array
		//This variable is the state
		$state = $categoryPathExplode[0];

		//Create a variable to store the second part of the exploded category path explode array
		//This variable is the city
		$city = $categoryPathExplode[1];

		//Create a variable to store the third part of the exploded category path explode array
		//This variable is the really the article alias of the article that is in the Static category
		//e.g. -- Home, FAQ, Contact, etc.. Any articles you create inside of the Static category
		$category = $this->articleAlias;

		//Build the category section path
		//This variable is created so we can look for this path in the categories table
		$categorySectionPath =  $state;
		$categorySectionPath .= '/';
		$categorySectionPath .= $city;
		$categorySectionPath .= '/';
		$categorySectionPath .= $category;

		//Select id from categories table where any path mataching the section path variable created above
		//There will be only one of these, as each city requires a Static category, with the Static articles inside
		$query = $this->q("SELECT id FROM $db->categories WHERE path = '$categorySectionPath'");

		//Create a varaible to store the category id from the above query
		$categoryId = $query[0]['id'];

		//Select all columns / rows with a mathcing alias of the section alias and the category id from variable created above
		$query = $this->q("SELECT * FROM $db->content WHERE alias = '$this->sectionAlias' AND catid = '$categoryId'");


		//Build query into variables that you can use in the section.php
		$this->variables($query);
	}

	public function sections($routerId, $dir, $articleAlias, $outerElement){
		$h = new Framework\Html();

		$this->routerId = $routerId;
		$this->articleAlias = $articleAlias;
		$sections = scandir($dir);
		
		$h->b($outerElement, 0, 1, '', '{"id":"'.$this->articleAlias.'"}');
			foreach($sections as $sectionKey => $section){
				if($section <> '.' AND $section <> '..'){
					$searchSection = strpos($section, PHP_EXT);
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