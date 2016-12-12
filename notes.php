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


    <br>
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
            $query = "SELECT student_number, name, facebook, twitter, instagram FROM Crusks WHERE student_number=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_POST["student_number"]);
            $stmt->execute();
            $stmt->bind_result($student_number, $name, $facebook, $twitter, $instagram);
            $num_rows = 0;
            while ($stmt->fetch())
            {
                echo '<li>';
                echo $student_number . "<br>";
                echo $name . "<br>";
                echo $facebook . "<br>";
                echo $twitter . "<br>";
                echo $instagram . "<br>";
                echo '</li>';

                $num_rows = $num_rows + 1;
            }

            $stmt->close();
        }
        echo '<h1>Notes<form method="POST" action="home.php"><input type="submit" value="Home"></form></h1>';

        $query = "SELECT note FROM Notes WHERE stalker_username=? AND   crusk_student_number=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $_SESSION["username"],$_POST["student_number"]);
        $stmt->execute();
        $stmt->bind_result($notes);
        $num_rows = 0;
        while ($stmt->fetch())
        {
            echo $notes;
            $num_rows = $num_rows + 1;
        }

        if ($num_rows == 0)
        {
            echo 'No notes.';
        }

        $stmt->close();
        $conn->close();
    ?>

</body>
</html>