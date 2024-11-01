<?php

/**
 * cake_Relay.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Retrieves the logs
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_relay_GetLogs($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Relay/GetLogs.php");
}

/**
 * Sends an email
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_relay_Send($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Relay/Send.php");
}

?>
