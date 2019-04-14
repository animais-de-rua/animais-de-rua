<?php

namespace App\Models;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public function getLink($entity, $link = true, $action = 'edit')
    {
        if (!$entity) {
            return '-';
        }

        $name = str_limit($entity->name, 60, '...');
        $class = strtolower(class_basename($entity));

        return $link ? "<a href='/admin/$class/{$entity->id}/$action'>$name</a>" : $name;
    }

    public function saveImage($model, $value, $path, $name, $max_width, $quality = 85, $attribute_name = 'image', $disk = 'uploads', $store = true)
    {
        // if the image was erased
        if ($value == null) {
            \Storage::disk($disk)->delete($model->{$attribute_name});
            $model->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            $timestamp = \Carbon\Carbon::now()->timestamp;
            $filename = str_slug("$name $timestamp") . '.jpg';

            $image = \Image::make($value);
            if ($image->width() > $max_width) {
                $image->resize($max_width, null, function ($c) {$c->aspectRatio();});
            }

            \Storage::disk($disk)->put($path . $filename, $image->stream('jpg', $quality));
            $model->attributes[$attribute_name] = str_replace(\Config::get('app.url'), '', $path . $filename);

            return $model->attributes[$attribute_name];
        } else {
            $model->attributes[$attribute_name] = $value;
        }

        // Clean path
        $model->attributes[$attribute_name] = $this->cleanPath($model->attributes[$attribute_name], $disk);
    }

    public function cleanPath($path, $disk)
    {
        // Remove URL
        $path = str_replace(\Config::get('app.url'), '', $path);

        // Remove Disk
        if ($disk) {
            $path = preg_replace("/\/?$disk\/?/", '', $path);
        }

        // Remove First slash
        $path = preg_replace("/^\//", '', $path);

        return $path;
    }
}
