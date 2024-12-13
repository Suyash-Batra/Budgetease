<?php
include("connection.php");
session_start();

function redirectWithAlert($message, $url) {
    echo "<script>alert('$message'); window.location.href='$url';</script>";
    exit();
}

if (isset($_POST['submit'])) {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    $checkEmailSql = "SELECT Email FROM login WHERE Email='$email'";
    $result = $con->query($checkEmailSql);

    if ($result->num_rows > 0) {
        redirectWithAlert("Error: Email already exists", "signup.php");
    } else {
        $sql = "INSERT INTO login (Email, Name, Password) VALUES ('$email', '$name', '$password')";
        if ($con->query($sql) === TRUE) {
            echo "<script>window.location.href='index.php'</script>";
        } else {
            redirectWithAlert("Error: " . $con->error, "signup.php");
        }
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT Name, Password FROM login WHERE Email='$email'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['Password']) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $row['Name'];
            $_SESSION['loggedin'] = true;
            header("Location: index.php");
            exit();
        } else {
            redirectWithAlert("Invalid password", "signup.php");
        }
    } else {
        redirectWithAlert("No user found with this email", "signup.php");
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="css/sign.css" />
    <link rel="icon" href="Images/x.png" />
    <title>Signup | BudgetEase</title>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="post">
                <h1>Create Account</h1>
                <!-- <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div> -->
                <span>or use your email for registration</span>
                <input type="text" placeholder="Name" name="Name" required>
                <input type="email" placeholder="Email" name="Email" required>
                <input type="password" placeholder="Password" name="Password" required>
                <button type="submit" name="submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post">
                <h1>Sign In</h1>
                <!-- <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div> -->
                <input type="email" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <a href="#">Forget Your Password?</a>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of the site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of the site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="JS/script.js"></script>
</body>
</html>
