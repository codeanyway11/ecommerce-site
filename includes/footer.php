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
</script>

</body>
</html>
