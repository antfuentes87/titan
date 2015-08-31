<?php
namespace Antfuentes\Titan\Joomla;

use Antfuentes\Titan\Joomla\Article;
use Antfuentes\Titan\Joomla\Category;
use Antfuentes\Titan\Framework\Html;
use Pelago\Emogrifier;

class Router extends Database{
	public function load($id, $path, $option, $view, $layout, $config, $instance, $base){
		$this->tables();
		$this->routerView = $view;
		$this->routerId = $id;
		$this->routerSiteName = $config->get('sitename');
		$this->routerMailFrom = $config->get('mailfrom');
		$this->routerInstance = $instance;
		$this->routerBase = $base;
		
		if($this->routerView  == 'article'){
			$q = "SELECT id, alias, catid FROM $this->content WHERE id = '$id'";
			$result = $this->q($q);
			
			$this->categoryId = $result[0]['catid'];
			$this->articleAlias = $result[0]['alias'];

			$q = "SELECT title, alias, parent_id FROM $this->categories WHERE id = '$this->categoryId'";
			$result = $this->q($q);

			$this->categoryAlias = $result[0]['alias'];
			$this->categoryTitle = $result[0]['title'];
			$this->categoryParentId = $result[0]['parent_id'];

			if($this->categoryAlias == 'static'){
				$pathAlias = $this->articleAlias;
			}else{
				$pathAlias = $this->categoryAlias;
			}
		}

		if($this->routerView == 'category'){
			$q = "SELECT title, alias, parent_id FROM $this->categories WHERE id = '$id'";
			$result = $this->q($q);

			$this->categoryAlias = $result[0]['alias'];
			$this->categoryTitle = $result[0]['title'];
			$this->categoryParentId = $result[0]['parent_id'];

			$pathAlias = $this->categoryAlias;
		}

		if($this->routerView == 'categories'){
			if($id == 0){
				$id = 1;
			}
			$q = "SELECT title, alias FROM $this->categories WHERE id = '$id'";
			$result = $this->q($q);
			$this->dump($result);
			$this->categoryAlias = $result[0]['alias'];
			$this->categoryTitle = $result[0]['title'];
			$pathAlias = $this->categoryAlias;
		}

		if($option){
			$path .= $option . '/';
		}
				
		if($view){
			$path .= $view . '/';
		}
			
		if($layout){
			$path .= $layout . '/';
		}

		if($pathAlias){
			$path .= $pathAlias . '/';
		}

		$path .= 'default.php';
		
		$this->path = $path;
	}
	
	public function init(){
		ob_start();
		require($this->path);
		$data = ob_get_contents();
		ob_end_clean();
		return $data;
	}
	
	public function meta(){
		if($this->routerView == 'article'){
			$h = new Html();
			$article = new Article();
			$article->content($this->routerId);
			$images = json_decode($article->images);
			$metadata = json_decode($article->metadata);
			$h->b('meta', 0, 1, '', '{"charset":"utf-8"}');
			$h->b('meta', 0, 1, '', '{"http-equiv":"X-UA-Compatible", "content":"IE=edge"}');
			$h->b('meta', 0, 1, '', '{"name":"viewport", "content":"width=device-width, initial-scale=1"}');
			$h->b('meta', 0, 1, '', '{"name":"keywords", "content":"'.$article->metakey.'"}');
			$h->b('meta', 0, 1, '', '{"name":"robots", "content":"'.$metadata->robots.'"}');
			$h->b('meta', 0, 1, '', '{"name":"revised", "content":"'.$article->modified.'"}');
			$h->b('meta', 0, 1, '', '{"name":"reply-to", "content":"'.$this->routerMailFrom.'"}');
			$h->b('meta', 0, 1, '', '{"name":"pagename", "content":"'.$article->title.'"}');
			$h->b('meta', 0, 1, '', '{"name":"description", "content":"'.$article->metadesc.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:url", "content":"'.$this->routerInstance.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:type", "content":"'.$this->routerView.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:title", "content":"'.$article->title.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:description", "content":"'.$article->metadesc.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:image", "content":"'.$this->routerBase.$images->image_intro.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:site_name", "content":"'.$this->routerSiteName.'"}');
			$h->b('meta', 0, 1, '', '{"property":"article:author", "content":"'.$metadata->xreference.'"}');
			$h->b('title', 0, 1);
			$h->e(1, $article->title.' - '.$this->routerSiteName);
			$h->b('title', 1, 1);
		}
		if($this->routerView == 'category'){
			$h = new Html();
			$category = new Category();
			$category->categoryContent($this->routerId);
			$params = json_decode($category->params);
			$metadata = json_decode($category->metadata);
			$h->b('meta', 0, 1, '', '{"charset":"utf-8"}');
			$h->b('meta', 0, 1, '', '{"http-equiv":"X-UA-Compatible", "content":"IE=edge"}');
			$h->b('meta', 0, 1, '', '{"name":"viewport", "content":"device-width, initial-scale=1"}');
			$h->b('meta', 0, 1, '', '{"name":"keywords", "content":"'.$category->metakey.'"}');
			$h->b('meta', 0, 1, '', '{"name":"robots", "content":"'.$metadata->robots.'"}');
			$h->b('meta', 0, 1, '', '{"name":"revised", "content":"'.$category->modified_time.'"}');
			$h->b('meta', 0, 1, '', '{"name":"reply-to", "content":"'.$this->routerMailFrom.'"}');
			$h->b('meta', 0, 1, '', '{"name":"pagename", "content":"'.$category->title.'"}');
			$h->b('meta', 0, 1, '', '{"name":"description", "content":"'.$category->metadesc.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:url", "content":"'.$this->routerInstance.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:type", "content":"'.$this->routerView.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:title", "content":"'.$category->title.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:description", "content":"'.$category->metadesc.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:image", "content":"'.$this->routerBase.$params->image.'"}');
			$h->b('meta', 0, 1, '', '{"property":"og:site_name", "content":"'.$this->routerSiteName.'"}');
			$h->b('meta', 0, 1, '', '{"property":"article:author", "content":"'.$category->description.'"}');
			$h->b('title', 0, 1);
			$h->e(1, $category->title.' - '.$this->routerSiteName);
			$h->b('title', 1, 1);
		}
	}
	
	public function css($dir){
		$html = new Html();

		//INCLUDE ANY .CSS IN CSS FOLDER
		$css = scandir($dir.CSS_PATH);
		foreach($css as $cssKey => $cssFile){
			if(stripos($cssFile, CSS_EXT)){
				$html->b('link', 0, 1, '', '{"href":"'.INCLUDE_PATH.CSS_PATH.$cssFile.'", "rel":"stylesheet"}');
			}
		}
	}
	
	public function js($dir, $location){
		require_once($dir.'/js/'.$location.PHP_FILE_EXT);
	}
}
?>