<?php

/**
 * cake_Client.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Activates a client
 *
 * @confirmation  string  confirmation code
 * @returns       mixed   an array or an exception
 */
function cake_client_Activate($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/Activate.php");
}

/**
 * Adds credits to a client
 *
 * @params   array  an array of parameters
 * @returns  mixed  nothing or an exception
 */
function cake_client_AddCredits($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/AddCredits.php");
}

/**
 * Creates a new client
 *
 * @params   array  an array of parameters
 * @returns  mixed  the confirmation code or an exception
 */
function cake_client_Create($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/Create.php");
}

/**
 * Gets the credit balance
 *
 * @params   array  an array of parameters
 * @returns  mixed  a value representing the credit balance or an exception
 */
function cake_client_GetCreditBalance($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/GetCreditBalance.php");
}

/**
 * Gets the credit transactions
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_client_GetCreditTransactions($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/GetCreditTransactions.php");
}

/**
 * Retrieves the informations about the client
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_client_GetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/GetInfo.php");
}

/**
 * Gets the list with a specified status
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_client_GetList($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "Client/GetList.php");
}

/**
 * Gets the timezones
 *
 * @params   void   nothing
 * @returns  mixed  an array or an exception
 */
function cake_client_GetTimezones($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/GetTimezones.php");
}

/**
 * Adds or removes credits to a client for the balance to be 0 at the end of the month
 *
 * @params   array  an array of parameters
 * @returns  mixed  nothing or an exception
 */
function cake_client_ResetCredits($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/ResetCredits.php");
}

/**
 * Searchs for clients based on a query string
 *
 * @params   array  an array of parameters
 * @returns  mixed  nothing or an exception
 */
function cake_client_Search($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/Search.php");
}

/*
 * Sets the parameters for a user
 * 
 * @params   array  an array of parameters
 * @returns  mixed  true or an exception
 */
function cake_client_SetInfo($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/SetInfo.php");
}

/*
 * Returns the parents of a given client_id from the current user_id point of view
 * 
 * @params   array  an array of parameters
 * @returns  mixed  array or an exception
 */
function cake_client_GetParents($params = array(), $locale = "en_US") {
	return require(LIB_CAKE . "Client/GetParents.php");
}
