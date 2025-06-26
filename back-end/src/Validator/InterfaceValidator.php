<?php
namespace Src\Validator;

interface InterfaceValidator{
    public function validate(array $data): bool;
}