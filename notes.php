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
              <li class="active"><a href="/cruskdb/home.php">Crusks</a></li>
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
            $query = "SELECT student_number, name, facebook, twitter, instagram FROM Crusks WHERE student_number=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_POST["student_number"]);
            $stmt->execute();
            $stmt->bind_result($student_number, $name, $facebook, $twitter, $instagram);
            $num_rows = 0;
            while ($stmt->fetch())
            {
                echo '<li>';
                echo '<h1>'.$name . "</h1>";
                echo '<b>'.$student_number . "</b><br>";
                echo $facebook . "<br>";
                echo $twitter . "<br>";
                echo $instagram . "<br>";
                echo '</li>';

                $num_rows = $num_rows + 1;
            }

            $stmt->close();
        }

        echo('<div>
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
                  <form action="add_note.php" method="POST">
                    <label for="note">Note</label>
                    <input type="text" id="note" name="note">
                    <input type="submit" value="Add Note">');
                    printf('<input type="hidden" name="student_number" value="%s">', $_POST["student_number"]);
            echo    '</form>
                </div>
            </div>';


        echo '<h1>Notes</h1>';

        // echo '<form method="POST" action="add_note.php">
        //         Note:<input type="text" name="note" value=""><br>';
        // printf('<input type="hidden" name="student_number" value="%s">', $_POST["student_number"]);
        // echo        '<input type="submit" value="Add note">
        //       </form><br>';

        $query = "SELECT note, id FROM Notes WHERE stalker_username=? AND   crusk_student_number=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $_SESSION["username"],$_POST["student_number"]);
        $stmt->execute();
        $stmt->bind_result($notes, $id);
        $num_rows = 0;
        while ($stmt->fetch())
        {
            echo '<li>';
            echo '<h3>'.$notes.'</h3>';
            printf(
                    '<form method="POST" action="edit_note.php">
                        <input type="hidden" name="student_number" value="%s">
                        <input type="hidden" name="id" value="%s">
                        <input type="hidden" name="note" value="%s">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </form>',
                    $_POST["student_number"],
                    $id,
                    $notes
                  );
            printf('<br><form method="POST" action="delete_note.php">
                        <input type="hidden" name="student_number" value="%s">
                        <input type="hidden" name="id" value="%s">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>', $_POST["student_number"], $id);
            $num_rows = $num_rows + 1;
            echo '</li>';
        }

        if ($num_rows == 0)
        {
            echo 'No notes.';
        }

        $stmt->close();
        $conn->close();
    ?>
    </div>

</body>
</html>