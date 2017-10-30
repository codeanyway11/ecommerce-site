<?php

require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
?>

<?php
$sql = "SELECT * FROM products WHERE deleted = 1";
$results = $db->query($sql);

if(isset($_GET['unarchive'])){
    $id = $_GET['unarchive'];
    $id = sanitize($id);
    $featuredSql = "UPDATE products SET deleted = 0 WHERE id ='$id'";
    $db->query($featuredSql);
    header('Location:archived.php');
}

?>

<h2 class="text-center">Archived Products</h2><hr>

<table class="table table-condensed table-striped table-bordered">
    <thead>
        <th></th>
        <th>Product</th>
        <th>Price</th>
        <th>Category</th>
    </thead>
    <tbody>
        <?php while($product = mysqli_fetch_assoc($results)):
            $childID =$product['categories'];
            $sql2 = "SELECT * FROM categories WHERE id = '$childID'";
            $cresult = $db->query($sql2);
            $child = mysqli_fetch_assoc($cresult);
            $parentID = $child['parent'];
            $sql3 = "SELECT * FROM categories WHERE id ='$parentID'";
            $presults = $db->query($sql3);
            $parent = mysqli_fetch_assoc($presults);
            $category = $parent['category'].' -> '.$child['category'];
            ?>
            <tr>
                <td>
                    <a href="archived.php?unarchive=<?=$product['id']; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-refresh"></span></a>
                </td>
                <td><?=$product['title']; ?></td>
                <td><?=money($product['price']); ?></td>
                <td><?=$category; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
include 'includes/footer.php';
?>
