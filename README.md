# Encoder

[![Build Status](https://travis-ci.org/nbish11/Encoder.svg)](https://travis-ci.org/nbish11/Encoder)

## Table of Contents
* [About](#about)
* [Getting Started](#getting-started)
* [Usage](#usage)
* [API](#api)
* [Testing](#testing)
* [Contributors](#contributors)
* [License](#license)

## <a name="about"></a>About
**Encoder** is a class that allows encoding/decoding from/to:
- Base64
- Quoted Printable
- 7bit (Future Feature) 
- 8bit (Future Feature)
- Binary

## <a name="getting-started"></a>Getting Started
1. PHP 5.3.x is required
2. Install manually

## <a name="usage"></a>Usage
Include and instantiate:

```php
require 'path\to\Encoder.php';

$encoder = new Encoder;
```

Convert text to base64:

```php
$encoder->encode('Hëllo World!', $encoder::BASE_64); // returns 'SOtsbG8gV29ybGQh'
```

Convert text to quoted-printable:

```php
$encoder->encode('Hëllo World!', $encoder::QUOTED_PRINTABLE); // returns 'H=EBllo World!'
```

Decode quoted-printable text:

```php
$encoder->decode('=E1=E9=ED=F3=FA', $encoder::QUOTED_PRINTABLE); // returns 'áéíóú'
```

## <a name="api"></a>API

Below is a list of the public methods available for use.

```php
$encoder->
    encode($input, $scheme, $length = 75)   // Encode text to encoding scheme.
    decode($input, $scheme)                 // Decode text from encoded text.
```

## <a name="testing"></a>Testing
This project keeps all tests stored in the `/tests` folder
and uses the [PHPUnit](https://github.com/sebastianbergmann/phpunit/) 
testing framework.

## <a name="contributors"></a>Contributors


## <a name="license"></a>License

(GNU General Public License, version 2)

Copyright &copy; 2013, [Nathan Bishop](nbish11@hotmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see [GNU General Public License, version 2](http://www.gnu.org/licenses/).