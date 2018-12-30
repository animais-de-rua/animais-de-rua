@component('mail::message')
# Pedido de informação sobre formação

<strong>Nome:</strong> {{ $request->name }}<br />
<strong>E-Mail:</strong> {{ $request->email }}<br />
<strong>Telefone:</strong> {{ $request->phone }}<br />
<strong>Concelho:</strong> {{ $request->territory }}<br />
<strong>Tema:</strong> {{ __($request->theme) }}<br />
<strong>Observações:</strong><br /> {{ $request->observations }}<br />
@endcomponent
