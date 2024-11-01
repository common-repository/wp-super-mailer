<?php

/**
 * cake_errors.php
 *
 * Copyright (c) 2002-2009 The Code Kitchen Inc.
 * 
 * Software designer & developer: 
 *  George Alexandru Dorobantu <gdalex@gmail.com>
 */

define("CLOSED_XML",       500);
define("UNCLOSED_XML",     501);
define("DECRYPTION_ERROR", 502);
define("ENCRYPTION_ERROR", 503);
define("XML_PARSE_ERROR",  504);

$lib_cake_errors = array (
    CLOSED_XML       => "The xml is closed; no further modifications",
    UNCLOSED_XML     => "The xml is not closed",
	DECRYPTION_ERROR => "Decryption error", 
    ENCRYPTION_ERROR => "Encryption error",
    XML_PARSE_ERROR  => "Error parsing the xml structure"
);

?>
