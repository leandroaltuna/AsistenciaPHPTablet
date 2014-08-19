<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Microinei
 *
 * Mini framework para encuestas
 *
 * @package		Microinei
 * @author		holivares
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://example.com/license.html
 * @link		http://Microinei.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Microinei String Helpers
 *
 * @package		Microinei
 * @subpackage	Helpers
 * @category	Helpers
 * @author		holivares
 * @link		http://example.com/helpers/string_helper.html
 */
// ------------------------------------------------------------------------

/**
 * Trim Slashes
 *
 * Removes any leading/trailing slashes from a string:
 *
 * /this/that/theother/
 *
 * becomes:
 *
 * this/that/theother
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('trim_slashes')) {

    function trim_slashes($str) {
        return trim($str, '/');
    }

}

// ------------------------------------------------------------------------

/**
 * Strip Slashes
 *
 * Removes slashes contained in a string or in an array
 *
 * @access	public
 * @param	mixed	string or array
 * @return	mixed	string or array
 */
if (!function_exists('strip_slashes')) {

    function strip_slashes($str) {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = strip_slashes($val);
            }
        } else {
            $str = stripslashes($str);
        }

        return $str;
    }

}

// ------------------------------------------------------------------------

/**
 * Strip Quotes
 *
 * Removes single and double quotes from a string
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('strip_quotes')) {

    function strip_quotes($str) {
        return str_replace(array('"', "'"), '', $str);
    }

}

// ------------------------------------------------------------------------

/**
 * Quotes to Entities
 *
 * Converts single and double quotes to entities
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('quotes_to_entities')) {

    function quotes_to_entities($str) {
        return str_replace(array("\'", "\"", "'", '"'), array("&#39;", "&quot;", "&#39;", "&quot;"), $str);
    }

}

// ------------------------------------------------------------------------

/**
 * Reduce Double Slashes
 *
 * Converts double slashes in a string to a single slash,
 * except those found in http://
 *
 * http://www.some-site.com//index.php
 *
 * becomes:
 *
 * http://www.some-site.com/index.php
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('reduce_double_slashes')) {

    function reduce_double_slashes($str) {
        return preg_replace("#(^|[^:])//+#", "\\1/", $str);
    }

}

// ------------------------------------------------------------------------

/**
 * Reduce Multiples
 *
 * Reduces multiple instances of a particular character.  Example:
 *
 * Fred, Bill,, Joe, Jimmy
 *
 * becomes:
 *
 * Fred, Bill, Joe, Jimmy
 *
 * @access	public
 * @param	string
 * @param	string	the character you wish to reduce
 * @param	bool	TRUE/FALSE - whether to trim the character from the beginning/end
 * @return	string
 */
if (!function_exists('reduce_multiples')) {

    function reduce_multiples($str, $character = ',', $trim = FALSE) {
        $str = preg_replace('#' . preg_quote($character, '#') . '{2,}#', $character, $str);

        if ($trim === TRUE) {
            $str = trim($str, $character);
        }

        return $str;
    }

}

// ------------------------------------------------------------------------

/**
 * Create a Random String
 *
 * Useful for generating passwords or hashes.
 *
 * @access	public
 * @param	string	type of random string.  basic, alpha, alunum, numeric, nozero, unique, md5, encrypt and sha1
 * @param	integer	number of characters
 * @return	string
 */
if (!function_exists('random_string')) {

    function random_string($type = 'alnum', $len = 8) {
        switch ($type) {
            case 'basic' : return mt_rand();
                break;
            case 'alnum' :
            case 'numeric' :
            case 'nozero' :
            case 'alpha' :

                switch ($type) {
                    case 'alpha' : $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum' : $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric' : $pool = '0123456789';
                        break;
                    case 'nozero' : $pool = '123456789';
                        break;
                }

                $str = '';
                for ($i = 0; $i < $len; $i++) {
                    $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
                }
                return $str;
                break;
            case 'unique' :
            case 'md5' :

                return md5(uniqid(mt_rand()));
                break;
            case 'encrypt' :
            case 'sha1' :

                $CI = & get_instance();
                $CI->load->helper('security');

                return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
                break;
        }
    }

}

// ------------------------------------------------------------------------

/**
 * Add's _1 to a string or increment the ending number to allow _2, _3, etc
 *
 * @param   string  $str  required
 * @param   string  $separator  What should the duplicate number be appended with
 * @param   string  $first  Which number should be used for the first dupe increment
 * @return  string
 */
function increment_string($str, $separator = '_', $first = 1) {
    preg_match('/(.+)' . $separator . '([0-9]+)$/', $str, $match);

    return isset($match[2]) ? $match[1] . $separator . ($match[2] + 1) : $str . $separator . $first;
}

// ------------------------------------------------------------------------

/**
 * Alternator
 *
 * Allows strings to be alternated.  See docs...
 *
 * @access	public
 * @param	string (as many parameters as needed)
 * @return	string
 */
if (!function_exists('alternator')) {

    function alternator() {
        static $i;

        if (func_num_args() == 0) {
            $i = 0;
            return '';
        }
        $args = func_get_args();
        return $args[($i++ % count($args))];
    }

}

// ------------------------------------------------------------------------

/**
 * Repeater function
 *
 * @access	public
 * @param	string
 * @param	integer	number of repeats
 * @return	string
 */
if (!function_exists('repeater')) {

    function repeater($data, $num = 1) {
        return (($num > 0) ? str_repeat($data, $num) : '');
    }

}
/**
 *
 */
if (!function_exists('hex_decode')) {

    function hex_decode($string) {
        for ($i = 0; $i < strlen($string); $i) {
            $decoded .= chr(hexdec(substr($string, $i, 2)));
            $i = (float) ($i) + 2;
        }
        return $decoded;
    }

}
/**
 *
 */
if (!function_exists('replace_unicode_escape_sequence')) {

    function replace_unicode_escape_sequence($match) {
        return hex_decode($match[1]);
    }

}
/**
 * 
 */
if (!function_exists('unicode_decode')) {

    function unicode_decode($string) {
        $str = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $string);
        return ($str === '') ? NULL : $str;
    }

}

if (!function_exists('utf8_decode2')) {

    function utf8_decode2($string) {
        return ($string === '' | $string === NULL) ? NULL : utf8_decode($string);
    }

}

if (!function_exists('utf8_decode3')) {

    function utf8_decode3($string) {
        return ($string === '' | $string === NULL) ? NULL : html_entity_decode(htmlentities($string), null, 'UTF-8');
    }

}

if (!function_exists('remove_invisible_characters')) {

    function remove_invisible_characters($str, $url_encoded = TRUE) {
        $non_displayables = array();

        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }

}

if (!function_exists('singular')) {

    function singular($str) {
        $result = strval($str);

        $singular_rules = array(
            '/(matr)ices$/' => '\1ix',
            '/(vert|ind)ices$/' => '\1ex',
            '/^(ox)en/' => '\1',
            '/(alias)es$/' => '\1',
            '/([octop|vir])i$/' => '\1us',
            '/(cris|ax|test)es$/' => '\1is',
            '/(shoe)s$/' => '\1',
            '/(o)es$/' => '\1',
            '/(bus|campus)es$/' => '\1',
            '/([m|l])ice$/' => '\1ouse',
            '/(x|ch|ss|sh)es$/' => '\1',
            '/(m)ovies$/' => '\1\2ovie',
            '/(s)eries$/' => '\1\2eries',
            '/([^aeiouy]|qu)ies$/' => '\1y',
            '/([lr])ves$/' => '\1f',
            '/(tive)s$/' => '\1',
            '/(hive)s$/' => '\1',
            '/([^f])ves$/' => '\1fe',
            '/(^analy)ses$/' => '\1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/' => '\1\2sis',
            '/([ti])a$/' => '\1um',
            '/(p)eople$/' => '\1\2erson',
            '/(m)en$/' => '\1an',
            '/(s)tatuses$/' => '\1\2tatus',
            '/(c)hildren$/' => '\1\2hild',
            '/(n)ews$/' => '\1\2ews',
            '/([^u])s$/' => '\1',
        );

        foreach ($singular_rules as $rule => $replacement) {
            if (preg_match($rule, $result)) {
                $result = preg_replace($rule, $replacement, $result);
                break;
            }
        }

        return $result;
    }

}
/* End of file string_helper.php */
/* Location: ./system/helpers/string_helper.php */