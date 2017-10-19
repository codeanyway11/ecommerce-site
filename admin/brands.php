<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
$sql = "SELECT * FROM brands ORDER BY brand";
$results = $db->query($sql);
?>

<h2 class="text-center">Brands</h2><hr>

<div>
    <form class="form-inline" action="brands.php" method="post">
        <div class="form-group">
            <label for="brand">Add a Brand:</label>
            <input type="text" class="form-control" name="brand" id="brand" value="<?=((isset($_POST['brand']))? ($_POST['brand']): ' '); ?>">
            <input type="submit" name="add_submit" value="Add Brand" class="btn btn-lg btn-success">        </div>
        </form>
    </div>

    <table class="table table-ordered table-striped table-auto">
        <thead>
            <th></th>
            <th>Brands</th>
            <th></th>
        </thead>
        <tbody>
            <?php while($brand = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td><a href="brands.php?edit=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    <td><?= $brand['brand']; ?></td>
                    <td><a href="brands.php?delete=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>



    <?php
    include 'includes/footer.php';
    ?>
