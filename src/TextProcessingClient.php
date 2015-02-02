<?php

class TextProcessingClient {

	private $_key;
	private $_endpoint = 'https://japerk-text-processing.p.mashape.com/';

	public function __construct($key) {
		$this->_key = $key;
	}

	public function makeRequest($method, $path, Array $headers = array(), Array $body = array()) {
		$request = call_user_func(array('Request', strtolower($method)), $this->_endpoint . $path);
		$request->addHeader('X-Mashape-Key', $this->_key);
		$request->addHeader('Accept', 'application/json');
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

	public function phrases($language, $text) {
		return $this->makeRequest('POST', 'phrases/', array(), array(
					'language' => $language,
					'text' => $text
		));
	}

	public function sentiment($language, $text) {
		return $this->makeRequest('POST', 'sentiment/', array(), array(
					'language' => $language,
					'text' => $text
		));
	}

	public function stem($language, $text, $stemmer = 'porter') {
		return $this->makeRequest('POST', 'stem/', array(), array(
					'language' => $language,
					'stemmer' => $stemmer,
					'text' => $text
		));
	}

	public function tag($language, $text, $output = 'tagged') {
		return $this->makeRequest('POST', 'tag/', array(), array(
					'language' => $language,
					'output' => $output,
					'text' => $text
		));
	}

}
