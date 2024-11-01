<?php

/**
 * SuppressionList/ImportEmails.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

	unset($GLOBALS["error_code"]);
	unset($GLOBALS["error_message"]);

	try {
		$xml = new cake_Xml();

		$xml->init();
		$xml->add_class("ClassSuppressionList", $locale);
		$xml->add_method("ImportEmails");
		
		foreach ($params as $k0 => $v0) {
			if (is_array($v0)) {
				foreach ($v0 as $k1 => $v1) {
					$xml->add_parameter($k0, $v1);
				}
			} else {
				$xml->add_parameter($k0, $v0);
			}
		}

		$xml->close_method();
		$xml->close_class();
		$xml->close();

		$xml->exec_xml();
		$r = $xml->get_response();
	
	} catch (Exception $e) {
		$GLOBALS["error_code"] = $e->getCode();
		$GLOBALS["error_message"] = $e->getMessage();
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}

	$records = array();
	if (isset($r[0]["class"][0]["method"])) {
		$i = 0;
		foreach ($r[0]["class"][0]["method"] as $result) {
			switch ($result["name"]) {
			case "error_code":
				$GLOBALS["error_code"] = $result["value"];
				break;
			case "error_message":
				$GLOBALS["error_message"] = $result["value"];
				break;
			case "record":
				foreach ($result["record"] as $element) {
					switch ($element["name"]) {
					case "email":
						$records[$i]["email"] = $element["value"];
						break;
					case "error_code":
						$records[$i]["error_code"] = $element["value"];
						break;
					case "error_message":
						$records[$i]["error_message"] = $element["value"];
						break;
					}
				}
				$i++;
			}
		}
	}

	if (isset($GLOBALS["error_code"]) || isset($GLOBALS["error_message"])) {
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}

	return $records;

?>
