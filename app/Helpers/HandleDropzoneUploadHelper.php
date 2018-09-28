<?php

namespace App\Helpers;

use App\Http\Requests\DropzoneRequest;
use Illuminate\Http\Request;

trait HandleDropzoneUploadHelper
{
    public function handleDropzoneUploadRaw(DropzoneRequest $request)
    {
        $column = \Route::current()->parameters()['column'];

        switch ($column) {
            case 'images':return $this->handleDropzoneUploadImage('uploads', false);
            case 'videos':return $this->handleDropzoneUploadVideo('uploads', false);
            case 'audios':return $this->handleDropzoneUploadAudio('uploads', false);
        }
    }

    public function handleDropzoneRemoveRaw(Request $request)
    {
        $entity = \Route::current()->parameters()['entity'];
        $column = \Route::current()->parameters()['column'];

        return $this->handleDropzoneRemove('uploads', $entity, $column);
    }

    // -----

    public function handleDropzoneRemove($disk, $entity, $column)
    {
        $id = $this->request->get('id');
        $filepath = $this->request->get('filepath');

        try
        {
            $filename = basename($filepath);

            // Remove File from disk
            \Storage::disk($disk)->delete("$entity/$filename");
            \Storage::disk($disk)->delete("$entity/thumb/$filename");

            // Remove file from DB
            $class = 'App\\Models\\' . ucfirst($entity);
            $entity = $class::find($id);

            if ($entity) {
                $imgs = $entity->getAttribute($column);
                if (isset($imgs)) {
                    unset($imgs[array_search($filepath, $imgs)]);

                    $entity->{$column} = array_values($imgs);
                    $entity->save();
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response('Unknown error' . $e, 412);
        }
    }

    public function handleDropzoneUploadImage($disk, $random_name = true)
    {
        $params = \Route::current()->parameters();
        $destination_path = $params['entity'];
        $max_size = $params['size'] ?? 0;
        $quality = $params['quality'] ?? 0;
        $thumb = $params['thumb'] ?? 0;

        try
        {
            $file = $this->request->file('file');

            if (!$this->compareMimeTypes($file, ['image'])) {
                return response('Not a valid image type', 412);
            }

            $image = \Image::make($file);
            if ($max_size > 0 && $image->width() > $max_size) {
                $image->resize($max_size, null, function ($c) {$c->aspectRatio();});
            }

            // Filename
            $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName()) . '_' . time();
            if ($random_name) {
                $filename = md5($filename);
            }

            $filename .= '.' . $file->extension();

            \Storage::disk($disk)->put($destination_path . '/' . $filename, $quality ? $image->stream('jpg', $quality) : $image->stream());

            if ($thumb > 0) {
                $image->resize($thumb, null, function ($c) {$c->aspectRatio();});
                \Storage::disk($disk)->put($destination_path . '/thumb/' . $filename, $quality ? $image->stream('jpg', $quality) : $image->stream());
            }

            return response()->json(['success' => true, 'filename' => $disk . '/' . $destination_path . '/' . $filename]);
        } catch (\Exception $e) {
            return response('Unknown error ' . $e, 412);
        }
    }

    public function handleDropzoneUploadVideo($disk, $destination_path, $random_name = true)
    {
        // TODO
    }

    public function handleDropzoneUploadAudio($disk, $destination_path, $random_name = true)
    {
        // TODO
    }

    private function compareMimeTypes($file, $mimes)
    {
        foreach ($mimes as $mime) {
            if (strpos($file->getMimeType(), $mime) === 0) {
                return true;
            }
        }

        return false;
    }
}
