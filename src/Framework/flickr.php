<?php
namespace framework;

use joomla\database;

class flickr{
    public $apiKey = '';
	
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function api($params){
        $params['api_key'] = $this->apiKey;
        $params['format'] = 'json';
        $params['nojsoncallback'] = 1;

        $encoded_params = array();
        foreach ($params as $k => $v){
            $encoded_params[] = urlencode($k).'='.urlencode($v);
        }

        $url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
        $rsp = file_get_contents($url);
        $rsp_obj = json_decode($rsp, 1);

        return $rsp_obj;
    }
	
	public function photosgetInfo($photoId, $imagePrefix){
		$db = new database();
		
		$params = array(
			'method' => 'flickr.photos.getInfo',
			'photo_id' => $photoId
		);
		$results = $this->api($params);
		
		$singleKeys = array(
			'id',
			'secret',
			'server',
			'farm',
			'dateuploaded',
			'isfavorite',
			'license',
			'safety_level',
			'rotation',
			'originalsecret',
			'originalformat'
		);

		foreach($singleKeys as $singleKey => $single){
			if(array_key_exists($single, $results["photo"])){
				$this->{$single} = $results["photo"][$single];
			}
		}
		
		$this->imagePath = 'https://c2.staticflickr.com/'.$this->farm.'/'.$this->server.'/'.$this->id.'_'.$this->secret.'.jpg';
		
		$owners = $results["photo"]["owner"];
		$titles = $results["photo"]["title"];
		$descriptions = $results["photo"]["description"];
		$dates = $results["photo"]["dates"];
		$tags = $results["photo"]["tags"];
		
		//posted
		//taken
		//takengranularity
		//takenunknown
		//lastupdate
		foreach($dates as $dateKey => $date){
			$this->{$dateKey} = $date;
		}
		
		//nsid
		//username
		//realname
		//location
		//iconserver
		//iconfarm
		//path_alias
		foreach($owners as $ownerKey => $owner){
			$this->{$ownerKey} = $owner;
		}
		
		//Image Title
		foreach($titles as $titleKey => $title){
			$this->imageTitle = $title;
		}
		
		//Image Description
		foreach($descriptions as $descriptionKey => $description){
			$this->imageDescription = $description;
		}
		
		//_content
		foreach($tags as $tagKey => $tag){
			$this->imageTags = $tag;
		}
	}
	
	public function loadTemplate($dir, $results, $showLimit, $template){
		$total = count($results["photos"]["photo"]);
		$pages = @$_GET["page"];
		$pagesTotal = ceil($total / $showLimit);
		if($pages < 1){
			$pages = 1;
		}else{ 
			$pages;
		}
		
		$start = ($pages - 1) * ($showLimit);
		$resultsSlices = array_slice($results["photos"]["photo"],$start,$showLimit);
		$this->pagesTotal = $pagesTotal;
		
		echo $total;
		
		$db = new database();
		$db->dump($resultsSlices);
		
		/*foreach($resultsSlices as $resultsKey => $resultSlice){
			foreach ($resultSlice as $column => $data){
				$this->{$column} = $data;
			}
			require($dir.'/'.$template.'.php');
		}*/
	}
}

?>