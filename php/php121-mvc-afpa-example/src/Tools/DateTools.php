<?php

namespace Afpa\Tools;

use DateTime;

/**
 * Encapsule les informations à la connexion à la BDD.
 * A utiliser dès que l'on souhaite effectuer une requête.
 */
class DateTools
{
    /**
     * Formatte une date "à la french" et envoie une chaîne de caractères.
     * Va également formater les heures.
     * 
     * @param $date la date à formater
     * @return La chaîne de caractères représentative en version française "jj/mm/aaaa hh:mm"
     */
    public static function formatDateAvecHeures(DateTime $date): string
    {
        return $date->format("d/m/Y H:i");
    }
}
