<form id="accountExecutiveForm" action="media_buyer_update" method="post" class="form-horizontal">

    <div class="form-group">
        <label for="pbl_media_buyer_id" class="col-sm-3 control-label">{{ Lang::get('admin.media_buyer') }}:</label>
        <div id="mediaBuyerCombo">
            <input type="hidden" value="{{ $publisher->getId() }}" name="publisherId" />
            <input type="hidden" name="mediaBuyerId" id="mediaBuyerId" value=@if(!$publisher->mediaBuyer) '0' @else '{{ $publisher->mediaBuyer->adm_id }} @endif' />

            <div class="col-sm-8">
                @if(Utility::hasPermission('publishers.media_buyer'))
                <select name="pbl_media_buyer_id" class="form-control">

                    @foreach( Administrator::all() as $admin )
                    @if($admin->getAdserverKey($publisher->getFirstAdserverId()))
                    <option value="{{ $admin->adm_id }}"
                            @if ($publisher->mediaBuyer)
                            {{ $admin->adm_id === $publisher->mediaBuyer->adm_id ? 'selected="selected"' : '' }}>
                            @else
                            >
                            @endif
                            {{ $admin->getName() }}</option>
                    @endif  
                    @endforeach
                    @unless ($publisher->mediaBuyer)
                    <option value="0" selected="selected">[{{ strtoupper(Lang::get('admin.none')) }}]</option>
                    @endunless
                </select>
                @else
                @if ($publisher->mediaBuyer)
                {{ $publisher->mediaBuyer->getName() }}
                @else
                {{ strtoupper(Lang::get('admin.none')) }}
                @endif
                @endif

                <span class="help-block"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9" id="errores"></div>
    </div>
    @if(Utility::hasPermission('publishers.media_buyer'))
    <div class="row">
        <div class="modal-footer col-sm-10 col-md-offset-1">
            <button type="submit" id="assign_mediabuyer_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('admin.save'); }}</span></button>
        </div>
    </div>
    @endif
</form>

<script>
    $("select[name=pbl_media_buyer_id]").click(function () {
        $("#mediaBuyerId").val($(this).val());
    });

    $("#assign_mediabuyer_form").click(function (e) {

        e.preventDefault();
        $("#assign_mediabuyer_form").html("Asignando ejecutivo... "+loader);
        var pub_id = $('input[name="publisherId"]').val();
        var datos = {
            'mediaBuyerId': $('input[name="mediaBuyerId"]').val(),
            'publisherId': $('input[name="publisherId"]').val()
        };

        $.ajax({
            data: datos,
            url: 'media_buyer_update',
            type: 'post',
            dataType: 'json',
            success: function (result) {

                if (result.error == 1 || result.error == 2) {
                    $('#errores').html(result.messages);
                    $("#submit_form").html("{{ Lang::get('admin.save'); }}");
                    return false;
                }
                else
                    $('#publisherData').html(loader).load('admin/publisher_details/' + pub_id);

            }
        });

        return false;
    });
</script>