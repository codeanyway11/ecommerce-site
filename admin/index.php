<?php
require_once '../core/init.php';
if(!is_logged_in()){
    header('Location: login.php');
}

if(!has_permission()){
    permission_error_redirect('brands.php');
}

include 'includes/head.php';
include 'includes/navigation.php';
?>

Administrator Home

<?php
include 'includes/footer.php';
?>
