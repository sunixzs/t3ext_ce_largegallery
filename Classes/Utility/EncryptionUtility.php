<?php
namespace MAB\CeLargegallery\Utility;

class EncryptionUtility
{
    /**
     * Returns an encrypted & utf8-encoded
     */
    public static function encrypt($string)
    {
        $result = '';
        for ($i = 0; $i < strlen($string); $i ++) {
            $char = substr($string, $i, 1);
            $keychar = substr($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'], ($i % strlen($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'])) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }
    
    /**
     * Returns decrypted original string
     */
    public static function decrypt($string)
    {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i ++) {
            $char = substr($string, $i, 1);
            $keychar = substr($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'], ($i % strlen($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'])) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }
}
