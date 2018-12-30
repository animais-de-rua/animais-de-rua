@component('mail::message')
# Fazer Voluntariado

<strong>Nome:</strong> {{ $request->name }}<br />
<strong>E-Mail:</strong> {{ $request->email }}<br />
<strong>Telefone:</strong> {{ $request->phone }}<br />
<strong>Idade:</strong> {{ $request->age }}<br />
<strong>Profissão:</strong> {{ $request->job }}<br />
<strong>Concelho:</strong> {{ $request->territory }}<br />
<strong>Disponibilidade:</strong> {{ $request->schedule }}<br />
<strong>Interesses:</strong>
<ul style="margin:0;font-size:16px;">
@foreach($request->interest as $interest)
<li>{{ __("web.forms.interests.$interest") }}</li>
@endforeach
</ul>
<strong>Observações:</strong><br /> {{ $request->observations }}<br />
@endcomponent
