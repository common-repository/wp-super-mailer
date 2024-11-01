<?php

/**
 * Mailing/GetInfo.php
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
		$xml->add_class("ClassMailing", $locale);
		$xml->add_method("GetInfo");
		
		reset($params);
		while ($p = each($params)) {
			if (is_array($p["value"])) {
				reset($p["value"]);
				while ($t = each($p["value"])) {
					$xml->add_parameter($p["key"], $t["value"]);
				}
			} else {
				$xml->add_parameter($p["key"], $p["value"]);
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
	
	$mailing = array();
	$mailings_array = array();
	$mailings_count = 0;
	if (isset($r[0]["class"][0]["method"])) {
		foreach ($r[0]["class"][0]["method"] as $result) {
			switch ($result["name"]) {
			case "error_code":
				$GLOBALS["error_code"] = $result["value"];
				break;
			case "error_message":
				$GLOBALS["error_message"] = $result["value"];
				break;
			case "mailing":
				foreach ($result["mailing"] as $element) {
					$mailings_array[$mailings_count][$element["name"]] = $element["value"];
				}
				$mailings_count++;
				break;
			default:
				$mailing[$result["name"]] = $result["value"];
				break;
			}
		}
	}
	
	if (isset($GLOBALS["error_code"]) || isset($GLOBALS["error_message"])) {
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}
	
	if (count($mailings_array)) {
		return $mailings_array;
	}
	
	return $mailing;

?>
