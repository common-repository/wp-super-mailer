<?php

/**
 * cake_Fbl.php
 *
 * Copyright (c) 2002-2010 The Code Kitchen Inc.
 */

require_once(LIB_CAKE . "cake_Xml.php");

function cake_config_GetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Config/GetInfo.php");
}

function cake_config_GetHosts($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Config/GetHosts.php");
}

?>