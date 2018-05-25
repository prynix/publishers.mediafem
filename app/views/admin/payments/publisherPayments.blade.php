<div class="widget">
    <div class="row">
        <a id='close' caption='Cerrar' type='button'><i class="fa fa-times" ></i> Cerrar </a>
    </div>
    <div class="row">
        <div id="publisherData"></div>
    </div>
    <div class="row">
        <div id="publisherBillings"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#close').click(function(e) {
            e.preventDefault();
            $('#publisherDetail').hide(500);
            $('#publisherData').html("");
            $('#publisherBillings').html("");
            return false;
        });
    });
</script>