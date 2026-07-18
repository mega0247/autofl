<?php
session_start();
$config = include 'config/mail_config.php';

// Open IMAP connection
$imap = imap_open("{" . $config['imap_host'] . ":" . $config['imap_port'] . "/imap/ssl}INBOX", $config['email'], $config['smtp_pass']);

// Retrieve the email ID from the query string
$id = intval($_GET['id']);

// Fetch the email structure and headers
$header = imap_headerinfo($imap, $id);

// Fetch the body of the email (multiple parts for HTML or plain text)
$structure = imap_fetchstructure($imap, $id);
$body = '';

// Check if the email has multiple parts (like HTML or plain text)
if ($structure->encoding == 3) {
    // If base64 encoded, decode it
    $body = imap_base64(imap_fetchbody($imap, $id, 1));
} elseif ($structure->encoding == 4) {
    // If quoted-printable encoded, decode it
    $body = imap_qprint(imap_fetchbody($imap, $id, 1));
} else {
    // If no encoding, fetch the raw body
    $body = imap_fetchbody($imap, $id, 1);
}

// Optional: You can also display other email information like sender, subject, etc.
echo "<h1>" . htmlspecialchars($header->subject) . "</h1>";
echo "<p><strong>From:</strong> " . htmlspecialchars($header->fromaddress) . "</p>";
echo "<p><strong>Date:</strong> " . htmlspecialchars($header->date) . "</p>";

// Display the body of the email
echo "<h3>Email Content:</h3>";
echo nl2br(htmlspecialchars($body)); // Ensure HTML special characters are properly escaped

// Close the IMAP connection
imap_close($imap);
?>
