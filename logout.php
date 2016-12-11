<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <?php
      $_SESSION["username"] = "";
      $_SESSION["password"] = "";
    ?>
    You are logged out
</body>
</html>
