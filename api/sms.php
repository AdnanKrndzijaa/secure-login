<?php
use Lcobucci\JWT\Signer\Rsa\Sha384;
require_once(__DIR__ . '/../config_default.php');

$number = $_GET['phone'];
$code = $_GET['code'];
//$sms_key = TEXT_MESSAGE_API_KEY ; 
$username_db = DB_USERNAME; 
//$sms_secret = TEXT_MESSAGE_SECRET;
$access_token = strtoupper(hash('sha384', DB_USERNAME));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://sssd-2022.adnan.dev/api/sms');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "from=SSSDBot/1.0.0&text=SMS Code: $code&to=$number&username=$username_db&access_token=$access_token");
$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
echo "Message sent!";

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SMS Confirmation</title>
  <link rel="stylesheet" href="../front/style.css">
</head>
<body>
  <div class="wrapper">
    <h2>SMS Confirmation</h2>
    <form action="#" method="POST" onsubmit="return validateForm()">
      <div class="input-box">
        <input type="text" name="code" id="code" placeholder="Enter code">
      </div>
      <div class="error-message" id="errorMessage"></div>
      <div class="input-box button">
        <input type="submit" value="Verify">
      </div>
    </form>
  </div>

  <style>
    .error-message {
      color: red;
      font-size: 14px;
      margin-bottom: 3px;
      text-align: center;
    }
  </style>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function validateForm() {
      localStorage.setItem("mfa-conf", "yes");        
      const phoneNumber = urlParams.get('phone');
      const generateCode = urlParams.get('code');      
      var code = document.getElementById('code').value;

      document.getElementById('errorMessage').textContent = '';
      if (code.trim() === '') {
        document.getElementById('errorMessage').textContent = 'Please enter the verification code.';
        return false;
      }
      localStorage.setItem("mfa-conf", "yes");        

      if (code !== generateCode) {
        document.getElementById('errorMessage').textContent = 'Incorrect verification code.';
        return false;
      } else {
        localStorage.setItem("mfa-conf", "yes");        
        window.location.replace('../front/login.html');
        return false; // Prevent form submission
      }
      return true;
    }
  </script>
</body>
</html>
