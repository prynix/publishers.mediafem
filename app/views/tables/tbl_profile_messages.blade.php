@if(count($messages)<=0)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> {{ Lang::get('mi_cuenta.dont_have_messages'); }}.</div>
@else
<table class="table table-hover messages">
    <thead>
        <tr>
            <th>{{ Lang::get('mi_cuenta.subject') }}</th>
            <th>{{ Lang::get('mi_cuenta.message') }}</th>
            <th>{{ Lang::get('mi_cuenta.date') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($messages as $message)
        <tr>
            <td><a id="{{ $message->msg_id }}" href="" data-toggle="modal" data-target="#viewMessageModal">{{ $message->msg_subject }}</a></td>
            <td>{{ Str::limit(strip_tags($message->msg_content), 50, '...') }}</td>
            <td>{{ $message->created_at }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">-</td>
        </tr>
        @endforelse
    </tbody>
</table>

@include('modals.message')

<script>
    $().ready(function(){
        
        @if(sizeof($messages) > 0)
        $('.messages').dataTable({
            'paging': false,
            'info': false,
            'bFilter': false,
            'ordering': false
        });

        $('a[data-target="#viewMessageModal"]').click(function() {
            $('#viewMessageModal .modal-body').html(loader).load('messages/get_message/' + $(this).attr('id'));
        });
        @endif
    });
</script>
@endif
