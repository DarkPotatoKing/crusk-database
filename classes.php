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
    <form method="POST" action="home.php"><input type="submit" value="Home"></form>

    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cruskdb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);


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
            $class_names = array();
            $class_ids = array();
            while ($stmt->fetch())
            {
                array_push($class_names, $class_name);
                array_push($class_ids, $id);
                $num_rows = $num_rows + 1;
            }

            if ($num_rows == 0)
            {
                echo "You have no classes.";
            }

            $stmt->close();


            for ($i=0; $i < $num_rows; $i++)
            {
                echo '<b>'.$class_names[$i].'</b><br>';
                echo 'classmates(s): ';

                $query = "SELECT student_number, name
                          FROM ((Classes JOIN IsClassmateIn ON Classes.id = IsClassmateIn.class_id) JOIN Stalks ON IsClassmateIn.stalk_id = Stalks.id) JOIN Crusks ON Crusks.student_number = Stalks.crusk_student_number
                          WHERE Classes.id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $class_ids[$i]);
                $stmt->execute();
                $stmt->bind_result($student_number, $name);
                $first = 1;
                while ($stmt->fetch())
                {
                    if ($first == 0)
                    {
                        printf(", ");
                    }
                    else
                    {
                        $first = 0;
                    }
                    printf("%s (%s)", $name, $student_number);
                }

                $stmt->close();

                echo '<form method="POST" action="add_classmates_form.php">';
                printf('<input type="hidden" value="%s" name="id">', $class_ids[$i]);
                printf('<input type="hidden" value="%s" name="class_name">', $class_names[$i]);
                echo '<input type="submit" value="Edit classmates"> </form><br>';

                echo '<form method="POST" action="delete_class.php">';
                printf('<input type="hidden" value="%s" name="id">', $class_ids[$i]);
                echo '<input type="submit" value="Delete class"> </form><br>';

                echo '<br>';
            }
        }
        $conn->close();
    ?>

</body>
</html>
