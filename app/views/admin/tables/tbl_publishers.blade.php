<?php
set_time_limit(0);
ini_set('post_max_size', '99999M');
ini_set('upload_max_filesize', '999999M');
ini_set('memory_limit', '999999M');
ini_set('max_execution_time', '99999');
ini_set('max_input_time', '99999');
$tester_permission = Utility::hasPermission('publishers.tester');
$adserver_permission = Utility::hasPermission('adserver.show');
?>
@if (Utility::hasPermission('adserver.show'))
{{ Forms::filters([[Lang::get('admin.media_buyer'), 'filter_publishers_media_buyer'],
            [Lang::get('admin.adserver'), 'filter_publishers_adserver'],
            [Lang::get('admin.publishers-tax_info'), 'filter_publishers_tax_complete']]) }}
@endif
{{ Forms::exportButton('export/publishers') }}

<i>&nbsp;&#8678;&nbsp;{{ Lang::get('admin.publishers-export_excel') }}</i>
<table class="table row-border" id="publishers-table"  style="white-space: nowrap !important; font-size: 11px !important;">
    <thead>
        <tr>
            @if($tester_permission)
                <th>&nbsp;</th>
            @endif
            <th>&nbsp;</th>
            <th>URL</th>
            <th>{{ Lang::get('admin.publishers-name') }}</th>
            <th>{{ Lang::get('admin.publishers-email') }}</th>
            <th>{{ Lang::get('admin.media_buyer') }}</th>
            @if($adserver_permission)
                <th>{{ Lang::get('admin.adserver') }}</th>
                <th>{{ Lang::get('admin.publishers-publisher_id') }}</th>
            @endif
            <th>{{ Lang::get('admin.created_at') }}</th>
            <th hidden="true">Id</th>
            <th>{{ Lang::get('admin.publishers-tax_info') }}</th>
            <th hidden="true">{{ Lang::get('admin.publishers-tax_info') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($publishers as $publisher)
           <tr name="fila" @unless ($publisher->media_buyer != 'Unassigned') style="font-weight: bold;" @endunless >
            @if($tester_permission) 
            <td>
                <a href=""
                   class="assignTester"
                   data-publisherId="{{ $publisher->id }}"
                   >
                    <i class="fa fa-angle-double-left"></i> {{Lang::get('admin.publishers-test') }} 
                </a>
            </td>
            @endif
            <td>
                <a href=""
                   class="publisherShow"
                   data-publisherId="{{ $publisher->id }}"
                   >
                    {{Lang::get('admin.see') }} <i class="fa fa-angle-double-right"></i>
                </a>
            </td>
            <td>{{ $publisher->url }}</td>
            <td>{{ $publisher->name }}</td>
            <td>{{ $publisher->email }}</td>
            <td>{{ $publisher->media_buyer }}</td>
           @if($adserver_permission)
                <td>{{ $publisher->adserver_name }}</td>
                <td>{{ $publisher->adserver_key }}</td>
            @endif
            <td>{{ $publisher->created_at }}</td>
            <td hidden="true">{{ $publisher->id }}</td>
            @if($publisher->pbl_tax_complete == '0')
            <td>NO</td>
            <td hidden="true">NO</td>
            @else
            <td><a data-taxFile="{{$publisher->pbl_tax_file}}" href="javascript:void(0)" style="cursor: pointer;" class="download_tax_file" target="_blank">Descargar</a></td>
            <td hidden="true">SI</td>
            @endif
        </tr>
        @endforeach
        
    </tbody>
</table>

<div class="col-md-4 floatRight">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Search..." id="filterTxt">
        <span class="input-group-btn">
            <button class="btn btn-default btn-marginR20" id="filterBtn"  type="button">Search</button>
        </span>
    </div><!-- /input-group -->
</div><!-- /.col-lg-6 -->

<script>
    $(document).ready(function () {
        @if (Utility::hasPermission('adserver.show'))
            addFilters("{{ Lang::get('admin.filters') }}");
        @endif
        tableHighlightRow();

        $('a.publisherShow').click(function (e) {
            e.preventDefault();
            $('#publisherData').html(loader).load('admin/publisher_details/' + $(this).attr('data-publisherId'));
            return false;
        });
        $('a.assignTester').click(function (e) {
            e.preventDefault();
            $('#publisherData').html(loader).load('admin/assign_tester/' + $(this).attr('data-publisherId'));
            return false;
        });
        $('a.download_tax_file').click(function (e) {
            e.preventDefault();
            window.location.href = 'download_tax_form/' + $(this).attr('data-taxFile');
        });
        
        $("div.dataTables_filter input").unbind();
        @if (Utility::hasPermission('adserver.show'))
            var sortColumn = 9;
        @else 
            var sortColumn = 6;
        @endif
            var datatable2 = $('#publishers-table').dataTable({
                "sDom": '<"top"i>rt<"bottom"lp><"clear">',
                'paging': false,
                'info': false,
                "bSort": true, //sorting
                "aaSorting": [[sortColumn, "desc"]], // default sort
                'bFilter': true,
                'bScrollCollapse': true,
                'sScrollY': "450px",
                'sScrollX': "100%",
            });
        
        @if (Utility::hasPermission('adserver.show'))
                datatable2.yadcf([
                {column_number: 5,
                        filter_container_id: "filter_publishers_media_buyer",
                        filter_reset_button_text: "&times;"},
                {column_number: 6,
                        filter_container_id: "filter_publishers_adserver",
                        filter_reset_button_text: "&times;"},
                {column_number: 11,
                        filter_container_id: "filter_publishers_tax_complete",
                        filter_reset_button_text: "&times;"}
                ]);
        @endif

            $('#filterBtn').click(function (e) {
            e.preventDefault();
            datatable2.fnFilter($("#filterTxt").val());
            return false;
        });
    });
</script>