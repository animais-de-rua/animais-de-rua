@component('mail::message')
# Olá {{ $process->contact }},

A sua candidatura foi recebida com sucesso.<br />
Abaixo seguem as informações enviadas.<br />

<strong>E-Mail:</strong> {{ $process->email }}<br />
<strong>Telefone:</strong> {{ $process->phone }}<br />
<strong>Processo:</strong> {{ $process->name }}<br />
<strong>ID:</strong> {{ $process->id }}<br />
<strong>Animais:</strong> {{ $process->amount_other }}<br />
<strong>Espécie:</strong> {{ __($process->specie) }}<br />
<strong>Morada</strong> {{ $process->address }}<br />
<strong>Observações:</strong><br /> {{ $process->history }}<br />

@component('mail::button', ['url' => config('app.url')])
Voltar ao site
@endcomponent

Obrigada,<br>
{{ config('app.name') }}
@endcomponent
