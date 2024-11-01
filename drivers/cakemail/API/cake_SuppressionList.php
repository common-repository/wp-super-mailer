<?php

/**
 * cake_SuppressionList.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Imports one or more domains into the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_suppressionlist_ImportDomains($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/ImportDomains.php");
}

/**
 * Imports one or more emails into the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_suppressionlist_ImportEmails($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/ImportEmails.php");
}

/**
 * Imports one or more local-parts into the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_suppressionlist_ImportLocalparts($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/ImportLocalparts.php");
}

/**
 * Exports the domains from the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or a number or an exception
 */
function cake_suppressionlist_ExportDomains($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/ExportDomains.php");
}

/**
 * Exports the emails from the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or a number or an exception
 */
function cake_suppressionlist_ExportEmails($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/ExportEmails.php");
}

/**
 * Exports the local-parts from the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or a number or an exception
 */
function cake_suppressionlist_ExportLocalparts($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/ExportLocalparts.php");
}

/**
 * Deletes one or more domains from the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_suppressionlist_DeleteDomains($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/DeleteDomains.php");
}

/**
 * Deletes one or more emails from the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_suppressionlist_Deleteemails($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/DeleteEmails.php");
}

/**
 * Deletes one or more local-parts from the suppression list
 *
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_suppressionlist_DeleteLocalparts($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "SuppressionList/DeleteLocalparts.php");
}

?>
