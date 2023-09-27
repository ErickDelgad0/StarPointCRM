<!-- localhost:8080/html/register.php -->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portal Login Form</title>
        <link rel="icon" href="images/favicon.png"/>
        <link href="https://fonts.googleapis.com/css2?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet"> 
        <!-- Custom styles for this template -->
        <link href="../css/register.css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                <form class="register-form">
                    <span class="form-title">Register</span>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="" required />
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="username" name="username" class="form-control" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="confirmpassword" name="confirmpassword" class="form-control" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-primary btn-lg btn-block" value="Register">
                    </div>
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </body>
</html>