<button id="exportar2" class="btn btn-default btn-marginR20 floatRight"><i class="fa fa-file-excel-o"></i> Exportar Excel</button>
<br /><br />
{{ Forms::filters([['Perfil', 'filter_users_profile'],
            ['Activado', 'filter_users_activated']]) }}
<table class="table table-hover table-condensed" id="users-table" style="white-space: nowrap !important; font-size: 11px !important;">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Id</th>
            <th>Email</th>
            <th>Nombre</th>
            <th>Perfil</th>
            <th>Fecha Alta</th>
            <th>Activado?</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr name="fila">

            <td>
                <a href=""
                   class="userShow"
                   data-userId="{{ $user->getId() }}"
                   >
                    Ver <i class="fa fa-angle-double-right"></i>
                </a>
            </td>
            <td>{{ $user->getId() }}</td>
            <td>{{ $user->getEmail() }}</td>
            <td>@if($user->profile){{ $user->profile->getName() }}@else---@endif</td>
            <td>@if($user->administrator) {{ $user->administrator->group->getDescription() }} @else Nuevo usuario @endif</td>
            <td>@if($user->created_at == '-0001-11-30 00:00:00')Antes de la migracion @else{{ $user->created_at }} @endif</td>
            <td>@if($user->isActived())SI @else NO @endif</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function () {
        addFilters("{{ Lang::get('admin.filters') }}");
        tableHighlightRow();
        $('a.userShow').click(function (e) {
            e.preventDefault();
            $('#userData').html(loader).load('admin/user_details/' + $(this).attr('data-userId'));
            return false;
        });

        $('#exportar2').click(function (e) {
            //oSettings._iDisplayLength = -1;
            //datatable1.fnDraw();
            e.preventDefault();
            $('#users-table').tableExport({type: 'excel', escape: 'false', ignoreColumn: '[0]'});
            return false;
            //oSettings._iDisplayLength = 10;
            //datatable1.fnDraw();
        });

        var datatables_options =
                {
                    "bAutoWidth": true,
                    "sDom": '<"top"i>rt<"bottom"flp><"clear">', //determine render order for datatables.net items, http://datatables.net/ref#sDom
                    "bPaginate": false, // paging
                    "sPaginationType": "full_numbers", // http://datatables.net/release-datatables/examples/basic_init/alt_pagination.html
                    "iDisplayLength": 10, // page row size
                    "bSort": true, //sorting
                    "bFilter": true, // "search" box
                    "aaSorting": [[1, "desc"]], // default sort
                    "bInfo": false, // "Showing x to y of z entries" message
                    "bStateSave": false, // save state into a cookie
                    "iCookieDuration": 0, // save state cookie duration
                    "bScrollAutoCss": true, // datatables.net auto styling of scrolling styles, http://datatables.net/forums/discussion/comment/15072
                    "bProcessing": true, // "processing" message while sorting .. doesn't appear to be doing anything
                    "bJQueryUI": false, // css classes for jQueryUI themes?
                    "aoColumns": [
                        {"bSortable": false},
                        {"sSortDataType": "numeric", "bVisible": false},
                        {"sType": "slo"},
                        {"sType": "slo"},
                        {"sType": "slo"},
                        {"sType": "slo"},
                        {"sType": "slo"},
                    ]
                            //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)

                };

        datatables_options["sScrollX"] = "100%";
        datatables_options["sScrollY"] = "450px";
        //datatables_options["sScrollXInner"] = '150%'; 
        datatables_options["bScrollCollapse"] = true;
        var datatable2 = $("#users-table").dataTable(datatables_options);
        datatable2.yadcf([
            {column_number: 4,
                filter_container_id: "filter_users_profile",
                filter_reset_button_text: "&times;"},
            {column_number: 6,
                filter_container_id: "filter_users_activated",
                filter_reset_button_text: "&times;"}

        ]);
    });
</script>