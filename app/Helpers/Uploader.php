<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;


class Uploader{

    public static function uploadFile(string $key, string $path): string{
        $fileName = self::generateFileName($key); //la key es el input del form
        request()->file($key)->storeAs($path, $fileName);//se guardará el archivo con el path y el nombre(fileName), path es la ruta
        return $fileName;
    }

    public static function removeFile(string $path, string $fileName){
        Storage::delete(sprintf('%s/%s', $path, $fileName));
        //con lo de dentro de sprintf se pasa la rua y el nombre 
    }

    protected static function generateFileName(string $key): string{
        $extension = request()->file($key)->getClientOriginalExtension();
        return sprintf('%s-%s.%s', auth()->id(), now()->timestamp, $extension);
    }
}