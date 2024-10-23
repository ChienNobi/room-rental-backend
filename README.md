## Setup
- Update version of composer to 2

```
  sudo composer self-update --2 // update
  composer self-update --rollback // rollback
```

- Run migrate && install passport

```
  php artisan migrate
  php artisan passport:install
```

- Generate app key

```
  php artisan key:generate
```

- Generate migration

```
    php artisan make:migration <name>
```

- Generate mail content

```
    php artisan make:mail <name>
```
