<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Search</title>
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
      <li><a href="/cruskdb/classes.php">Classes</a></li>
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



    <h1>
        Search results
    </h1>

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
            $query = "SELECT student_number, name, facebook, twitter, instagram FROM Crusks WHERE name LIKE ? AND NOT EXISTS(SELECT stalker_username, crusk_student_number FROM Stalks WHERE stalker_username=? AND crusk_student_number=student_number)";
            $_POST["search_string"]  = strtoupper( $_POST["search_string"]);
            $stmt = $conn->prepare($query);
            $s = '%' . $_POST["search_string"] . '%';
            $stmt->bind_param("ss", $s, $_SESSION["username"]);
            $stmt->execute();
            $stmt->bind_result($student_number, $name, $facebook, $twitter, $instagram);
            $num_rows = 0;
            while ($stmt->fetch())
            {
                echo '<li>';
                echo '<h3>'.$name.'</h3>' . "<br>";
                echo '<b>'.$student_number . "</b><br>";
                echo $facebook . "<br>";
                echo $twitter . "<br>";
                echo $instagram . "<br>";
                // printf('<form method="POST" action="stalk.php"><input type="hidden" name="student_number" value="%s"><input type="submit" value="Stalk"></form>', $student_number);
                // <button type="submit" class="btn" >Log out</button>
                printf('<form method="post" action="stalk.php">
                            <input type="hidden" name="student_number" value="%s">
                            <button type="submit" class="btn btn-primary" >Stalk</button>
                        </form>', $student_number);
                echo '</li><br>';

                $num_rows = $num_rows + 1;
            }

            if ($num_rows == 0)
            {
                echo "No search results.";
            }


            $stmt->close();
        }

        $conn->close();
    ?>
    </div>
</body>
</html>