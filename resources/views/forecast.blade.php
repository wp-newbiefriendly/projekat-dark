<h1>Prognoza za {{ ($city) }}</h1>

@if($error)
    <p style="color:red;">{{ $error }}</p>
@else
    <ul>
        @foreach($temperatures as $day => $temp)
            <li>{{ $days[$day] }}: {{ $temp }}°C</li>
        @endforeach
    </ul>

@endif
