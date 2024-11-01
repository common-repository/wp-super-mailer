<?php

/**
 * cake_List.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Adds a test e-mail to list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_AddTestEmail($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/AddTestEmail.php");
}

/**
 * Creates a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  list_id or exception
 */
function cake_list_Create($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/Create.php");
}

/**
 * Creates a sublist
 * 
 * @params   array  an array of parameters
 * @returns  mixed  sublist_id or an exception
 */
function cake_list_CreateSublist($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/CreateSublist.php");
}

/**
 * Deletes a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_Delete($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/Delete.php");
}

/**
 * Deletes an e-mail from a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_DeleteEmail($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/DeleteEmail.php");
}

/**
 * Deletes a record from a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_DeleteRecord($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/DeleteRecord.php");
}

/**
 * Deletes a sublist from a list
 *
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_DeleteSublist($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "List/DeleteSublist.php");
}

/**
 * Deletes a test e-mail from a list
 *
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_DeleteTestEmail($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/DeleteTestEmail.php");
}

/**
 * Edits the structure of the list
 *
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_EditStructure($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/EditStructure.php");
}

/**
 * Gets the list's fields
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_GetFields($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/GetFields.php");
}

/**
 * Gets the list's informations
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_GetInfo($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/GetInfo.php");
}

/**
 * Gets the lists with a specified status
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_GetList($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/GetLists.php");
}

/**
 * Returns the logs of a list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_GetLog($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "List/GetLog.php");
}

/**
 * Retrieves a single record from a list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_GetRecord($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/GetRecord.php");
}

/**
 * Gets the sublists of a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_GetSublists($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/GetSublists.php");
}

/**
 * Gets the list of the test e-mails
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_GetTestEmails($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/GetTestEmails.php");
}

/**
 * Imports records into a list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_Import($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/Import.php");
}

/**
 * Sets the parameters for a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_SetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "List/SetInfo.php");
}

/**
 * Searchs a list after a set of conditions
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_Search($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "List/Search.php");
}

/**
 * Shows a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_list_Show($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/Show.php");
}

/**
 * Subscribes an e-mail to a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_SubscribeEmail($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/SubscribeEmail.php");
}

/**
 * Tests a sublist before creation
 *
 * @params   array  an array of parameters
 * @returns  mixed  the results fetching the query or an exception
 */
function cake_list_TestSublist($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/TestSublist.php");
}

/**
 * Unsubscribes an e-mail from a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_UnsubscribeEmail($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/UnsubscribeEmail.php");
}

/**
 * Updates a record into a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_list_UpdateRecord($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/UpdateRecord.php");
}

/**
 * Uploads a file into a list
 * 
 * @params   array  an array of parameters
 * @returns  mixed
 */
function cake_list_Upload($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "List/Upload.php");
}

?>
