<?php

/**
 * cake_Mailing.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Creates a mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  mailing_id or an exception
 */
function cake_mailing_Create($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/Create.php");
}

/**
 * Deletes a mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_mailing_Delete($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "Mailing/Delete.php");
}

/**
 * Forwards a mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_mailing_Forward($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "Mailing/Forward.php");
}

/**
 * Gets the formated email message
 *
 * @params   array  an array of parameters
 * @returns  mixed  the message or an exception
 */
function cake_mailing_GetEmailMessage($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetEmailMessage.php");
}

/**
 * Gets the formated HTML message
 *
 * @params   array  an array of parameters
 * @returns  mixed  the message or an exception
 */
function cake_mailing_GetHtmlMessage($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetHtmlMessage.php");
}

/**
 * Gets a mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_mailing_GetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetInfo.php");
}

/**
 * Returns the list of links for a mailing
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_mailing_GetLinks($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetLinks.php");
}

/**
 * Returns the total and unique counts of openings for mailing's links
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_mailing_GetLinksLog($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetLinksLog.php");
}

/**
 * Retrieves the list of mailings
 * 
 * @params   array  an array of parameters
 * @returns  mixed  the new trigger's id or an exception
 */
function cake_mailing_GetList($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetList.php");
}

/**
 * Returns the logs of a mailing
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_mailing_GetLog($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetLog.php");
}

/**
 * Gets a mailing information
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_mailing_GetStats($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetStats.php");
}

/**
 * Gets the formated TEXT message
 *
 * @params   array  an array of parameters
 * @returns  mixed  the message or an exception
 */
function cake_mailing_GetTextMessage($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/GetTextMessage.php");
}

/**
 * Resumes a suspended mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_mailing_Resume($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/Resume.php");
}

/**
 * Schedules a mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_mailing_Schedule($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/Schedule.php");
}

/**
 * Suspends a mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_mailing_Suspend($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/Suspend.php");
}

/**
 * Sends a test email
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_mailing_SendTestEmail($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/SendTestEmail.php");
}

/**
 * Sets the parameters for a mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_mailing_SetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/SetInfo.php");
}

/**
 * Unschedules a mailing
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_mailing_Unschedule($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Mailing/Unschedule.php");
}

?>
