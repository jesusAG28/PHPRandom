<?php

declare(strict_types=1);

namespace JesusAG28;

/**
 * Clase Random para generar valores aleatorios de diferentes tipos de datos.
 */
class Random
{

    /**
     * Genera un número entero aleatorio dentro del rango especificado.
     *
     * @param int $min El valor mínimo (incluido).
     * @param int $max El valor máximo (incluido).
     * @return int El número entero aleatorio generado.
     */
    public static function int($min = 0, $max = PHP_INT_MAX)
    {
        return random_int($min, $max);
    }

    /**
     * Genera un número de punto flotante aleatorio dentro del rango especificado.
     *
     * @param float $min El valor mínimo (incluido).
     * @param float $max El valor máximo (incluido).
     * @return float El número de punto flotante aleatorio generado.
     */
    public static function float($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    /**
     * Genera una cadena aleatoria de longitud especificada.
     *
     * @param int $length La longitud de la cadena aleatoria.
     * @return string La cadena aleatoria generada.
     */
    public static function string($length = 10)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters .= 'áéíóúüñÁÉÍÓÚÜÑ'; // Caracteres del alfabeto español
        $characters .= '!@#$%^&*()-_+=<>,.?/[]{}|'; // Caracteres especiales comunes
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * Genera un valor booleano aleatorio.
     *
     * @return bool El valor booleano aleatorio generado.
     */
    public static function boolean()
    {
        return (bool) mt_rand(0, 1);
    }

    /**
     * Genera un array de longitud especificada, donde cada elemento es generado por la función $valueGenerator.
     * Si no se proporciona $valueGenerator, se generan valores enteros aleatorios por defecto.
     *
     * @param int $length La longitud del array.
     * @param callable|null $valueGenerator La función para generar los valores del array.
     * @return array El array generado.
     */
    public static function array($length = 5, $valueGenerator = null)
    {
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
    public static function token($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Genera un valor aleatorio de una lista de opciones proporcionada.
     *
     * @param array $options La lista de opciones.
     * @return mixed El valor aleatorio seleccionado de la lista.
     */
    public static function fromArray(array $options)
    {
        $index = mt_rand(0, count($options) - 1);
        return $options[$index];
    }

    /**
     * Genera un token aleatorio basado en la cantidad de bytes especificada, en formato Base64.
     *
     * @param int $length La longitud del token en bytes.
     * @return string El token aleatorio generado en formato Base64.
     * @throws Exception Si la generación de bytes aleatorios falla.
     */
    public static function base64Token($length = 32)
    {
        return base64_encode(random_bytes($length));
    }

    /**
     * Genera una contraseña segura con cierta longitud y complejidad requerida.
     *
     * @param int $length La longitud de la contraseña.
     * @param bool $includeSpecialChars Indica si se deben incluir caracteres especiales (por defecto, true).
     * @return string La contraseña segura generada.
     */
    public static function password($length = 10, $includeSpecialChars = true)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($includeSpecialChars) {
            $characters .= '!@#$%^&*()-_+=<>,.?/[]{}|';
        }
        $charactersLength = strlen($characters);
        $password         = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }

    /**
     * Genera una fecha aleatoria dentro del rango especificado.
     *
     * @param string $startDate La fecha de inicio del rango (en formato 'Y-m-d').
     * @param string $endDate La fecha de fin del rango (en formato 'Y-m-d').
     * @return string La fecha aleatoria generada (en formato 'Y-m-d').
     */
    public static function date($startDate = '1970-01-01', $endDate = 'now')
    {
        $startTimestamp  = strtotime($startDate);
        $endTimestamp    = strtotime($endDate);
        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
        return date('Y-m-d', $randomTimestamp);
    }

    /**
     * Genera un color aleatorio en formato hexadecimal (#RRGGBB).
     *
     * @return string El color aleatorio generado en formato hexadecimal.
     */
    public static function color()
    {
        // Genera valores aleatorios para los componentes RGB
        $red   = dechex(mt_rand(0, 255));
        $green = dechex(mt_rand(0, 255));
        $blue  = dechex(mt_rand(0, 255));

        // Asegura que cada componente tenga dos caracteres
        $red   = str_pad($red, 2, '0', STR_PAD_LEFT);
        $green = str_pad($green, 2, '0', STR_PAD_LEFT);
        $blue  = str_pad($blue, 2, '0', STR_PAD_LEFT);

        // Concatena los componentes para formar el color hexadecimal
        $color = '#' . $red . $green . $blue;

        return $color;
    }

    /**
     * Genera un código de verificación aleatorio.
     *
     * @param int $length La longitud del código de verificación.
     * @param bool $numeric Indica si el código debe contener solo dígitos (por defecto, false).
     * @return string El código de verificación generado.
     */
    public static function verificationCode($length = 6, $numeric = false)
    {
        $characters       = $numeric ? '0123456789' : '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $code             = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }
        return $code;
    }

    /**
     * Genera una dirección de correo electrónico aleatoria.
     *
     * @param string $domain El dominio de correo electrónico (por defecto, 'example.com').
     * @return string La dirección de correo electrónico generada.
     */
    public static function email($domain = 'example.com')
    {
        $usernameLength = mt_rand(5, 10); // Longitud aleatoria para el nombre de usuario
        $username       = self::string($usernameLength); // Genera un nombre de usuario aleatorio
        return $username . '@' . $domain;
    }

    /**
     * Genera una dirección IP aleatoria válida.
     *
     * @return string La dirección IP aleatoria generada.
     */
    public static function ipAddress()
    {
        return mt_rand(1, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(1, 254);
    }


    /**
     * Genera una dirección MAC aleatoria válida.
     *
     * @return string La dirección MAC aleatoria generada.
     */
    public static function macAddress()
    {
        $mac = '';
        for ($i = 0; $i < 6; $i++) {
            $mac .= sprintf('%02x', mt_rand(0, 255));
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
     */
    public static function domainName($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $domain     = '';
        for ($i = 0; $i < $length; $i++) {
            $domain .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $domain . '.com';
    }

    /**
     * Genera una URL aleatoria.
     *
     * @param int $length La longitud de la parte del path de la URL (por defecto, 10).
     * @return string La URL aleatoria generada.
     */
    public static function url($length = 10)
    {
        $path = self::string($length);
        return 'http://www.example.com/' . $path;
    }




    /**
     * Genera un nombre completo aleatorio (nombre y apellido) en español o inglés.
     *
     * @param bool $spanish Indica si se debe generar en español (true) o en inglés (false).
     * @return string El nombre completo aleatorio generado.
     */
    public static function fullName($spanish = true)
    {
        $maleFirstNamesES   = ['Juan', 'Antonio', 'José', 'Manuel', 'Francisco', 'David', 'José Antonio', 'José Manuel', 'Javier', 'Francisco Javier'];
        $femaleFirstNamesES = ['María', 'Carmen', 'Ana', 'Isabel', 'Laura', 'María Carmen', 'María Teresa', 'Ana María', 'María José', 'Cristina'];
        $lastNamesES        = ['García', 'González', 'Rodríguez', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Gómez', 'Martín'];
        $maleFirstNamesEN   = ['John', 'Michael', 'James', 'David', 'Daniel', 'Christopher', 'Joseph', 'Robert', 'William', 'Richard'];
        $femaleFirstNamesEN = ['Mary', 'Jennifer', 'Linda', 'Patricia', 'Elizabeth', 'Susan', 'Jessica', 'Sarah', 'Karen', 'Nancy'];
        $lastNamesEN        = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia', 'Rodriguez', 'Martinez'];

        if ($spanish) {
            $firstName = mt_rand(0, 1) ? self::fromArray($maleFirstNamesES) : self::fromArray($femaleFirstNamesES);
            $lastName1 = self::fromArray($lastNamesES);
            $lastName2 = self::fromArray($lastNamesES);
        } else {
            $firstName = mt_rand(0, 1) ? self::fromArray($maleFirstNamesEN) : self::fromArray($femaleFirstNamesEN);
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
     */
    public static function postalAddress($spanish = true)
    {
        $streetsES   = ['Calle Mayor', 'Avenida de la Constitución', 'Calle Real', 'Plaza España', 'Paseo de la Castellana', 'Avenida del Libertador', 'Avenida de Andalucía', 'Calle Gran Vía', 'Avenida Diagonal', 'Paseo del Prado'];
        $citiesES    = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Palma', 'Las Palmas de Gran Canaria', 'Bilbao'];
        $provincesES = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Baleares', 'Las Palmas', 'Vizcaya'];
        $zipCodesES  = ['28001', '08001', '46001', '41001', '50001', '29001', '30001', '07001', '35001', '48001'];
        $streetsEN   = ['Main St', 'First Ave', 'Elm St', 'Maple Ave', 'Oak St', 'Park Ave', 'Pine St', 'Cedar Ave', 'Walnut St', 'Willow Ave'];
        $citiesEN    = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'];
        $provincesEN = ['NY', 'CA', 'IL', 'TX', 'AZ', 'PA', 'TX', 'CA', 'TX', 'CA'];
        $zipCodesEN  = ['10001', '90001', '60601', '77001', '85001', '19101', '78201', '92101', '75201', '95101'];

        if ($spanish) {
            $street   = self::fromArray($streetsES);
            $city     = self::fromArray($citiesES);
            $province = self::fromArray($provincesES);
            $zipCode  = self::fromArray($zipCodesES);
        } else {
            $street   = self::fromArray($streetsEN);
            $city     = self::fromArray($citiesEN);
            $province = self::fromArray($provincesEN);
            $zipCode  = self::fromArray($zipCodesEN);
        }
        return $street . ', ' . $zipCode . ', ' . $city . ', ' . $province;
    }

    /**
     * Genera un número de teléfono aleatorio con formato nacional español o inglés.
     *
     * @param bool $spanish Indica si se debe generar en español (true) o en inglés (false).
     * @return string El número de teléfono aleatorio generado.
     */
    public static function phoneNumber($spanish = true)
    {
        if ($spanish) {
            return '6' . mt_rand(0, 9) . ' ' . sprintf('%03d', mt_rand(0, 999)) . ' ' . sprintf('%02d', mt_rand(0, 99)) . ' ' . sprintf('%02d', mt_rand(0, 99));
        } else {
            return '555-' . sprintf('%03d', mt_rand(0, 999)) . '-' . sprintf('%04d', mt_rand(0, 9999));
        }
    }


    /**
     * Genera un número de teléfono aleatorio con formato internacional español o inglés.
     *
     * @param bool $spanish Indica si se debe generar en español (true) o en inglés (false).
     * @return string El número de teléfono aleatorio generado.
     */
    public static function internationalPhoneNumber($spanish = true)
    {
        if ($spanish) {
            return '+34 ' . mt_rand(6, 7) . mt_rand(0, 9) . ' ' . sprintf('%03d', mt_rand(0, 999)) . ' ' . sprintf('%02d', mt_rand(0, 99)) . ' ' . sprintf('%02d', mt_rand(0, 99));
        } else {
            return '+1-555-' . sprintf('%03d', mt_rand(0, 999)) . '-' . sprintf('%04d', mt_rand(0, 9999));
        }
    }


    /**
     * Genera una fecha y hora aleatoria dentro de un rango específico.
     *
     * @param string $startDate La fecha de inicio del rango (en formato 'Y-m-d H:i:s').
     * @param string $endDate La fecha de fin del rango (en formato 'Y-m-d H:i:s').
     * @return string La fecha y hora aleatoria generada (en formato 'Y-m-d H:i:s').
     */
    public static function dateTime($startDate = '1970-01-01 00:00:00', $endDate = 'now')
    {
        $startTimestamp  = strtotime($startDate);
        $endTimestamp    = strtotime($endDate);
        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
        return date('Y-m-d H:i:s', $randomTimestamp);
    }

    /**
     * Genera un número de tarjeta de crédito válido para pruebas en español o inglés.
     *
     * @param bool $spanish Indica si se debe generar en español (true) o en inglés (false).
     * @return string El número de tarjeta de crédito generado.
     */
    public static function creditCardNumber($spanish = true)
    {
        if ($spanish) {
            $prefixesES = ['4', '51', '52', '53', '54', '55'];
            $prefixES   = self::fromArray($prefixesES);
            $length     = 16;
            $number     = $prefixES;
            while (strlen($number) < $length - 1) {
                $number .= mt_rand(0, 9);
            }
            $checksum = 0;
            $isEven   = true;
            for ($i = $length - 1; $i >= 0; $i--) {
                $digit = intval($number[$i]);
                if ($isEven) {
                    $digit *= 2;
                    $digit = ($digit > 9) ? $digit - 9 : $digit;
                }
                $checksum += $digit;
                $isEven   = !$isEven;
            }
            $checksum %= 10;
            $checksum = ($checksum == 0) ? 0 : 10 - $checksum;
            $number .= $checksum;
            return $number;
        } else {
            $prefixesEN = ['4', '51', '52', '53', '54', '55'];
            $prefixEN   = self::fromArray($prefixesEN);
            $length     = 16;
            $number     = $prefixEN;
            while (strlen($number) < $length - 1) {
                $number .= mt_rand(0, 9);
            }
            $checksum = 0;
            $isEven   = true;
            for ($i = $length - 1; $i >= 0; $i--) {
                $digit = intval($number[$i]);
                if ($isEven) {
                    $digit *= 2;
                    $digit = ($digit > 9) ? $digit - 9 : $digit;
                }
                $checksum += $digit;
                $isEven   = !$isEven;
            }
            $checksum %= 10;
            $checksum = ($checksum == 0) ? 0 : 10 - $checksum;
            $number .= $checksum;
            return $number;
        }
    }

    /**
     * Genera un nombre de usuario aleatorio combinando palabras y adjetivos.
     *
     * @return string El nombre de usuario aleatorio generado.
     */
    public static function username()
    {
        // Lista de palabras y adjetivos
        $words      = ['Parzival', 'Clown', 'Sapphire', 'Dragon', 'Shadow', 'Phoenix', 'Titan', 'Spectre', 'Eagle', 'Mystic'];
        $adjectives = ['Oasis', 'Colored', 'Stalker', 'Knight', 'Whisper', 'Ninja', 'Reaper', 'Warrior', 'Phantom', 'Hunter'];

        // Obtener un índice único utilizando la función uniqid()
        $uniqueIndex = uniqid();

        // Calcular los índices para seleccionar una palabra y un adjetivo
        $wordIndex      = hexdec(substr(md5($uniqueIndex), 0, 8)) % count($words);
        $adjectiveIndex = hexdec(substr(md5($uniqueIndex), 8, 8)) % count($adjectives);

        // Construir el nombre de usuario combinando la palabra y el adjetivo
        $username = $words[$wordIndex] . $adjectives[$adjectiveIndex];

        return $username;
    }

}




