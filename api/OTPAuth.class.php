<?php

require ("../vendor/autoload.php");
use OTPHP\TOTP;


class OTPAuth {

    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }
    public function generateQRCode() {
        // A random secret will be generated from this.
        // You should store the secret with the user for verification.
        $otp = TOTP::generate();
        echo "The OTP secret is: {$otp->getSecret()}\n";
        return $otp->getSecret();
    }

    public function getSecret() {
        
        return $this->generateRandomSecret();
    }

    private function generateRandomSecret() {
        // Generate a random secret using a library or function of your choice
        // Here's an example using the random_bytes function
        $secretLength = 16; // Length of the secret in bytes (adjust as needed)
        $secret = bin2hex(random_bytes($secretLength));
        
        return $secret;
    }

    public function createQR($secret) {
        $otp = TOTP::createFromSecret($secret);
        $otp->setLabel('Secure Login App');
        $qrCodeUri = $otp->getQrCodeUri(
            'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
            '[DATA]'
        );
        return $qrCodeUri;
    }

    public function otpValidation($secret, $input_otp) {
        $otp = TOTP::createFromSecret($secret); // create TOTP object from the secret.
        return $otp->verify($input_otp); // Returns true if the input is verified, otherwise false.
    }

    public function validateOTP($userInput) {
    $secret = $this->dao->getSecret(); // Replace this with your logic to retrieve the secret from the database

    // Generate the OTP based on the secret
    $otp = TOTP::createFromSecret($secret);
    $generatedOTP = $otp->now();

    // Compare the generated OTP with the user input OTP
    if ($generatedOTP === $userInput) {
        return true;
    } else {
        return false;
    }
}




    public function getQrCode($user) {
        return $this->dao->getQrCode($user);
    }

}

?>