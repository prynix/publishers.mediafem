@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general-payments') }} @stop

@section ('section-title') {{ Lang::get('admin.general-payments') }} @stop

@section ('content')

<div class="page-content inset container-fluid">
    <div class="row">
        <h2>Comisiones de Media Buyer</h2>
    </div>

<div class="widget" id="mediaBuyersCommissions">
</div>

<script>
    $(window).bind("load", function() {        
        $('#mediaBuyersCommissions').html(loader).load('/admin/mediabuyer_commissions_table');        
    });
</script>

@stop