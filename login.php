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
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cruskdb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
        }
        else
        {
            $query = "SELECT * FROM Stalkers WHERE username=? AND password=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result)
            {
                $_SESSION["username"] = $_POST["username"];
                $_SESSION["password"] = $_POST["password"];
                echo '<script type="text/javascript"> window.location = "//localhost/cruskdb/home.php"</script>';
            }
            else
            {
                echo "Invalid username/password.";
            }
            $stmt->close();
        }

        $conn->close();
    ?>
</body>
</html>
