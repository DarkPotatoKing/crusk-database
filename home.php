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

<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Crusk Database</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Crusks</a></li>
      <li><a href="#">Classes</a></li>
    </ul>

    <div class="col-sm-3 col-md-3">
        <form class="navbar-form" role="search">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="q">
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

    <form method="POST" action="classes.php"><input type="submit" value="Classes"></form>


    <h1>Crusks</h1>
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
            $query = "SELECT stalker_username, crusk_student_number FROM Stalks WHERE stalker_username=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION["username"]);
            $stmt->execute();
            $stmt->bind_result($col1, $crusk_student_number);
            $num_rows = 0;
            $student_numbers = array();
            while ($stmt->fetch())
            {
                array_push($student_numbers, $crusk_student_number);
                $num_rows = $num_rows + 1;
            }

            if ($num_rows == 0)
            {
                echo "You have no crusks.";
            }

            $stmt->close();

            echo "<ul>";
            foreach ($student_numbers as $sn)
            {
                $query = "SELECT student_number, name, facebook, twitter, instagram FROM Crusks WHERE student_number=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $sn);
                $stmt->execute();
                $stmt->bind_result($student_number, $name, $facebook, $twitter, $instagram);
                $stmt->fetch();
                echo '<li>';
                echo '<img src="heart.png"></img>';
                echo "<h3>".$name ."</h3>";
                echo "<p>";
                echo $student_number . "<br>";
                echo $facebook . "<br>";
                echo $twitter . "<br>";
                echo $instagram . "<br>";
                echo "</p>";
                echo "</li>";
                printf('<form method="POST" action="notes.php"><input type="hidden" name="student_number" value="%s"><input type="submit" value="Notes"></form>', $student_number);
                printf('<form method="POST" action="unstalk.php"><input type="hidden" name="student_number" value="%s"><input type="submit" value="Unstalk"></form>', $student_number);

                // echo '<div class="btn-group">
                //   <button type="button" class="btn btn-primary">Apple</button>
                //   <button type="button" class="btn btn-primary">Samsung</button>
                //   <button type="button" class="btn btn-primary">Sony</button>
                // </div>';

                $stmt->close();
            }
            echo "</ul>";

        }

        $conn->close();
    ?>
    </div>
</body>
</html>