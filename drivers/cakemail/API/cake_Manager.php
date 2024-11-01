<?php

/**
 * cake_Manager.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "cake_Xml.php");

/**
 * Gets the list of managers
 * 
 * @params   array  an array of parameters
 * @returns  mixed  an array or an exception
 */
function cake_manager_GetList($params = array(), $locale = "en_US") {
    return require(LIB_CAKE . "Manager/GetList.php");
}

?>
