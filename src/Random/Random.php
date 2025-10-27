<?php

declare(strict_types=1);

namespace Random;

use Exception;

/**
 * Clase Random para generar valores aleatorios de diferentes tipos de datos.
 */
class Random
{
    private const ALPHANUMERIC = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const SPECIAL_CHARS = '!@#$%^&*()-_+=<>,.?/[]{}|';
    private const SPANISH_CHARS = 'áéíóúüñÁÉÍÓÚÜÑ';

    /**
     * Genera un número entero aleatorio dentro del rango especificado.
     *
     * @param int $min El valor mínimo (incluido).
     * @param int $max El valor máximo (incluido).
     * @return int El número entero aleatorio generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function int(int $min = 0, int $max = PHP_INT_MAX): int
    {
        return random_int($min, $max);
    }

    /**
     * Genera un número de punto flotante aleatorio dentro del rango especificado.
     *
     * @param float $min El valor mínimo (incluido).
     * @param float $max El valor máximo (incluido).
     * @return float El número de punto flotante aleatorio generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function float(float $min = 0, float $max = 1): float
    {
        return $min + (random_int(0, PHP_INT_MAX) / PHP_INT_MAX) * ($max - $min);
    }

    /**
     * Genera una cadena aleatoria de longitud especificada.
     *
     * @param int $length La longitud de la cadena aleatoria.
     * @return string La cadena aleatoria generada.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function string(int $length = 10): string
    {
        if ($length < 1) {
            throw new Exception('Length must be greater than 0');
        }

        $characters = self::ALPHANUMERIC . self::SPANISH_CHARS . self::SPECIAL_CHARS;
        $charactersLength = mb_strlen($characters, 'UTF-8');
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= mb_substr($characters, random_int(0, $charactersLength - 1), 1, 'UTF-8');
        }
        
        return $randomString;
    }

    /**
     * Genera un valor booleano aleatorio.
     *
     * @return bool El valor booleano aleatorio generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function boolean(): bool
    {
        return (bool) random_int(0, 1);
    }

    /**
     * Genera un array de longitud especificada, donde cada elemento es generado por la función $valueGenerator.
     * Si no se proporciona $valueGenerator, se generan valores enteros aleatorios por defecto.
     *
     * @param int $length La longitud del array.
     * @param callable|null $valueGenerator La función para generar los valores del array.
     * @return array El array generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function array(int $length = 5, ?callable $valueGenerator = null): array
    {
        if ($length < 0) {
            throw new Exception('Length must be greater than or equal to 0');
        }

        $array = [];
        for ($i = 0; $i < $length; $i++) {
            $array[] = $valueGenerator ? $valueGenerator() : self::int();
        }
        return $array;
    }

    /**
     * Genera un token aleatorio basado en la cantidad de bytes especificada.
     *
     * @param int $length La longitud del token en bytes.
     * @return string El token aleatorio generado.
     * @throws Exception Si la generación de bytes aleatorios falla.
     */
    public static function token(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Genera un valor aleatorio de una lista de opciones proporcionada.
     *
     * @param array $options La lista de opciones.
     * @return mixed El valor aleatorio seleccionado de la lista.
     * @throws Exception Si falla la generación de números aleatorios o el array está vacío.
     */
    public static function fromArray(array $options): mixed
    {
        if (empty($options)) {
            throw new Exception('Options array cannot be empty');
        }

        $index = random_int(0, count($options) - 1);
        return $options[$index];
    }

    /**
     * Selecciona un valor aleatorio de un array con pesos especificados.
     *
     * @param array $options Array de opciones.
     * @param array $weights Array de pesos (deben sumar un valor positivo).
     * @return mixed El valor seleccionado.
     * @throws Exception Si los arrays no tienen la misma longitud o los pesos son inválidos.
     */
    public static function weightedPick(array $options, array $weights): mixed
    {
        if (count($options) !== count($weights)) {
            throw new Exception('Options and weights arrays must have the same length');
        }

        $totalWeight = array_sum($weights);
        if ($totalWeight <= 0) {
            throw new Exception('Total weight must be greater than 0');
        }

        $random = self::float(0, $totalWeight);
        $currentWeight = 0;

        foreach ($options as $index => $option) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $option;
            }
        }

        return end($options);
    }

    /**
     * Genera un token aleatorio basado en la cantidad de bytes especificada, en formato Base64.
     *
     * @param int $length La longitud del token en bytes.
     * @return string El token aleatorio generado en formato Base64.
     * @throws Exception Si la generación de bytes aleatorios falla.
     */
    public static function base64Token(int $length = 32): string
    {
        return base64_encode(random_bytes($length));
    }

    /**
     * Genera una contraseña segura con cierta longitud y complejidad requerida.
     *
     * @param int $length La longitud de la contraseña.
     * @param bool $includeSpecialChars Indica si se deben incluir caracteres especiales (por defecto, true).
     * @return string La contraseña segura generada.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function password(int $length = 10, bool $includeSpecialChars = true): string
    {
        if ($length < 1) {
            throw new Exception('Password length must be greater than 0');
        }

        $characters = self::ALPHANUMERIC;
        if ($includeSpecialChars) {
            $characters .= self::SPECIAL_CHARS;
        }
        
        $charactersLength = strlen($characters);
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $charactersLength - 1)];
        }
        
        return $password;
    }

    /**
     * Genera una fecha aleatoria dentro del rango especificado.
     *
     * @param string $startDate La fecha de inicio del rango (en formato 'Y-m-d').
     * @param string $endDate La fecha de fin del rango (en formato 'Y-m-d').
     * @return string La fecha aleatoria generada (en formato 'Y-m-d').
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function date(string $startDate = '1970-01-01', string $endDate = 'now'): string
    {
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        $randomTimestamp = random_int($startTimestamp, $endTimestamp);
        return date('Y-m-d', $randomTimestamp);
    }

    /**
     * Genera un color aleatorio en formato hexadecimal (#RRGGBB).
     *
     * @return string El color aleatorio generado en formato hexadecimal.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function color(): string
    {
        return '#' . self::hexColor();
    }

    /**
     * Genera un color aleatorio en formato hexadecimal sin el prefijo #.
     *
     * @return string El color aleatorio generado en formato hexadecimal (RRGGBB).
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function hexColor(): string
    {
        $red = str_pad(dechex(random_int(0, 255)), 2, '0', STR_PAD_LEFT);
        $green = str_pad(dechex(random_int(0, 255)), 2, '0', STR_PAD_LEFT);
        $blue = str_pad(dechex(random_int(0, 255)), 2, '0', STR_PAD_LEFT);

        return $red . $green . $blue;
    }

    /**
     * Genera un código de verificación aleatorio.
     *
     * @param int $length La longitud del código de verificación.
     * @param bool $numeric Indica si el código debe contener solo dígitos (por defecto, false).
     * @return string El código de verificación generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function verificationCode(int $length = 6, bool $numeric = false): string
    {
        if ($length < 1) {
            throw new Exception('Verification code length must be greater than 0');
        }

        $characters = $numeric ? '0123456789' : self::ALPHANUMERIC;
        $charactersLength = strlen($characters);
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, $charactersLength - 1)];
        }
        
        return $code;
    }

    /**
     * Genera una dirección de correo electrónico aleatoria.
     *
     * @param string $domain El dominio de correo electrónico (por defecto, 'example.com').
     * @return string La dirección de correo electrónico generada.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function email(string $domain = 'example.com'): string
    {
        $usernameLength = random_int(5, 10);
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $username = '';
        
        for ($i = 0; $i < $usernameLength; $i++) {
            $username .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $username . '@' . $domain;
    }

    /**
     * Genera una dirección IP aleatoria válida.
     *
     * @return string La dirección IP aleatoria generada.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function ipAddress(): string
    {
        return random_int(1, 255) . '.' . random_int(0, 255) . '.' . random_int(0, 255) . '.' . random_int(1, 254);
    }

    /**
     * Genera una dirección MAC aleatoria válida.
     *
     * @return string La dirección MAC aleatoria generada.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function macAddress(): string
    {
        $mac = '';
        for ($i = 0; $i < 6; $i++) {
            $mac .= sprintf('%02x', random_int(0, 255));
            if ($i < 5) {
                $mac .= ':';
            }
        }
        return $mac;
    }

    /**
     * Genera un nombre de dominio aleatorio.
     *
     * @param int $length La longitud del nombre de dominio (por defecto, 10).
     * @return string El nombre de dominio aleatorio generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function domainName(int $length = 10): string
    {
        if ($length < 1) {
            throw new Exception('Domain name length must be greater than 0');
        }

        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $domain = '';
        
        for ($i = 0; $i < $length; $i++) {
            $domain .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $domain . '.com';
    }

    /**
     * Genera una URL aleatoria.
     *
     * @param int $length La longitud de la parte del path de la URL (por defecto, 10).
     * @return string La URL aleatoria generada.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function url(int $length = 10): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789-';
        $path = '';
        
        for ($i = 0; $i < $length; $i++) {
            $path .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return 'https://www.example.com/' . $path;
    }

    /**
     * Genera un slug aleatorio tipo URL-friendly.
     *
     * @param int $words Número de palabras en el slug (por defecto, 3).
     * @return string El slug generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function slug(int $words = 3): string
    {
        if ($words < 1) {
            throw new Exception('Slug must have at least 1 word');
        }

        $wordList = ['random', 'test', 'example', 'sample', 'demo', 'mock', 'fake', 'dummy', 'placeholder', 'temporary'];
        $slug = [];
        
        for ($i = 0; $i < $words; $i++) {
            $slug[] = self::fromArray($wordList);
        }
        
        return implode('-', $slug) . '-' . random_int(100, 999);
    }

    /**
     * Genera un UUID v4 aleatorio.
     *
     * @return string El UUID v4 generado.
     * @throws Exception Si la generación de bytes aleatorios falla.
     */
    public static function uuid(): string
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Genera una latitud aleatoria.
     *
     * @return float Latitud entre -90 y 90.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function latitude(): float
    {
        return self::float(-90, 90);
    }

    /**
     * Genera una longitud aleatoria.
     *
     * @return float Longitud entre -180 y 180.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function longitude(): float
    {
        return self::float(-180, 180);
    }

    /**
     * Genera coordenadas GPS aleatorias.
     *
     * @return array Array con 'latitude' y 'longitude'.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function coordinates(): array
    {
        return [
            'latitude' => self::latitude(),
            'longitude' => self::longitude()
        ];
    }

    /**
     * Genera un User Agent aleatorio común.
     *
     * @return string User Agent generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function userAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPad; CPU OS 17_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Mobile/15E148 Safari/604.1',
        ];

        return self::fromArray($userAgents);
    }

    /**
     * Genera un tamaño de archivo aleatorio en formato human-readable.
     *
     * @param int $minBytes Tamaño mínimo en bytes (por defecto, 1KB).
     * @param int $maxBytes Tamaño máximo en bytes (por defecto, 1GB).
     * @return string Tamaño formateado (ej: "1.5 MB").
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function fileSize(int $minBytes = 1024, int $maxBytes = 1073741824): string
    {
        $bytes = random_int($minBytes, $maxBytes);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $power = min($power, count($units) - 1);
        
        $size = $bytes / pow(1024, $power);
        return round($size, 2) . ' ' . $units[$power];
    }

    /**
     * Mezcla un array de forma criptográficamente segura.
     *
     * @param array $array Array a mezclar.
     * @return array Array mezclado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function shuffle(array $array): array
    {
        $count = count($array);
        $keys = array_keys($array);
        
        for ($i = $count - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$keys[$i], $keys[$j]] = [$keys[$j], $keys[$i]];
        }
        
        $shuffled = [];
        foreach ($keys as $key) {
            $shuffled[$key] = $array[$key];
        }
        
        return $shuffled;
    }

    /**
     * Selecciona un caso aleatorio de un enum (PHP 8.1+).
     *
     * @param string $enumClass Nombre completo de la clase enum.
     * @return mixed Caso del enum seleccionado.
     * @throws Exception Si la clase no es un enum o falla la generación.
     */
    public static function enum(string $enumClass): mixed
    {
        if (!enum_exists($enumClass)) {
            throw new Exception("Class {$enumClass} is not an enum");
        }

        $cases = $enumClass::cases();
        return self::fromArray($cases);
    }

    /**
     * Genera un nombre completo aleatorio (nombre y apellido) en español o inglés.
     *
     * @param bool $spanish Indica si se debe generar en español (true) o en inglés (false).
     * @return string El nombre completo aleatorio generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function fullName(bool $spanish = true): string
    {
        $maleFirstNamesES = ['Juan', 'Antonio', 'José', 'Manuel', 'Francisco', 'David', 'José Antonio', 'José Manuel', 'Javier', 'Francisco Javier'];
        $femaleFirstNamesES = ['María', 'Carmen', 'Ana', 'Isabel', 'Laura', 'María Carmen', 'María Teresa', 'Ana María', 'María José', 'Cristina'];
        $lastNamesES = ['García', 'González', 'Rodríguez', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Gómez', 'Martín'];
        $maleFirstNamesEN = ['John', 'Michael', 'James', 'David', 'Daniel', 'Christopher', 'Joseph', 'Robert', 'William', 'Richard'];
        $femaleFirstNamesEN = ['Mary', 'Jennifer', 'Linda', 'Patricia', 'Elizabeth', 'Susan', 'Jessica', 'Sarah', 'Karen', 'Nancy'];
        $lastNamesEN = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia', 'Rodriguez', 'Martinez'];

        if ($spanish) {
            $firstName = random_int(0, 1) ? self::fromArray($maleFirstNamesES) : self::fromArray($femaleFirstNamesES);
            $lastName1 = self::fromArray($lastNamesES);
            $lastName2 = self::fromArray($lastNamesES);
        } else {
            $firstName = random_int(0, 1) ? self::fromArray($maleFirstNamesEN) : self::fromArray($femaleFirstNamesEN);
            $lastName1 = self::fromArray($lastNamesEN);
            $lastName2 = self::fromArray($lastNamesEN);
        }
        
        return $firstName . ' ' . $lastName1 . ' ' . $lastName2;
    }

    /**
     * Genera una dirección postal en español o inglés.
     *
     * @param bool $spanish Indica si se debe generar en español (true) o en inglés (false).
     * @return string La dirección postal generada.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function postalAddress(bool $spanish = true): string
    {
        $streetsES = ['Calle Mayor', 'Avenida de la Constitución', 'Calle Real', 'Plaza España', 'Paseo de la Castellana', 'Avenida del Libertador', 'Avenida de Andalucía', 'Calle Gran Vía', 'Avenida Diagonal', 'Paseo del Prado'];
        $citiesES = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Palma', 'Las Palmas de Gran Canaria', 'Bilbao'];
        $provincesES = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Baleares', 'Las Palmas', 'Vizcaya'];
        $zipCodesES = ['28001', '08001', '46001', '41001', '50001', '29001', '30001', '07001', '35001', '48001'];
        $streetsEN = ['Main St', 'First Ave', 'Elm St', 'Maple Ave', 'Oak St', 'Park Ave', 'Pine St', 'Cedar Ave', 'Walnut St', 'Willow Ave'];
        $citiesEN = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'];
        $provincesEN = ['NY', 'CA', 'IL', 'TX', 'AZ', 'PA', 'TX', 'CA', 'TX', 'CA'];
        $zipCodesEN = ['10001', '90001', '60601', '77001', '85001', '19101', '78201', '92101', '75201', '95101'];

        if ($spanish) {
            $street = self::fromArray($streetsES);
            $city = self::fromArray($citiesES);
            $province = self::fromArray($provincesES);
            $zipCode = self::fromArray($zipCodesES);
        } else {
            $street = self::fromArray($streetsEN);
            $city = self::fromArray($citiesEN);
            $province = self::fromArray($provincesEN);
            $zipCode = self::fromArray($zipCodesEN);
        }
        
        return $street . ', ' . $zipCode . ', ' . $city . ', ' . $province;
    }

    /**
     * Genera un número de teléfono aleatorio con formato nacional español o inglés.
     *
     * @param bool $spanish Indica si se debe generar en español (true) o en inglés (false).
     * @return string El número de teléfono aleatorio generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function phoneNumber(bool $spanish = true): string
    {
        if ($spanish) {
            return '6' . random_int(0, 9) . ' ' . sprintf('%03d', random_int(0, 999)) . ' ' . sprintf('%02d', random_int(0, 99)) . ' ' . sprintf('%02d', random_int(0, 99));
        } else {
            return '555-' . sprintf('%03d', random_int(0, 999)) . '-' . sprintf('%04d', random_int(0, 9999));
        }
    }

    /**
     * Genera un número de teléfono aleatorio con formato internacional español o inglés.
     *
     * @param bool $spanish Indica si se debe generar en español (true) o en inglés (false).
     * @return string El número de teléfono aleatorio generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function internationalPhoneNumber(bool $spanish = true): string
    {
        if ($spanish) {
            return '+34 ' . random_int(6, 7) . random_int(0, 9) . ' ' . sprintf('%03d', random_int(0, 999)) . ' ' . sprintf('%02d', random_int(0, 99)) . ' ' . sprintf('%02d', random_int(0, 99));
        } else {
            return '+1-555-' . sprintf('%03d', random_int(0, 999)) . '-' . sprintf('%04d', random_int(0, 9999));
        }
    }

    /**
     * Genera una fecha y hora aleatoria dentro de un rango específico.
     *
     * @param string $startDate La fecha de inicio del rango (en formato 'Y-m-d H:i:s').
     * @param string $endDate La fecha de fin del rango (en formato 'Y-m-d H:i:s').
     * @return string La fecha y hora aleatoria generada (en formato 'Y-m-d H:i:s').
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function dateTime(string $startDate = '1970-01-01 00:00:00', string $endDate = 'now'): string
    {
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        $randomTimestamp = random_int($startTimestamp, $endTimestamp);
        return date('Y-m-d H:i:s', $randomTimestamp);
    }

    /**
     * Genera un número de tarjeta de crédito válido para pruebas.
     *
     * @return string El número de tarjeta de crédito generado.
     * @throws Exception Si falla la generación de números aleatorios.
     */
    public static function creditCardNumber(): string
    {
        $prefixes = ['4', '51', '52', '53', '54', '55'];
        $prefix = self::fromArray($prefixes);
        $length = 16;
        $number = $prefix;
        
        while (strlen($number) < $length - 1) {
            $number .= random_int(0, 9);
        }
        
        $checksum = 0;
        $isEven = true;
        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = intval($number[$i]);
            if ($isEven) {
                $digit *= 2;
                $digit = ($digit > 9) ? $digit - 9 : $digit;
            }
            $checksum += $digit;
            $isEven = !$isEven;
        }
        $checksum %= 10;
        $checksum = ($checksum == 0) ? 0 : 10 - $checksum;
        $number .= $checksum;
        
        return $number;
    }

    /**
     * Genera un nombre de usuario aleatorio combinando palabras y adjetivos.
     *
     * @param int|string|null $wordCount Número de palabras a combinar, palabra específica, o null.
     * @param int|string|null $adjectiveCount Número de adjetivos a combinar, adjetivo específico, o null.
     * @return string El nombre de usuario aleatorio generado.
     * @throws Exception Si falla la generación de números aleatorios o no se puede cargar el archivo de palabras.
     */
    public static function username(int|string|null $wordCount = 1, int|string|null $adjectiveCount = 1): string
    {
        static $wordData = null;

        if ($wordData === null) {
            $dataFile = __DIR__ . '/data/username_words.php';
            
            if (!file_exists($dataFile)) {
                throw new Exception("Username words data file not found at: {$dataFile}");
            }

            $wordData = require $dataFile;

            if (!isset($wordData['words']) || !isset($wordData['adjectives'])) {
                throw new Exception("Invalid username words data structure");
            }
        }

        $parts = [];

        // Procesar palabras
        if (is_string($wordCount)) {
            // Usuario proporcionó una palabra específica
            $parts[] = $wordCount;
        } elseif (is_int($wordCount)) {
            if ($wordCount < 0) {
                throw new Exception('Word count must be non-negative');
            }
            for ($i = 0; $i < $wordCount; $i++) {
                $parts[] = self::fromArray($wordData['words']);
            }
        }

        // Procesar adjetivos
        if (is_string($adjectiveCount)) {
            // Usuario proporcionó un adjetivo específico
            $parts[] = $adjectiveCount;
        } elseif (is_int($adjectiveCount)) {
            if ($adjectiveCount < 0) {
                throw new Exception('Adjective count must be non-negative');
            }
            for ($i = 0; $i < $adjectiveCount; $i++) {
                $parts[] = self::fromArray($wordData['adjectives']);
            }
        }

        if (empty($parts)) {
            throw new Exception('Username must have at least one word or adjective');
        }

        return implode('', $parts);
    }
}