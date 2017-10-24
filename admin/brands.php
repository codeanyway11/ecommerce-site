<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include_once '../helpers/helpers.php';

$sql = "SELECT * FROM brands ORDER BY brand";
$results = $db->query($sql);
$errors = array();

if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    // echo $edit_id;
    $sql = "SELECT * FROM brands WHERE id = '$edit_id'";
    $edit_result = $db->query($sql);
    $eBrand = mysqli_fetch_assoc($edit_result);
    // header('Location: brands.php');
}

if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    // echo $delete_id;
    $sql = "DELETE FROM brands WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location: brands.php');
}


//If add form is submitted
if(isset($_POST['add_submit'])){
    $brand = sanitize($_POST['brand']);
    if($_POST['brand'] == ' '){
        $errors[] .= 'You must enter a brand!';
    }

    if(!empty($errors)){
        display_errors($errors);
    } else {
        $sql = "SELECT * FROM brands WHERE brand = '$brand'";
        if(isset($_GET['edit'])){
            $sql = "SELECT * FROM brands WHERE brand = '$brand' AND id!='$edit_id'";
        }
        $result = $db->query($sql);
        $count = mysqli_num_rows($result);
        if($count){
            $errors[] .= 'This brand already exists in the Database!';
            display_errors($errors);
        } else {
            $sql = "INSERT into brands (brand) VALUES ('$brand')";
            if(isset($_GET['edit'])){
                $sql = "UPDATE brands SET brand = '$brand' WHERE id='$edit_id'";
            }
            $db->query($sql);
            header('Location: brands.php');
        }
    }
}

?>

<h2 class="text-center">Brands</h2><hr>
<div class="text-center">
    <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id: ' '); ?>" method="post">
        <div class="form-group">
            <?php
            $brand_value = ' ';
            if(isset($_GET['edit'])){
                $brand_value = $eBrand['brand'];
            } else{
                if(isset($_POST['brand'])){
                    $brand_value = sanitize($_POST['brand']);
                }
            } ?>
            <label for="brand"><?=((isset($_GET['edit']))?'Edit ': 'Add a '); ?> Brand:</label>
            <input type="text" class="form-control" name="brand" id="brand" value="<?=$brand_value; ?>">

            <?php if(isset($_GET['edit'])): ?>
                <a href="brands.php" class="btn btn-default">Cancel</a>
            <?php endif; ?>

            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit ': 'Add '); ?> Brand" class="btn btn-lg btn-success">        </div>
        </form>
    </div>
    <br>
    <hr>

    <table class="table table-ordered table-striped table-auto table-condensed">
        <thead>
            <th></th>
            <th>Brands</th>
            <th></th>
        </thead>
        <tbody>
            <?php while($brand = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td><a href="brands.php?edit=<?=$brand['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    <td><?= $brand['brand']; ?></td>
                    <td><a href="brands.php?delete=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br>


    <?php
    include 'includes/footer.php';
    ?>
