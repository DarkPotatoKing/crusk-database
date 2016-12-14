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
        else if (isset($_POST['class_name']) === FALSE)
        {
            echo '<script type="text/javascript"> window.location = "//localhost/cruskdb/classes.php"</script>';
            exit();
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

    <form method="POST" action="home.php"><input type="submit" value="Home"></form>

    <form action="add_classmates.php" method="post">

    <?php echo $_POST['class_name'] ?>
    <br />
    <!-- <input type="checkbox" name="formDoor[]" value="A" />Acorn Building<br />
    <input type="checkbox" name="formDoor[]" value="B" />Brown Hall<br />
    <input type="checkbox" name="formDoor[]" value="C" />Carnegie Complex<br />
    <input type="checkbox" name="formDoor[]" value="D" />Drake Commons<br />
    <input type="checkbox" name="formDoor[]" value="E" />Elliot House<br /> -->

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
            $query = "SELECT crusk_student_number, id FROM Stalks WHERE stalker_username=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION["username"]);
            $stmt->execute();
            $stmt->bind_result($crusk_student_number, $stalk_id);
            $num_rows = 0;
            $student_numbers = array();
            $stalk_ids = array();
            while ($stmt->fetch())
            {
                array_push($student_numbers, $crusk_student_number);
                array_push($stalk_ids, $stalk_id);
                $num_rows = $num_rows + 1;
            }

            if ($num_rows == 0)
            {
                echo "You have no crusks.";
                exit();
            }
            $stmt->close();


            for ($i=0; $i < $num_rows; $i++)
            {
                $query = "SELECT name FROM Crusks WHERE student_number=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $student_numbers[$i]);
                $stmt->execute();
                $stmt->bind_result($name);
                $stmt->fetch();
                printf('<input type="checkbox" name="stalk_id[]" value="%s" />%s %s<br />',
                        $stalk_ids[$i], $student_numbers[$i], $name);
                $stmt->close();
            }
            printf('<input type="hidden" name="class_id" value="%s">', $_POST['id']);

        }
        $conn->close();
    ?>


    <input type="submit" name="formSubmit" value="Submit" />

    </form>


</body>
</html>