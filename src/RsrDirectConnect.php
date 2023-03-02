<?php

namespace RomanMiranda\RsrGroup;

class RsrDirectConnect{

	protected $username;

	protected $password;

	protected $endpoint;
	
	public function __construct($username, $password, $live = false)
	{
		$this->username = $username;

		$this->password = $password;

		if( $live ){
			$this->endpoint = 'https://rsrgroup.com/api/rsrbridge/1.0/pos/';
		}else{
			$this->endpoint = 'https://test.rsrgroup.com/api/rsrbridge/1.0/pos/';
		}
	}

	public function checkCatalog($params = [])
	{
		return $this->apiCall('check-catalog', $params );
	}

	public function getItems($params = [])
	{
		// Transform arrays to string comma-separated
		foreach($params as $key => $value){
			if( in_array($key, ['Departments','Manufacturers']) && is_array($value) ){
				$params[$key] = implode( ',', $value);
			}
		}

		return $this->apiCall('get-items', $params );
	}

	public function getItemAttributes($params)
	{
		return $this->apiCall('get-item-attributes', $params );
	}

	public function placeOrder($params = [])
	{
		return $this->apiCall('place-order', $params );
	}

	public function checkOrder($params = [])
	{
		return $this->apiCall('check-order', $params );
	}

	protected function apiCall($endpoint, $params)
	{
		$params = array_merge($params, [
			'Username' 	=> $this->username,
			'Password' 	=> $this->password,
			'POS'		=> 'I',
		]);

		$params = $this->flattenParams($params);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->endpoint . $endpoint );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);

		curl_close($ch);

		return json_decode($server_output, true);
	}

	protected function flattenParams($array, &$result = array(), $parentKey = '')
	{
	    foreach($array as $key => $value) {
	        $newKey = empty($parentKey) ? $key : "{$parentKey}[{$key}]";
	        if(is_array($value)) {
	            $this->flattenParams($value, $result, $newKey);
	        } else {
	            $result[$newKey] = $value;
	        }
	    }

	    return $result;
	}

}
