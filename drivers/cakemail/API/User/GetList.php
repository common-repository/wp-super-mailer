<?php

/**
 * User/GetList.php
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
		$xml->add_class("ClassUser", $locale);
		$xml->add_method("GetList");

		reset($params);
		while ($p = each($params)) {
			$xml->add_parameter(strtolower($p["key"]), $p["value"]);
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

	$res = array();
	$res["users"] = array();
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
			case "user":
				$res["users"][$i]["group_id"] = array();
				foreach ($result["user"] as $element) {
					switch ($element["name"]) {
					case "group_id":
						$res["users"][$i]["group_id"][] = $element["value"];
						break;
					default:
						$res["users"][$i][$element["name"]] = $element["value"];
					}
				}
				$i++;
				break;
			default:
				$res[$result["name"]] = $result["value"];
			}
		}
	}

	if (isset($GLOBALS["error_code"]) || isset($GLOBALS["error_message"])) {
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}

	if (isset($res["count"]) && isset($res["users"])) {
		unset($res["users"]);
	}
	
	return $res;

?>
