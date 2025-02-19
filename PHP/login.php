<?php include("conn.php") ?>
<?php 
if (session_start()) {
    session_destroy();
}

// Set default admin credentials
$default_admin_email = "admin@gmail.com";  // Set your default admin email
$default_admin_password = "admin123";  // Set your default admin password (plaintext for this example, but you can hash it if preferred)

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if default admin credentials are used
    if ($email === $default_admin_email && $password === $default_admin_password) {
        // Start session for admin
        session_start();
        $_SESSION['id'] = 1;  // Set a default admin ID, e.g., 1
        $_SESSION['fName'] = 'Admin';
        $_SESSION['lName'] = 'User';
        $_SESSION['email'] = $default_admin_email;
        $_SESSION['role'] = 'admin';

        // Redirect to admin dashboard
        header('location:admin/admin_home.php');
        exit();
    }

    // If not default credentials, proceed with normal login process
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            if (password_verify($password, $row["password"])) {
                // Check if user is a normal user or admin
                if ($row["role"] == 'user') {
                    session_start();
                    $_SESSION['id'] = $row['userid'];
                    $_SESSION['fName'] = $row['fName'];
                    $_SESSION['lName'] = $row['lName'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['image'] = $row['image'];
                    $_SESSION['role'] = $row['role'];
                    $conn->close();
                    header('location:user/home.php');
                    exit();
                } else {
                    session_start();
                    $_SESSION['id'] = $row['userid'];
                    $_SESSION['fName'] = $row['fName'];
                    $_SESSION['lName'] = $row['lName'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['image'] = $row['image'];
                    $_SESSION['role'] = $row['role'];
                    $conn->close();
                    header('location:admin/admin_home.php');
                    exit();
                }
            } else {
                echo '<script> alert("Incorrect password");</script>';
            }
        } else {
            echo '<script> alert("User not found");</script>';
        }
    } else {
        echo '<script> alert("Cant find user..");</script>';
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>

<body>
    <div class="login-container">
        <a href="index.php"><img src="../Image/logo/X.png" alt="x" style="float: inline-end; width: 20px; height: 20px;"></a>
        <div class="logodiv">
            <a href="index.php"> <img src="../Image/logo/logo1.png" alt="logo" class="logo"></a>
        </div>
        <br><br>
        <h2>Login to JobDen</h2><br>
        <form class="loginform" action="login.php" method="post">
            <div class="form-group">
                <label for="username" style="text-align: left;">Email</label>
                <input type="email" id="username" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password" style="text-align: left;">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <h5 id="fp"><a href="froget_password.php">Forgot password?</a></h5>
            <div class="form-group" id="loginbtn">
                <button type="submit" name="submit">Login</button>
            </div>
        </form>
        <h5 id="btm"> -- Don't you have JobDen account?<a href="signup.php">Sign Up</a></h5>
    </div>
</body>

</html>
