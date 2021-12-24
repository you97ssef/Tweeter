<?php

namespace mf\utils;

abstract class AbstractClassLoader {
    
    protected $prefix = '';


    /**
     * Constructeur: enregistre le chemin vers la racine des espaces de noms
     * dans l'attribut $prefix 
     *
     */
    
    public function __construct($file_root) {
        $this->prefix = $file_root;
    }

    /**
     * MÃ©thode loadClass: charge le fichier de dÃ©finition d'une classe.
     *
     * ParamÃ¨tres:
     *
     *  - $classname (string): le nom complet d'une classe
     *  
     *  
     * Algorithme:
     *
     * - transforme le non de la classe en un chemin vers le fichier 
     *   de dÃ©finition avec la methode $this->getFilename
     *
     * - ajoute le prefix pour avoir le chemin complet depuis la racine du
     *   de l'application avec la mÃ©thode $this->makePath
     *
     * - si le fichier existe :
     *   
     *   - le charger avec l'instruction require_once
     * 
     * - sinon : rien (surtout ne pas gÃ©nÃ©rer d'exception ou d'erreur)
     *        
     */
    
    abstract public function loadClass(string $classname);

    /**
     * MÃ©thode makePath: ajoute le prÃ©fix au chemin
     * vers le fichier de dÃ©finition d'une classe.
     *
     * ParamÃ¨tres:
     *
     * - $filename (string): le chemin ver le fichier d'une classe
     *
     * Retourne:
     *  
     * - string : le mÃªme chemin avec le prÃ©fixe au dÃ©but 
     *
     * Algorithme:
     *
     * - ajoute $this->prefix et DIRECTORY_SEPARATOR au dÃ©but de $filename
     *  
     * - retourne la nouvelle chaine 
     *
     */
    
    abstract protected function makePath(string $filename): string;

     /**
     * MÃ©thode getFilename: transfomre le nom d'une classe espace de noms 
     * compris en un chemain vers la dÃ©finition de la classe.
     * 
     * Exemple: 
     *
     *   peopleapp\personne\Etudiant -> peopleapp/personne/Etudiant.php
     *
     * ParamÃ¨tres:
     *
     * - $classname (string): le nom complet d'une classe
     *
     * Retourne:
     *  
     * - string : le chemin ver le fichier depuis la racine des espaces de nom 
     *
     * Algorithme:
     *
     * 
     * - remplacer toute les autres occurrences du caractÃ¨re "\"
	 *   par la constante DIRECTORY_SEPARATOR
     *
     * - ajouter ".php" a la fin de la chaine 
     *
     * - retourner la chaine finale.
     *
     */
   
    abstract protected function getFilename(string $classname): string;

    /**
     * MÃ©thode register : enregistre le chargeur de classe au prÃ¨s de
     * l'interprÃ¨te PHP 
     * 
     * Note : 
     * 
     * Comme le chargeur de classe est une mÃ©thode, on doit donner une
     * une instance sur laquelle sera appelÃ©e cette mÃ©thode.
     * 
     */
    
    public function register () {
        spl_autoload_register( array($this, 'loadClass') );
    }



}