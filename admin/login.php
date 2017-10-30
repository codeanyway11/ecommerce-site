<?php
require_once '../core/init.php';
include 'includes/head.php';
$email =((isset($_POST['email']))?sanitize($_POST['email']):'');
$email= trim($email);
$password =((isset($_POST['password']))?sanitize($_POST['password']):'');
$password= trim($password);

$errors = array();
?>
<style media="screen">
body{
    background-image: url('/shopping/images/headerlogo/background.png');
    background-size: 100vw 100vh;
    background-attachment: fixed;
}
</style>

<div id="login-form">
    <div>
        <?php
        if($_POST){
            if($_POST['email']=='' || $_POST['password']==''){
                $errors[] = 'Email or password cannot be left blank.';
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors[] = 'You must provide a valid email.';
            }

            if(strlen($password) <6 ){
                $errors[] = "Password must be min 6 characters long!";
            }
            $query = $db->query("SELECT * FROM users WHERE email = '$email'");
            $user = mysqli_fetch_assoc($query);
            $userCount = mysqli_num_rows($query);
            if($userCount == 0){
                $errors[] = "This email is not registered with us.";
            }
            if(!password_verify($password, $user['password'])){
                $errors[] = 'The password does not match our records.';
            }

            if(!empty($errors)){
                display_errors($errors);
            }else{
                $user_id = $user['id'];
                login($user_id);
            }
        }

        ?>

    </div>
    <h2 class="text-center">Login</h2><hr>
    <form  action="login.php" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?=$email;?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" value="<?=$password;?>" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" value="Login" id="password" value="<?=$password;?>" class="btn btn-primary">
        </div>
    </form>
    <p class="text-right"><a href="/shopping/index.php">Visit Site</a></p>
</div>


<?php
include 'includes/footer.php';
?>
