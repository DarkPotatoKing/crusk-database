<html>

<head>
    <title>Crusk DB</title>
</head>

<body>
    <form method="POST" action="login.php">
        Username:<br>
        <input type="text" name="username" value="username">
        <br>
        Password:<br>
        <input type="password" name="password" value="password">
        <br>
        <input type="submit" value="Login">
    </form>

    <form method="POST" action="signup.php">
        Username:<br>
        <input type="text" name="username" value="username"><br>
        Password:<br>
        <input type="password" name="password" value="password"><br>
        Confirm password:<br>
        <input type="password" name="confirm_password" value="password"><br>
        <input type="submit" value="Sign up">
    </form>

    <?php
        phpinfo();
    ?>
</body>
</html>
