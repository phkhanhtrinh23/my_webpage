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
            echo "<script type='text/javascript'>alert('$error');</script>";
            return false;
        }
        else{
            return true;
        }
    }

    if (isset($_POST['register'])){
        if(isset($_POST['username'])){
            $username_user = clean_input($_POST["username"]);
        }
        if(isset($_POST['password'])){
            $password_user = clean_input($_POST["password"]);
        }
        if(isset($_POST['gender'])){
            $gender_user = clean_input($_POST["gender"]);
        }

        $servername = "localhost";
        $username_server = "root";
        $password_server = "";
        $dbname = "myDB";
        $conn = new mysqli($servername, $username_server, $password_server, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SHOW TABLES LIKE 'users'");
        if($result->num_rows == 0){
            $sql = "CREATE TABLE users (
                id int(100) NOT NULL AUTO_INCREMENT,
                username VARCHAR(30) UNIQUE NOT NULL,
                password VARCHAR(30) NOT NULL,
                gender VARCHAR(50) NOT NULL,
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            )";

            if ($conn->query($sql) == false) {
                die("Connection failed: " . $conn->connect_error);
            }
        }

        $sql = "SELECT username, password, gender FROM users";
        $result = $conn->query($sql);
        $duplicate = false;

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($row["username"] == $username_user){
                    $duplicate = true;
                }
            }
        }
        
        if ($duplicate == true){
            $message = "Your username has been used";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
        elseif (check_password($password_user) == true){
            $values = "(\"" . $username_user . "\", \"" . $password_user . "\", \"" . $gender_user . "\")";

            $sql = "INSERT INTO users (username, password, gender) VALUES " . $values;
            
            if ($conn->query($sql)){
                $message = "Your account is created successfully!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=login';</script>";
            }
            else {
                $message = "Error creating user's account: " . $conn->error;
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
        }
    }
?>

<!DOCTYPE HTML>  
<html>
<head>
</head>
<body>

<div class="container h-100">
    <div class="d-flex justify-content-center h-100">
        <div class="user_card">
            <div class="d-flex justify-content-center">
                <div class="brand_logo_container">
                    <img src="circle_avatar.jpg" class="brand_logo" alt="Logo">
                </div>
            </div>
            <div class="d-flex justify-content-center form_container">
                <form method="post" action="">
                    <table>	
                        <tr>
                            <td style="text-align: right;">Username:</td>
                            <td><input type="text" name="username"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td> <!-- empty string -->
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Password:</td>
                            <td><input type="password" name="password" id="pwd" onkeyup="validPassword()"></td>
                        </tr>
                    </table>
                    <p id="txtError" style="color: red;"></p>
                    
                    Gender:
                    <input type="radio" name="gender" value="female">Female
                    <input type="radio" name="gender" value="male" checked="checked">Male
                    <input type="radio" name="gender" value="other">Other
                    <br><br>
                    <div class="d-flex justify-content-center mt-3 login_container">
                        <button type="submit" name="register" class="btn login_btn">Register</button>
                    </div> 
                </form>
                <script>
                    function validPassword() {
                        var str = document.getElementById("pwd").value;
                        var list = [];
                        const letters = (() => {
                            const caps = [...Array(26)].map((val, i) => String.fromCharCode(i + 65));
                            return caps;
                        })();

                        for (var i = 0; i <= 9; i++) {
                            list.push(i.toString());
                        }
                        
                        error = "";
                        num_check = false;
                        uppercase_check = false;
                        if (str.length < 8){
                            error += "Min length is 8 chars";
                        }
                        for (let i = 0; i < str.length; i++){
                            if (list.includes(str[i])){
                                num_check = true;
                            }
                            if (letters.includes(str[i])){
                                uppercase_check = true;
                            }
                        }
                        if (num_check == false){
                            if (error.length == 0){
                                error = "At lease one number";
                            }
                            else{
                                error += ", at least one number";
                            }
                        }
                        if (uppercase_check == false)
                            {if (error.length == 0){
                                error = "At least one uppercase letter";
                            }
                            else{
                                error += ", at least one uppercase letter"
                            }
                            
                        }
                        if (error.length > 0) {
                            document.getElementById("txtError").innerHTML = "Error: " + error;
                            // return false;
                        }
                        else{
                            document.getElementById("txtError").innerHTML = "";
                            // return true;
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</div>

</body>
</html>