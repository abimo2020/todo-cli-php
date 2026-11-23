<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\TodoInvalidTitleException;
use JsonSerializable;

class Todo implements JsonSerializable{
    const MAX_TITLE = 50;

    public function __construct(
        public readonly int $id,
        private string $title,
        private StatusTodo $status = StatusTodo::Unfinished,
    ){
        

        $this->title = self::validateTitle($title);
    }

    private static function validateTitle(string $title) : string {
        $title = trim($title);
        if ($title === "") {
            throw new TodoInvalidTitleException("Judul tidak boleh kosong.");
        }
        if (mb_strlen($title) > self::MAX_TITLE){
            throw new TodoInvalidTitleException("Judul melebihi " . self::MAX_TITLE . " karakter.");
        }
        return $title;
    }

    public static function fromArray(array $data) : self {
        return new self(
            id: $data["id"],
            title: $data["title"],
            status: StatusTodo::from($data["status"]),
        );
    }

    public function getTitle() : string {
        return $this->title;
    }

    public function getStatus() : StatusTodo {
        return $this->status;
    }

    public function isDone() : bool {
        return $this->status === StatusTodo::Finished;
    }

    public function markDone() : void {
        $this->status = StatusTodo::Finished;
    }

    public function __toString() : string {
        return "{$this->id}. {$this->status->getMark()} {$this->title}";
    }

    public function updateTitle(string $title) : void {
        $this->title = self::validateTitle($title);
    }

    public function jsonSerialize() : array {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "status" => $this->status->value,
        ];
    }
}