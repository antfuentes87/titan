<?php
namespace framework;

class mailchimp{
	
	private $apiKey;
	
	function __construct($apiKey){
		$this->apiKey = $apiKey;
	}
	
	public function test(){
		echo 'test mailchimp';
	}
	
	public function subscribe($email, $listId){
		$auth = base64_encode('user:'.$this->apiKey);
		$data = array(
		'apikey'        => $this->apiKey,
		/*IT HAS TO BE email_address, only way the API will take the email*/
		'email_address' => $email,
		'status'        => 'subscribed'
		/*'merge_fields'  => array(
		'FNAME' => 'Anthony'
		)*/
		);
		$json_data = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://us11.api.mailchimp.com/3.0/lists/'.$listId.'/members/');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data); 

		$result = curl_exec($ch);
		
		$result = json_decode($result);

		foreach($result as $key => $val){
			if($key == 'status'){
				if($val == 'subscribed'){
					$status = $val;
				}else{
					$status = $val;	
				}
			}
		}
		
		return $status;
   }
}