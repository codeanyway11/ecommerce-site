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
            <!-- <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo $parent['category']; ?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#"></a></li>
                </ul>
            </li> -->
        </ul>
    </div>
</nav>
