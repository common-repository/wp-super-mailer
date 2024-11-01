<?php

/**
 * global_template.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

//if (!defined("_DEBUG_")) {
//	define("_DEBUG_", 1);
//}

if (!defined("CAKE_VERSION")) {
	define("CAKE_VERSION", "1.0");
}

if (!defined("CAKE_MCRYPT_ALG")) {
	// choose one of these: blowfish (recommended), serpent, twofish
	define("CAKE_MCRYPT_ALG", "blowfish");
}

if (!defined("CAKE_MCRYPT_MODE")) {
	// choose one of these: ecb, cbc, cfb (recommended)
	define("CAKE_MCRYPT_MODE", "cfb");
}

require_once(LIB_CAKE . "cake_errors.php");

?>
