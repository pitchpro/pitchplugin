<?php

/**
 * These functions should exist in WordPress or PHP already
 * but they don't :(
 */

if( !function_exists( 'is_post_type' ) ){
    function is_post_type( $type ){
        global $wp_query;
        if( ( is_array($type) && in_array( get_post_type( $wp_query->post->ID ), $type ) ) || $type == get_post_type( $wp_query->post->ID ) )
                return true;
        return false;
    }
}

if( !function_exists( 'get_last_path_segment' ) ){
    function get_last_path_segment($url) {
        $path = parse_url($url, PHP_URL_PATH); // to get the path from a whole URL
        $pathTrimmed = trim($path, '/'); // normalise with no leading or trailing slash
        $pathTokens = explode('/', $pathTrimmed); // get segments delimited by a slash

        if (substr($path, -1) !== '/') {
            array_pop($pathTokens);
        }
        return end($pathTokens); // get the last segment
    }
}

if( !function_exists( 'is_valid_guid' ) ){
    function is_valid_guid( $guid ){
        return !(preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $guid ));
    }
}

/**
* Returns a GUIDv4 string
*
* Uses the best cryptographically secure method
* for all supported pltforms with fallback to an older,
* less secure version.
*
* @param bool $trim
* @return string
*/
if( !function_exists( 'create_unique_GUIDv4' ) ){
    function create_unique_GUIDv4 ($trim = true) {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace.
                  substr($charid,  0,  8).$hyphen.
                  substr($charid,  8,  4).$hyphen.
                  substr($charid, 12,  4).$hyphen.
                  substr($charid, 16,  4).$hyphen.
                  substr($charid, 20, 12).
                  $rbrace;
        return $guidv4;
    }
}
