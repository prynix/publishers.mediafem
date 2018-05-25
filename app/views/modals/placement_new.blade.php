<!-- start: Modal new Placement -->
<div class="modal fade" id="createPlacementModal" tabindex="-1" role="dialog" aria-labelledby="createPlacementModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">{{ Lang::get('placements.crear_placement'); }}</h4>
            </div>

            <form id="createPlacementModalForm" action="create_placement" method="post" class="form-horizontal">
                <input type="hidden" id="one_call" name="one_call" value='0' />
                <div class="modal-body">

                    <input type="hidden" name="plc_site_id" value="" />

                    <div class="form-group">
                        <label for="plc_name" class="col-sm-4 control-label">{{ Lang::get('placements.nombre'); }}:</label>
                        <div class="col-sm-7">
                            <input type="text" name='plc_name' id="plc_name" readonly="true" value="" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="plc_size_id" class="col-sm-4 control-label">{{ Lang::get('placements.tamano'); }}:</label>
                        <div class="col-sm-7">
                            <select name="plc_size_id" id="plc_size_id" class="form-control">
                                <?php $select = 0; ?>
                                @foreach( $sizes as $size )
                                @if($size->siz_is_active == '1')
                                <option value="{{ $size->siz_id }}" @if($select == 0) selected <?php $select = 1; ?> @endif>{{ $size->siz_name }} - {{ $size->sizeType->getName() }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="display: none;" id="div_plc_url_video">
                        <label for="plc_url_video" class="col-sm-4 control-label">Video URL (*):</label>
                        <div class="col-sm-7">
                            <input type="text" name='plc_url_video' id="plc_url_video" value="" class="form-control" />
                        </div>
                    </div>
                    <div style="padding-left: 200px;font-size: 12px;display: none;" id="div_msg_plc_url_video">
                        <label>(*) {{ Lang::get('placements.formatos_video_aceptados') }}</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">{{ Lang::get('general.cancelar'); }}</a>
                    <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('placements.crear_placement'); }}</span></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end: Modal new Placement -->

<script>

    $().ready(function() {
        $.get('/placement_name/{{$site->getId()}}/' + $($('#plc_size_id').find('option:selected')).val(), function(result) {
            $('#plc_name').val(result);
            $('#plc_name').attr('value', result);
        });

        $('#plc_size_id').change(function() {

            if ($(this).val() == 11) {
                $('#div_plc_url_video').fadeIn(150);
                $('#div_msg_plc_url_video').fadeIn(150);
            } else {
                $('#div_plc_url_video').fadeOut(150);
                $('#div_msg_plc_url_video').fadeOut(150);
            }

            $.get('/placement_name/{{$site->getId()}}/' + $($('#plc_size_id').find('option:selected')).val(), function(result) {

                $('#plc_name').val(result);
                $('#plc_name').attr('value', result);
            });
        });

        $('#plc_name_ID').text();
        $('#createPlacementModalForm').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                plc_name: {
                    validators: {
                        notEmpty: {},
                        stringLength: {
                            min: 5,
                            max: 255
                        }
                    }
                },
                plc_url_video: {
                    validators: {
                        notEmpty: {},
                        callback: {
                            message: '{{ Lang::get('placements.url_video_invalida') }}',
                            callback: function(value, validator, $field) {
                                var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;

                                var extension = value.substring(value.lastIndexOf("."));
                               
                                if(value.match(p) || extension=='.mp4') {
                                    return true; 
                                }else{
                                    return false;
                                }
                                return false;
                            }
                        }
                    }
                }
            }
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            
            if($('#one_call').val() == 1){
                return false;
            }
            
            $('#one_call').val(1);
            
            $('input[name="plc_site_id"]').attr('value', ($('#siteSelect option:selected').val()));

            $('#createPlacementModalForm').processForm(function() {
                if (resultCallback.error === 0) {
                    $('#siteSelect').change();
                    
                    $('#createPlacementModal').modal('hide');
                    $('#msgPlacementModal').modal('toggle');
                    $('#one_call').val(0);
                }
            });

            return false;
        });
    });
</script>