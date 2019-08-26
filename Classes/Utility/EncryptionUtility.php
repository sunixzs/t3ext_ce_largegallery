<?php
namespace MAB\CeLargegallery\Utility;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Class to crype/decrypt a string using the Typo3 encryptionKey.
 */
class EncryptionUtility
{
    /**
     * Encrypts a string
     * @param string $string plain string
     * @return string encrypted string
     */
    public static function encrypt($string)
    {
        $result = "";
        for ($i = 0; $i < strlen($string); $i ++) {
            $char = substr($string, $i, 1);
            $keychar = substr($GLOBALS["TYPO3_CONF_VARS"]["SYS"]["encryptionKey"], ($i % strlen($GLOBALS["TYPO3_CONF_VARS"]["SYS"]["encryptionKey"])) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }
    
    /**
     * Undocumented function
     *
     * @param string $string encrypted string
     * @return string plain string
     */
    public static function decrypt($string)
    {
        $result = "";
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i ++) {
            $char = substr($string, $i, 1);
            $keychar = substr($GLOBALS["TYPO3_CONF_VARS"]["SYS"]["encryptionKey"], ($i % strlen($GLOBALS["TYPO3_CONF_VARS"]["SYS"]["encryptionKey"])) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }
}
