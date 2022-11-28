<!DOCTYPE HTML>  
<html>
<head>
</head>
<body>
    <?php
        function clean_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function check_password($str){
            $error = "";
            $num_check = false;
            $uppercase_check = false;

            if (strlen($str) < 8){
                $error = "Min length is 8 chars";
            }

            $length = strlen($str);
            for ($i=0; $i<$length; $i++) {
                if (is_numeric($str[$i]) == true){
                    $num_check = true;
                }
                if (ctype_upper($str[$i]) == true){
                    $uppercase_check = true;
                }
            }

            if ($num_check == false){
                if (strlen($error) == 0){
                    $error = "At lease one number";
                }
                else{
                    $error = $error . ", at least one number";
                }
            }
            if ($uppercase_check == false){
                if (strlen($error) == 0){
                    $error = "At least one uppercase letter";
                }
                else{
                    $error = $error . ", at least one uppercase letter";
                }
            }

            if (strlen($error) > 0){
                echo "<script type='text/javascript'>alert('$error');window.location.href='index.php?page=login';</script>";
                return false;
            }
            else{
                return true;
            }
        }

        $username_user = $password_user = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['username'])){
                $username_user = clean_input($_POST["username"]);
            }
            if(isset($_POST['password'])){
                $password_user = clean_input($_POST["password"]);
            }
        }

        $servername = "localhost";
        $username_server = "root";
        $password_server = "";
        $dbname = "myDB";
        $conn = new mysqli($servername, $username_server, $password_server, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $exist = false;
        $gender = "";

        if (check_password($password_user) == true){
            $sql = "SELECT username, password, gender FROM users";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) 
            {
                while($row = $result->fetch_assoc()) {
                    if ($row["username"] == $username_user){
                        if ($row["password"] == $password_user){
                            $exist = true;
                            $gender = $row["gender"];
                        }
                    }
                }
            }

            if ($exist == false){
                $message = "Your account information is incorrect. Please enter it again!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=login';</script>";
            }
            else{
                $cookie_name = explode("@",$username_user)[0];
                $cookie_value = $gender;
                setcookie($cookie_name, $cookie_value, time() + 3600, "/");

                session_start();
                $_SESSION["cookie_name"] = $cookie_name;
                $_SESSION["cookie_value"] = $cookie_name;

                echo "<script type='text/javascript'>window.location.href='index.php?page=login';</script>";
            }
        }
    ?>
</body>
</html>