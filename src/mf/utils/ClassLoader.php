<?php

namespace mf\utils;

class ClassLoader extends AbstractClassLoader {
    
   public function loadClass(string $classname){
        $fileName=$this->getFilename($classname);
        $path=$this->makePath($fileName);
        if(file_exists($path)){
            require_once($path);
        }
    }

    public function getFilename(string $classname): string{
        $str=str_replace("\\",'/',$classname).".php";
        return $str;
    }

    public function makePath(string $filename): string{
        return $this->prefix. DIRECTORY_SEPARATOR .$filename;
    }
    
}