<?php

namespace App\Core;

class Validator{

    private array $errors = [];

    public function __construct(private array $data){
    }

    public function required(string $field, string $message): self{

        $value = $this->value($field);

        if ($value === ''){
            $this->errors[$field][] = $message;
        }

        return $this;
    }

    public function min(string $field, int $length, string $message): self{

        $value = $this->value($field);

        if ($value !== '' && mb_strlen($value) < $length){

            $this->errors[$field][] = $message;
        }

        return $this;
    }

    public function max(string $field, int $length, string $message): self{

        $value = $this->value($field);

        if ($value !== '' && mb_strlen($value) > $length){
            $this->errors[$field][] = $message;
        }

        return $this;
    }

    public function fails(): bool{

        return !empty($this->errors);
    }

    public function allErrors(): array{

        $allErrors = [];

        foreach ($this->errors as $fieldErrors){

            foreach ($fieldErrors as $error){

                $allErrors[] = $error;
            }
        }

        return $allErrors;
    }

    public function firstError(): ?string{

        return $this->allErrors()[0] ?? null;
    }

    public function errors(): array{

        return $this->errors;
    }

    private function value(string $field): string{

        $value = $this->data[$field] ?? '';

        return is_string($value) ? trim($value) : '';
    }
}

?>