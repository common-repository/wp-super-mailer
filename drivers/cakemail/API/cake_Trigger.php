<?php

/**
 * cake_Trigger.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Creates a new trigger
 * 
 * @params   array  an array of parameters
 * @returns  mixed  the new trigger's id or an exception
 */
function cake_trigger_Create($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Trigger/Create.php");
}

/**
 * Gets a trigger
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_trigger_GetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Trigger/GetInfo.php");
}

/**
 * Returns the list of links for a trigger
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_trigger_GetLinks($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Trigger/GetLinks.php");
}

/**
 * Returns the total and unique counts of openings for triger's links
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_trigger_GetLinksLog($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Trigger/GetLinksLog.php");
}

/**
 * Retrieves the list of triggers
 * 
 * @params   array  an array of parameters
 * @returns  mixed  the new trigger's id or an exception
 */
function cake_trigger_GetList($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Trigger/GetList.php");
}

/**
 * Returns the logs of a trigger
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_trigger_GetLog($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Trigger/GetLog.php");
}

/**
 * Sets the parameters for a trigger
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_trigger_SetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Trigger/SetInfo.php");
}

/**
 * Unleashes a trigger
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_trigger_Unleash($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Trigger/Unleash.php");
}

?>
