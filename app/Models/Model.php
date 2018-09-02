<?php

namespace App\Models;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public function getLink($entity)
    {
        if (!$entity) {
            return '-';
        }

        $class = strtolower(class_basename($entity));
        return "<a href='/admin/$class/{$entity->id}/edit'>" . str_limit($entity->name, 60, '...') . '</a>';
    }
}
