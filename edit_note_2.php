<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
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
            $stmt->bind_param("ss", $_SESSION["username"], $_SESSION["password"]);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result)
            {
            }
            else
            {
                echo "You are not logged in.";
                $stmt->close();
                $conn->close();
                exit();

            }
            $stmt->close();
        }

        $conn->close();
    ?>

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
            if ($stmt = $conn->prepare("UPDATE Notes SET  note=? WHERE id=?"))
            {
                /* bind parameters for markers */
                $stmt->bind_param("ss", $_POST["note"], $_POST["id"]);
                /* execute query */
                if ($stmt->execute() === true)
                {
                }
                /* close statement */
                $stmt->close();
            }

        }
        $_SESSION["currently_viewing"] = $_POST["student_number"];
        $conn->close();
        echo '<script type="text/javascript"> window.location = "//localhost/cruskdb/notes.php"</script>';
    ?>
</body>
</html>