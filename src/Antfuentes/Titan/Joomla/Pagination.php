<?php
namespace Antfuentes\Titan\Joomla;

use Antfuentes\Titan\Joomla\Menu;
use Antfuentes\Titan\Framework\Html;

class Pagination{
	public function load($itemId, $pages_total){
		$html = new Html();
		$menu = new Menu();

		$menu->link($itemId);

		if(isset($_GET['page'])){
			if($_GET['page'] <= 1){
				$back = $_GET['page'] == 1;
			}else{
				$back = $_GET['page'] - 1;
			}
		}else{
			$back = '';
		}
		$html->b('section', 0, 1, '', '{"class":"row", "data-min-height":"10"}');
			$html->b('div', 0, 1, '', '{"class":"vertical"}');
				$html->b('div', 0, 1, '', '{"class":"col col-center col-base-8"}');
					$html->b('ul', 0, 1, '','{"class":"pagination"}');
						$html->b('li', 0, 1, '', '{"class":"back"}');
							$html->b('a', 0, 1, '', '{"href":"'.$menu->currentLink.'&page='.$back.'"}');
								$html->e(1, 'Back');
							$html->b('a', 1, 1);
						$html->b('li', 1, 1);
						for($i=1; $i <= $pages_total; $i++){
							if(empty($_GET["page"])){
								$_GET["page"] = 1;
							}  

							if($_GET["page"] == $i){
								$class = 'active';
							}else{
								$class = '';
							}

							$html->b('li', 0, 1, '', '{"class":"'.$class.'"}');
								$html->b('a', 0, 1, '', '{"href":"'.$menu->currentLink.'&page='.$i.'"}');
									$html->e(1, $i);
								$html->b('a', 1, 1);
							$html->b('li', 1, 1);
						}

						if($_GET['page'] >= $pages_total){
							$next = $pages_total;
						}else{
							$next = $_GET["page"] + 1;
						}

						$html->b('li', 0, 1, '', '{"class":"next"}');
							$html->b('a', 0, 1, '', '{"href":"'.$menu->currentLink.'&page='.$next.'"}');
								$html->e(1, 'Next');
							$html->b('a', 1, 1);
						$html->b('li', 1, 1);
					$html->b('ul', 1, 1);
				$html->b('div', 1, 1 );
			$html->b('div', 1, 1);
		$html->b('section', 1, 1);
	}
}
?>