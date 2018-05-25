@extends ('emails.layout')

@section ('title') New payment registered @stop

@section ('content')
Dear Publisher,
<br /><br />
Recently we have released a payment of <b>{{ $billing->getBalance() }} usd</b>, corresponding to the the income made in the Adtomatik advertising programe.
<br />
For more information on the subject, please access your account and visit the Payements tab.    

@stop