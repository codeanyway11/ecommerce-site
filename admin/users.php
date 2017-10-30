<?php
require_once '../core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}

if(!has_permission('admin')){
    permission_error_redirect('index.php');
}

include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['delete'])){
    $delete_id = sanitize($_GET['delete']);
    $db->query("DELETE FROM users WHERE id = '$delete_id'");
    $_SESSION['success_flash'] ="User has been deleted";
    header('Location: users.php');
}

$usersQuery = $db->query("SELECT * FROM users ORDER by full_name");
?>
<h2>Users</h2><hr>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-button">Add New User</a>
<table class="table table-bordered table-striped table-condensed">
    <thead>
        <th></th>
        <th>Name</th>
        <th>Email</th>
        <th>Join Date</th>
        <th>Last Login</th>
        <th>Permissions</th>
    </thead>
    <tbody>
        <?php while($user = mysqli_fetch_assoc($usersQuery)): ?>
            <tr>
                <td>
                    <?php if($user['id'] != $user_data['id']): ?>
                        <a href="users.php?delete=<?=$user['id']?>" class="btn btn-default btn-xs" ><span class="glyphicon glyphicon-remove-sign"></span></a>
                    <?php endif; ?>
                </td>
                <td><?=$user['full_name'];?></td>
                <td><?=$user['email'];?></td>
                <td><?=pretty_date($user['join_date']);?></td>
                <td><?=pretty_date($user['last_login']);?></td>
                <td><?=pretty_permissions($user['permissions']);?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
include 'includes/footer.php';
?>
