<h1>Prognoza za {{ $city->name }}</h1>

<ul>
    @forelse($city->forecasts as $f)
        <li>{{ $f->forecast_date }} → {{ $f->temperature }}°C</li>
    @empty
        <li>Nema prognoza za ovaj grad.</li>
    @endforelse
</ul>
