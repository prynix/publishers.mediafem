@if(count($allBillings)<1)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> {{ Lang::get('admin.payments-hasnt_process_payments') }}</div>
@else

<div class="panel panel-success">
    <div class="panel-heading" id="filtrosHead" style="cursor: pointer"><h4>+ Filtros</h4></div>
    <div class="panel-body" id="filtros" hidden="true">
        @if($type!='affiliate')
            <div class="btn btn-default btn-marginR20">            
                {{Lang::get('admin.media_buyer')}}
                <span id="filter_pagos_proceso_todos_ejecutivo"></span>
            </div>
            <div class="btn btn-default btn-marginR20">        
                {{Lang::get('admin.publisher')}}
                <span id="filter_pagos_proceso_todos_publisher"></span>
            </div>
            <div class="btn btn-default btn-marginR20">        
                Activo
                <span id="filter_pagos_proceso_todos_estado"></span>
            </div>
        @endif
        <div class="btn btn-default btn-marginR20">        
            {{Lang::get('admin.payments-income_date')}}
            <span id="filter_pagos_proceso_todos_descripcion"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            {{Lang::get('admin.payments-amount_range')}}
            <span id="filter_pagos_proceso_todos_importe"></span>
        </div>
        
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <button id="exportar" class="btn btn-success">Exportar Excel</button>
    </div>
    <div class="panel-body">
        <table id="pagos_proceso_todos" class="table table-condensed table-hover" style="white-space: nowrap !important; font-size: 11px !important;">
            <thead>
                <tr>
                    <th>{{ Lang::get('admin.payments-'.$type) }}</th>
                    <th>Descripcion</th>
                    <th>{{ Lang::get('mis_pagos.fecha_estimada'); }}</th>
                    <th>{{ Lang::get('mis_pagos.importe'); }}</th>
                    @if($type=='publisher')<th>Activo</th>@endif
                    <th>&nbsp;</th>
                    @if($type=='publisher')<th>Media Buyer</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($allBillings as $billing)
                @if($type=='publisher')
                    <?php $publisher = $billing->getPublisher(); ?>
                    @if($publisher)
                    @if($publisher->getId() != '1476')
                    <tr>
                        <td>{{ $publisher->getName() }}</td>
                        <td><span id="{{ $billing->getLastDate() }}">{{ Lang::get('payments.earnings') . ' ' . $billing->getConcept() }}</span></td>
                        <td>{{ $billing->getStipulatedDate() }}</td>
                        <td>{{ number_format($billing->getBalance(),2) }}</td>
                        <td>
                            @if (multidimensional_array_search($publisher->getId(), $imps, 'id')->imps < 100)
                            NO
                            @else
                            SI
                            @endif
                        </td>
                        <td>
                            <a href="" class="itemShow" data-itemId="{{ $publisher->getId() }}">
                                Ver <i class="fa fa-angle-double-right"></i>
                            </a>
                        </td>
                        <td>
                            @if($publisher->mediaBuyer){{ $publisher->mediaBuyer->getName() }}@else --- @endif
                        </td>
                    </tr>
                    @endif
                    @endif
                @else
                    <?php $administrator = $billing->getAdministrator(); ?>
                    @if($administrator)
                    <tr>
                        <td>{{ $administrator->user->getEmail() }}</td>
                        <td><span id="{{ $billing->getLastDate() }}">{{ Lang::get('payments.earnings') . ' ' . $billing->getConcept() }}</span></td>
                        <td>{{ $billing->getStipulatedDate() }}</td>
                        <td>{{ number_format($billing->getBalance(),2) }}</td>
                        <td>
                            <a href="" class="itemShow" data-itemId="{{ $administrator->getId() }}">
                                Ver <i class="fa fa-angle-double-right"></i>
                            </a>
                        </td>
                    </tr>
                    @endif
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
<script>
    $(document).ready(function () {
        $('a.itemShow').click(function (e) {
            e.preventDefault();
            $('#publisherData').html(loader).load('/admin/item_payments/' + $(this).attr('data-itemId') + '/{{ $type }}');
            $('#publisherBillings').html(loader).load('/admin/item_billings/' + $(this).attr('data-itemId') + '/{{ $type }}');
            $('#publisherDetail').show();
            return false;
        });
        $('#filtrosHead').click(function (e) {
            e.preventDefault();
            filtros = $('#filtros');
            if ($('#filtros').css('display') == 'block') {
                filtros.hide("fast");
            } else {
                filtros.show("fast");
            }
        });
   
        $('#exportar').click(function (e) {
            oSettings._iDisplayLength = -1;
            datatable1.fnDraw();
            $('#pagos_proceso_todos').tableExport({type: 'excel', escape: 'false', ignoreColumn: '[5]'});
            oSettings._iDisplayLength = 10;
            datatable1.fnDraw();
        });


        function filterMonths(filterVal, columnVal) {
            var found;
            if (columnVal === '') {
                return true;
            }
            switch (filterVal) {
                case '1':
                    found = columnVal.search(/Enero|January/g);
                    break;
                case '2':
                    found = columnVal.search(/Febrero|February/g);
                    break;
                case '3':
                    found = columnVal.search(/Marzo|March/g);
                    break;
                case '4':
                    found = columnVal.search(/Abril|April/g);
                    break;
                case '5':
                    found = columnVal.search(/Mayo|May/g);
                    break;
                case '6':
                    found = columnVal.search(/Junio|June/g);
                    break;
                case '7':
                    found = columnVal.search(/Julio|July/g);
                    break;
                case '8':
                    found = columnVal.search(/Agosto|August/g);
                    break;
                case '9':
                    found = columnVal.search(/Septiembre|September/g);
                    break;
                case '10':
                    found = columnVal.search(/Octubre|October/g);
                    break;
                case '11':
                    found = columnVal.search(/Noviembre|November/g);
                    break;
                case '12':
                    found = columnVal.search(/Diciembre|December/g);
                    break;
                default:
                    found = 1;
                    break;
            }

            if (found !== -1) {
                return true;
            }
            return false;
        };

        var datatables_options =
                {   
                    "bAutoWidth": true,
                    "sDom": '<"top"if>rt<"bottom"lp><"clear">', //determine render order for datatables.net items, http://datatables.net/ref#sDom
                    "bPaginate": true, // paging
                    "sPaginationType": "simple_numbers", // http://datatables.net/release-datatables/examples/basic_init/alt_pagination.html
                    "iDisplayLength": 10, // page row size
                    "bSort": true, //sorting
                    "bFilter": true, // "search" box
                    "aaSorting": [[2, "asc"]], // default sort
                    "default": true,
                    "bInfo": false, // "Showing x to y of z entries" message
                    "bStateSave": false, // save state into a cookie
                    "iCookieDuration": 0, // save state cookie duration//"bScrollAutoCss": true, // datatables.net auto styling of scrolling styles, http://datatables.net/forums/discussion/comment/15072
                    "bProcessing": true, // "processing" message while sorting .. doesn't appear to be doing anything
                    "bJQueryUI": false, // css classes for jQueryUI themes?//"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)
                };
                @if($type == 'affiliate')
                    datatables_options["aoColumns"] = [
                            null,
                            null,
                            null,
                            null,
                            null,
                        ];
                @else
                    datatables_options["aoColumns"] = [
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            {"bVisible": false},
                        ];
                @endif
        var datatable1 = $("#pagos_proceso_todos").dataTable(datatables_options);
        var oSettings = datatable1.fnSettings();

        datatable1.yadcf([
            @if($type != 'affiliate')
            {column_number: 0,
                filter_container_id: "filter_pagos_proceso_todos_publisher",
                filter_reset_button_text: "&times;"},
            {column_number: 4,
                filter_reset_button_text: "&times;",
                filter_container_id: "filter_pagos_proceso_todos_estado"},
            {column_number: 6,
                filter_reset_button_text: "&times;",
                filter_container_id: "filter_pagos_proceso_todos_ejecutivo"},
            @endif
            {column_number: 1,
                filter_type: 'custom_func',
                filter_reset_button_text: "&times;",
                custom_func: filterMonths,
                data: [{
                        value: '1',
                        label: 'Enero'
                    }, {
                        value: '2',
                        label: 'Febrero'
                    }, {
                        value: '3',
                        label: 'Marzo'
                    }, {
                        value: '4',
                        label: 'Abril'
                    }, {
                        value: '5',
                        label: 'Mayo'
                    }, {
                        value: '6',
                        label: 'Junio'
                    }, {
                        value: '7',
                        label: 'Julio'
                    }, {
                        value: '8',
                        label: 'Agosto'
                    }, {
                        value: '9',
                        label: 'Septiembre'
                    }, {
                        value: '10',
                        label: 'Octubre'
                    }, {
                        value: '11',
                        label: 'Noviembre'
                    }, {
                        value: '12',
                        label: 'Diciembre'
                    }],
                filter_default_label: "Ingresos del mes",
                filter_container_id: "filter_pagos_proceso_todos_descripcion"},
            {column_number: 3,
                filter_reset_button_text: "&times;",
                filter_type: "range_number", filter_container_id: "filter_pagos_proceso_todos_importe"}
        ]);
    });
</script>