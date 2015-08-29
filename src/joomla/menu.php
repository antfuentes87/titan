<?php
namespace joomla;

use framework\element;
use framework\html;

class menu extends database{
	
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
			$this->currentLink = $val['link'].'&Itemid='.$id;
		}
	}
	
	public function load($menutype){
		$this->tables();
		$query = "SELECT * FROM `$this->menu` WHERE menutype = '$menutype'";
		$array = $this->q($query);
		return $array;
	}

	public function build($menutype, $element, $active, $id = '', $class = ''){
		$h = new html();

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
}
?>