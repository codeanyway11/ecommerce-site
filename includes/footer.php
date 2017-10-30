</div>

<div class="col-md-12 text-center">
    &copy; Copyright 2017 Shaunta's Boutique
</div>

<script>
jQuery(window).scroll(function(){
    var vscroll = jQuery(this).scrollTop();
    jQuery('#logotext').css({
        "transform" : "translate(0px, "+vscroll/2+"px)"
    });

    var vscroll = jQuery(this).scrollTop();
    jQuery('#back-flower').css({
        "transform" : "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
    });

    var vscroll = jQuery(this).scrollTop();
    jQuery('#fore-flower').css({
        "transform" : "translate(0px, -"+vscroll/2+"px)"
    });
});

function detailsmodal(id){
    var data = {"id" : id};
    jQuery.ajax({
        // url: "includes/detailsmodal.php",
        url : '/shopping/includes/detailsmodal.php',
        type: 'POST',
        data: data,
        success: function(data){
            console.log('success', data);
            jQuery('body').append(data);
            jQuery("#details-modal").modal('toggle');
        },
        error: function(){
            alert("Something went wrong!");
        },
    });
}

function add_to_cart(){
    jQuery('#modal_errors').html("");
    var size = jQuery('#size').val();
    var quantity = jQuery('#quantity').val();
    var available = jQuery('#available').val();
    var error = '';
    var data = jQuery('#add_product_form').serialize();
    if(size == '' || quantity == '' || quantity == 0){
        error += '<p class="text-center text-danger">You must choose a size & a quantity!</p>';
        jQuery('#modal_errors').html(error);
        return;
    }else if (quantity > available) {
        error += '<p class="text-center text-danger">There are only '+available+' available!</p>';
        jQuery('#modal_errors').html(error);
        return;
    }else{
        console.log("ajax");
        jQuery.ajax({
            url: '/shopping/admin/parsers/add_cart.php',
            method: 'post',
            data :data,
            success: function(){
                location.reload();
            },
            error: function(){
                alert('Something went wrong!');
            }
        });
    }
}
</script>

</body>
</html>
