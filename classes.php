<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
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

    <div>
        <style scoped>
        input[type=text], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        div {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }
        </style>
        <body>

        <div>
          <form action="add_class.php" method="POST">
            <label for="cname">Class Name</label>
            <input type="text" id="cname" name="class_name">
            <input type="submit" value="Add Class">
          </form>
        </div>
    </div>
<!--
    echo '<form method="POST" action="add_class.php">
                Class:<input type="text" name="class_name" value=""><br>';
        echo '<input type="submit" value="Add class"> </form><br>'; -->


    <div>
    <style scoped>
        div {
          margin: 20px;
        }

        ul {
          list-style-type: none;
          width: 500px;
        }

        h3 {
          font: bold 20px/1.5 Helvetica, Verdana, sans-serif;
        }

        li img {
          float: left;
          margin: 0 15px 0 0;
        }

        li p {
          font: 200 12px/1.5 Georgia, Times New Roman, serif;
        }

        li {
          padding: 10px;
          overflow: auto;
        }

        li:hover {
          background: #eee;
          cursor: pointer;
        }

    </style>

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
            echo '<h1>Classes</h1>';
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
                echo "<h3>You have no classes.</h3>";
            }

            $stmt->close();


            for ($i=0; $i < $num_rows; $i++)
            {
                echo '<li>';
                echo '<h3>'.$class_names[$i].'</h3>';
                echo '<b>Classmates(s):</b> ';

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

                echo '<br><br>';
                echo '<form method="POST" action="add_classmates_form.php">';
                printf('<input type="hidden" value="%s" name="id">', $class_ids[$i]);
                printf('<input type="hidden" value="%s" name="class_name">', $class_names[$i]);
                echo '<button type="submit" class="btn btn-primary">Edit Classmates</button></form><br>';

                echo '<form method="POST" action="delete_class.php">';
                printf('<input type="hidden" value="%s" name="id">', $class_ids[$i]);
                echo '<button type="submit" class="btn btn-primary"> Delete Class</button></form><br>';

                echo '<br>';
                echo '</li>';
            }
        }
        $conn->close();
    ?>
    </div>

</body>
</html>
