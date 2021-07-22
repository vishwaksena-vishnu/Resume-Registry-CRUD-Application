<?php
require_once "pdo.php";
require_once 'util.php';

session_start();
unset($_SESSION['name']);
unset($_SESSION['user_id']);

if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}


$salt = 'XyZzy12*_';

if (isset($_POST['email']) && isset($_POST['pass'])) {
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    } else if (strpos($_POST['email'], "@") === false) {

        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    } else {

        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( $row !== false ) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;
        } else {

            $_SESSION['error'] = "Incorrect password";
            // error_log("Login fail ".$_POST['email']." $check");
            header("Location: login.php");
            return;
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once "head.php"; ?>
    <title>b0e5469b Login Page</title>
</head>

<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php
    flashMessage();
    ?>
        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="text" name="email" id="email"><br />
            <label for="id_1723">Password</label>
            <input type="password" name="pass" id="id_1723"><br />
            <input type="submit" onclick="return doValidate();" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>
        <p>
            For a password hint, view source and find a password hint
            in the HTML comments.
            <!-- Hint: The password is the four character sound a cat
        makes (all lower case) followed by 123. -->
        </p>
    </div>
    <script>
        function doValidate() {
            console.log('Validating...');
            try {
                addr = document.getElementById('email').value;
                pw = document.getElementById('id_1723').value;
                console.log("Validating addr=" + addr + " pw=" + pw);
                if (addr == null || addr == "" || pw == null || pw == "") {
                    alert("Both fields must be filled out");
                    return false;
                }
                if (addr.indexOf('@') == -1) {
                    alert("Invalid email address");
                    return false;
                }
                return true;
            } catch (e) {
                return false;
            }
            return false;
        }
    </script>
</body>

</html>

<!-- // $smt->execute(array(':em'=>$_POST['email'], ':pw'=>$check));
        // $smt->execute(array(':em'=>$_POST['email']));
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // if($row===false){
        //     $_SESSION['error'] = "Invalid Email ID";
        //     // error_log("Login fail ".$_POST['email']." $check");
        //     header("Location: login.php");
        //     return;
        // } -->