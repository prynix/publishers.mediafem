<div class="row">
    <span class="col-md-3"><b>{{ Lang::get('mi_cuenta.from') }}:</b></span>
    <span>{{ $message->msg_from }}</span>
</div>
<div class="row">
    <span class="col-md-3"><b>{{ Lang::get('mi_cuenta.subject') }}:</b></span>
    <span>{{ $message->msg_subject }}</span>
</div>
<div class="row">
    <div class="col-md-3"><b>{{ Lang::get('mi_cuenta.message') }}:</b></div><br /><br />
    <div class="col-md-12 clear">{{ $message->msg_content }}</div>
</div>