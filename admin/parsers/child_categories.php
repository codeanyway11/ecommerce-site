<?php

require_once '../../core/init.php';
$parentID = $_POST['parentID'];
$selected = sanitize($_POST['selected']);
$childQuery = $db->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER by category");

ob_start(); ?>
<option value=""></option>
<?php while($child = mysqli_fetch_assoc($childQuery)) : ?>
    <option value="<?=$child['id']?>" <?=(($selected ==$child['id'])?' selected': ' '); ?>><?=$child['category']?></option>
<?php endwhile; ?>

<?php
echo ob_get_clean();
?>
