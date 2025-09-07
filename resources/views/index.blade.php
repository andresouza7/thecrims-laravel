<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>cash: {{$user->cash}}</div>
    <div>bank: {{$user->bank}}</div>
    <div>in jail: {{$user->in_jail ? 'sim' : 'nao'}}</div>
    <div>jail end time: {{$user->jail_end_time}}</div>

    <div>stats</div>
    <div>health: {{$user->health}}</div>
    <div>max_health: {{$user->max_health}}</div>
    <div>stamina: {{$user->stamina}}</div>
    <div>strength: {{$user->strength}}</div>
    <div>intelligence: {{$user->intelligence}}</div>
    <div>charisma: {{$user->charisma}}</div>
    <div>tolerance: {{$user->tolerance}}</div>
    <div>single_robbery_power: {{$user->single_robbery_power}}</div>
    <div>gang_robbery_power: {{$user->gang_robbery_power}}</div>
    <div>assault_power: {{$user->assault_power}}</div>
    <div>respect: {{$user->respect}}</div>
    
    @foreach ($user->hookers as $hooker)
        <div>{{ $hooker->name }}, amount: {{ $hooker->pivot->amount }}</div>
    @endforeach
    @foreach ($user->drugs as $drug)
        <div>{{ $drug->name }}, amount: {{ $drug->pivot->amount }}</div>
    @endforeach
    @foreach ($user->components as $component)
        <div>{{ $component->name }}, amount: {{ $component->pivot->amount }}</div>
    @endforeach
    @foreach ($user->factories as $factory)
        <div>{{ $factory->name }}, level: {{ $factory->pivot->level }}</div>
        <img src="{{ $factory->avatar }}" alt="avatar">
    @endforeach
</body>
</html>