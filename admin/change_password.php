<?php
require_once '../core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';

$hashed = $user_data['password'];
$old_password =((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password= trim($old_password);
$password =((isset($_POST['password']))?sanitize($_POST['password']):'');
$password= trim($password);
$confirm =((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm= trim($confirm);
$user_id = $user_data['id'];
$errors = array();
?>


<div id="login-form">
    <div>
        <?php
        if($_POST){
            if($_POST['old_password']=='' || $_POST['password']=='' || $_POST['confirm']==''){
                $errors[] = 'None of the fields can be left blank.';
            }

            if(strlen($password) <6 || strlen($confirm) <6 ){
                $errors[] = "Password must be min 6 characters long!";
            }

            if($password != $confirm){
                $errors[] = 'New passwords do not match!';
            }

            if(!password_verify($old_password, $hashed)){
                $errors[] = 'The old password does not match our records.';
            }

            if(!empty($errors)){
                display_errors($errors);
            }else{
                $new_hashed = password_hash($password, PASSWORD_DEFAULT);
                $db->query("UPDATE users SET password ='$new_hashed' WHERE id ='$user_id' ");
                $_SESSION['success_flash'] ='Your password has been updated!';
                header('Location: index.php');
            }
        }

        ?>

    </div>
    <h2 class="text-center">Change Password</h2><hr>
    <form  action="change_password.php" method="post">
        <div class="form-group">
            <label for="old_password">Old Password</label>
            <input type="password" name="old_password" id="old_password" value="<?=$old_password;?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" value="<?=$password;?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="confirm">Confirm New Password</label>
            <input type="password" name="confirm" id="confirm" value="<?=$confirm;?>" class="form-control">
        </div>
        <div class="form-group">
            <a href="index.php" class="btn btn-primary">Cancel</a>
            <input type="submit" value="Change Password" id="password" value="<?=$password;?>" class="btn btn-primary">
        </div>
    </form>
    <p class="text-right"><a href="/shopping/index.php">Visit Site</a></p>
</div>


<?php
include 'includes/footer.php';
?>
