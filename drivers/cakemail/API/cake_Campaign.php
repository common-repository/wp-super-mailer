<?php

/**
 * cake_Campaign.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 *
 * Software designer & developer:
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Creates a new campaign
 *
 * @params   array  an array of parameters
 * @returns  mixed  the new campaign's id or an exception
 */
function cake_campaign_Create($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Campaign/Create.php");
}

/**
 * Deletes a campaign
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_campaign_Delete($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "Campaign/Delete.php");
}

/**
 * Gets the list of campaigns specified by status
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array with the campaigns or an exception
 */
function cake_campaign_GetList($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Campaign/GetList.php");
}

/**
 * Gets the parameters for a campaign
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_campaign_GetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Campaign/GetInfo.php");
}

/**
 * Returns the count of mailings assigned to a campaign
 *
 * @params   array  an array of parameters
 * @returns  mixed  an integer or an exception
 */
function cake_campaign_GetMailingsCount($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Campaign/GetMailingsCount.php");
}

/**
 * Sets the parameters for a campaign
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_campaign_SetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Campaign/SetInfo.php");
}


?>
