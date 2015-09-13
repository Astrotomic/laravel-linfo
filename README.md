# laravel-linfo
This is a Laravel 5 Wrapper for the linfo package from jrgp - https://github.com/jrgp/linfo

```json
{
    "require": {
        "linfo/laravel": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Gummibeer/laravel-linfo"
        }
    ]
}
```

```php
Gummibeer\Linfo\LinfoServiceProvider::class,
```

```php
new \Gummibeer\Linfo\Models\Linfo()
```

Pleas look at the demo site for examples, usage and installation instructions. - [http://linfo.gummibeer.de/](http://linfo.gummibeer.de/)