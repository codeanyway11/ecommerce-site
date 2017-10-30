<!-- Top NavBar -->
<?php
$sql = "SELECT * FROM categories WHERE parent = 0";
$pquery = $db->query($sql);
?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a href="index.php" class="navbar-brand">Shaunta's Boutique Admin</a>
        <ul class="nav navbar-nav">
            <li><a href="brands.php">Brands</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="archived.php">Archived</a></li>
        </ul>
    </div>
</nav>
