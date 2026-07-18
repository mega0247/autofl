<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form values
    $email = $_POST['username'];
    $password = $_POST['password'];

    // Debugging: Check if the form values are being passed correctly
    // var_dump($email, $password);  // For debugging purpose, you can remove this after testing
    
    // Get the IMAP settings from config
    $config = include('config/mail_config.php');
    $imap_host = $config['imap_host'];
    $imap_user = $config['smtp_user'];  // Your email address
    $imap_pass = $config['smtp_pass'];  // Your password

    // Attempt to open IMAP connection
    $imap = @imap_open("{" . $imap_host . ":993/imap/ssl}INBOX", $imap_user, $imap_pass);

    // Check if the connection was successful
    if ($imap) {
        // Only proceed with login check if IMAP is successfully connected
        if ($email === $imap_user && $password === $imap_pass) {
            $_SESSION['user_id'] = $email;
            $_SESSION['email_pass'] = $password;
            header("Location: compose.php"); // Redirect to compose page
            exit;
        } else {
            echo "❌ Invalid login credentials.";
        }

        imap_close($imap);  // Close connection
    } else {
        echo "Failed to connect: " . imap_last_error();  // Show IMAP error message
    }
}
?>

<form method="post" action="">
    Email: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>
