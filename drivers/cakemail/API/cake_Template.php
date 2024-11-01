<?php

/**
 * cake_Template.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Creates a new template
 *
 * @params   array  an array of parameters
 * @returns  mixed  the new template's id or an exception
 */
function cake_template_Create($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Template/Create.php");
}

/**
 * Deletes a template
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_template_Delete($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "Template/Delete.php");
}

/**
 * gets the list of templates specified by status
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array with the templates or an exception
 */
function cake_template_GetList($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Template/GetList.php");
}

/**
 * retrieves the template's infos
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_template_GetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Template/GetInfo.php");
}

/**
 * Sets the parameters for a template
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_template_SetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Template/SetInfo.php");
}

?>
