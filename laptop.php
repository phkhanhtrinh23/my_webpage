<?php
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
        echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=laptop';</script>";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <style>
            .card {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                max-width: 300px;
                margin: auto;
                text-align: center;
                font-family: arial;
            }

            .price {
                font-weight: bold;
                font-size: 24px;
            }

            .productname {
                font-weight: bold;
                font-size: 28px;
                color: #093545;
            }

            .card button {
                border: none;
                outline: 0;
                padding: 12px;
                color: white;
                background-color: #093545;
                text-align: center;
                cursor: pointer;
                width: 100%;
                font-size: 18px;
            }

            .card button:hover {
                opacity: 0.7;
            }

            input[type=text], select {
                width: 30%;
                padding: 12px 20px;
                margin: 8px 0;
                border: 2px solid #093545;
                border-radius: 8px;
            }

            .dropdown_content_search {
                display: none;
                left: 25%;
                position: absolute;
                background-color: #f6f6f6;
                min-width: 100px;
                overflow: auto;
                border: 1px solid #ddd;
                z-index: 1;
            }

            .dropdown_content_search a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }

            /* .show {display: block;} */
        </style>
    </head>
    <body>
        <script>
            function click_item(str){
                document.getElementById("search_bar").value = str.replace(/\u2002/g, " ");
                document.getElementById("search_bar").value = document.getElementById("search_bar").value.replace(" ", " ");
            }

            function show_items(str) {
                str = document.getElementById("search_bar").value;
                
                if (str.length == 0) {
                    document.getElementById("search_drop_down").style.display= "";
                    // document.getElementById("suggestion").innerHTML = "";
                    document.getElementById("search_drop_down").innerHTML = "";
                    return;
                } 
                else {
                    document.getElementById("search_drop_down").style.display="block";
                    document.getElementById("search_drop_down").innerHTML = "";
                    
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // document.getElementById("suggestion").innerHTML = this.responseText;
                            if (this.responseText != "No suggestion"){
                                var list_response_text = this.responseText.split(", ");
                                for (let i=0; i<list_response_text.length; i++){
                                    new_list_response_text = list_response_text[i].replace(/ /g, "\u2002");
                                    console.log(new_list_response_text);
                                    if (document.getElementById("search_drop_down").innerHTML == ""){
                                        document.getElementById("search_drop_down").innerHTML = "<a href='#' onclick=click_item(\"" + new_list_response_text + "\");return false;>" + list_response_text[i] + "</button>";
                                    }
                                    else{
                                        document.getElementById("search_drop_down").innerHTML = document.getElementById("search_drop_down").innerHTML.concat("<a href='#' onclick=click_item(\"" + new_list_response_text + "\");return false;>" + list_response_text[i] + "</button>");
                                    }
                                }
                            }
                        }
                    };
                    xmlhttp.open("GET", "get_items.php?inp=" + str, false);
                    xmlhttp.send();
                }
            }
        </script>
        <form class="form-group" method="post" action="" style="text-align: center;">
            <input id="search_bar" type="text" name="search_bar" placeholder="Search your product here..." onkeyup="show_items()">
            <button type="submit" name="search"> Search </button> <br>
            <div id="search_drop_down" class="dropdown_content_search"></div>
            Price ascending <input type="radio" name="sort" value="asc">
            Price descending <input type="radio" name="sort" value="des">
        </form>
        <div>
            <?php
                if (isset($_POST["search"]) && strlen($_POST["search_bar"]) > 0){
                    $value = $_POST["search_bar"];
                    $value = strtolower($value);
                    $len = strlen($value);
                    
                    if (!isset($_POST["sort"])){
                        $sql = "SELECT * FROM products";
                        $result = mysqli_query($conn, $sql);

                        while ($data = mysqli_fetch_assoc($result)) {
                            if (stristr(substr($data["productname"], 0, $len), $value)){
            ?>
                                <form method="post" action="index.php?page=item">
                                    <div class="card">
                                        <img style="style=width:100%" src="./images/<?php echo $data['imagename'];?>">
                                        <p class="productname"><?php echo $data['productname'];?></p>
                                        <p class="price"><?php echo $data['price'];?></p>
                                        <p><?php echo $data['description'];?></p>

                                        <input type='hidden' name ="id" value=<?php echo $data['id'];?>>
                                        <p><button>View</button></p>
                                    </div>
                                </form>
            <?php
                            }
                        }
                    }
                    elseif ($_POST["sort"] == "asc"){
                        $sql = "SELECT * FROM products ORDER BY price ASC";
                        $result = mysqli_query($conn, $sql);
                        
                        while ($data = mysqli_fetch_assoc($result)) {
                            if (stristr(substr($data["productname"], 0, $len), $value)){
                ?>
                                <form method="post" action="index.php?page=item">
                                    <div class="card">
                                        <img style="style=width:100%" src="./images/<?php echo $data['imagename'];?>">
                                        <p class="productname"><?php echo $data['productname'];?></p>
                                        <p class="price"><?php echo $data['price'];?></p>
                                        <p><?php echo $data['description'];?></p>

                                        <input type='hidden' name ="id" value=<?php echo $data['id'];?>>
                                        <p><button>View</button></p>
                                    </div>
                                </form>
                <?php
                            }
                        }
                    }
                    elseif ($_POST["sort"] == "des"){
                        $sql = "SELECT * FROM products ORDER BY price DESC";
                        $result = mysqli_query($conn, $sql);

                        while ($data = mysqli_fetch_assoc($result)) {
                            if (stristr(substr($data["productname"], 0, $len), $value)){
                ?>
                                <form method="post" action="index.php?page=item">
                                    <div class="card">
                                        <img style="style=width:100%" src="./images/<?php echo $data['imagename'];?>">
                                        <p class="productname"><?php echo $data['productname'];?></p>
                                        <p class="price"><?php echo $data['price'];?></p>
                                        <p><?php echo $data['description'];?></p>

                                        <input type='hidden' name ="id" value=<?php echo $data['id'];?>>
                                        <p><button>View</button></p>
                                    </div>
                                </form>
                <?php
                            }
                        }
                    }
                }
                else {
                    $sql = "SELECT * FROM products";
                    $result = mysqli_query($conn, $sql);

                    while ($data = mysqli_fetch_assoc($result)) {
            ?>
                        <form method="post" action="index.php?page=item">
                            <div class="card">
                                <img style="style=width:100%" src="./images/<?php echo $data['imagename'];?>">
                                <p class="productname"><?php echo $data['productname'];?></p>
                                <p class="price"><?php echo $data['price'];?></p>
                                <p><?php echo $data['description'];?></p>

                                <input type='hidden' name ="id" value=<?php echo $data['id'];?>>
                                <p><button>View</button></p>
                            </div>
                        </form>
            <?php
                    }
                }
            ?>
        </div>
    </body>
 </html>