<?php


class Pont
{
    // Déclaration des propriétés (variables) de la classe
    public float $longueur;
    public float $largeur;

    // Définition d'une méthode (fonction) qui calcule la surface du pont
    public function getSurface(): float
    {
        return $this->longueur * $this->largeur;
    }
}

// Création d'une nouvelle instance (objet) de la classe Pont pour le Tower Bridge
$towerBridge = new Pont;
$towerBridge->longueur = 286.0;
$towerBridge->largeur = 15.0;

// Création d'une autre instance pour le Manhattan Bridge
$manhattanBridge = new Pont;
$manhattanBridge->longueur = 2089.0;
$manhattanBridge->largeur = 36.6;

// Calcul de la surface pour chaque pont en utilisant la méthode getSurface()
$towerBridgeSurface = $towerBridge->getSurface();
$manhattanBridgeSurface = $manhattanBridge->getSurface();

// Affichage des résultats avec var_dump()
var_dump($towerBridgeSurface);
var_dump($manhattanBridgeSurface);
