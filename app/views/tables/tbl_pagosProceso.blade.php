@if(!$billings)
    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> {{ Lang::get('payments.sin_pagos_en_proceso'); }}.</div>
@else
    <table class="table table-hover report">
        <thead>
            <tr>
                <th>{{ Lang::get('mis_pagos.fecha_estimada'); }}</th>
                <th>{{ Lang::get('mis_pagos.descripcion'); }}</th>
                <th>{{ Lang::get('mis_pagos.importe'); }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($billings as $billing)
            <tr>
                <td>{{ $billing->getStipulatedDate() }}</td>
                <td>{{ Lang::get('payments.earnings') . ' ' . $billing->getConcept() }}</td>
                <td>US$ {{ number_format($billing->getBalance(),2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
