<!DOCTYPE html>
<html>
<head>
    <title>Sign up</title>
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
        else if ($_POST["password"] != $_POST["confirm_password"])
        {
            echo "Password does not match";
        }
        else
        {
            if ($stmt = $conn->prepare("INSERT INTO Stalkers (username, password) VALUES (?, ?)"))
            {
                /* bind parameters for markers */
                $stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
                /* execute query */
                if ($stmt->execute() === true)
                {
                    echo "Created user.";
                }
                else
                {
                    echo "'".  $_POST["username"] . "'" . " is already taken.";
                }
                /* close statement */
                $stmt->close();
            }
        }

        $conn->close();

    ?>

</body>
</html>
