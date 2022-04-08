# Blogging package

This package enables you to easily implement blogging/content creation features in your applications.


## Installation

You can install the package via composer.

First, add the repository to your composer.json
```json
"repositories": [
	{
		"type": "vcs",
		"url": "https://github.com/Detosphere-Ltd/blogging-package"
	}
]
```
Then...
```bash
composer require :vendor_slug/:package_slug
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="DetosphereLtd\BlogPackage\BlogPackageServiceProvider" --tag="blogging-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="DetosphereLtd\BlogPackage\BlogPackageServiceProvider" --tag="blogging-config"
```

Now you can also publish the file that validates the Editor.js request object.
```bash
php artisan vendor:publish --provider="DetosphereLtd\BlogPackage\BlogPackageServiceProvider" --tag="blogging-files"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

In your user model, or any other model that may be allowed to author posts, simply import the **HasPosts** trait.
```php
use Detosphere\BlogPackage\Traits\HasPosts;

class Admin extends Model
{
	use HasPosts;
	
	...
}
```
Now you can access the posts of the Admin user via `$admin->posts`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [:author_name](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.