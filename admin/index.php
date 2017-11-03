<?php
require_once '../core/init.php';
if(!is_logged_in()){
    header('Location: login.php');
}

if(!has_permission()){
    permission_error_redirect('brands.php');
}

include 'includes/head.php';
include 'includes/navigation.php';
?>

<?php
$txnQuery = "SELECT t.id, t.cart_id, t.full_name, t.description, t.txn_date, t.grand_total, c.items, c.paid, c.shipped FROM transactions t
LEFT JOIN cart c ON t.cart_id = c.id
WHERE c.paid =1 AND c.shipped =0
ORDER BY t.txn_date";
$txnResults = $db->query($txnQuery);
?>


<div class="col-md-12">
    <div class="row">
        <h2 class="text-center"> Orders To Fill </h2>
        <table class="table table-bordered table-striped table-condensed">
            <thead>
                <th>#</th>
                <th>Item</th>
                <th>Description</th>
                <th>Total</th>
                <th>Date</th>
            </thead>
            <tbody>
                <?php while($order = mysqli_fetch_assoc($txnResults)): ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endwhile;?>
                </tbody>
            </table>
        </div>
    </div>


    <?php
    include 'includes/footer.php';
    ?>
