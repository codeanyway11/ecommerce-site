<?php
function display_errors($errors){
    $display = '<ul class = "bg-danger">';
    foreach ($errors as $error){
        $display .= '<li class="text-danger">'.$error.'</li>';
    }
    $display .= '</ul>';
    echo $display;
}

function errors_html($errors){
    $display = '<ul class = "bg-danger">';
    foreach ($errors as $error){
        $display .= '<li class="text-danger">'.$error.'</li>';
    }
    $display .= '</ul>';
    return $display;
}

function sanitize($dirty){
    return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}

function money($number){
    return '$'.number_format($number, 2);
}

function login($user_id){
    $_SESSION['SBUser'] = $user_id;
    global $db;
    $date = date('Y-m-d H:i:s');
    $db->query("UPDATE users SET last_login= '$date' WHERE id = '$user_id'");
    $_SESSION['success_flash'] ='You are now logged in!';
    header('Location: index.php');
}

function login_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] ="You must be logged in to view this page!";
    header('Location: '.$url);

}

function has_permission($permission = 'admin'){
    global $user_data;
    $permissions = explode(',', $user_data['permissions']);
    if(in_array($permission, $permissions, true)){
        return true;
    }
    return false;
}

function permission_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] ="You don't have permission to access this page!";
    header('Location: '.$url);

}

function pretty_date($date){
    return date("M d, Y h:i A", strtotime($date));
}

function pretty_permissions($permissions){
    $arr = explode(',' ,$permissions);
    $permissions = implode(', ', $arr);
    $permissions =ucwords($permissions);
    return $permissions;
}
?>
