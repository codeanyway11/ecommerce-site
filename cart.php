<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
include 'includes/leftbar.php';

if($cart_id !='' && $cart_id!='0'){
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = [];
    if($result)
    $items = json_decode($result['items'], true);
    $i =1; $sub_total =0; $item_count =0;
    $tax= 0; $grand_total =0;
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
                            <td>
                                <button class="btn btn-xs btn-default" onclick="update_cart('removeone', '<?=$product['id'];?>', '<?=$item['size']; ?>');">-</button>
                                <?=$item['quantity']; ?>
                                <?php if($item['quantity'] < $available): ?>
                                    <button class="btn btn-xs btn-default" onclick="update_cart('addone', '<?=$product['id'];?>', '<?=$item['size']; ?>');">+</button>
                                <?php else: ?>
                                    <span class="text-danger">Max</span>
                                <?php endif; ?>
                            </td>
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
                <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <form id="payment-form" action="thankYou.php" method="post">
                                    <span class="bg-danger" id="payment-errors"></span>
                                    <div id="step1" style="display:block;">
                                        <div class="form-group col-md-6">
                                            <label for="full_name">Full Name:</label>
                                            <input type="text" name="full_name" id="full_name" class="form-control" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Email:</label>
                                            <input type="email" name="email" id="email" class="form-control" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="street">Street Address:</label>
                                            <input type="street" name="street" id="street" class="form-control" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="street2">Street Address 2:</label>
                                            <input type="text" name="street2" id="street2" class="form-control" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="city">City:</label>
                                            <input type="text" name="city" id="city" class="form-control" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="state">State :</label>
                                            <input type="text" name="state" id="state" class="form-control" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="zip_code">Zip Code:</label>
                                            <input type="number" name="zip_code" id="zip_code" class="form-control" value="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="country">Country:</label>
                                            <input type="text" name="country" id="country" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div id="step2" style="display:none;">
                                        <div class="form-group col-md-3">
                                            <label for="name">Name on Card:</label>
                                            <input type="text" id="name" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="card_number">Card Number:</label>
                                            <input type="number" id="card_number" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="cvc">CVC:</label>
                                            <input type="number" id="cvc" class="form-control">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="expire_month">Expire Month:</label>
                                            <select class="form-control" name="expire_month" id="expire_month">
                                                <option value=""></option>
                                                <?php for($i =1; $i<13; $i++): ?>
                                                    <option value="<?=$i;?>"><?=$i;?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="expire_year">Expire Year:</label>
                                            <select class="form-control" name="expire_year" id="expire_year">
                                                <option value=""></option>
                                                <?php $yr= date("Y"); ?>
                                                <?php for($i =1; $i<11; $i++): ?>
                                                    <option value="<?=$yr + $i;?>"><?=$yr + $i;?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button" >Next >></button>
                                <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none;"><< Back</button>
                                <button type="submit" class="btn btn-primary" onclick="check_address();" id="check_out_button" style="display:none;">Check Out >></button>

                            </form>
                            </div>

                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>


</div>
<script>
function back_address(){
    jQuery('#payment-errors').html("");
    jQuery('#step1').css("display", "block");
    jQuery('#step2').css("display", "none");
    jQuery('#next_button').css("display", "inline-block");
    jQuery('#back_button').css("display", "none");
    jQuery('#check_out_button').css("display", "none");
    jQuery('#checkoutModalLabel').html('Shipping Address');
}

function check_address(){
    var data = {
        'full_name' : jQuery('#full_name').val(),
        'email' : jQuery('#email').val(),
        'street' : jQuery('#street').val(),
        'street2' : jQuery('#street2').val(),
        'city' : jQuery('#city').val(),
        'state' : jQuery('#state').val(),
        'zip_code' : jQuery('#zip_code').val(),
        'country' : jQuery('#country').val(),
    };
    jQuery.ajax({
        url : '/shopping/admin/parsers/check_address.php',
        method : 'POST',
        data :data,
        success: function(data){
            if(data!='passed'){
                jQuery('#payment-errors').html(data);

            }
            if(data =='passed'){
                jQuery('#payment-errors').html("");
                jQuery('#step1').css("display", "none");
                jQuery('#step2').css("display", "block");
                jQuery('#next_button').css("display", "none");
                jQuery('#back_button').css("display", "inline-block");
                jQuery('#check_out_button').css("display", "inline-block");
                jQuery('#checkoutModalLabel').html('Enter Your Card Details!');
            }
        },
        error: function(){
            alert("Something went wrong!");
        }
    });
}
</script>

<?php
include 'includes/rightbar.php';
include 'includes/footer.php';
?>
