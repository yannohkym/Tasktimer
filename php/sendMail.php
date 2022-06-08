<?php
require_once '../vendor/autoload.php';
// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
    ->setUsername('f3b186cdbb8280')
    ->setPassword('83e0017c275f05');
//Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

function sendVerificationEmail($userEmail, $subject, $message)
{
    global $mailer;
    $body = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Test mail</title>
    <style>
        #head{
            text-align: center;
            font-size: 1.5em;
            margin: 0;
            color: darkred;
            font-family: monospace;
        }
        .container{
            position: relative;
            top: 20%;
            text-align: center;
            padding: 30px;
            background-color: rgb(212 173 179 / 86%);
            height: fit-content;
            width: 50%;
            margin: auto;
            box-shadow: 3px 3px 6px 1px #000000;
            border-radius: 10px;
        }
        a {
            text-align: center;
            background: darkred;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
        }
    </style>
</head>

<body>
<div class='container'>
    <div>
        <h1 id='head'>Tasktimer</h1>
    </div>
    " . $message . "
</div>
</body>

</html>";

   try{
       // Create a message
       $message = (new Swift_Message('Verify your email'))
           ->setFrom('iankym1997@gmail.com')
           ->setTo($userEmail)
           ->setSubject($subject)
           ->setBody($body, 'text/html');

       // Send the message
       $result = $mailer->send($message);

       if ($result > 0) {
           return true;
       } else {
           return false;
       }
   }catch (Exception $e) {
      throw $e;
   }
}
