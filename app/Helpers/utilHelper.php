<?php
/**
 * Created by PhpStorm.
 * User: EDWARD OSORIO
 * Date: 31/03/2018
 * Time: 10:39 AM
 */

if (! function_exists('writeFile')) {
    /**
     * Método que permite escribir en un archivo
     * @param $file
     * @param $message
     */
    function writeFile($file, $message){
        $file = fopen($file, "a");
        $data = "### Date: ".date('d-M-Y H:i:s')."\n".$message;
        fwrite($file, $data . PHP_EOL);
        fclose($file);
    }
}

if (! function_exists('str_file')) {
    /**
     * Método que convierte un archivo en cadena de texto
     * @param $filePath
     * @return null|string
     */
    function str_file($filePath){
        $file = fopen($filePath, "a+");
        $linea = null;
        while(!feof($file)) {
            $linea .= fgets($file);
        }
        fclose($file);
        return $linea;
    }
}

if (! function_exists('lang')) {
    /**
     * Método de traduccion simultanea
     * @param $key
     * @param array $attributes
     * @return mixed
     */
    function lang($key, $attributes = [])
    {
        if (!empty($attributes)) {
            return \Illuminate\Support\Facades\Lang::get($key, $attributes);
        }
        return \Illuminate\Support\Facades\Lang::get($key);
    }
}