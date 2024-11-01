<?php

/**
 * cake_Group.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Adds a user to a group
 *
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_group_AddUser($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Group/AddUser.php");
}

/**
 * Creates a new group
 * 
 * @params   array  an array of parameters
 * @returns  mixed  group_id or an exception
 */
function cake_group_Create($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Group/Create.php");
}

/**
 * Deletes a group
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_group_Delete($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Group/Delete.php");
}

/**
 * Gets the informations of a group
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_group_GetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Group/GetInfo.php");
}

/**
 * Returns the list of the groups
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_group_GetList($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Group/GetList.php");
}

/**
 * Removes a user from a group
 *
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_group_RemoveUser($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Group/RemoveUser.php");
}

/**
 * Sets the informations of a group
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_group_SetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Group/SetInfo.php");
}

?>
