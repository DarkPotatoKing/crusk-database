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
          <form action="edit_note_2.php" method="POST">
            <label for="note">Class Name</label>
            <?php printf('<input type="text" name="note" value="%s">', $_POST["note"]) ?>
            <?php printf('<input type="hidden" name="student_number" value="%s">', $_POST["student_number"]) ?>
            <?php printf('<input type="hidden" name="id" value="%s">', $_POST["id"]) ?>
            <input type="submit" value="Save">
          </form>
        </div>
    </div>


</body>
</html>