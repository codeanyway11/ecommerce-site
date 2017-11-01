<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
include 'includes/leftbar.php';

if($cart_id !='' && $cart_id!='0'){
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'], true);
    $i =1; $sub_total =0; $item_count =0;
}

?>


<div class="col-md-8">
    <div class="row">
        <h2 class="text-center"> My Shopping Cart </h2>
        <?php if($cart_id== '' || $cart_id=='0'): ?>
            <div class="bg-danger text-center">
                <p class="text-center text-danger">
                    Your shopping cart is empty!
                </p>
            </div>
        <?php else: ?>
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <th>#</th>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Size</th>
                    <th>Sub Total</th>
                </thead>
                <tbody>
                    <?php foreach($items as $item){
                        $product_id = $item['id'];
                        $productQ = $db->query("SELECT * FROM products WHERE id='{$product_id}'");
                        $product = mysqli_fetch_assoc($productQ);
                        $sArray = explode(',', $product['sizes']);
                        foreach($sArray as $sizeString){
                            $s = explode(':', $sizeString);
                            if($s[0] == $item['size']){
                                $available = $s[1];
                            }
                        }
                        ?>
                        <tr>
                            <td><?=$i;?></td>
                            <td><?=$product['title']; ?></td>
                            <td><?=money($product['price']); ?></td>
                            <td><?=$item['quantity']; ?></td>
                            <td><?=$item['size']; ?></td>
                            <td><?=money($product['price'] * $item['quantity']); ?></td>
                        </tr>

                        <?php
                        $i++;
                        $item_count +=$item['quantity'];
                        $sub_total += $product['price'] * $item['quantity'];
                    }

                    $tax = TAXRATE * $sub_total;
                    $tax = number_format($tax, 2);
                    $grand_total = $tax + $sub_total;
                    ?>
                </tbody>
            </table>
            <table class="table table-bordered table-condensed table-striped text-right">
                <legend>Totals</legend>
                <thead class="totals-table-header">
                    <th>Total Items</th>
                    <th>Sub Total</th>
                    <th>Tax</th>
                    <th>Total Amount</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$item_count;?></td>
                        <td><?=money($sub_total);?></td>
                        <td><?=money($tax);?></td>
                        <td class="bg-success"><?=money($grand_total);?></td>
                    </tr>
                </tbody>
            </table>
            <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info btn-lg pull-right" data-toggle="modal" data-target="#checkoutModal">
                <span class="glyphicon glyphicon-shopping-cart"></span>Checkout <<
            </button>

            <!-- Modal -->
            <div id="checkoutModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
                        </div>
                        <div class="modal-body">
                            <p>Some text in the modal.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>


</div>

<?php
include 'includes/rightbar.php';
include 'includes/footer.php';
?>
