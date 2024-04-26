<?php

declare(strict_types=1);

namespace App\Utility;

use JsonException;
use App\Language\LanguageCode;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Параметры запроса со встроенной проверкой типов
 */
class StrictRequest
{
    protected array $data_from_get = [];
    protected array $data_from_post = [];
    protected array $data_from_json = [];
    protected array $request_data = [];

    public function __construct(
        readonly public Request $request,
    ) {
        $this->data_from_get = $this->request->query->all();
        $this->data_from_post = $this->request->request->all();
        $content = $this->request->getContent();

        $from_json = false;

        if (is_string($content)) {
            try {
                $decoded = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
                if (is_array($decoded)) {
                    $this->data_from_json = $decoded;
                    $from_json = true;
                }
            } catch (JsonException) {
            }
        }

        if (!$from_json) {
            $request_data_encoded = $this->getString('requestData');
            if ($request_data_encoded !== null) {
                try {
                    $request_data_decoded = json_decode($request_data_encoded, true, flags: JSON_THROW_ON_ERROR);
                    if (is_array($request_data_decoded)) {
                        $this->request_data = $request_data_decoded;
                    }
                } catch (JsonException) {
                }
            }
        }
    }

    public function getInt(string $key): ?int
    {
        $var = $this->get($key);
        if ($var !== null) {
            if (is_int($var)) {
                return $var;
            }

            if (is_string($var)) {
                $int_var = (int)$var;
                if ((string)$int_var === $var) {
                    return $int_var;
                }
            }
        }

        return null;
    }

    public function getFloat(string $key): ?float
    {
        $var = $this->get($key);
        if ($var !== null) {
            if (is_float($var)) {
                return $var;
            }

            if (is_int($var)) {
                return (float)$var;
            }

            if (is_string($var) && $var !== '' && is_numeric($var)) {
                return (float)$var;
            }
        }

        return null;
    }

    public function getString(string $key): ?string
    {
        $var = $this->get($key);
        if ($var !== null) {
            if (is_string($var)) {
                $var = trim($var);
                if ($var !== '') {
                    return $var;
                }
            }

            if (is_int($var) || is_float($var)) {
                return (string)$var;
            }
        }

        return null;
    }

    public function get(string $key, mixed $default = null, bool $from_post = false): mixed
    {
        if ($from_post) {
            return $this->data_from_post[$key];
        }

        return $this->data_from_json[$key]
            ?? $this->request->get($key)
            ?? $this->data_from_get[$key]
            ?? $this->request_data[$key]
            ?? $default;
    }

    /**
     * NULL - не передано либо передано некорректно
     */
    public function getArray(string $key, bool $associative = null): ?array
    {
        $var = $this->get($key);

        if (is_string($var)) {
            try {
                $var = json_decode($var, true, flags: JSON_THROW_ON_ERROR);
            } catch (JsonException) {
            }
        }

        if (is_array($var)) {
            if ($associative !== null && $var !== []) {
                foreach ($var as $key => $value) {
                    if ($associative) {
                        if (is_int($key)) {
                            return null;
                        }
                    } else {
                        if (is_string($key)) {
                            return null;
                        }
                    }
                }
            }

            return $var;
        }

        return null;
    }

    public function getUuid(string $key): ?UuidInterface
    {
        $var = $this->getString($key);
        if ($var !== null && Uuid::isValid($var)) {
            return Uuid::fromString($var);
        }

        return null;
    }

    public function getBool(string $key, int|string $true_value = null, int|string $false_value = null): ?bool
    {
        $var = $this->get($key);
        if ($var !== null) {
            if (is_bool($var)) {
                return $var;
            }

            if ($true_value !== null && $false_value !== null) {
                if ($var === $true_value || $var === $false_value) {
                    return $var === $true_value;
                }

                return null;
            }

            if (is_string($var)) {
                $var = strtolower($var);

                if ($var === 'true' || $var === 'false') {
                    return $var === 'true';
                }

                if ($var === '1' || $var === '0') {
                    return $var === '1';
                }
            } elseif (is_int($var)) {
                if ($var === 1 || $var === 0) {
                    return $var === 1;
                }
            }
        }

        return null;
    }

    public function getLanguageCode(): ?LanguageCode
    {
        return LanguageCode::tryFrom($this->request->getLocale());
    }

    public function getGeoposition(string $key): ?Geoposition
    {
        $value = $this->getArray($key) ?? $this->get($key);

        if (is_array($value)) {
            return Geoposition::fromArray($value);
        }

        if (is_string($value)) {
            return Geoposition::tryFromString($value);
        }

        return null;
    }

    /**
     * NULL - не передано либо передано некорректно
     *
     * @return UuidInterface[]|null
     */
    public function getArrayOfUuid(string $key, bool $associative = null): ?array
    {
        $values = $this->getArray($key, $associative);

        if (empty($values) && !$associative) {
            $values_string = $this->getString($key);
            if (empty($values_string)) {
                return null;
            }

            $values = explode(',', $values_string);
        }

        $result = [];
        foreach ($values as $key => $value) {
            if (!is_string($value)) {
                return null;
            }

            $value = trim($value);
            if (!Uuid::isValid($value)) {
                return null;
            }

            $result[$key] = Uuid::fromString($value);
        }

        return $result;
    }

    public function toArray(): array
    {
        return [
            'get' => $this->data_from_get,
            'post' => $this->data_from_post,
            'json' => $this->data_from_json,
            'request_data' => $this->request_data,
        ];
    }
}
