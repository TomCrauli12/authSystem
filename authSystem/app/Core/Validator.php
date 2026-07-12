<?php

class Validator{

    private array $errors = [];

    public function __construct(private array $data){
    }

    public function required(string $field, string $message): self{

        $value = trim($this->data[$field] ?? '');

        if ($value === ''){
            $this->errors[$field][] = $message;
        }

        return $this;
    }

    public function min(string $field, int $length, string $message): self{

        $value = trim($this->data[$field] ?? '');

        if (mb_strlen($value) < $length){

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

    public function errors(): array
    {
        return $this->errors;
    }
}