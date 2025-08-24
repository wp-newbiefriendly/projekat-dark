<form action="{{ route('forecast.update') }}" method="POST"
      class="d-flex align-items-center gap-2 p-2 rounded shadow-sm quick-add-form">
    @csrf

    <label class="fw-bold quick-label me-2 mb-0 h5">➕ Brzo editovanje grada:</label>

    <select name="city_id" class="form-select form-select-sm w-auto">
        @foreach ($allCities as $city)
            <option value="{{ $city->id }}">{{ $city->name }}</option>
        @endforeach
    </select>

    <input type="number" name="temperature" class="form-control w-auto" placeholder="Temperatura" required>
    <select name="weather_type" class="form-select form-select-sm w-auto" required>
        <option value="sunny">Sunny</option>
        <option value="rainy">Rainy</option>
        <option value="snowy">Snowy</option>
    </select>
    <input type="number" name="probability" class="form-control w-auto" placeholder="Sansa padavina" required>
    <input type="date" name="forecast_date" class="form-control w-auto" required>

    <button type="submit" class="btn btn-success">Snimi</button>
</form>
@foreach ($allCities as $city)
    <h5>{{ $city->name }}</h5>
    <ul>
        @foreach ($city->forecasts as $forecast)
            <li>{{ $forecast->forecast_date }} - {{ $forecast->temperature }}</li>
        @endforeach
    </ul>
@endforeach
