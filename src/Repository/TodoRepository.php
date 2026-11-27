<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\TodoNotFoundException;
use App\Model\Todo;

interface TodoRepository{
    /** @return Todo[] */
    public function getAll(): array;

    /** @throws TodoNotFoundException */
    public function getById(int $id): Todo;

    /** save new todo, or renew if the id exist */
    public function save(Todo $todo) : void;

    /** @throws TodoNotFoundException */
    public function delete(int $id) : void;

    /** get the highest number from the ids */
    public function generateID() : int;
}