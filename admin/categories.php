<?php

require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include_once '../helpers/helpers.php';

$sql = "SELECT * FROM categories WHERE parent = 0";
$result = $db->query($sql);
$errors = array();

if(isset($_POST) && !empty($_POST)){
    $parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);
    $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$parent'";
    $fresult = $db->query($sqlform);
    $count = mysqli_num_rows($fresult);

    if($category == ''){
        $errors[] .= 'The category cannot be left blank';
    }

    if($count > 0){
        $errors[] .= $category. " already exists. Please choose a new category.";
    }

    if(!empty($errors)){
        $display = errors_html($errors); ?>
        <script>
        jQuery('document').ready(function(){
            jQuery('#errors').html('<?= $display; ?>');
        })
        </script>

    <?php }else{
        $updatesql = "INSERT into categories (category, parent) VALUES ('$category', $parent)";
        $db->query($updatesql);
        header('Location: categories.php');

    }
}

?>

<h2 class="text-center">Categories</h2><hr>
<div class="row">
    <div class="col-md-1">
    </div>
    <div class="col-md-4">
        <form class="form" action="categories.php" method="post">
            <legend>Add A Category</legend>
            <div id="errors"></div>
            <div class="form-group">
                <label for="parent">Parent</label>
                <select class="form-control" name="parent" id="parent">
                    <option value="0">Parent</option>
                    <?php while($parent = mysqli_fetch_assoc($result)): ?>
                        <option value="<?=$parent['id']?>"> <?=$parent['category']?> </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" id="category" name="category" value="">
            </div>
            <div class="form-group">
                <input type="submit" name="category_submit" value="Add Category" class="btn btn-lg btn-success">
            </div>
        </form>

    </div>
    <div class="col-md-1">
    </div>
    <div class="col-md-6">
        <table class="table table-ordered">
            <thead>
                <th>Category</th>
                <th>Parent</th>
                <th></th>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM categories WHERE parent = 0";
                $result = $db->query($sql);
                while($parent = mysqli_fetch_assoc($result)):
                    $parent_id = $parent['id'];
                    $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
                    $cresult = $db->query($sql2);
                    ?>
                    <tr class="bg-primary">
                        <td><?= $parent['category']; ?></td>
                        <td>Parent</td>
                        <td>
                            <a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                        </td>
                    </tr>
                    <?php while($child = mysqli_fetch_assoc($cresult)):
                        ?>
                        <tr class="bg-info">
                            <td><?= $child['category']; ?></td>
                            <td><?= $parent['category']; ?></td>
                            <td>
                                <a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                                <a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
