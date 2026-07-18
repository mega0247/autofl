<?php
session_start();

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload the PHPMailer classes
require 'lib/PHPMailer/Exception.php';
require 'lib/PHPMailer/PHPMailer.php';
require 'lib/PHPMailer/SMTP.php';

$config = include 'config/mail_config.php';

// Fetch form data
$subject = $_POST['subject'];
$bcc = $_POST['bcc'];
$body = $_POST['body'];
$signature = isset($_POST['signature']) ? $_POST['signature'] : '';

// Append the signature to the message body
$body .= "\n\n" . $signature;

// Set up the PHPMailer instance
$mail = new PHPMailer(true);
try {
    // SMTP server settings
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];  // SMTP host (e.g. smtp.nitromail.io)
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_user'];  // SMTP username
    $mail->Password = $config['smtp_pass'];  // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['smtp_port'];  // SMTP port (587 for TLS)

    // Sender info
    $mail->setFrom($config['smtp_user'], $config['name']);
    $mail->addReplyTo($config['smtp_user'], $config['name']);

    // BCC recipients
    $bccEmails = explode(',', $bcc); // Split the BCC emails by commas
    foreach ($bccEmails as $bccEmail) {
        $mail->addBCC(trim($bccEmail));  // Add each email to BCC
    }

    // Email subject and body
    $mail->Subject = $subject;
    $mail->Body    = $body;

    // Send the email
    $mail->send();
    echo 'Message has been sent successfully.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
