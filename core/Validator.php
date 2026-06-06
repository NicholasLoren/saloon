<?php
namespace Core;

class Validator {
    private array $errors = [];
    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function required(string $field, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        if (empty(trim((string)($this->data[$field] ?? '')))) {
            $this->errors[$field] = "$label is required.";
        }
        return $this;
    }

    public function email(string $field, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        $val   = $this->data[$field] ?? '';
        if (!empty($val) && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "$label must be a valid email address.";
        }
        return $this;
    }

    public function min(string $field, int $min, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        $val   = $this->data[$field] ?? '';
        if (!empty($val) && strlen(trim((string)$val)) < $min) {
            $this->errors[$field] = "$label must be at least $min characters.";
        }
        return $this;
    }

    public function max(string $field, int $max, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        $val   = $this->data[$field] ?? '';
        if (!empty($val) && strlen(trim((string)$val)) > $max) {
            $this->errors[$field] = "$label must not exceed $max characters.";
        }
        return $this;
    }

    public function numeric(string $field, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        $val   = $this->data[$field] ?? '';
        if (!empty($val) && !is_numeric($val)) {
            $this->errors[$field] = "$label must be a number.";
        }
        return $this;
    }

    public function positiveNumber(string $field, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        $val   = $this->data[$field] ?? '';
        if (!empty($val) && (!is_numeric($val) || (float)$val <= 0)) {
            $this->errors[$field] = "$label must be a positive number.";
        }
        return $this;
    }

    public function date(string $field, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        $val   = $this->data[$field] ?? '';
        if (!empty($val)) {
            $d = \DateTime::createFromFormat('Y-m-d', $val);
            if (!$d || $d->format('Y-m-d') !== $val) {
                $this->errors[$field] = "$label must be a valid date (YYYY-MM-DD).";
            }
        }
        return $this;
    }

    public function futureDate(string $field, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        $val   = $this->data[$field] ?? '';
        if (!empty($val)) {
            $d = \DateTime::createFromFormat('Y-m-d', $val);
            if ($d && $d < new \DateTime('today')) {
                $this->errors[$field] = "$label must be a future date.";
            }
        }
        return $this;
    }

    public function phone(string $field, string $label = ''): self {
        $label = $label ?: ucwords(str_replace('_', ' ', $field));
        $val   = $this->data[$field] ?? '';
        if (!empty($val) && !preg_match('/^[\+]?[\d\s\-\(\)]{7,20}$/', $val)) {
            $this->errors[$field] = "$label must be a valid phone number.";
        }
        return $this;
    }

    public function fails(): bool {
        return !empty($this->errors);
    }

    public function passes(): bool {
        return empty($this->errors);
    }

    public function errors(): array {
        return $this->errors;
    }

    public function first(string $field): string {
        return $this->errors[$field] ?? '';
    }
}
