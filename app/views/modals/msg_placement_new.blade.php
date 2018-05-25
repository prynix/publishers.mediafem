<!-- start: Modal msg new Placement -->
<div class="modal fade" id="msgPlacementModal" tabindex="-1" role="dialog" aria-labelledby="msgPlacementModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form id="createSiteForm" action="" method="post" class="form-horizontal">
                <div class="modal-body text-center">
                    <p class="great-icon"></p>
                    <h3 class="text-uppercase">{{ Lang::get('general.felicitaciones'); }}</h3>
                    <p>{{ Lang::get('placements.placement_creado'); }}</p>
                    <p><a href="#" class="btn btn-primary text-uppercase" data-dismiss="modal">{{ Lang::get('general.aceptar'); }}</a></p>
                </div>                
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end: Modal msg new Placement -->

<script>

    $().ready(function() {
        $('.btn-primary').click(function() {
            $('.modal-backdrop').remove();
        });
    });
</script>