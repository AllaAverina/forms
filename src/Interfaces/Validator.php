<?php

namespace Forms\Interfaces;

interface Validator
{
    public function validate(array $data): array;
}
