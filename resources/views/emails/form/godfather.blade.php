@component('mail::message')
# Apadrinhamento

<strong>Nome:</strong> {{ $request->name }}<br />
<strong>E-Mail:</strong> {{ $request->email }}<br />
<strong>Telefone:</strong> {{ $request->phone }}<br />
<strong>Valor:</strong> {{ $request->value }}€<br />
<strong>Processo:</strong> <a href="{{ env('APP_URL') }}/admin/process/{{ $request->process_id }}">{{ $request->process_name }}</a> (ID: {{ $request->process_id }})<br />
<strong>Observações:</strong><br /> {{ $request->observations }}<br />
@endcomponent
