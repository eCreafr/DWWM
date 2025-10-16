<?php
// Cette ligne active le typage strict en PHP
// Cela signifie que PHP vérifiera rigoureusement les types de données
declare(strict_types=1);

// Définition d'une classe nommée "Pont"
// Une classe est comme un plan qui définit les caractéristiques et comportements d'un objet
class Pont
{
    // Déclaration des propriétés (variables) de la classe
    // 'public' signifie que ces propriétés sont accessibles depuis l'extérieur de la classe
    // 'float' indique que ces propriétés ne peuvent contenir que des nombres décimaux
    public float $longueur;    // Stocke la longueur du pont en mètres
    public float $largeur;     // Stocke la largeur du pont en mètres

    // Définition d'une méthode (fonction) qui calcule la surface du pont
    // ': float' après les parenthèses indique que la méthode retournera un nombre décimal
    public function getSurface(): float
    {
        // $this-> fait référence à l'instance actuelle de la classe
        // On multiplie la longueur par la largeur pour obtenir la surface
        return $this->longueur * $this->largeur;
    }
}

// Création d'une nouvelle instance (objet) de la classe Pont pour le Tower Bridge
$towerBridge = new Pont;
// Attribution des valeurs aux propriétés de l'objet
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
// var_dump() est utile pour le débogage car il affiche le type + la valeur (sinon on lui prefere echo en production)
var_dump($towerBridgeSurface);      // Affiche la surface du Tower Bridge
var_dump($manhattanBridgeSurface);  // Affiche la surface du Manhattan Bridge