<?php

/**
 * cake_Country.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Gets the list of countries
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_country_GetList($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "Country/GetList.php");
}

/**
 * Gets the list of provinces for a specified country
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_country_GetProvinces($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "Country/GetProvinces.php");
}

?>
