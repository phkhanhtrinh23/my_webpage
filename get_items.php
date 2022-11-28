<?php
    $servername = "localhost";
    $username_server = "root";
    $password_server = "";
    $dbname = "myDB";
    $conn = new mysqli($servername, $username_server, $password_server, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $GLOBALS["list_of_items"] = array();

    $result = $conn->query("SHOW TABLES LIKE 'products'");
    if($result->num_rows == 0){
        $message = "The table is not created. Please insert some items first!";
        echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=laptop';</script>";
    }
    else{
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);

        while ($data = mysqli_fetch_assoc($result)) {
            array_push($GLOBALS["list_of_items"], $data["productname"]);
        }
    }

    $inp = $_GET["inp"];

    $hints = "";

    if ($inp !== "") {
        $inp = strtolower($inp);
        $len = strlen($inp);
        foreach($GLOBALS["list_of_items"] as $productname) {
            if (stristr($inp, substr($productname, 0, $len))) {
                if ($hints === "") {
                    $hints = $productname;
                }
                else {
                    $hints .= ", $productname";
                }
            }
        }
    }

    echo $hints === "" ? "No suggestion" : $hints;
?>