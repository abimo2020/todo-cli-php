<?php

declare(strict_types=1);

namespace App\Model;

enum StatusTodo : string {
    case Finished = "finish";
    case Unfinished = "unfinished";

    public function getMark() : string {
        return match($this){
            self::Finished => "[x]",
            self::Unfinished => "[ ]",
        };
    }
}