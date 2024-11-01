<?php

/**
 * Relay/GetLogs.php
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
		$xml->add_class("ClassRelay", $locale);
		$xml->add_method("GetLogs");

		reset($params);
		while ($p = each($params)) {
			if (is_array($p["value"])) {
				reset($p["value"]);
				while ($t = each($p["value"])) {
					$xml->add_custom_parameter($t["key"], $t["value"]);
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
		$GLOBALS["error_code"]    = $e->getCode();
		$GLOBALS["error_message"] = $e->getMessage();
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}

	$res = array();
	if (isset($r[0]["class"][0]["method"])) {
		$sent_logs_i = 0;
		$clickthru_logs_i = 0;
		$open_logs_i = 0;
		$bounce_logs_i = 0;
		foreach ($r[0]["class"][0]["method"] as $result) {
			switch ($result["name"]) {
			case "error_code":
				$GLOBALS["error_code"] = $result["value"];
				break;
			case "error_message":
				$GLOBALS["error_message"] = $result["value"];
				break;
			case "bounce_log":
				foreach ($result["bounce_log"] as $element) {
					$res["bounce_logs"][$bounce_logs_i][$element["name"]] = $element["value"];
				}
				$bounce_logs_i++;
				break;
			case "clickthru_log":
				foreach ($result["clickthru_log"] as $element) {
					$res["clickthru_logs"][$clickthru_logs_i][$element["name"]] = $element["value"];
				}
				$clickthru_logs_i++;
				break;
			case "open_log":
				foreach ($result["open_log"] as $element) {
					$res["open_logs"][$open_logs_i][$element["name"]] = $element["value"];
				}
				$open_logs_i++;
				break;
			case "sent_log":
				foreach ($result["sent_log"] as $element) {
					$res["sent_logs"][$sent_logs_i][$element["name"]] = $element["value"];
				}
				$sent_logs_i++;
				break;
			default:
				$res[$result["name"]] = $result["value"];
			}
		}
	}

	if (isset($GLOBALS["error_code"]) || isset($GLOBALS["error_message"])) {
		throw new Exception($GLOBALS["error_message"], $GLOBALS["error_code"]);
	}

	return $res;

?>
