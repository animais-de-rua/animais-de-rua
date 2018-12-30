@component('mail::message')
# Contacto Animais de Rua

<strong>Nome:</strong> {{ $request->name }}<br />
<strong>E-Mail:</strong> {{ $request->email }}<br />
<strong>Telefone:</strong> {{ $request->phone }}<br />
<strong>Concelho:</strong> {{ $request->territory }}<br />
<hr />
<strong>Assunto:</strong> {{ $request->subject }}<br />
<strong>Observações:</strong><br /> {{ $request->observations }}<br />
@endcomponent
