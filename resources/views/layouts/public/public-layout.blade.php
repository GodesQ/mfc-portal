<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $event->title }} Registration | MFC Events</title>
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('layouts.head-css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #1e2d4e;
            --navy-soft: #31456f;
            --gold: #f5c518;
            --gold-soft: #ffe389;
            --gold-dark: #c9a000;
            --sky: #dce7fb;
            --surface: #ffffff;
            --canvas: #f5f7fc;
            --border: #dce3f0;
            --text: #21324f;
            --muted: #6d7b95;
            --success: #1d7a4d;
            --radius-lg: 24px;
            --radius-md: 18px;
            --radius-sm: 12px;
            --shadow: 0 20px 60px rgba(30, 45, 78, 0.12);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--navy);
            min-height: 100vh;
        }
    </style>
    @stack('head-styles')
</head>

<body>
    @yield('content')
    @include('layouts.footer')
    @include('layouts.vendor-scripts')
</body>
