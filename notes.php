<?php
// Start the session
session_start();

if (isset($_SESSION["currently_viewing"]))
{
    $_POST["student_number"] = $_SESSION["currently_viewing"];
    unset($_SESSION["currently_viewing"]);
}

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
        echo '<form method="POST" action="add_note.php">
                Note:<input type="text" name="note" value=""><br>';
        printf('<input type="hidden" name="student_number" value="%s">', $_POST["student_number"]);
        echo        '<input type="submit" value="Add note">
              </form><br>';

        $query = "SELECT note, id FROM Notes WHERE stalker_username=? AND   crusk_student_number=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $_SESSION["username"],$_POST["student_number"]);
        $stmt->execute();
        $stmt->bind_result($notes, $id);
        $num_rows = 0;
        while ($stmt->fetch())
        {
            echo $notes;
            printf('<form method="POST" action="delete_note.php"><input type="hidden" name="student_number" value="%s"><input type="hidden" name="id" value="%s"><input type="submit" value="delete"></form>', $_POST["student_number"], $id);
            $num_rows = $num_rows + 1;
            echo '<br>';
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