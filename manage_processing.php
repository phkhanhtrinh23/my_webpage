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
            echo "<script type='text/javascript'>alert('$error');window.location.href='index.php?page=manage'</script>";
            return false;
        }
        else{
            return true;
        }
    }

    //! Insert product
    if (isset($_POST['insert_products_processing'])) {
        $servername = "localhost";
        $username_server = "root";
        $password_server = "";
        $dbname = "myDB";
        $conn = new mysqli($servername, $username_server, $password_server, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SHOW TABLES LIKE 'products'");
        if($result->num_rows == 0){
            $sql = "CREATE TABLE products(
                id int(100) NOT NULL AUTO_INCREMENT,
                imagename varchar(100) UNIQUE NOT NULL,
                productname varchar(100) UNIQUE NOT NULL,
                price varchar(100) NOT NULL,
                description varchar(500) NOT NULL,
                PRIMARY KEY (id)
                )";

            if (mysqli_query($conn, $sql) == false) {
                die("Connection failed: " . $conn->connect_error);
            }
        }

        $imagename = $_FILES["insertfile"]["name"];
        $tempname = $_FILES["insertfile"]["tmp_name"];
        $folder = "./images/" . $imagename;

        if(strlen($imagename) == 0){
            $message = "Please upload an image!";
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
        }
        else{
            $productname = $_POST["productname"];
            $price = $_POST["price"];
            $description = $_POST["description"];

            $sql = "SELECT * FROM products";
            $result = mysqli_query($conn, $sql);
            $duplicate_image = false;
            $duplicate_product = false;

            while ($data = mysqli_fetch_assoc($result)) {
                if ($data["imagename"] == $imagename){
                    $duplicate_image = true;
                }
                if ($data["productname"] == $productname){
                    $duplicate_product = true;
                }
            }
            
            if ($duplicate_image == true){
                $message = "Your image\'s name has already existed. Please use another name!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
            elseif ($duplicate_product == true){
                $message = "Your product\'s name has already existed. Please use another name!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
            else{
                $sql = "INSERT INTO products (imagename, productname, price, description) VALUES ('$imagename', '$productname', '$price', '$description')";
            
                mysqli_query($conn, $sql);
            
                // Move the uploaded image into the folder: images
                if (move_uploaded_file($tempname, $folder)){
                    $message = "Insert successfully!";
                }
                else{
                    $message = "Failed to upload image!";
                }
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
        }
    }

    //! Update product
    elseif (isset($_POST['update_products_processing'])) {
        $servername = "localhost";
        $username_server = "root";
        $password_server = "";
        $dbname = "myDB";
        $conn = new mysqli($servername, $username_server, $password_server, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SHOW TABLES LIKE 'products'");

        if($result->num_rows == 0){
            $message = "The table is not created. Please insert some items first!";
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
        }
        else{
            $result = mysqli_query($conn, "SELECT * FROM products");
            if(mysqli_num_rows($result) == 0){
                $message = "There is no entry to update. Please insert some items first!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
            elseif(strlen($_POST["id"]) == 0){
                $message = "Please enter the Id!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
            else{
                $imagename = $_FILES["updatefile"]["name"];
                $tempname = $_FILES["updatefile"]["tmp_name"];
                $folder = "./images/" . $imagename;

                $id = (int) $_POST["id"];
                $productname = $_POST["productname"];
                $price = $_POST["price"];
                $description = $_POST["description"];
            
                if (strlen($imagename) > 0){
                    $sql = "UPDATE products SET imagename='$imagename' WHERE id=$id";
                    mysqli_query($conn, $sql);
                    move_uploaded_file($tempname, $folder);
                }
                if (strlen($productname) > 0){
                    $sql = "UPDATE products SET productname='$productname' WHERE id=$id";
                    mysqli_query($conn, $sql);
                }
                if (strlen($price) > 0){
                    $sql = "UPDATE products SET price='$price' WHERE id=$id";
                    mysqli_query($conn, $sql);
                }
                if (strlen($description) > 0){
                    $sql = "UPDATE products SET description='$description' WHERE id=$id";
                    mysqli_query($conn, $sql);
                }
                $message = "Update successfully!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
        }
        
    }

    //! Delete product
    elseif (isset($_POST['delete_products_processing'])) {
        $servername = "localhost";
        $username_server = "root";
        $password_server = "";
        $dbname = "myDB";
        $conn = new mysqli($servername, $username_server, $password_server, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SHOW TABLES LIKE 'products'");

        if($result->num_rows == 0){
            $message = "The table is not created. Please insert some items first!";
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
        }
        elseif(!isset($_POST["id"]) && !isset($_POST["productname"])){
            $message = "Both Id and Product name are empty. Please enter it again!";
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
        }
        else{
            $result = mysqli_query($conn, "SELECT * FROM products");
            if(mysqli_num_rows($result) == 0){
                $message = "There is no entry to delete. Please insert some items first!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
            else{
                if (isset($_POST["id"])){
                    $id = (int) $_POST["id"];
                }
                elseif (isset($_POST["productname"])){
                    $productname = $_POST["productname"];
                }
                
                $sql = "SELECT * FROM products";
                $result = mysqli_query($conn, $sql);
                $exist = false;
    
                $imagename = "";
                while($row = mysqli_fetch_assoc($result)){
                    if (isset($_POST["id"]) && $row["id"] == $id){
                        $imagename = $row["imagename"];
                        $exist = true;
                    }
                    elseif (isset($_POST["productname"]) && $row["productname"] == $productname){
                        $imagename = $row["imagename"];
                        $exist = true;
                    }
                }
    
                if ($exist == true){
                    $folder = "images/" . $imagename;
            
                    if (isset($_POST["id"])){
                        $sql = "DELETE FROM products WHERE id=$id";
                        mysqli_query($conn, $sql);
                    }
                    elseif (isset($_POST["productname"])){
                        $sql = "DELETE FROM products WHERE productname='$productname'";
                        mysqli_query($conn, $sql);
                    }
                    
                    // Delete image file in the folder
                    if (strlen($imagename) > 0){
                        unlink($folder);
                        $message = "Delete successfully!";
                        echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
                    }
                }
                else{
                    $message = "Your Delete condition does not exist. Please enter the Id or Product name again!";
                    echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
                }
            }
        }
    }

    //! Insert user
    if (isset($_POST['insert_users_processing'])) {
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

            if (mysqli_query($conn, $sql) == false) {
                die("Connection failed: " . $conn->connect_error);
            }
        }

        $username_user = $_POST["username"];
        $password_user = clean_input($_POST["password"]);
        $gender_user = $_POST["gender"];

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
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage'</script>";
        }
        elseif (check_password($password_user)){
            $values = "(\"" . $username_user . "\", \"" . $password_user . "\", \"" . $gender_user . "\")";

            $sql = "INSERT INTO users (username, password, gender) VALUES " . $values;
            mysqli_query($conn, $sql);

            $message = "Insert successfully!";
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
        }
    }

    //! Update user
    elseif (isset($_POST['update_users_processing'])) {
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
            $message = "The table is not created. Please sign up some users first!";
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
        }
        else{
            $result = mysqli_query($conn, "SELECT * FROM users");
            if(mysqli_num_rows($result) == 0){
                $message = "There is no entry to update. Please sign up some users first!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
            elseif(strlen($_POST["id"]) == 0){
                $message = "Please enter the Id!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
            else{
                $id = (int) $_POST["id"];
                $username = $_POST["username"];
                $password = clean_input($_POST["password"]);
                $gender = $_POST["gender"];
            
                if (strlen($username) > 0){
                    $sql = "UPDATE users SET username='$username' WHERE id=$id";
                    mysqli_query($conn, $sql);
                }
                if (strlen($password) > 0){
                    if (check_password($password)){
                        $sql = "UPDATE users SET password='$password' WHERE id=$id";
                        mysqli_query($conn, $sql);
                    }
                }
                if (strlen($gender) > 0){
                    $sql = "UPDATE users SET gender='$gender' WHERE id=$id";
                    mysqli_query($conn, $sql);
                }
                $message = "Update successfully!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
        }   
    }

    //! Delete user
    elseif (isset($_POST['delete_users_processing'])) {
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
            $message = "The table is not created. Please sign up some users first!";
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
        }
        elseif(!isset($_POST["id"]) && !isset($_POST["username"])){
            $message = "Both Id and Username are empty. Please enter it again!";
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
        }
        else{
            $result = mysqli_query($conn, "SELECT * FROM users");
            if(mysqli_num_rows($result) == 0){
                $message = "There is no entry to delete. Please sign up some users first!";
                echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
            }
            else{
                if (isset($_POST["id"])){
                    $id = (int) $_POST["id"];
                }
                elseif (isset($_POST["username"])){
                    $username = $_POST["username"];
                }
                
                $sql = "SELECT * FROM users";
                $result = mysqli_query($conn, $sql);
                $exist = false;
    
                while($row = mysqli_fetch_assoc($result)){
                    if (isset($_POST["id"]) && $row["id"] == $id){
                        $exist = true;
                    }
                    elseif (isset($_POST["username"]) && $row["username"] == $username){
                        $exist = true;
                    }
                }
    
                if ($exist == true){
                    if (isset($_POST["id"])){
                        $sql = "DELETE FROM users WHERE id=$id";
                        mysqli_query($conn, $sql);
                    }
                    elseif (isset($_POST["username"])){
                        $sql = "DELETE FROM users WHERE username='$username'";
                        mysqli_query($conn, $sql);
                    }
                    
                    $message = "Delete successfully!";
                    echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
                }
                else{
                    $message = "Your Delete condition does not exist. Please enter the Id or Username again!";
                    echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=manage';</script>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<body>
    <?php
        if(isset($_POST["products"]) ){
    ?>
        <form method="POST" action="" enctype="multipart/form-data" style="text-align: center;">
            <div class="form-group">
                <button class="btn btn-primary" type="submit" name="insert_products">insert</button>
                <button class="btn btn-primary" type="submit" name="update_products">update</button>
                <button class="btn btn-primary" type="submit" name="delete_products">delete</button>
            </div>
        </form>
    <?php
        }
        elseif(isset($_POST["users"]) ){
    ?>
        <form method="POST" action="" enctype="multipart/form-data" style="text-align: center;">
            <div class="form-group">
                <button class="btn btn-primary" type="submit" name="insert_users">insert</button>
                <button class="btn btn-primary" type="submit" name="update_users">update</button>
                <button class="btn btn-primary" type="submit" name="delete_users">delete</button>
            </div>
        </form>
    <?php
        }
        if(isset($_POST["insert_products"]) ){
    ?>
            <form method="POST" action="" enctype="multipart/form-data" style="text-align: center;">
                <table style="margin-left:auto; margin-right:auto;">
                    <tr>
                        <td style="text-align: right;">Product name:</td>
                        <td><input type="text" name="productname"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Price:</td>
                        <td><input type="text" name="price"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Description:</td>
                        <td><textarea name="description" rows="5" cols="40"></textarea><br></td>
                    </tr>
                </table>
                <br>
                <div class="form-group">
                    <input type="file" name="insertfile" value="" />
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="insert_products_processing">Insert</button>
                </div>
            </form>
    <?php
        }
        elseif(isset($_POST["update_products"]) ){
    ?>
        <h5 style="text-align: center;">Enter the Id of the product, then fill in the information you want to update.</h5>
            <form method="POST" action="" enctype="multipart/form-data" style="text-align: center;">
                <table style="margin-left:auto; margin-right:auto;">
                    <tr>
                        <td style="text-align: right;">Id:</td>
                        <td><input type="text" name="id"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Product name:</td>
                        <td><input type="text" name="productname"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Price:</td>
                        <td><input type="text" name="price"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Description:</td>
                        <td><textarea name="description" rows="5" cols="40"></textarea><br></td>
                    </tr>
                </table>
                <br>
                <div class="form-group">
                    <input type="file" name="updatefile" value="" />
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="update_products_processing">update</button>
                </div>
            </form>
    <?php
        }
        elseif(isset($_POST["delete_products"]) ){
    ?>
            <h5 style="text-align: center;">Choose to Delete an entity by filling in: Id or Product name.</h5>
            
            <div class="form-group" style="text-align: center;">
                <input type="radio" id="id" name="option" value="id" onclick="id_choice()">
                <label for="id">Id</label>
                
                <input type="radio" id="product" name="option" value="product" onclick="product_choice()">
                <label for="product">Product name</label>
            </div>

            <form method="POST" action="" enctype="multipart/form-data" style="text-align: center;">
                <table class="form-group" style="margin-left:auto; margin-right:auto;">
                    <tr id="input_id">
                    </tr>
                    <tr id="input_product">
                    </tr>
                </table>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="delete_products_processing">delete</button>
                </div>
            </form>

            <script>
                function id_choice(){
                    document.getElementById("input_id").innerHTML='<td style="text-align: right;">Id:</td><td><input type="text" name="id"></td>';
                    document.getElementById("input_product").innerHTML='';
                }
                function product_choice(){
                    document.getElementById("input_id").innerHTML='';
                    document.getElementById("input_product").innerHTML='<td style="text-align: right;">Product name:</td><td><input type="text" name="productname"></td>';
                }
            </script>
    <?php
        }
        if(isset($_POST["insert_users"]) ){
        ?>
                <form method="POST" action="" enctype="multipart/form-data" style="text-align: center;">
                    <table style="margin-left:auto; margin-right:auto;">
                        <tr>
                            <td style="text-align: right;">Username:</td>
                            <td><input type="text" name="username"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Password:</td>
                            <td><input type="password" name="password"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Gender:</td>
                            <td><input type="text" name="gender"></td>
                        </tr>
                    </table>
                    <br>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit" name="insert_users_processing">Insert</button>
                    </div>
                </form>
    <?php
        }
        elseif(isset($_POST["update_users"]) ){
    ?>
        <h5 style="text-align: center;">Enter the Id of the user, then fill in the information you want to update.</h5>
            <form method="POST" action="" enctype="multipart/form-data" style="text-align: center;">
                <table style="margin-left:auto; margin-right:auto;">
                    <tr>
                        <td style="text-align: right;">Id:</td>
                        <td><input type="text" name="id"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Username:</td>
                        <td><input type="text" name="username"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Password:</td>
                        <td><input type="password" name="password"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Gender:</td>
                        <td><input type="text" name="gender"></td>
                    </tr>
                </table>
                <br>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="update_users_processing">update</button>
                </div>
            </form>
    <?php
        }
        elseif(isset($_POST["delete_users"]) ){
    ?>
            <h5 style="text-align: center;">Choose to Delete an entity by filling in: Id or Username.</h5>
            
            <div class="form-group" style="text-align: center;">
                <input type="radio" id="id" name="option" value="id" onclick="id_choice()">
                <label for="id">Id</label>
                
                <input type="radio" id="username" name="option" value="username" onclick="username_choice()">
                <label for="username">Username</label>
            </div>

            <form method="POST" action="" enctype="multipart/form-data" style="text-align: center;">
                <table class="form-group" style="margin-left:auto; margin-right:auto;">
                    <tr id="input_id">
                    </tr>
                    <tr id="input_username">
                    </tr>
                </table>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="delete_users_processing">delete</button>
                </div>
            </form>

            <script>
                function id_choice(){
                    document.getElementById("input_id").innerHTML='<td style="text-align: right;">Id:</td><td><input type="text" name="id"></td>';
                    document.getElementById("input_username").innerHTML='';
                }
                function username_choice(){
                    document.getElementById("input_id").innerHTML='';
                    document.getElementById("input_username").innerHTML='<td style="text-align: right;">Username:</td><td><input type="text" name="username"></td>';
                }
            </script>
    <?php
        }
    ?>
</body>
</html>