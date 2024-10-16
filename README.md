# Simple XML to Array Converter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/php-junior/laravel-xml.svg?style=flat-square)](https://packagist.org/packages/php-junior/laravel-xml)
[![Total Downloads](https://img.shields.io/packagist/dt/php-junior/laravel-xml.svg?style=flat-square)](https://packagist.org/packages/php-junior/laravel-xml)

This package is a simple XML to Array converter for Laravel.

## Installation

You can install the package via composer:

```bash
composer require php-junior/laravel-xml
```

## Usage
default xml root tag is `root`
```php
use Illuminate\Support\Arr;

$data = [
    'name' => 'John Doe',
    'description' => 'This is a <strong>description</strong>',
    'bio01' => 'This is a <strong>bio01</strong>',
    'bio02' => 'This is a <strong>bio02</strong>',
    'bio03' => 'This is a <strong>bio03</strong>',
    'bio04' => 'This is a <strong>bio04</strong>',
];

$xml = Arr::toXml($data);
<root>
    <name>John Doe</name>
    ...
</root>

// change root tag
$xml = Arr::toXml($data, 'user');
<user>
    <name>John Doe</name>
    ...
</user>

// cdata tag
$xml = Arr::toXml($data, 'user', ['description']);
<user>
    <name>John Doe</name>
    <description><![CDATA[This is a <strong>description</strong>]]></description>
    ...
</user>

// cdata wildcard tag
$xml = Arr::toXml($data, 'user', ['bio*']);
<user>
    <name>John Doe</name>
    <bio01><![CDATA[This is a <strong>bio01</strong>]]></bio01>
    <bio02><![CDATA[This is a <strong>bio02</strong>]]></bio02>
    <bio03><![CDATA[This is a <strong>bio03</strong>]]></bio03>
    <bio04><![CDATA[This is a <strong>bio04</strong>]]></bio04>
</user>

// multi-dimensional array
Arr::toXml([
    'user' => [
        [
            'name' => 'User',
            'email' => 'user@user.com'
        ],
        [
            'name' => 'User 2',
            'email' => 'user2@user.com'
        ],
    ]
], 'users');
<users>
    <user>
        <name>User</name>
        <email>user@user.com</email>
    </user>
    <user>
        <name>User 2</name>
        <email>user2@user.com</email>
    </user>
</users>

// xml to array
$data = Arr::fromXml($xml);
[
    'name' => 'John Doe',
    'description' => 'This is a <strong>description</strong>',
    'bio01' => 'This is a <strong>bio01</strong>',
    'bio02' => 'This is a <strong>bio02</strong>',
    'bio03' => 'This is a <strong>bio03</strong>',
    'bio04' => 'This is a <strong>bio04</strong>',
]
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email nyinyilwin1992@hotmail.com instead of using the issue tracker.

## Credits

-   [Nyi Nyi Lwin](https://github.com/PHPJunior)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.