@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general-publishers') }} @stop

@section ('section-title') {{ Lang::get('admin.general-publishers') }} @stop

@section ('content')

<div class="page-content inset container-fluid">

    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="publishers_table_content">
                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="publisherData"></div>
</div>

<script>
$(window).bind("load", function() {
       $('#publishers_table_content').html(loader);
        
        $.ajax({
            url: 'load_publishers_table',
            type: 'GET',
            dataType: "html",
            success: function(result) {
                $('#publishers_table_content').html(result);
            }
        });
});
</script>

@stop