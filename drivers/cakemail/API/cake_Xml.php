<?php

/**
 * cake_Xml.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Object.php");
require_once(LIB_CAKE . "cake_functions.php");

class cake_Xml extends cake_Object {
	protected $xml;
	protected $closed_xml;
	protected $x_i;
	protected $x_v;
	protected $classes;
	protected $depth;

	/**
	 * The constructor; sets the database and the table name
	 * 
	 * @client_id	the client's ID
	 */
	function __construct() {
		parent::__construct();
		$this->closed_xml = false;
		$this->xml = NULL;
		$this->depth = 0;
	}

	/**
	 * The destructor
	 */
	function __destruct() {
		parent::__destruct();
	}

	/**
	 * Adds a new class into the xml' structure
	 *
	 * @class		the class' name
	 */
	function add_class($class, $locale = "en_US") {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}
		
		$this->insert("<class type=\"" . rawurlencode($class) . "\" locale=\"" . rawurlencode($locale) . "\">");
		$this->depth += 2;
	}

	/**
	 * Adds a new method to a class
	 *
	 * @method		the method's name
	 */
	function add_method($method) {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}

		$this->insert("<method type=\"" . rawurlencode($method) . "\">");
		$this->depth += 2;	
	}

	/**
	 * Adds a new parameter into a method
	 *
	 * @p_name		the parameter's name
	 * @p_value		the parameters's value
	 */
	function add_parameter($p_name, $p_value) {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}
	
		$this->insert("<" . $p_name . ">" . rawurlencode($p_value) . "</" . $p_name . ">");
	}

	/**
	 * Adds a new custom parameter into a method
	 *
	 * @p_name		the parameter's name
	 * @p_type		the parameter's type
	 * @p_value		the parameters's value
	 */
	function add_custom_parameter($p_type, $p_value) {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}
	
		$this->insert("<data type=\"" . rawurlencode($p_type) . "\">" . rawurlencode(strval($p_value)) . "</data>");
	}

	/**
	 * Adds a new tag
	 */
	function add_tag($tag_name) {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}

		$this->insert("<" . $tag_name . ">");
		$this->depth += 2;
	}
	
	/**
	 * Closes the xml' structure
	 */
	function close() {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}

		$this->depth -= 2;
		$this->insert("</body>");

		$this->closed_xml = true;
	}

	/**
	 * Closes a class
	 */
	function close_class() {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}

		$this->depth -= 2;
		$this->insert("</class>");
	}

	/**
	 * Closes a method
	 */
	function close_method() {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}

		$this->depth -= 2;
		$this->insert("</method>");
	}

	/**
	 * Closes a tag
	 */
	function close_tag($tag_name) {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}

		$this->depth -= 2;
		$this->insert("</" . $tag_name . ">");
	}
	
	/**
	 * Executes the xml
	 */
	function exec_xml() {
		$start_time = @microtime(true);
		
		if (false === $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][UNCLOSED_XML], UNCLOSED_XML);
		}

		$xml_req = $this->xml;
		$iv_size = @mcrypt_get_iv_size(CAKE_MCRYPT_ALG, CAKE_MCRYPT_MODE);
		$iv = @mcrypt_create_iv($iv_size, defined(MCRYPT_DEV_URANDOM) ? MCRYPT_DEV_URANDOM : MCRYPT_RAND);
		$xml_req = cake_encrypt($xml_req, CAKE_INTERFACE_KEY, CAKE_MCRYPT_ALG, CAKE_MCRYPT_MODE, $iv);

		if ("ecb" == CAKE_MCRYPT_MODE) {
			$iv = "";
		} else {
			$iv = @unpack("H*", $iv);
			$iv = $iv[1];
		}
				
		if (false === $ch = @curl_init()) {
			throw new Exception("curl_init error", -1);
		}
		if (false === @curl_setopt($ch, CURLOPT_URL, API_CAKE_URL)) {
			throw new Exception(@curl_error($ch), -1);
		}
		if (false === @curl_setopt($ch, CURLOPT_POST, true)) {
			throw new Exception(@curl_error($ch), -1);
		}
		if (false === @curl_setopt($ch, CURLOPT_POSTFIELDS, array("id" => CAKE_INTERFACE_ID, "alg" => CAKE_MCRYPT_ALG, "mode" => CAKE_MCRYPT_MODE, "request" => $iv . $xml_req))) {
			throw new Exception(@curl_error($ch), -1);
		}
		if (defined("CAKE_SSL_VERIFYPEER")) {
			if (false === @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, CAKE_SSL_VERIFYPEER)) {
				throw new Exception(@curl_error($ch), -1);
			}
		}
		if (defined("CAKE_SSL_VERIFYHOST")) {
			if (false === @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, CAKE_SSL_VERIFYHOST)) {
				throw new Exception(@curl_error($ch), -1);
			}
			$co[CURLOPT_SSL_VERIFYHOST] = CAKE_SSL_VERIFYHOST;
		}
		if (false === @ob_start()) {
			throw new Exception("ob_start error", -1);
		}
		if (false === @curl_exec($ch)) {
			throw new Exception(@curl_error($ch), -1);
		}
		if (false === ($response = @ob_get_contents())) {
			throw new Exception("ob_get_contents error", -1);
		}
		if (false === @ob_end_clean()) {
			throw new Exception("ob_end_clean error", -1);
		}
		@curl_close($ch);

		cake_debug($response);

		if (0 !== strpos($response, "<?xml")) {
			list($iv, $response) = cake_get_iv_and_er($response, CAKE_MCRYPT_ALG, CAKE_MCRYPT_MODE);
			$response = cake_decrypt($response, CAKE_INTERFACE_KEY, CAKE_MCRYPT_ALG, CAKE_MCRYPT_MODE, $iv);
		}
		
		cake_debug($this->xml);
		cake_debug($response);

		$x = @xml_parser_create("UTF-8");
		@xml_parser_set_option($x, XML_OPTION_CASE_FOLDING, 0);
		if (0 === xml_parse_into_struct($x, $response, $this->x_v, $this->x_i)) {
			@xml_parser_free($x);
			throw new Exception($GLOBALS["lib_cake_errors"][XML_PARSE_ERROR], XML_PARSE_ERROR);
		}
		@xml_parser_free($x);
	
		$index = 0;
		$r = array();
		$response = $this->parse_response($index, $r);
		$this->response = $response["result"];
	
		if (is_array($response)) {
			foreach ($response as $result) {
				if (is_array($result)) {
					foreach ($result as $sub_r1) {
						if (is_array($sub_r1)) {
							switch ($sub_r1["name"]) {
							case "error_code":
								$GLOBALS["error_code"] = $sub_r1["value"];
								break;
							case "error_message":
								$GLOBALS["error_message"] = $sub_r1["value"];
								break;
							default:
								foreach ($sub_r1 as $sub_r2) {
									if (is_array($sub_r2)) {
										foreach ($sub_r2 as $sub_r3) {
											switch ($sub_r3["name"]) {
											case "error_code":
												$GLOBALS["error_code"] = $sub_r3["value"];
												break;
											case "error_message":
												$GLOBALS["error_message"] = $sub_r3["value"];
												break;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
		if (isset($GLOBALS["error_code"]) || isset($GLOBALS["error_message"])) {
			throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
		}
	
		$end_time = @microtime(true);

		if (defined("_DEBUG_")) {
			$f = "/tmp/debug_lib_cake";
			@file_put_contents($f, "\nSpent time: " . strval(round($end_time - $start_time, 4)) . " s \n\n", FILE_APPEND);
			@chmod($f, 0666);
		}
	}

	/**
	 * Returns the xml structure
	 */
	function get_xml() {
		return $this->xml;
	}

	/**
	 * Returns the parsed response
	 */
	function get_response() {
		return $this->response;
	}

	/**
	 * Initialises the xml' structure
	 */
	function init() {
		if (NULL == $this->xml) {
			$this->insert("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
			$this->insert("<body version=\"" . CAKE_VERSION . "\">");
			$this->depth += 2;
		}
	}

	/**
	 * Insert a new line with identation
	 *
	 * @value
	 * @returns	an exception in case of error
	 */
	protected function insert($value) {
		if (false !== $this->closed_xml) {
			throw new Exception($GLOBALS["lib_cake_errors"][CLOSED_XML], CLOSED_XML);
		}

		for ($i = 0; $i < $this->depth; $i++) {
			$this->xml .= " ";
		}
		$this->xml .= $value . "\n";
	}
	
	/**
	 * Parses the xml response
	 *
	 * @index	a pointer to the strusture's depth
	 * @r		an array with the result
	 */
	protected function parse_response(&$index, $r) {
		switch ($this->x_v[$index]["type"]) {
		case "open":
			$count = 0;
			$r[$count] = array();
			while (isset($this->x_v[$index])) {
				$i = $index;
				$index++;
				if (!isset($r[$count][$this->x_v[$i]["tag"]])) {
					$r[$count][$this->x_v[$i]["tag"]] = NULL;
				}
				$res = $this->parse_response($index, $r[$count][$this->x_v[$i]["tag"]]);

				if (NULL == $res) {
					break;
				}

				$r["name"] = rawurldecode($this->x_v[$i]["tag"]);

				if (isset($this->x_v[$i]["attributes"]["type"])) {
					$r["type"] = rawurldecode($this->x_v[$i]["attributes"]["type"]);
				} 

				$r[rawurldecode($this->x_v[$i]["tag"])][$count] = $res;

				$count++;
			}

			return $r;

		case "complete":
			$i = $index;
			$index++;

			$r["name"] = rawurldecode($this->x_v[$i]["tag"]);

			if (isset($this->x_v[$i]["attributes"]["type"])) {
				$r["type"] = rawurldecode($this->x_v[$i]["attributes"]["type"]);
			}

			$r["value"] = isset($this->x_v[$i]["value"]) ? rawurldecode($this->x_v[$i]["value"]) : NULL;	

			return $r;

		case "close":
			$index++;
			$r = NULL;
			return $r;
		}
	}
}

?>
