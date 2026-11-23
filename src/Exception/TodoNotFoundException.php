<?php

declare(strict_types=1);

namespace App\Exception;

class TodoNotFoundException extends TodoException {
    public function __construct(public readonly int $id,){
        parent::__construct("Todo dengan id $id tidak ditemukan.");
    }
}