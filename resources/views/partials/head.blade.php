<meta charset="utf-8">

<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1, user-scalable=0, initial-scale=1">

<title>{{ filled($title ?? null) ? $title : 'Laravel' }} ⋅ {{ config('app.name') }}</title>
<meta name="description" content="Access various app services with a single identity.">

<meta property="og:title" content="{{ $title ?? 'Laravel' }} ⋅ {{ config('app.name') }}" />
<meta property="og:description" content="Access various app services with a single identity." />
<meta property="og:image" content="{{ asset('favicon.png') }}" />

<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=reddit-mono:200,300,400,500,600,700,800,900|reddit-sans:200,300,400,500,600,700,800,900" rel="stylesheet" />

<script defer src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "85da2a9cc8544dd2880bab70c2ce2221"}'></script>

@vite(['resources/css/app.css', 'resources/ts/app.ts'])
