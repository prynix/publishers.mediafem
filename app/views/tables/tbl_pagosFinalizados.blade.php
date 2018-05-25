@if(!$records)
    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> {{ Lang::get('payments.sin_pagos_en_proceso'); }}</div>
@else
    <table class="table table-hover report">
        <thead>
            <tr>
                <th>{{ Lang::get('mis_pagos.fecha'); }}</th>
                <th>{{ Lang::get('mis_pagos.descripcion'); }}</th>
                <th>{{ Lang::get('mis_pagos.creditos'); }}</th>
                <th>{{ Lang::get('mis_pagos.debitos'); }}</th>
                <th>{{ Lang::get('mis_pagos.saldo_acumulado'); }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ date("Y-m-d", strtotime($record['date'])) }}</td>
                @if($record['type']=='earning')
                    <td>{{ Lang::get('payments.earnings') . ' ' . $record['record']->getConcept() }}</td>
                    <td>US$ {{ number_format($record['record']->getAmount(),2) }}</td>
                    <td>---</td>
                @else
                    <td>{{ $record['record']->getConcept() }}</td>
                    <td>---</td>
                    <td>US$ -{{ number_format($record['record']->getAmount(),2) }}</td>
                @endif
                <td>US$ {{ number_format($record['balance'],2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif