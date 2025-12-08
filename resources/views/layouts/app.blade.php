<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albion Profit Calculator</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-base-100 min-h-screen">
    <div class="container mx-auto p-4">
        @yield('content')
    </div>
    @livewireScripts
</body>

</html>
