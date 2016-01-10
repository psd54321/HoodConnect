<?php session_start();?>

<html>
<?php include "header.php"; ?>
<body class="login">
<div id="container">
<div class="login-screen" style="width: 500px; margin: 100px auto 300px auto; border:solid 1px; border-radius:5px; border-color:#f1f1f1; padding:15px; background:#f1f1f1;">
<?php if (isset($_SESSION['errors'])): ?>
    <div class="errorclass">
        <?php foreach($_SESSION['errors'] as $error): ?>
            <p><?php echo $error ?></p>
        <?php endforeach; ?>
    </div>
<?php unset($_SESSION['errors']); endif; ?>
<div style="padding:40px;margin-left:95px;">
<form action="logincheck.php" method="post">
	

      <p>Username <input type="text" name="username" /></p>

      <p>Password <input type="password" name="password" /></p>

      <p><input type="submit" value="Login" name ="sub" /> </p>

</form>
</div>
</div>
</div>
<div id="footer_container">
    <div id="footer">
    	Want to Connect with your neighbours? &nbsp;&nbsp;
<a id ="register" href="signup.php"> Sign Up</a>
</div>
</div>
</body>
</html>

