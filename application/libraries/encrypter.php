<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Encrypter {
/**
     * Encrypt data using AES256
     *
     * @param string $data The plaintext
     * @return string The encyrypted data
     */
    public function encryptData($data) {
        $ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        return mcrypt_encrypt(MCRYPT_BLOWFISH, "gw2iYt26Gw", trim($data), MCRYPT_MODE_ECB, $iv);
    }

    /**
     * Decrypt data using AES256
     *
     * @param string $data The AES256 encrypted data
     * @return string The decyrypted data
     */
    public function decryptData($data) {
	    $ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        return mcrypt_decrypt(MCRYPT_BLOWFISH, "gw2iYt26Gw", trim($data), MCRYPT_MODE_ECB, $iv);
    }

}