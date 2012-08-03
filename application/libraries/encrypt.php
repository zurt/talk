<?php

class Encrypt {
/**
     * Encrypt data using AES256
     *
     * @param string $data The plaintext
     * @return string The encyrypted data
     */
    function _encrypt($data)
    {
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
error_log($iv);
        return mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256, "gw2iYt26Gw", trim($data), MCRYPT_MODE_ECB,
            $iv
        );
    }

    /**
     * Decrypt data using AES256
     *
     * @param string $data The AES256 encrypted data
     * @return string The decyrypted data
     */
    function _decrypt($data)
    {
	    $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        return
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_256, "gw2iYt26Gw", trim($data),
                MCRYPT_MODE_ECB, $iv
        );
    }

}