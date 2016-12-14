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
                echo "Hello " . $_SESSION["username"];
                echo '<form method="POST" action="logout.php">
                       <input type="submit" value="Logout">
                      </form>';

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

            if ($stmt = $conn->prepare("DELETE FROM IsClassmateIn WHERE IsClassmateIn.stalk_id = ANY(SELECT id FROM Stalks JOIN Stalkers ON stalker_username = username WHERE Stalkers.username = ?) AND IsClassmateIn.class_id = ANY(SELECT id FROM Classes WHERE id = ?)"))
            {
                /* bind parameters for markers */
                $stmt->bind_param("ss", $_SESSION["username"], $_POST['class_id']);
                /* execute query */
                if ($stmt->execute() === true)
                {
                    echo "Reset classmates.";
                }
                /* close statement */
                $stmt->close();
            }

            foreach ($_POST['stalk_id'] as $si)
            {
                if ($stmt = $conn->prepare("INSERT INTO IsClassmateIn (stalk_id, class_id) VALUES (?, ?)"))
                {
                    /* bind parameters for markers */
                    $stmt->bind_param("ss", $si, $_POST['class_id']);
                    /* execute query */
                    if ($stmt->execute() === true)
                    {
                        echo "Added classmate.";
                    }
                    /* close statement */
                    $stmt->close();
                }
            }

        }
        $conn->close();
        echo '<script type="text/javascript"> window.location = "//localhost/cruskdb/classes.php"</script>';
    ?>
</body>
</html>