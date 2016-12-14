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

    <form method="POST" action="search.php">
        <input type="text" name="search_string" value=""><br>
        <input type="submit" value="search">
    </form>


    <h1>Classes</h1>

    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cruskdb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        echo '<form method="POST" action="home.php"><input type="submit" value="Home"></form>';
        echo '<form method="POST" action="add_class.php">
                Class:<input type="text" name="class_name" value=""><br>';
        echo '<input type="submit" value="Add class"> </form><br>';

        // Check connection
        if ($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
        }
        else
        {
            $query = "SELECT class_name, id FROM Classes WHERE stalker_username=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION["username"]);
            $stmt->execute();
            $stmt->bind_result($class_name, $id);
            $num_rows = 0;
            while ($stmt->fetch())
            {
                echo $class_name;
                echo '<form method="POST" action="delete_class.php">';
                printf('<input type="hidden" value="%s" name="id">', $id);
                echo '<input type="submit" value="Delete class"> </form><br>';
                $num_rows = $num_rows + 1;
            }

            if ($num_rows == 0)
            {
                echo "You have no classes.";
            }

            $stmt->close();
        }
        $conn->close();
    ?>

</body>
</html>
