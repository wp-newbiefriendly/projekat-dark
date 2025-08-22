<h1>Prognoza za {{ $city->name }}</h1>

<ul>
    @forelse($forecasts as $f)
        <li>{{ \Carbon\Carbon::parse($f->forecast_date)->format('d.m.Y') }} → {{ $f->temperature }}°C</li>
    @empty
        <li>Nema prognoza za ovaj grad.</li>
    @endforelse
</ul>
