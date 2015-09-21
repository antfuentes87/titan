<?php
namespace Antfuentes\Titan\Joomla;

use Antfuentes\Titan\Framework;

class Menu extends Database{
	
	public function current($itemid){
    	$this->tables();
		$query = "SELECT alias FROM `$this->menu` WHERE id = '$itemid'";
		$array = $this->q($query);
		$this->currentAlias = $array[0]['alias'];
  	}
	
	public function link($id){
		$this->tables();
		$query = "SELECT * FROM `$this->menu` WHERE id = '$id'";
		$array = $this->q($query);
		foreach($array as $key => $val){
			return $val['link'].'&Itemid='.$id;
		}
	}
	
	public function load($menutype){
		$this->tables();
		$query = "SELECT * FROM `$this->menu` WHERE menutype = '$menutype' ORDER BY lft ASC";
		$array = $this->q($query);
		return $array;
	}

	public function build($menutype, $element, $active, $id = '', $class = ''){
		$h = new Framework\Html();

		$data = '';

		$load = $this->load($menutype);
		foreach($load as $key => $val){
			if($id <> ''){
				$count = '-'.$key;
			}else{
				$count = '';
			}
			if($val['id'] == $active){
				$this->activeItemAlias = $val['alias'];
				if($class <> ''){
					$activeClass = ' active';
				}else{
					$activeClass = 'active';
				}
				
			}else{
				$activeClass = '';
			}
			$h->b($element, 0, 1, '', '{"id":"'.$id.$count.'", "class":"'.$class.$activeClass.'"}');
				$h->b('a', 0, 1, '', '{"href":"'.$val['link'].'&Itemid='.$val['id'].'"}');
					$h->e(1, $val['title']);
				$h->b('a', 1, 1);
			$h->b($element, 1, 1);
		}
	}
	
	public function article($id, $menuid){
		$this->articleLink = 'index.php?option=com_content&view=article&id='.$id.'&Itemid='.$menuid;
	}

	public function categoryBlog($catid, $menuid){
		$this->categoryBlog = 'index.php?option=com_content&view=category&layout=blog&id='.$catid.'&Itemid='.$menuid;
	}

	public function stateCityLinkByAlias($routerId, $routerView, $alias){
		$string = new Framework\String();

		$this->tables();		
		if ($routerView == 'article'){
			$query = $this->q("SELECT catid FROM `$this->content` WHERE id = '$routerId'");
			$catId = $query[0]['catid'];
			$query = $this->q("SELECT path FROM `$this->categories` WHERE id = '$catId'");
			$path = $query[0]['path'];	
		}else{
			$query = $this->q("SELECT path FROM `$this->categories` WHERE id = '$routerId'");
			$path = $query[0]['path'];
		}
		
		$pathExplode = $string->explode($path, '/');

		$state = $pathExplode[0];
		$city = $pathExplode[1];

		$pathMenu = $city;
		$pathMenu .= '-';
		$pathMenu .= $alias;

		$query = $this->q("SELECT id, link FROM `$this->menu` WHERE path = '$pathMenu'");


		$menuId = $query[0]['id'];
		$menuLink = $query[0]['link'];

		$link = $menuLink;
		$link .= '&Itemid=';
		$link .= $menuId;

		return $link;
	}
}
?>