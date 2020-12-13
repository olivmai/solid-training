<?php

namespace App\Classes\Tools;

class Strings
{
    /**
     * randomString returns a random string
     *
     * @param int $length length of the string
     * @param string $type lowercase / uppercase / numeric
     *
     * @return string
     */
    public static function randomString($length = 10, $type = 'lowercase')
    {
        $id = '';
        srand();
        switch ($type) {
            case 'uppercase':
                $tokens = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                break;

            case 'numeric':
                $tokens = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                break;

            case 'lowercase':
            default:
                $tokens = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                break;
        }

        for ($i = 0; $i < $length; ++$i) {
            $id .= $tokens[array_rand($tokens)];
        }

        return $id;
    }

    /**
     * quotes2entity replaces all " in the string by &quot;
     * It is to be used in all HTML attributes such as title, longdesc,
     * value in a form, etc.
     *
     * @param string $string string to recode
     *
     * @return string
     */
    public static function quotes2entity($string)
    {
        return str_replace('"', '&quot;', $string);
    }

    /**
     * htmlEncodeString replaces all <, > and & by their HTML entities
     *
     * @param string $string string to encode
     * @param string $charset character encoding, optionnal (defaults to utf-8)
     *
     * @return string an encoded string
     */
    public static function htmlEncodeString($string, $charset = 'utf-8')
    {
        return trim(htmlspecialchars($string, ENT_NOQUOTES, $charset));
    }

    /**
     * htmlCleanString
     *
     * @param string $string
     *
     * @return string
     */
    public static function htmlCleanString($string)
    {
        $string = strtr(
            $string,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
        );
        $string = preg_replace('/([^.a-z0-9]+)/i', '-', $string);

        return null === $string ? '' : $string;
    }

    /**
     * htmlEncodeArray recursively loops an array to encode <, > and & in
     * all entries (typically a POST data array)
     *
     * @param array $tab array passed by reference
     * @param array $htmlOk list of array keys where HTML is accepted, hence
     *                      where no encoding is needed
     *
     * @note no return value since array is passed by reference
     */
    public static function htmlEncodeArray(array &$tab, array $htmlOk = [])
    {
        while (list($k) = each($tab)) {
            if (!is_array($tab[$k])) {
                if (!in_array($k, $htmlOk)) {
                    $tab[$k] = Strings::htmlEncodeString($tab[$k]);
                }
            } else {
                if (in_array($k, $htmlOk)) {
                    $htmlOk = array_merge($htmlOk, array_keys($tab[$k]));
                }
                self::htmlEncodeArray($tab[$k], $htmlOk);
            }
        }
    }
}
