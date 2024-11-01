<?php

/**
 * cake_functions.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

require_once(LIB_CAKE . "global.php");

/**
 * Formats and returns a message
 */
function M() {
	$args = @func_get_args();
	return @call_user_func_array("sprintf", $args);
}

function cake_debug($var) {
	if (defined("_DEBUG_")) {
		$f = "/tmp/debug_lib_cake";
		@file_put_contents($f, "\n***** START *****\n\n", FILE_APPEND);
		@file_put_contents($f, @var_export($var, true), FILE_APPEND);
		@file_put_contents($f, "\n\n***** END *****\n\n", FILE_APPEND);
		@chmod($f, 0666);
	}
}

/**
 * Decrypts some data with a given key
 *
 * @data	in hex encrypted format
 * @key
 * @returns	the decrypted data
 */
function cake_decrypt($data, $key, $alg, $mode, $iv) {
	if (false === ($td = @mcrypt_module_open($alg, "", $mode, ""))) {
		throw new Exception(sprintf(_("Decryption error!")), DECRYPTION_ERROR);
	}

	$iv_size = @mcrypt_get_iv_size($alg, $mode);
	if (strlen($iv) != $iv_size) {
		throw new Exception(sprintf(_("The IV size should be %d!"), $iv_size), DECRYPTION_ERROR);
	}

	$r = @mcrypt_generic_init($td, $key, $iv);
	if (false === $r || 0 > $r) {
		throw new Exception(sprintf(_("Decryption error!")), DECRYPTION_ERROR);
	}

	$data = @mdecrypt_generic($td, @pack("H*", $data));
	
	@mcrypt_generic_deinit($td);
	@mcrypt_module_close($td);

	// remove the padded "\0"
	return str_replace("\0", "", $data);
}

/**
 * Encrypts some data with a given key
 *
 * @data
 * @key
 * @returns		the encrypted data in binary hex format
 */
function cake_encrypt($data, $key, $alg = "blowfish", $mode = "ecb", $iv = "00000000") {
	if (false === ($td = @mcrypt_module_open($alg, "", $mode, ""))) {
		throw new Exception("Can not initialize the encryption module!", ENCRYPTION_ERROR);
	}

	$iv_size = @mcrypt_get_iv_size($alg, $mode);
	if (strlen($iv) != $iv_size) {
		throw new Exception(sprintf("The IV size should be %d!", $iv_size), ENCRYPTION_ERROR);
	}

	if (@mcrypt_generic_init($td, $key, $iv)) {
		throw new Exception("Encryption error!", ENCRYPTION_ERROR);
	}

	$data = @mcrypt_generic($td, $data);
	
	@mcrypt_generic_deinit($td);
	@mcrypt_module_close($td);

	$data = @unpack("H*", $data);
	return $data[1];
}

/**
 * Retrieves the IV and the encrypted response
 *
 * @req      string  the request
 * @alg      string  the encryption algorithm
 * @mode     string  the encryption mode
 * @returns  array   the IV and the encrypted response
 */
function cake_get_iv_and_er($req, $alg, $mode) {
	$iv = "";
	$er = "";
	
	$iv_size = @mcrypt_get_iv_size($alg, $mode);

	if ("ecb" != $mode) {
		for ($i = 0; $i < $iv_size * 2; $i++) {
			$iv .= $req[$i];
		}
		$len = strlen($req);
		for (; $i < $len; $i++) {
			$er .= $req[$i];
		}
	} else {
		for ($i = 0; $i < $iv_size; $i++) {
			$iv .= "30";
		}
		$er = $req;
	}
	
	return array(@pack("H*", $iv), $er);
}

/**
 * Strips slashes from a variable according to magic_quotes_gpc
 *
 * @value
 */
function cake_smq($value) {
	return (@get_magic_quotes_gpc()) ? @stripslashes($value) : $value;
}

/**
 * Returns the limit hours for a day
 *
 * @date
 */
function get_hours($date) {
	$d = explode(" ", $date);
	$hours[0]["begin"]  = $d[0] . " 00:00:00";	$hours[0]["end"]  = $d[0] . " 00:59:59";
	$hours[1]["begin"]  = $d[0] . " 01:00:00";	$hours[1]["end"]  = $d[0] . " 01:59:59";
	$hours[2]["begin"]  = $d[0] . " 02:00:00";	$hours[2]["end"]  = $d[0] . " 02:59:59";
	$hours[3]["begin"]  = $d[0] . " 03:00:00";	$hours[3]["end"]  = $d[0] . " 03:59:59";
	$hours[4]["begin"]  = $d[0] . " 04:00:00";	$hours[4]["end"]  = $d[0] . " 04:59:59";
	$hours[5]["begin"]  = $d[0] . " 05:00:00";	$hours[5]["end"]  = $d[0] . " 05:59:59";
	$hours[6]["begin"]  = $d[0] . " 06:00:00";	$hours[6]["end"]  = $d[0] . " 06:59:59";
	$hours[7]["begin"]  = $d[0] . " 07:00:00";	$hours[7]["end"]  = $d[0] . " 07:59:59";
	$hours[8]["begin"]  = $d[0] . " 08:00:00";	$hours[8]["end"]  = $d[0] . " 08:59:59";
	$hours[9]["begin"]  = $d[0] . " 09:00:00";	$hours[9]["end"]  = $d[0] . " 09:59:59";
	$hours[10]["begin"] = $d[0] . " 10:00:00";	$hours[10]["end"] = $d[0] . " 10:59:59";
	$hours[11]["begin"] = $d[0] . " 11:00:00";	$hours[11]["end"] = $d[0] . " 11:59:59";
	$hours[12]["begin"] = $d[0] . " 12:00:00";	$hours[12]["end"] = $d[0] . " 12:59:59";
	$hours[13]["begin"] = $d[0] . " 13:00:00";	$hours[13]["end"] = $d[0] . " 13:59:59";
	$hours[14]["begin"] = $d[0] . " 14:00:00";	$hours[14]["end"] = $d[0] . " 14:59:59";
	$hours[15]["begin"] = $d[0] . " 15:00:00";	$hours[15]["end"] = $d[0] . " 15:59:59";
	$hours[16]["begin"] = $d[0] . " 16:00:00";	$hours[16]["end"] = $d[0] . " 16:59:59";
	$hours[17]["begin"] = $d[0] . " 17:00:00";	$hours[17]["end"] = $d[0] . " 17:59:59";
	$hours[18]["begin"] = $d[0] . " 18:00:00";	$hours[18]["end"] = $d[0] . " 18:59:59";
	$hours[19]["begin"] = $d[0] . " 19:00:00";	$hours[19]["end"] = $d[0] . " 19:59:59";
	$hours[20]["begin"] = $d[0] . " 20:00:00";	$hours[20]["end"] = $d[0] . " 20:59:59";
	$hours[21]["begin"] = $d[0] . " 21:00:00";	$hours[21]["end"] = $d[0] . " 21:59:59";
	$hours[22]["begin"] = $d[0] . " 22:00:00";	$hours[22]["end"] = $d[0] . " 22:59:59";
	$hours[23]["begin"] = $d[0] . " 23:00:00";	$hours[23]["end"] = $d[0] . " 23:59:59";
	
	return $hours;
}

?>
