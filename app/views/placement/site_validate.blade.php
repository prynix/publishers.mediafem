<div class="alert alert-danger">
    <b>{{ Lang::get('placements.sitio_no_verificado'); }}</b>

    <p>{{ Lang::get('placements.texto_verificar', ['brand' => Session::get('platform.brand')]); }}</p>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#withArchive" data-toggle="tab">{{ Lang::get('placements.verificar_archivo'); }}</a></li>
    <li><a href="#withTag" data-toggle="tab">{{ Lang::get('placements.verificar_tag'); }}</a></li>
    <li><a href="#withList" data-toggle="tab">{{ Lang::get('placements.verificar_listado'); }}</a></li>
</ul>

<div class="panel-body">
    <div class="tab-content">
        <div class="tab-pane active" id="withArchive">
            <p>{{ Lang::get('placements.descargar_archivo_txt', ['site' => $site->sit_name, 'brand' => Session::get('platform.brand')]); }}</p>

            <div>
                <a href="/download_verification_file/{{ $site->sit_id }}" id="descargar_archivo" class="btn btn-primary" target="_BLANK"><i class="fa fa-download"></i> {{ Lang::get('placements.descargar_archivo'); }}</a>

                <form id="validateSiteFormFile" action="validate_site" method="post" class="form-horizontal" style="display: inline-block">
                    <input type="hidden" name="sit_id" value="{{ $site->sit_id }}" />
                    <input type="hidden" name="sit_name" value="{{ $site->sit_name }}" />
                    <input type="hidden" name="validate_method" value="file" />
                    <button type="submit" id="submit_form" class="btn btn-default ladda-button" data-style="zoom-out"><span class="ladda-label"><i class="fa fa-check"></i> {{ Lang::get('placements.validar_sitio'); }}</span></button>
                    <span id="validateError" class="text-danger"></span>
                </form>
            </div>
        </div>

        <div class="tab-pane" id="withTag">
            <p>{{ Lang::get('placements.etiqueta_html'); }}</p>

            <div>
                <form id="validateSiteFormTag" action="validate_site" method="post" class="form-inline" style="display: inline-block">
                    <input type="hidden" name="sit_id" value="{{ $site->sit_id }}" />
                    <input type="hidden" name="sit_name" value="{{ $site->sit_name }}" />
                    <input type="hidden" name="validate_method" value="tag" />
                    <div class="form-group">
                        <input type="text" name="meta_code" value="&lt;meta name=&quot;{{ Session::get('platform.brand') }}-tag&quot; content=&quot;{{ base64_encode($site->sit_id); }}&quot;/&gt;" class="form-control" style="width: 400px; cursor: text !important;" readonly="true" />
                    </div>
                    <div class="form-group">
                        <button type="submit" id="submit_form" class="btn btn-default ladda-button" data-style="zoom-out"><span class="ladda-label"><i class="fa fa-check"></i> {{ Lang::get('placements.validar_sitio'); }}</span></button>
                        <span id="validateErrorTag" class="text-danger"></span>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="tab-pane" id="withList">
            <p>{{ Lang::get('placements.lista_dominios'); }}</p>

            <form id="domainListForm" action="add_domain_list" method="post" class="form-horizontal">
                <input type="hidden" name="sit_id" value="{{ $site->sit_id }}" />
                <div class="form-group">
                    <div class="col-sm-5">
                        <textarea name="domain_list" class="form-control" rows="3" placeholder="http://domain.com">{{ $site->sit_name }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-5">
                    <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('placements.guardar'); }}</span></button>
                    <span id="domainListError" class="text-danger"></span>
                    </div>
                </div>
            </form>
        </div>
       
    </div>
</div>

<script>
    $(document).ready(function(){        
        $('#descargar_archivo').click(function(){
            $('#validateSiteForm button[type="submit"]').addClass('btn-primary').removeClass('btn-default');
            $('#descargar_archivo').addClass('btn-default').removeClass('btn-primary');
        });
        
        $('#validateSiteFormFile button[type="submit"]').click(function(e) {
            e.preventDefault();
            
            $('#validateError').html('');
            
            $('#validateSiteFormFile button[type="submit"]').addClass('btn-primary').removeClass('btn-default');
            $('#descargar_archivo').addClass('btn-default').removeClass('btn-primary');

            $('#validateSiteFormFile').processForm(function(){
                
                if(resultCallback.error == 0)
                    $('#msgValidateSiteModal').modal('toggle');
                
                $('#siteSelect').change();
                
            }, function(){
                if(resultCallback.error != 0)
                    $('#validateError').html(resultCallback.messages);
            });            

            return false;
        });
        
        $('#validateSiteFormTag button[type="submit"]').click(function(e) {
            e.preventDefault();
            
            $('#validateErrorTag').html('');
            
            $('#validateSiteFormTag button[type="submit"]').addClass('btn-primary').removeClass('btn-default');
            $('#descargar_archivo').addClass('btn-default').removeClass('btn-primary');

            $('#validateSiteFormTag').processForm(function(){
                
                if(resultCallback.error == 0)
                    $('#msgValidateSiteModal').modal('toggle');
                
                $('#siteSelect').change();
                
            }, function(){
                if(resultCallback.error != 0)
                    $('#validateErrorTag').html(resultCallback.messages);
            });            

            return false;
        });
        
        $('#domainListForm button[type="submit"]').click(function(e) {
            e.preventDefault();
            
            $('#domainListError').html('');

            $('#domainListForm').processForm(function(){
                if(resultCallback.error == 0)
                    $('#msgValidateSiteModal').modal('show');
                
                $('#siteSelect').change();
            }, function(){
                console.log(resultCallback);
                
                if(resultCallback.error != 0){
                    if(resultCallback.messages.sit_name != undefined){
                        $('#domainListError').html(resultCallback.messages.sit_name[0]);
                    }else{
                        $('#domainListError').html(resultCallback.messages);
                    }
                }
            });            

            return false;
        });
    });
    
    
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown', function (e) {
        window.location.hash = e.target.hash;
    })
</script>
