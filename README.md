# Laravel Linfo

[![GitHub Author](https://img.shields.io/badge/author-@gummibeer-lightgrey.svg?style=flat-square)](https://github.com/Gummibeer)
[![GitHub release](https://img.shields.io/github/release/gummibeer/laravel-linfo.svg?style=flat-square)](https://github.com/Gummibeer/laravel-linfo/releases)
[![GitHub license](https://img.shields.io/badge/license-GPL3-blue.svg?style=flat-square)](https://raw.githubusercontent.com/Gummibeer/laravel-linfo/master/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/Gummibeer/laravel-linfo.svg?style=flat-square)](https://github.com/Gummibeer/laravel-linfo/issues)
[![Travis branch](https://img.shields.io/travis/Gummibeer/laravel-linfo/master.svg?style=flat-square)](https://travis-ci.org/Gummibeer/laravel-linfo/branches)
[![StyleCI](https://styleci.io/repos/58059429/shield)](https://styleci.io/repos/58059429)
[![Code Climate](https://img.shields.io/codeclimate/github/Gummibeer/laravel-linfo.svg?style=flat-square)](https://codeclimate.com/github/Gummibeer/laravel-linfo)
[![Code Climate](https://img.shields.io/codeclimate/coverage/github/Gummibeer/laravel-linfo.svg?style=flat-square)](https://codeclimate.com/github/Gummibeer/laravel-linfo/coverage)
[![Code Climate](https://img.shields.io/codeclimate/issues/github/Gummibeer/laravel-linfo.svg?style=flat-square)](https://codeclimate.com/github/Gummibeer/laravel-linfo/issues)
[![Slack Team](https://img.shields.io/badge/slack-gummibeer-orange.svg?style=flat-square)](https://gummibeer.slack.com)
[![Slack join](https://img.shields.io/badge/slack-join-green.svg?style=social)](https://gummibeer.signup.team)

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