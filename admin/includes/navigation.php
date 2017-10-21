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
        </ul>
    </div>
</nav>
