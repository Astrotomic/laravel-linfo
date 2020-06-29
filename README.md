# Laravel Linfo

[![Latest Version](http://img.shields.io/packagist/v/linfo/laravel.svg?label=Release&style=for-the-badge)](https://packagist.org/packages/linfo/laravel)
[![MIT License](https://img.shields.io/github/license/Astrotomic/laravel-linfo.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/laravel-linfo/blob/master/LICENSE.md)
[![Offset Earth](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-green?style=for-the-badge)](https://plant.treeware.earth/Astrotomic/laravel-linfo)

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/laravel-linfo/run-tests?style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/Astrotomic/laravel-linfo/actions?query=workflow%3Arun-tests)
[![StyleCI](https://styleci.io/repos/42302702/shield)](https://styleci.io/repos/42302702)
[![Total Downloads](https://img.shields.io/packagist/dt/linfo/laravel.svg?label=Downloads&style=flat-square)](https://packagist.org/packages/linfo/laravel)

This is a Laravel 5 Wrapper for the linfo package from jrgp - https://github.com/jrgp/linfo

## Installation

```console
composer require linfo/laravel
```

Publish config file `linfo.php` by running this command.

```console
php artisan vendor:publish --provider="Linfo\Laravel\LinfoServiceProvider"
```

## Usage

You can use the function like this.

```php
use Linfo\Laravel\Linfo;

$linfo = new Linfo();

$os = $linfo->os; // string
$kernel = $linfo->kernel; // string
$model = $linfo->model; // string
$ram = $linfo->ram; // array
$cpu = $linfo->cpu; // array
$arc = $linfo->cpuarchitecture; // string

```

You can see other data using `dump($linfo)` or `var_dump($linfo)`

## Testing

```bash
composer test
```
## Contributing

Please see [CONTRIBUTING](https://github.com/Astrotomic/.github/blob/master/CONTRIBUTING.md) for details. You could also be interested in [CODE OF CONDUCT](https://github.com/Astrotomic/.github/blob/master/CODE_OF_CONDUCT.md).

### Security

If you discover any security related issues, please check [SECURITY](https://github.com/Astrotomic/.github/blob/master/SECURITY.md) for steps to report it.

## Credits

-   [Tom Witkowski](https://github.com/Gummibeer)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

You're free to use this package, but if it makes it to your production environment I would highly appreciate you buying the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to [plant trees](https://www.bbc.co.uk/news/science-environment-48870920). If you contribute to my forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees at [offset.earth/treeware](https://plant.treeware.earth/Astrotomic/laravel-linfo)

Read more about Treeware at [treeware.earth](https://treeware.earth)
