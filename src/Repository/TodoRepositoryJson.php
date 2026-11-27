<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\{TodoException, TodoInvalidTitleException,TodoNotFoundException};
use App\Model\Todo;
use JsonException;
use Override;

class TodoRepositoryJson implements TodoRepository {
    public function __construct(
        private string $path
    ){}

    #[Override]
    public function getAll(): array
    {
        return array_values($this->load());
    }

    #[Override]
    public function getById(int $id): Todo
    {
        return $this->load()[$id] ?? throw new TodoNotFoundException($id);
    }

    #[Override]
    public function save(Todo $todo): void
    {
        $todos = $this->load();
        $todos[$todo->id] = $todo;
        $this->write($todos);
    }

    #[Override]
    public function delete(int $id): void
    {
        $todos = $this->load();
        if (!isset($todos[$id])){
            throw new TodoNotFoundException($id);
        }
        unset($todos[$id]);
        $this->write($todos);
    }

    #[Override]
    public function generateID(): int
    {
        $todos = $this->load();
        return $todos === [] ? 1 : max(array_keys($todos)) + 1;
    }

    /** @return array<id,Todo> where id as key */
    public function load():array {
        if(!file_exists($this->path)){
            return [];
        }
        
        $contents = file_get_contents($this->path);
        if($contents === false || trim($contents) === ""){
            return [];
        }

        try {
            $data = json_decode($contents,true,512,JSON_THROW_ON_ERROR);
        }catch(JsonException $e){
            throw new TodoException("The todo data is broken");
        }

        $todos = [];
        foreach($data as $v) {
            $todo = Todo::fromArray($v);
            $todos[$todo->id] = $todo;
        }

        return $todos;
    }

    /** @param array<int, Todo> $todos */
    public function write(array $todos) : void {
        $folder = dirname($this->path);
        if(!is_dir($folder)){
            mkdir($folder,0755,true);
        }

        $json = json_encode(
            array_values($todos),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        );

        file_put_contents($this->path,$json,LOCK_EX);
    }
}