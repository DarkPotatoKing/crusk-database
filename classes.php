<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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

    <nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Hello <?php echo $_SESSION["username"]?></a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

    <ul class="nav navbar-nav">
      <li><a href="/cruskdb/home.php">Crusks</a></li>
      <li class="active"><a href="/cruskdb/classes.php">Classes</a></li>
    </ul>

    <div class="col-sm-3 col-md-3">
        <form class="navbar-form" role="search" action="search.php" method="post">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="search_string">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        </form>
    </div>

    <ul class="nav navbar-nav navbar-right">
      <li>
        <form action="logout.php" method="post">
            <button type="submit" class="btn" >Log out</button>
        </form>
      </li>
    </ul>

  </div><!-- /.navbar-collapse -->
</nav>

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
