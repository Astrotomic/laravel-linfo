# Laravel Linfo

[![GitHub Author](https://img.shields.io/badge/author-@astrotomic-orange.svg?style=flat-square)](https://github.com/Astrotomic)
[![GitHub release](https://img.shields.io/github/release/astrotomic/laravel-linfo.svg?style=flat-square)](https://github.com/Astrotomic/laravel-linfo/releases)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/Astrotomic/laravel-linfo/master/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/Astrotomic/laravel-linfo.svg?style=flat-square)](https://github.com/Astrotomic/laravel-linfo/issues)

[![Travis branch](https://img.shields.io/travis/Astrotomic/laravel-linfo/master.svg?style=flat-square)](https://travis-ci.org/Astrotomic/laravel-linfo/branches)
[![StyleCI](https://styleci.io/repos/42302702/shield)](https://styleci.io/repos/42302702)
[![Code Climate](https://img.shields.io/codeclimate/github/Astrotomic/laravel-linfo.svg?style=flat-square)](https://codeclimate.com/github/Astrotomic/laravel-linfo)
[![Code Climate](https://img.shields.io/codeclimate/coverage/github/Astrotomic/laravel-linfo.svg?style=flat-square)](https://codeclimate.com/github/Astrotomic/laravel-linfo/coverage)
[![Code Climate](https://img.shields.io/codeclimate/issues/github/Astrotomic/laravel-linfo.svg?style=flat-square)](https://codeclimate.com/github/Astrotomic/laravel-linfo/issues)

This is a Laravel 5 Wrapper for the linfo package from jrgp - https://github.com/jrgp/linfo

## Installation

Open `composer.json` and add this line below.

```json
{
    "require": {
        "linfo/laravel": "~1.1"
    }
}
```

Or you can run this command from your project directory.

```console
composer require linfo/laravel
```

## Configuration

Open the `config/app.php` and add this line in `providers` section.

```php
Linfo\Laravel\LinfoServiceProvider::class,
```

Publish config file `linfo.php` by running this command.

```console
php artisan vendor:publish --provider="Linfo\Laravel\LinfoServiceProvider"
```

## Usage

You can use the function like this.

```php
use Linfo\Laravel\Models\Linfo();

$linfo = new Linfo();

$os = $linfo->os; // string
$kernel = $linfo->kernel; // string
$model = $linfo->model; // string
$ram = $linfo->ram; // array
$cpu = $linfo->cpu; // array
$arc = $linfo->cpuarchitecture; // string

```

You can see other data using `dump($linfo)` or `var_dump($linfo)`.