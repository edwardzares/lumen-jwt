<?php
if(! function_exists('namesGenerator')){
    /**
     * Método que genera nombres teniendo en cuenta que siempre va la fecha en formato unix
     * time() para el tiempo en unix
     * Se puede agregar un prefijo y/o un posfijo
     *
     * @param null $prefix
     * @param null $postfix
     * @return string
     */
    function namesGenerator($prefix = null, $postfix = null){
        return ((empty($prefix)?"":$prefix)).time().((empty($postfix)?"":$postfix));
    }
}
