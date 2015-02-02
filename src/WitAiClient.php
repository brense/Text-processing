<?php

class TextProcessingClient {

	private $_key;
	private $_endpoint = 'https://api.wit.ai/';

	public function __construct($key) {
		$this->_key = $key;
	}

	public function makeRequest($method, $path, Array $headers = array(), Array $body = array()) {
		$request = call_user_func(array('Request', strtolower($method)), $this->_endpoint . $path);
		$request->addHeader('Authorization', 'Bearer ' . $this->_key);
		foreach ($headers as $key => $value) {
			$request->addHeader($key, $value);
		}
		$request->setBody($body);
		try {
			$response = $request->execute();
			return json_decode($response);
		} catch (Exception $e) {
			// TODO: handle curl exceptions
		}
		return false;
	}

	public function message($message) {
		return $this->makeRequest('POST', 'message', array(), array(
					'q' => $message
		));
	}

}
