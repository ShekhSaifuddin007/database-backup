## Database Backup for Laravel

- clone this repo and copy the classes from `app/Console/Commands` folder

### Note if you are using laravel version 8 then don't need the class from

- `app/Console/Commands/ScheduleWork` laravel has by default this command.

- Then copy the class from inside `app/Managers` folder

- Then copy the file from `config/dbbackup`

## Optional thing

- If want to show the db backup `.zip` in the view then copy the controller from `app/Http/Controllers/DatabaseBackupController` and create view however you want.

- run the command `php artisan storage:link`

- Then copy the file from `bootstrap/custom.php` for global function and for available those functions inside `composer.json` add this

```php
"autoload": {
    "files": [
        "bootstrap/custom.php"
    ]
}
```

- and run `composer dump-autoload` you are good to go.