<?php
    $servername = "localhost";
    $username_server = "root";
    $password_server = "";
    $dbname = "myDB";
    $conn = new mysqli($servername, $username_server, $password_server, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_POST["id"];

    $result = $conn->query("SHOW TABLES LIKE 'products'");
    if($result->num_rows == 0){
        $message = "The table is not created. Please insert some items first!";
        echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=laptop';</script>";
    }
    else{
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);

        while ($data = mysqli_fetch_assoc($result)) {
            if ($data["id"] === $id){
                $GLOBALS["imagename"] = $data["imagename"];
                $GLOBALS["productname"] = $data["productname"];
                $GLOBALS["price"] = $data["price"];
                $GLOBALS["description"] = $data["description"];
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <style>
            .content-container {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                margin: 50px;
                padding: 50px;
                display: flex;
            }
            .display-image{
                width: 40%;
                height: auto;
                margin-right: 50px;
            }
            .content{
                height: 50%;
                width: auto;
            }
            .productname {
                color: #093545;
                margin-bottom: 20px;
            }
            .price {
                font-weight: bold;
                font-size: 24px;
                margin-bottom: 25px;
            }
            .description {
                margin-bottom: 25px;
            }
            .buy {
                border: none;
                outline: 0;
                padding: 12px;
                color: white;
                background-color: #093545;
                text-align: center;
                cursor: pointer;
                width: 150px;
                font-size: 18px;
                margin-right: 20px;
            }
            .buy:hover{
                opacity: 0.7;
            }
            .add-to-cart {
                outline: 0;
                padding: 12px;
                color: #093545;
                background-color: white;
                text-align: center;
                cursor: pointer;
                width: 150px;
                font-size: 18px;
            }
            .add-to-cart:hover{
                opacity: 0.7;
            }
        </style>
    </head>
    <body>
        <div class="content-container">
            <img class="display-image" src="images/<?php echo $GLOBALS['imagename'];?>">
            <div class="content">
                <h1 class="productname"><?php echo $GLOBALS['productname'];?></h1>
                <h3 class="price"><?php echo $GLOBALS['price'];?></h3>
                <p class="description"><?php echo $GLOBALS['description'];?></p>
                <button class="buy">Buy</button>
                <button class="add-to-cart">Add to cart</button>
            </div>
        </div>
    </body>
</html>