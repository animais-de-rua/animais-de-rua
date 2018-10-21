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

    public function saveImage($model, $value, $path, $name, $max_width, $quality)
    {
        $attribute_name = 'image';
        $disk = 'uploads';

        // if the image was erased
        if ($value == null) {
            \Storage::disk($disk)->delete($model->{$attribute_name});
            $model->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            $filename = str_slug($name) . '.jpg';

            $image = \Image::make($value);
            if ($image->width() > $max_width) {
                $image->resize($max_width, null, function ($c) {$c->aspectRatio();});
            }

            \Storage::disk($disk)->put($path . $filename, $image->stream('jpg', $quality));
            $model->attributes[$attribute_name] = $path . $filename;
        }
    }
}
