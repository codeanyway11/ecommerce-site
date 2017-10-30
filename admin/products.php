<?php

require_once '../core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['delete'])){
    $id = sanitize($_GET['delete']);
    $db->query("UPDATE products SET deleted =1 WHERE id = '$id'");
    header('Location: products.php');
}

if(isset($_GET['add'])  || isset($_GET['edit'])  ){
    $category = '';
    $bQuery = "SELECT * FROM brands ORDER by brand";
    $bresult = $db->query($bQuery);
    $cquery = "SELECT * FROM categories WHERE parent = 0 ORDER by category";
    $cresult = $db->query($cquery);
    $title = ((isset($_POST['title'])) && $_POST['title']!= '')?sanitize($_POST['title']):'';
    $brand = ((isset($_POST['brand'])) && $_POST['brand']!= '')?sanitize($_POST['brand']):'';
    $parent = ((isset($_POST['parent'])) && $_POST['parent']!= '')?sanitize($_POST['parent']):'';
    $category = ((isset($_POST['child'])) && $_POST['child']!= '')?sanitize($_POST['child']):'';
    $price = ((isset($_POST['price'])) && $_POST['price']!= '')?sanitize($_POST['price']):'';
    $list_price = ((isset($_POST['list_price'])) && $_POST['list_price']!= '')?sanitize($_POST['list_price']):'';
    $description = ((isset($_POST['description'])) && $_POST['description']!= '')?sanitize($_POST['description']):'';
    $sizes = ((isset($_POST['sizes'])) && $_POST['sizes']!= '')?sanitize($_POST['sizes']):'';
    $sizes = rtrim($sizes, ',');
    $saved_image = '';
    $db_path = '';
    $tmpLoc = '';
    $uploadPath = '';

    if(isset($_GET['edit'])){
        $edit_id = $_GET['edit'];
        $productresults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
        $product = mysqli_fetch_assoc($productresults);
        if(isset($_GET['delete_image'])){
            $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
            unlink($image_url);
            $db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
            header('Location: products.php?edit='.$edit_id);
        }
        $category = ((isset($_POST['child'])) && !empty($_POST['child']))?sanitize($_POST['child']): $product['categories'];
        $title = ((isset($_POST['title'])) && !empty($_POST['title']))?sanitize($_POST['title']): $product['title'];
        $brand = ((isset($_POST['brand'])) && !empty($_POST['brand']))?sanitize($_POST['brand']): $product['brand'];
        $parentResults = $db->query("SELECT * FROM categories WHERE id = '$category'");
        $parent = mysqli_fetch_assoc($parentResults);
        $parent = ((isset($_POST['parent'])) && !empty($_POST['parent']))?sanitize($_POST['parent']): $parent['parent'];
        $price = ((isset($_POST['price'])) && !empty($_POST['price']))?sanitize($_POST['price']): $product['price'];
        $list_price = ((isset($_POST['list_price'])) && !empty($_POST['list_price']))?sanitize($_POST['list_price']): $product['list_price'];
        $description = ((isset($_POST['description'])) && !empty($_POST['description']))?sanitize($_POST['description']): $product['description'];
        $sizes = ((isset($_POST['sizes'])) && !empty($_POST['sizes']))?sanitize($_POST['sizes']): $product['sizes'];
        $sizes = rtrim($sizes, ',');
        $saved_image = (($product['image'] != '')?'http://localhost/shopping/'.$product['image']:'');
        $db_path = $product['image'];
    }

    if(!empty($sizes)){
        $sizeString = sanitize($sizes);
        $sizeString = rtrim($sizeString, ',');
        $sizesArray = explode(',', $sizeString);
        $sArray = array();
        $qArray = array();
        foreach($sizesArray as $ss){
            $s = explode(':', $ss);
            if(!empty($s[0])){
                $sArray[]= $s[0];
            }
            if(!empty($s[1])){
                $qArray[]= $s[1];
            }
        }
    }else{
        $sizesArray = array();
    }

    if($_POST){
        $dbpath = '';
        $errors = array();

        $required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
        foreach($required as $field){
            if($_POST[$field]== ''){
                $errors[] = "All fields with an * are required";
                break;
            }
        }

        if(!empty($_FILES)){
            var_dump($_FILES['photo']['error']);
            $photo = $_FILES['photo'];
            $name = $photo['name'];
            $nameArray = explode('.', $name);
            $fileName = $nameArray[0];
            if(!empty($nameArray[1])){
                $fileExt = $nameArray[1];
            }
            $mime = explode('/', $photo['type']);
            $mimeType = $mime[0];
            $fileExt = '';
            if(!empty($nameArray[1])){
                $fileExt = $nameArray[1];
            }
            $mimeExt = '';
            if(!empty($mime[1])){
                $mimeExt = $mime[1];
            }

            $tmpLoc = $photo['tmp_name'];
            $fileSize = $photo['size'];
            $allowed = array('png', 'jpg', 'jpeg', 'gif');
            $uploadName = md5(microtime()).'.'.$fileExt;
            $uploadPath =  BASEURL.'images/products/'.$uploadName;
            $dbpath = 'images/products/'.$uploadName;
            if($mimeType !='image'){
                $errors[] =" The file must be an image";
            }
            if(!in_array($fileExt,  $allowed)){
                $errors[] = "The photo extension must be jpeg, jpg, png or gif.";
            }
            if($fileSize > 25000000){
                $error[]= "The file size must be max 25 Mb.";
            }
            if($fileExt != $mimeExt && ($mimeType =='jpeg' && $fileExt!='jpg')){
                $errors[] ="File extension does not match the file.";
            }

        }

        if(!empty($errors)){
            display_errors($errors);
        } else{
            move_uploaded_file($tmpLoc, $uploadPath);
            $insertSql = "INSERT INTO products (`title`, `price`, `list_price`, `brand`, `categories`, `sizes`, `image`, `description`) VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$sizes', '$dbpath', '$description')";

            if (isset($_GET['edit'])) {
                $insertSql = "UPDATE products SET title = '$title', price ='$price', list_price = '$list_price', brand= '$brand', categories = '$category', sizes = '$sizes', image = '$dbpath',  description= '$description' WHERE id = '$edit_id'";
            }
            $result = $db->query($insertSql);
            header('Location: products.php');
        }
    }

    ?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add a New ');''?>Product</h2><hr>
    <form class="" action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:"add=1");?>" method="post" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for="title">Title*:</label>
            <input type="text" name="title" id="title" class="form-control" value="<?=$title;?>" >
        </div>

        <div class="form-group col-md-3">
            <label for="brand">Brand*:</label>
            <select class="form-control" name="brand" id="brand">
                <option value="" <?=($brand  == '')?'selected':'';?>></option>
                <?php while($b = mysqli_fetch_assoc($bresult)): ?>
                    <option value="<?=$b['id']?>" <?=(($brand == $b['id'])?'selected':'');?>  ><?=$b['brand']?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="parent">Parent Category*:</label>
            <select class="form-control" name="parent" id="parent">
                <option value="" <?=(($parent == '')?'selected':'');?> ></option>
                <?php while($p = mysqli_fetch_assoc($cresult)): ?>
                    <option value="<?=$p['id']?>" <?=(($parent == $p['id'])?'selected':' ');?> ><?=$p['category']?></option>
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
            <input type="text" name="price"  class="form-control" value="<?=$price;?> ">
        </div>

        <div class="form-group col-md-3">
            <label for="list_price">List Price:</label>
            <input type="text" name="list_price"  class="form-control" value="<?=$list_price;?> ">
        </div>

        <div class="form-group col-md-3">
            <label>Quantity & Sizes*:</label>
            <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity & Sizes</button>
        </div>

        <div class="form-group col-md-3">
            <label for="sizes">Sizes & Quantity Preview*:</label>
            <input type="text" class="form-control" name="sizes" id="sizes" value="<?=$sizes; ?>" readonly>
        </div>

        <div class="form-group col-md-6">
            <label for="photo">Product Photo*:</label>

            <?php if($saved_image!=''): ?>
                <div class="saved-image">
                    <img src="<?=$saved_image;?>" alt="">
                </div><br>
                <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image</a>
            <?php else: ?>
                <input type="file" class="form-control" name="photo" id="photo" value="<?=((isset($_POST['photo']))?$_POST['photo']:' '); ?>" readonly>
            <?php endif; ?>

        </div>

        <div class="form-group col-md-6">
            <label for="description">Description*:</label>
            <textarea name="description"  id="description" class="form-control" rows="8" cols="80">
                <?=$description; ?>
            </textarea>
        </div>
        <div class="form-group pull-right col-md-3">
            <a href="products.php" class="btn btn-default pull-right">Cancel</a>
            <input type="submit" value="<?=((isset($_GET['edit']))?'Update ':'Add ');''?>Product" class=" btn btn-success pull-right">
        </div>
        <div class="clearfix">
        </div>
    </form>

    <div class="modal fade bs-example-modal-lg" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" onclick="closeModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-center" id="sizesModalLabel">Size & Quantity:</h4>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <?php  for($i = 1; $i <= 12; $i++ ): ?>
                                <div class="form-group col-md-4">
                                    <label for="size<?=$i; ?>">Size: </label>
                                    <input  class="form-control" type="text" name="size<?=$i; ?>" id="size<?=$i; ?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="qty<?=$i; ?>">Quantity: </label>
                                    <input type="number" name="qty<?=$i; ?>" id="qty<?=$i; ?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0" class="form-control">
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" onclick="closeModal()">Close</button>
                        <button class="btn btn-success" type="submit" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;" >Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
<script>
function closeModal(){
    jQuery('#sizesModal').modal('hide');
    setTimeout(function(){
        jQuery('#sizesModal').remove();
        jQuery('.modal-backdrop').remove();
    }, 500);
}

jQuery('document').ready(function(){
    get_child_options('<?=$category;?>');
});
</script>
