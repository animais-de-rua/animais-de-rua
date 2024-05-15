@component('mail::message')
# Novo pedido de petsitting

<strong>Nome:</strong> {{ $request->first_name }} {{ $request->last_name }}<br />
<strong>Email:</strong> {{ $request->email }}<br />
<strong>Telefone:</strong> {{ $request->phone }}<br />
<strong>Morada:</strong> {{ $request->address }}<br />
<strong>Município:</strong> {{ $request->city }}<br />
<strong>Freguesia:</strong> {{ $request->town }}<br />

<br />

<strong>Data de início:</strong> {{ \Carbon\Carbon::parse($request->initial_date)->format('d/m/Y') }}<br />
<strong>Data de fim:</strong> {{ \Carbon\Carbon::parse($request->final_date)->format('d/m/Y') }}<br />
<strong>Animal:</strong>@if(count($request->animals) > 1) {{ implode(', ', $request->animals) }} @else {{ $request->animals[0] }} @endif<br />
@if ($request->other_animals)
  <strong>Outros animais:</strong> {{ $request->other_animals }}<br />
@endif
<strong>Número de animais:</strong> {{ $request->number_of_animals }}<br />
<strong>Temperamento:</strong> {{ $request->animal_temper }}<br />
<strong>Número de visitas:</strong> {{ $request->visit_number }}<br />
<strong>Passeios:</strong> @if($request->has_walk === 'yes') {{ $request->walk_number }} @else Sem passeios @endif<br />
<strong>Outros serviços:</strong>
@if ($request->services)
  <ul>
    @foreach($request->services as $service)
      <li>{{ __($service) }}</li>
    @endforeach
  </ul>
@else
Sem outros serviços<br />
@endif
<strong>Notas:</strong> @if($request->notes) {{ $request->notes }} @else Sem outros detalhes @endif<br />
<strong>Permissão para divulgar fotos:</strong> {{ ucfirst(__($request->has_consent)) }}<br />
@endcomponent
