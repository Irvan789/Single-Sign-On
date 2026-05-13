# Single Sign-On (SSO)

## About
Single Sign-On (SSO) using Laravel Fortify, Laravel Passport, and Livewire with some customization.

*This website compatible with [vercel](https://vercel.com) enviroment.

## Requirements
- PHP v8.4 or above
- Nodejs v25 or above
- Google Client ID & Client Secret
- GitHub Client ID & Client Secret
- Cloudflare Turnstile Site Key & Secret Key

## Installation

1. Copy `.env.example` and rename to `.env`

2. Generate App keys & Passport keys
```bash
php artisan key:generate && php artisan passport:keys
```

3. Configure your App name, Database, and Mail.

4. Install all dependencies.
```bash
composer install && npm ci
```

5. Building assets.
```bash
npm run build
```

6. Run preview server.
```bash
php artisan serve
```

To change server host and port, put this in your `.env`.
```
SERVER_HOST=
SERVER_PORT=
```

## Read More
[Laravel Fortify](https://laravel.com/docs/fortify)\
[Laravel Passport](https://laravel.com/docs/passport)\
[Livewire](https://livewire.laravel.com)