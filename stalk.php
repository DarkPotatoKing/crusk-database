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
                // echo "Hello " . $_SESSION["username"];
                // echo '<form method="POST" action="logout.php">
                //        <input type="submit" value="Logout">
                //       </form>';
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
            if ($stmt = $conn->prepare("INSERT INTO Stalks (stalker_username, crusk_student_number) VALUES (?, ?)"))
            {
                /* bind parameters for markers */
                $stmt->bind_param("ss", $_SESSION["username"], $_POST["student_number"]);
                /* execute query */
                if ($stmt->execute() === true)
                {
                    echo "Stalked " . $_POST["student_number"];
                }
                /* close statement */
                $stmt->close();
            }
        }

        $conn->close();

        echo '<script type="text/javascript"> window.location = "//localhost/cruskdb/home.php"</script>';
    ?>
</body>
</html>