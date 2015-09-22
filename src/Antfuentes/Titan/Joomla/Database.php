<?php
namespace Antfuentes\Titan\Joomla;
use Antfuentes\Titan\Framework;

/*
* Database
*
* Please report bugs on https://github.com/antfuentes87/titan/issues
*
* @author Anthony Fuentes <antfuentes87@gmail.com>
* @author Shaun Farrell <shaunfarrell86@gmail.com>
* @copyright Copyright (c) 2015, MNDYRS. All rights reserved.
* @license MIT License
*/

class Database{
	//Create a public variable for mysqli connect
	public $link;
	
	//This function will be called once a new instance of this class has been created
	//Setup connection database using mysqli
	function __construct(){
		//Assign the public $link variable to a myslqi connection
		//Constants are defined from the _define.php located in the What template
		//Please see https://github.com/decayedcross/what/blob/master/_define.php for reference of _define.php
		//DB_HOST, DB_USER, DB_PASS, DB_NAME Constants are all declared inside of _define.php 
		$this->link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		//If can not connect to the mysql database using the Constants provided, throw error and exit()
		if (mysqli_connect_errno()){
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}else{
			//If no errors then set the charset to utf-8
			mysqli_set_charset($this->link, 'utf8');
		}
	}

	//On destroy of this class, make sure to kill the database conenction
	function __destruct(){
		//Close the connection to the mysqli database
		mysqli_close($this->link);
	}

	//Create a query with the established mysqli connection
	//This will fetch all results and create array of the data
	//Returned data is composed of a array
	public function q($q){
		$data = array();
		$result = mysqli_query($this->link, $q);
		if($result){
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}
		}
		return $data;
	}

	public function tables(){
		$array = $this->q('SHOW TABLES');
		foreach($array as $key => $val){	
			$table = explode('_', $val['Tables_in_'.DB_NAME]);
			$varname = $table[1];
			$this->$varname = $table[0].'_'.$table[1];
		}
	}

	public function columns($table){
		$array = $this->q('SHOW COLUMNS FROM '. $table);
		var_dump($array);
		foreach($array as $key => $val){
			$varname = $val['Field'];
			$this->$varname = $val['Field'];
		}
	}

	public function variables($results){
		foreach($results as $resultKey => $result){
			foreach ($result as $column => $data) {
				$this->{$column} = $data;
			}
		}
	}

	public function variable($result){
		foreach ($result as $column => $data) {
			$this->{$column} = $data;
		}
	}

	public function dump($dump){
		$h = new Framework\Html();
		$h->b('pre', 0, 1);
			var_dump($dump);
		$h->b('pre', 1, 1);
	}
}
?>