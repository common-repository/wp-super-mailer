<?php

/**
 * Trigger/GetLog.php
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
		$xml->add_class("ClassTrigger", $locale);
		$xml->add_method("GetLog");

		reset($params);
		while ($p = each($params)) {
			$xml->add_parameter($p["key"], $p["value"]);
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
	$res["logs"] = array();
	if (isset($r[0]["class"][0]["method"])) {
		$i = 0;
		$i_daily = 0;
		$i_hourly = 0;
		foreach ($r[0]["class"][0]["method"] as $result) {
			switch ($result["name"]) {
			case "error_code":
				$GLOBALS["error_code"] = $result["value"];
				break;
			case "error_message":
				$GLOBALS["error_message"] = $result["value"];
				break;
			case "count":
				if (isset($res["logs"])) {
					unset($res["logs"]);
				}
				foreach ($result["count"] as $element) {
					$res["count"][$element["name"]] = $element["value"];
					$res[$element["name"]] = $element["value"];	//to be removed
				}
				break;
			case "daily":
				if (isset($res["logs"])) {
					unset($res["logs"]);
				}
				foreach ($result["daily"] as $element) {
					$res["daily"][$i_daily][$element["name"]] = $element["value"];
				}
				$i_daily++;
				break;
			case "hourly":
				if (isset($res["logs"])) {
					unset($res["logs"]);
				}
				foreach ($result["hourly"] as $element) {
					$res["hourly"][$i_hourly][$element["name"]] = $element["value"];
				}
				$i_hourly++;
				break;
			case "log":
				foreach ($result["log"] as $element) {
					$res["logs"][$i][$element["name"]] = $element["value"];
				}
				$i++;
			}
		}
	}

	if (isset($GLOBALS["error_code"]) || isset($GLOBALS["error_message"])) {
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}

	return $res;

?>
