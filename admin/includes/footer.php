</div>

<div class="col-md-12 text-center">
    &copy; Copyright 2017 Shaunta's Boutique
</div>

<script type="text/javascript">
    function get_child_options(){
        var parentID = jQuery('#parent').val();
        jQuery.ajax({
            url : '/shopping/admin/parsers/child_categories.php',
            type: 'POST',
            data: { parentID: parentID},
            success: function(data){
                console.log(data);
                jQuery('#child').html(data);
            },
            error: function(){
                alert("Something went wrong with the child options!");
            }
        })
    }

    jQuery('select[name = "parent"]').change(get_child_options);

</script>

</body>
</html>
