<?php

require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$sql = "SELECT * FROM products WHERE deleted = 0";
$results = $db->query($sql);

if(isset($_GET['featured'])){
    $id = $_GET['id'];
    $featured = $_GET['featured'];
    $id = sanitize($id);
    $featured = sanitize($featured);
    $featuredSql = "UPDATE products SET featured = '$featured' WHERE id ='$id'";
    $db->query($featuredSql);
    header('Location:products.php');
}

?>

<h2 class="text-center">Products</h2><hr>
<a href="products.php?add=1" class="btn btn-lg btn-success pull-right" id="add-product-button">Add Product</a>
<div class="clearfix">

</div>

<table class="table table-condensed table-striped table-bordered">
    <thead>
        <th></th>
        <th>Product</th>
        <th>Price</th>
        <th>Category</th>
        <th>Featured</th>
        <th>Sold</th>
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
                    <a href="products.php?edit=<?=$product['id']; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="products.php?delete=<?=$product['id']; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></a>
                </td>
                <td><?=$product['title']; ?></td>
                <td><?=money($product['price']); ?></td>
                <td><?=$category; ?></td>
                <td>
                    <a href="products.php?featured=<?=(($product['featured'] == 0)?1:0);?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>"> </span>
                    </a>&nbsp <?=(($product['featured']==1)?'Featured':'Not featured'); ?>
                </td>
                <td></td>

            </tr>


        <?php endwhile; ?>
    </tbody>
</table>

<?php
include 'includes/footer.php';
?>
