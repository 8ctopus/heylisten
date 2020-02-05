# Hey,Listen!

> Hey,Listen! is an app that lets you collect ideas and feedback from your customers and prioritize your roadmap. Clients like the ideas so you can see which features the majority of them want!


## Features

- Nuxt 2.11
- Laravel 6
- SPA or SSR
- VueI18n + ESlint + Bootstrap 4 + Font Awesome 5

## Installation
- Copy `.env.example` to `.env`
- Edit `.env` to set your database connection details and `APP_URL` (the url to your Laravel application)
- `composer install`
- Run `php artisan key:generate` and `php artisan jwt:secret`)
- `php artisan migrate`
- `npm install`

## Usage

### Development

```bash
npm run dev
php artisan serve
```

You can access your application at `http://localhost:3000`.

### Production

```bash
npm run build
```

You can access your application the url you set `APP_URL` to.

### Enable SSR

- Remove `mode: 'spa'` and `'~plugins/nuxt-client-init'` from `client/nuxt.config.js`
- Edit `.env` to set `APP_URL=http://api.example.com` and `CLIENT_URL=http://example.com`
- Run `npm run build` and `npm run start`

#### Nginx Proxy

For Nginx you can add a proxy using the follwing location block:

```
server {
    location / {
        proxy_pass http://http://127.0.0.1:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

#### Process Manager

In production you need a process manager to keep the Node server alive forever:

```bash
# install pm2 process manager
npm install -g pm2

# startup script
pm2 startup

# start process
pm2 start npm --name "laravel-nuxt" -- run start

# save process list
pm2 save

# list all processes
pm2 l
```

After each deploy you'll need to restart the process:

```bash
pm2 restart laravel-nuxt
```

Make sure to read the [Nuxt docs](https://nuxtjs.org/).

## Notes

- This project based on [cretueusebiu / laravel-nuxt](https://github.com/cretueusebiu/laravel-nuxt).