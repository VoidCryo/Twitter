<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) && $title ? 'Tenebris | ' . $title : 'Tenebris' }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/bootstrap.js'])
</head>
<body class="h-100">
<div class="d-flex vh-100">
    <aside class="d-none d-md-flex flex-column justify-content-center p-5 bg-dark text-white flex-shrink-0" style="width: 420px;">

    </aside>
    <main class="w-100 d-flex align-items-center justify-content-center p-4">
        <div class="w-100" style="max-width: 400px;">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>

