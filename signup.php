<!DOCTYPE html>
<html>
<head>
    <title>Sign up</title>
  <meta charset="UTF-8">
  <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


      <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="form">


        <div id="login">
          <h1>
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
                    echo "Connection failed";
                }
                else if ($_POST["password"] != $_POST["confirm_password"])
                {
                    echo "Passwords do not match";
                }
                else
                {
                    if ($stmt = $conn->prepare("INSERT INTO Stalkers (username, password) VALUES (?, ?)"))
                    {
                        /* bind parameters for markers */
                        $stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
                        /* execute query */
                        if ($stmt->execute() === true)
                        {
                            echo "Sign Up succesful";
                        }
                        else
                        {
                            echo "'".  $_POST["username"] . "'" . " is already taken";
                        }
                        /* close statement */
                        $stmt->close();
                    }
                }

                $conn->close();

            ?>
          </h1>

          <form action="index.php" method="post">

          <button class="button button-block"/>Log In/Sign Up</button>

          </form>

        </div>



</div> <!-- /form -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>



</body>
</html>
