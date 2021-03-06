<!-- start: Modal msg new Site -->
<div class="modal fade" id="msgSiteModal" tabindex="-1" role="dialog" aria-labelledby="msgSiteModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form id="createSiteForm" action="create_site" method="post" class="form-horizontal">
                <div class="modal-body text-center">
                    <p class="great-icon"></p>
                    <h3 class="text-uppercase">{{ Lang::get('general.felicitaciones'); }}</h3>
                    <p>{{ Lang::get('placements.sitio_creado'); }}</p>
                    <p><a href="#" class="btn btn-primary text-uppercase" data-dismiss="modal">{{ Lang::get('general.aceptar'); }}</a></p>
                </div>                
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end: Modal msg new Site -->