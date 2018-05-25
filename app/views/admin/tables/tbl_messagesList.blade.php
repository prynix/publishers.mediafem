<table class="table table-hover messages">
    <thead>
        <tr>
            <th>{{ Lang::get('admin.messages-receiver') }}</th>
            <th>{{ Lang::get('admin.messages-subject') }}</th>
            <th>{{ Lang::get('admin.messages-message') }}</th>
            <th>{{ Lang::get('admin.messages-sending_date') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($messages as $message)
        <tr>
            <td><b>{{ $message->user->email }}</b></td>
            <td><a id="{{ $message->msg_id }}" href="" data-toggle="modal" data-target="#viewMessageModal">{{ $message->msg_subject }}</a></td>
            <td>{{ Str::limit(strip_tags($message->msg_content), 30, '...') }}</td>
            <td>{{ $message->created_at }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center"><b>{{ Lang::get('admin.messages-no_messages') }}</b></td>
        </tr>
        @endforelse
    </tbody>
</table>

@include('admin.messages.view')

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
            $('#viewMessageModal .modal-body').html(loader).load('/messages/get_message/' + $(this).attr('id'));
        });
        @endif
    });
</script>