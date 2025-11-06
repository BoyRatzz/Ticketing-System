<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Ticketing System</title>
</head>

<style>
    .login-page {
        display: flex;
        flex-direction: column;
        align-items: center; 
        justify-content: center;
        height: 100vh;
    }

    .login-header {
        display: flex;
    }

    .login-form {
        display: flex; 
        flex-direction: column;
        gap: 10px;
    }

    .login-element {
        display: flex; 
        gap: 5px;
    }

    .login-card {
        border-style: solid;
        border-width: 2px;
        padding: 36px;
    }

    .btn-login {
        display: flex;
        justify-content: end;
    }
</style>

<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-header">
                <h2>Login | Ticketing System</h2>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                <?php endif; ?>
            <div class="login-form">
                <form action="includes/auth.php" method="POST" class="login-form">
                    <div class="login-element">
                        <label style="width: 75px" for="username">Username</label>
                        <input type="text" name="username">
                    </div>
                    <div class="login-element">
                        <label style="width: 75px" for="password">Password</label>
                        <input type="password" name="password">
                    </div>
                    <div class="btn-login">
                        <button style="width: 100px" type="submit" name="login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>