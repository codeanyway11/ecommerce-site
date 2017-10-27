<?php

require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['add'])){
    $bQuery = "SELECT * FROM brands ORDER by brand";
    $bresult = $db->query($bQuery);

    $cquery = "SELECT * FROM categories WHERE parent = 0 ORDER by category";
    $cresult = $db->query($cquery);

    ?>
    <h2 class="text-center">Add a New Product</h2><hr>
    <form class="" action="products.php?add=1" method="post" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for="title">Title*:</label>
            <input type="text" name="title" id="title" class="form-control" value="<?=((isset($_POST['title']))?sanitize($_POST['title']):'');?>" >
        </div>

        <div class="form-group col-md-3">
            <label for="brand">Brand*:</label>
            <select class="form-control" name="brand" id="brand">
                <option value="" <?=((isset($_POST['brand']) && $_POST['brand'] == '')?'selected':'');?>></option>
                <?php while($brands = mysqli_fetch_assoc($bresult)): ?>
                    <option value="<?=$brands['id']?>" <?=((isset($_POST['brand']) && $_POST['brand'] == $brand['id'])?'selected':'');?>  ><?=$brands['brand']?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="parent">Parent Category*:</label>
            <select class="form-control" name="parent" id="parent">
                <option value="" <?=((isset($_POST['parent']) && $_POST['parent'] == '')?'selected':'');?> ></option>
                <?php while($parent = mysqli_fetch_assoc($cresult)): ?>
                    <option value="<?=$parent['id']?>" <?=((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])?'selected':' ');?> ><?=$parent['category']?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="child">Child Category*:</label>
            <select class="form-control" name="child" id="child">
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="price">Price*:</label>
            <input type="text" name="price"  class="form-control" value="<?=((isset($_POST['price']))?$_POST['price']:'');?> ">
        </div>

        <div class="form-group col-md-3">
            <label for="list-price">List Price*:</label>
            <input type="text" name="list-price"  class="form-control" value="<?=((isset($_POST['list-price']))?$_POST['list-price']:'');?> ">
        </div>

        <div class="form-group col-md-3">
            <label>Quantity & Sizes*:</label>
            <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity & Sizes</button>
        </div>

        <div class="form-group col-md-3">
            <label for="sizes">Sizes & Quantity Preview*:</label>
            <input type="text" class="form-control" name="size" id="size" value="<?=((isset($_POST['sizes']))?$_POST['sizes']:' '); ?>" readonly>
        </div>

        <div class="form-group col-md-6">
            <label for="photo">Product Photo*:</label>
            <input type="file" class="form-control" name="photo" id="photo" value="<?=((isset($_POST['photo']))?$_POST['photo']:' '); ?>" readonly>
        </div>

        <div class="form-group col-md-6">
            <label for="description">Description*:</label>
            <textarea name="description"  id="description" class="form-control" rows="8" cols="80">
                <?=((isset($_POST['description']))?$_POST['description']:' '); ?>
            </textarea>
        </div>
        <div class="form-group pull-right col-md-3">
            <input type="submit" value="Add Product" class="form-control btn btn-lg btn-success pull-right">
        </div><div class="clearfix">

        </div>
    </form>


    <?php
} else{

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

<?php  }
include 'includes/footer.php';
?>
