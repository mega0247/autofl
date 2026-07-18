<?php
$config = include 'config/mail_config.php';

// Connect to IMAP
$imap = imap_open("{".$config['imap_host'].":".$config['imap_port']."/imap/ssl}INBOX", $config['email'], $config['smtp_pass']);

if (!$imap) {
    echo "Failed to connect to the inbox. Please check your IMAP settings.";
    exit;
}

// Search for all emails
$mails = imap_search($imap, 'ALL');

if ($mails) {
    rsort($mails); // Sort emails from newest to oldest
    
    // Loop through each email and display a clickable link
    foreach ($mails as $mail_id) {
        $header = imap_headerinfo($imap, $mail_id);
        $subject = htmlspecialchars($header->subject);
        $from = htmlspecialchars($header->fromaddress);
        $date = htmlspecialchars($header->date);

        // Display email summary with a link to view the full message
        echo "<a href='view.php?id=$mail_id'>" . $from . ": " . $subject . "</a><br>";
        echo "<small>Date: $date</small><br><hr>";
    }
} else {
    echo "No emails found in your inbox.";
}

// Close the IMAP connection
imap_close($imap);
?>
