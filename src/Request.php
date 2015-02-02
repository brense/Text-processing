<?php

class Request {

	private $_method;
	private $_url;
	private $_parameters;
	private $_body;
	private $_headers = array();
	private $_allowedMethods = array('get', 'post', 'put', 'delete');
	private $_handle;

	private function __construct($requestMethod, $url) {
		$this->_method = strtoupper($requestMethod);
		$this->_url = $url;
		if (strpos($url, '?') !== false) {
			$this->_url = substr($url, 0, strpos($url, '?'));
		}
		if (strpos($url, '?')) {
			$queryString = parse_url($url, PHP_URL_QUERY);
			parse_str($queryString, $this->_parameters);
		}
	}

	public function __callStatic($method, Array $parameters = array()) {
		if (in_array($method, $this->_allowedMethods) && isset($parameters[0])) {
			return new self($method, $parameters[0]);
		}
	}

	public function addHeader($key, $value) {
		$this->_headers[] = $key . ': ' . $value;
		return $this;
	}

	public function setBody($body) {
		if (is_array($body)) {
			$this->_body = $body;
		}
		// TODO: else, parse body and set content headers
		return $this;
	}

	public function execute() {
		$this->_handle = curl_init();

		$url = $this->_url;
		if (count($this->_parameters) > 0 && in_array($this->_method, array('GET', 'DELETE'))) {
			$url = $this->_url . '?' . http_build_query($this->_parameters);
		}
		curl_setopt($this->_handle, CURLOPT_URL, $url);

		$this->setDefaultCurlOptions();
		switch ($this->_method) {
			case 'POST':
				curl_setopt($this->_handle, CURLOPT_POST, true);
				curl_setopt($this->_handle, CURLOPT_POSTFIELDS, $this->_body);
				break;
			case 'PUT':
				curl_setopt($this->_handle, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($this->_handle, CURLOPT_POSTFIELDS, $this->_body);
				break;
			case 'DELETE':
				curl_setopt($this->_handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		}

		$response = curl_exec($this->_handle);
		if (curl_errno($this->_handle)) {
			throw new Exception('curl error: ' . curl_error($this->_handle));
		}
		curl_close($this->_handle);
		return $response;
	}

	private function setDefaultCurlOptions() {
		curl_setopt($this->_handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->_handle, CURLOPT_HTTPHEADER, $this->_headers);
		curl_setopt($this->_handle, CURLOPT_HEADER, false);
	}

}
