<!DOCTYPE HTML>  
<html>
<head>
</head>
<body>
    <?php
        function terminate(){
            $message = "Cookies and Sessions have been terminated!";
            setcookie($_SESSION["cookie_name"], $_SESSION["cookie_value"], time() - 3600);
            session_unset();
            session_destroy();
            echo "<script type='text/javascript'>alert('$message');window.location.href='index.php?page=home';</script>";
        }

        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['logout'])) {
                terminate();
            }
        }
    ?>
</body>
</html>