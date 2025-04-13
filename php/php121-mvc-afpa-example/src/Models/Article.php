<?php

namespace Afpa\Models;

use Afpa\Tools\DateTools;
use DateTime;

class Article
{
    private int $id;
    private string $titre;
    private string $texte;
    private string $technologie;

    /**
     * La date de création de l'article
     */
    private DateTime $date;
    
    public function __construct(int $id, string $titre, string $texte, string $technologie, DateTime $date)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->texte = $texte;
        $this->technologie = $technologie;
        $this->date = $date;
    }

    public function __toString()
    {
        // formatage de la date pour un rendu correct
        $dateFormate = DateTools::formatDateAvecHeures($this->date);
        return "$this->titre $dateFormate";
    }

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of titre
     *
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     * @param string $titre
     *
     * @return self
     */
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get the value of texte
     *
     * @return string
     */
    public function getTexte(): string
    {
        return $this->texte;
    }

    /**
     * Set the value of texte
     *
     * @param string $texte
     *
     * @return self
     */
    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get the value of technologie
     *
     * @return string
     */
    public function getTechnologie(): string
    {
        return $this->technologie;
    }

    /**
     * Set the value of technologie
     *
     * @param string $technologie
     *
     * @return self
     */
    public function setTechnologie(string $technologie): self
    {
        $this->technologie = $technologie;

        return $this;
    }

    /**
     * Get the value of date
     *
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @param DateTime $date
     *
     * @return self
     */
    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}

?>