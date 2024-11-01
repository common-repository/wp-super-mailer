<?php

/**
 * cake_User.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Checks for a permission on a user
 *
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_user_CheckPermission($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "User/CheckPermission.php");
}

/**
 * Creates a user
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_user_Create($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "User/Create.php");
}

/**
 * Gets the informations about a user
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_user_GetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "User/GetInfo.php");
}

/**
 * Gets the list with a specified status
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_user_GetList($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "User/GetList.php");
}

/**
 * Recovers a password for a user
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_user_PasswordRecovery($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "User/PasswordRecovery.php");
}

/**
 * Logs-in a user
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_user_Login($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "User/Login.php");
}

/**
 * Sets the parameters for a user
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_user_SetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "User/SetInfo.php");
}

?>
