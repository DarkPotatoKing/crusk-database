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
      session_destroy();
    ?>
    Logging out...
    <?php
    echo '<script type="text/javascript"> window.location = "//localhost/cruskdb/"</script>';
    ?>
</body>
</html>
