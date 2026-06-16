<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            @include('admin.partials.navbar')

            <!-- Main Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>