# PHP Random

Librería PHP para generar valores aleatorios de diferentes tipos de datos de forma criptográficamente segura.

## Requisitos

- PHP >= 8.1
- ext-mbstring

## Instalación

```bash
composer require jesus-ag28/php-random
```

## Uso Básico

```php
<?php

use Random\Random;

// Generar un número entero aleatorio
$numero = Random::int(1, 100);

// Generar una cadena aleatoria
$cadena = Random::string(20);

// Generar un booleano aleatorio
$bool = Random::boolean();
```

## Métodos Disponibles

### Números

#### `int(int $min = 0, int $max = PHP_INT_MAX): int`
Genera un número entero aleatorio dentro del rango especificado.

```php
Random::int(1, 100); // Número entre 1 y 100
Random::int(); // Número entre 0 y PHP_INT_MAX
```

#### `float(float $min = 0, float $max = 1): float`
Genera un número de punto flotante aleatorio.

```php
Random::float(0, 1); // Float entre 0 y 1
Random::float(10.5, 20.5); // Float entre 10.5 y 20.5
```

### Cadenas y Texto

#### `string(int $length = 10): string`
Genera una cadena aleatoria con caracteres alfanuméricos, especiales y españoles.

```php
Random::string(15); // "aBc123!@ñÑ"
```

#### `password(int $length = 10, bool $includeSpecialChars = true): string`
Genera una contraseña segura.

```php
Random::password(16); // "aB3!xY9@mN5#qW2$"
Random::password(12, false); // "aBc123XyZ789"
```

#### `verificationCode(int $length = 6, bool $numeric = false): string`
Genera un código de verificación.

```php
Random::verificationCode(6); // "aB3xY9"
Random::verificationCode(6, true); // "123456"
```

#### `slug(int $words = 3): string`
Genera un slug URL-friendly.

```php
Random::slug(3); // "random-test-example-456"
```

### Tokens y UUIDs

#### `token(int $length = 32): string`
Genera un token hexadecimal.

```php
Random::token(32); // "a1b2c3d4e5f6..."
```

#### `base64Token(int $length = 32): string`
Genera un token en formato Base64.

```php
Random::base64Token(32); // "YWJjZGVm..."
```

#### `uuid(): string`
Genera un UUID v4.

```php
Random::uuid(); // "550e8400-e29b-41d4-a716-446655440000"
```

### Booleanos y Arrays

#### `boolean(): bool`
Genera un valor booleano aleatorio.

```php
Random::boolean(); // true o false
```

#### `array(int $length = 5, ?callable $valueGenerator = null): array`
Genera un array de valores aleatorios.

```php
Random::array(5); // [123, 456, 789, 101, 112]
Random::array(3, fn() => Random::string(5)); // ["aBc12", "xYz34", "mNp56"]
```

#### `fromArray(array $options): mixed`
Selecciona un elemento aleatorio de un array.

```php
Random::fromArray(['rojo', 'verde', 'azul']); // "verde"
```

#### `weightedPick(array $options, array $weights): mixed`
Selecciona un elemento con pesos especificados.

```php
Random::weightedPick(['común', 'raro', 'épico'], [70, 25, 5]); // "común" (70% probabilidad)
```

#### `shuffle(array $array): array`
Mezcla un array de forma criptográficamente segura.

```php
Random::shuffle([1, 2, 3, 4, 5]); // [3, 1, 5, 2, 4]
```

#### `enum(string $enumClass): mixed`
Selecciona un caso aleatorio de un enum (PHP 8.1+).

```php
enum Color { case Red; case Green; case Blue; }
Random::enum(Color::class); // Color::Green
```

### Fechas y Tiempo

#### `date(string $startDate = '1970-01-01', string $endDate = 'now'): string`
Genera una fecha aleatoria.

```php
Random::date('2020-01-01', '2024-12-31'); // "2022-06-15"
```

#### `dateTime(string $startDate = '1970-01-01 00:00:00', string $endDate = 'now'): string`
Genera una fecha y hora aleatoria.

```php
Random::dateTime('2023-01-01 00:00:00', '2023-12-31 23:59:59'); // "2023-06-15 14:30:45"
```

### Colores

#### `color(): string`
Genera un color hexadecimal con prefijo #.

```php
Random::color(); // "#a1b2c3"
```

#### `hexColor(): string`
Genera un color hexadecimal sin prefijo.

```php
Random::hexColor(); // "a1b2c3"
```

### Coordenadas GPS

#### `latitude(): float`
Genera una latitud aleatoria (-90 a 90).

```php
Random::latitude(); // 45.123456
```

#### `longitude(): float`
Genera una longitud aleatoria (-180 a 180).

```php
Random::longitude(); // -73.987654
```

#### `coordinates(): array`
Genera coordenadas GPS completas.

```php
Random::coordinates(); // ['latitude' => 45.123456, 'longitude' => -73.987654]
```

### Datos de Red

#### `email(string $domain = 'example.com'): string`
Genera una dirección de correo electrónico.

```php
Random::email(); // "abc123@example.com"
Random::email('midominio.com'); // "xyz789@midominio.com"
```

#### `ipAddress(): string`
Genera una dirección IP v4 válida.

```php
Random::ipAddress(); // "192.168.1.100"
```

#### `macAddress(): string`
Genera una dirección MAC válida.

```php
Random::macAddress(); // "a1:b2:c3:d4:e5:f6"
```

#### `domainName(int $length = 10): string`
Genera un nombre de dominio.

```php
Random::domainName(8); // "abcdefgh.com"
```

#### `url(int $length = 10): string`
Genera una URL aleatoria.

```php
Random::url(15); // "https://www.example.com/abc123xyz"
```

#### `userAgent(): string`
Genera un User Agent común.

```php
Random::userAgent(); // "Mozilla/5.0 (Windows NT 10.0; Win64; x64)..."
```

### Datos Personales

#### `fullName(bool $spanish = true): string`
Genera un nombre completo en español o inglés.

```php
Random::fullName(); // "Juan García López"
Random::fullName(false); // "John Smith Johnson"
```

#### `postalAddress(bool $spanish = true): string`
Genera una dirección postal.

```php
Random::postalAddress(); // "Calle Mayor, 28001, Madrid, Madrid"
Random::postalAddress(false); // "Main St, 10001, New York, NY"
```

#### `phoneNumber(bool $spanish = true): string`
Genera un número de teléfono nacional.

```php
Random::phoneNumber(); // "65 123 45 67"
Random::phoneNumber(false); // "555-123-4567"
```

#### `internationalPhoneNumber(bool $spanish = true): string`
Genera un número de teléfono internacional.

```php
Random::internationalPhoneNumber(); // "+34 65 123 45 67"
Random::internationalPhoneNumber(false); // "+1-555-123-4567"
```

#### `username(int|string|null $wordCount = 1, int|string|null $adjectiveCount = 1): string`
Genera un nombre de usuario combinando palabras y adjetivos (700+ palabras épicas, 600+ adjetivos).

```php
// Totalmente aleatorio
Random::username(); // "DragonWarrior"
Random::username(2, 1); // "ThunderPhoenixMaster"
Random::username(1, 2); // "CyberNinjaHunter"
Random::username(3, 0); // "DragonTitanStorm"
Random::username(0, 3); // "MightySwiftDark"

// Con palabra personalizada
Random::username('Jesus', 1); // "JesusWarrior"
Random::username('Maria', 2); // "MariaSwiftDark"

// Con adjetivo personalizado
Random::username(1, 'Pro'); // "DragonPro"
Random::username(2, 'Gaming'); // "ThunderPhoenixGaming"

// Ambos personalizados con aleatorios
Random::username('Dark', 1); // "DarkHunter"
Random::username(1, 'Legend'); // "PhoenixLegend"

// Solo personalizados
Random::username('Shadow', 'Master'); // "ShadowMaster"
Random::username('Cyber', 'King'); // "CyberKing"

// Mezcla compleja
Random::username('Epic', 2); // "EpicMightySwift"
Random::username(2, 'Ultimate'); // "DragonPhoenixUltimate"
```

### Datos Financieros

#### `creditCardNumber(): string`
Genera un número de tarjeta de crédito válido (solo para pruebas).

```php
Random::creditCardNumber(); // "4532123456789012"
```

### Utilidades

#### `fileSize(int $minBytes = 1024, int $maxBytes = 1073741824): string`
Genera un tamaño de archivo en formato legible.

```php
Random::fileSize(); // "15.6 MB"
Random::fileSize(1024, 1048576); // "512.3 KB"
```

## Seguridad

Todos los métodos utilizan `random_int()` y `random_bytes()`, que son criptográficamente seguros (CSPRNG). Esto hace que la librería sea adecuada para generar tokens, contraseñas y otros datos sensibles.

## Características

- ✅ Criptográficamente seguro (CSPRNG)
- ✅ Compatible con caracteres UTF-8 y españoles
- ✅ Type hints estrictos (PHP 8.1+)
- ✅ Validación de parámetros
- ✅ Soporte para enums (PHP 8.1+)
- ✅ Sin dependencias externas

## Ejemplos de Uso

### Generar datos de prueba para usuarios

```php
use Random\Random;

$usuario = [
    'nombre' => Random::fullName(),
    'email' => Random::email('miapp.com'),
    'telefono' => Random::phoneNumber(),
    'direccion' => Random::postalAddress(),
    'username' => Random::username(),
    'password' => Random::password(16),
    'uuid' => Random::uuid(),
    'fecha_registro' => Random::dateTime('2023-01-01 00:00:00'),
];
```

### Generar tokens de autenticación

```php
$token = Random::token(64);
$apiKey = Random::base64Token(32);
```

### Generar códigos de verificación

```php
$codigoSMS = Random::verificationCode(6, true); // Solo números
$codigoEmail = Random::verificationCode(8); // Alfanumérico
```

### Generar datos geográficos

```php
$coordenadas = Random::coordinates();
echo "Lat: {$coordenadas['latitude']}, Lon: {$coordenadas['longitude']}";
```

## Licencia

MIT

## Autor

Jesús Ayús - [dev@jesusayus.es](mailto:dev@jesusayus.es)

## Repositorio

[https://github.com/jesusAG28/PHPRandom](https://github.com/jesusAG28/PHPRandom)