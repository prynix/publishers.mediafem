<div class="panel panel-success">
    <div class="panel-heading"><a href="http://{{ $site->getName() }}" target="_blank"><b>{{ $site->getName() }}</b></a></div>
    <div class="panel-body">
        @foreach($categories as $category)
        <div class="col-md-4"><input type="checkbox" class="category" value="{{ $category->getId() }}" @if($site->hasCategory($category->getId())) checked @endif> {{ $category->getName() }}</input></div>
        @endforeach
        <div class="col-xs-12"><hr /></div>
        <div class="row">
            <p class="col-md-6" style="font-size: 20px"><span class="label label-danger" id="errores"></span></p>
            <button id="categorize" class="col-md-3 col-md-offset-2 btn btn-primary ladda-button" data-siteId="{{ $site->getId() }}" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('admin.sites-categorize') }}</span></button>
        </div>
    </div>
</div>

<script>
    $('#categorize').click(function(e) {
        $site = $('#categorize').attr('data-siteId');
        $selected_categories = "";
        $('input.category').each(function() {
            if ($(this).is(':checked')) {
                $selected_categories += $(this).attr('value') + "_";
            }
        });
        if ($selected_categories.length > 0) {
            $('#errores').html("");
            $selected_categories = $selected_categories.slice(0, -1);
            $('#errores').removeClass('label-danger');
            $('#errores').addClass('label-success');
            $('#categorize').html(loader).load('admin/categorize/' + $site + '/' + $selected_categories, function(){
                $('#categorize').html("{{ Lang::get('admin.sites-categorize') }}");
                $('#errores').html("{{ Lang::get('admin.sites-categorized_ok') }}");
            });
        } else {
            $('#errores').removeClass('label-success');
            $('#errores').addClass('label-danger');
            $('#errores').html("{{ Lang::get('admin.sites-categorized_error') }}");
            
        }
        return false;
        
    });
</script>
