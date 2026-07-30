<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ NIVEAU 3 — ÉTAPE 1 — Livre enrichi
 * ===================================================================
 * Reprend le Livre du niveau 2 (etape1/src/Livre.php) et ajoute les
 * trois défis du niveau 3 :
 *
 *   1. propriété $isbn avec validation dans le setter
 *   2. méthode magique __toString()
 *   3. le jeu d'essai est dans jeu-essai.php (même dossier)
 *
 * C'est bien la MÊME classe que celle de l'étape 1, enrichie — le
 * stagiaire continue son fichier, il n'en démarre pas un autre.
 */
class Livre
{
    /** Nullable : un livre peut exister sans ISBN renseigné. */
    private ?string $isbn = null;

    public function __construct(
        private string $titre,
        private string $auteur,
        private int $annee
    ) {}

    // ---------- Niveaux 1 et 2 (rappel) ----------

    public function getTitre(): string  { return $this->titre;  }
    public function getAuteur(): string { return $this->auteur; }
    public function getAnnee(): int     { return $this->annee;  }

    public function setTitre(string $titre): void
    {
        if (trim($titre) === '') {
            throw new InvalidArgumentException('Le titre ne peut pas être vide.');
        }

        $this->titre = trim($titre);
    }

    public function afficher(): string
    {
        return sprintf('%s (%d), de %s', $this->titre, $this->annee, $this->auteur);
    }

    // ---------- Niveau 3, défi 1 : ISBN ----------

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * ATTENDU MINIMAL DU COURS : 13 chiffres.
     *
     *   if (preg_match('/^\d{13}$/', $isbn) !== 1) { throw ... }
     *
     * La version ci-dessous va plus loin et vérifie aussi la CLÉ DE
     * CONTRÔLE (norme ISO 2108). Différence à faire verbaliser :
     *
     *   - validation de FORMAT   : « ça ressemble à un ISBN »
     *     → 1111111111111 passerait
     *   - validation de VALIDITÉ : « c'est un ISBN réel »
     *     → seul le bon 13e chiffre passe
     *
     * Ce raisonnement se transpose tel quel sur IBAN, SIRET, numéro
     * de sécurité sociale, code-barres EAN. C'est l'argument à sortir
     * devant le jury, pas la formule de calcul.
     */
    public function setIsbn(string $isbn): void
    {
        // 1. Nettoyage — les ISBN sont souvent écrits avec des tirets.
        $isbn = str_replace(['-', ' '], '', $isbn);

        // 2. Format — exactement 13 chiffres.
        if (preg_match('/^\d{13}$/', $isbn) !== 1) {
            throw new InvalidArgumentException('ISBN invalide : 13 chiffres attendus.');
        }

        // 3. Clé de contrôle — somme pondérée 1,3,1,3... sur les 12 premiers.
        $somme = 0;
        for ($i = 0; $i < 12; $i++) {
            $somme += (int) $isbn[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        $cleAttendue = (10 - ($somme % 10)) % 10;

        if ((int) $isbn[12] !== $cleAttendue) {
            throw new InvalidArgumentException('ISBN invalide : clé de contrôle incorrecte.');
        }

        $this->isbn = $isbn;
    }

    // ---------- Niveau 3, défi 2 : __toString() ----------

    /**
     * DIFFÉRENCE AVEC afficher() — réponse attendue du stagiaire :
     *
     *   afficher()    est une méthode MÉTIER, appelée explicitement.
     *                 Son nom documente l'intention. On peut en avoir
     *                 plusieurs : afficherCourt(), afficherComplet()...
     *
     *   __toString()  est un CONTRAT DE CONVERSION. PHP l'appelle
     *                 AUTOMATIQUEMENT dès que l'objet se retrouve dans
     *                 un contexte de chaîne :
     *                     echo $livre;
     *                     "Titre : $livre"
     *                     implode(', ', $tableauDeLivres)
     *                 Il ne peut y en avoir qu'une seule par classe.
     *
     * [PIÈGE] Ne jamais lever d'exception depuis __toString() : personne
     * ne s'attend à ce qu'un `echo` puisse planter. Techniquement permis
     * depuis PHP 7.4, mais reste une mauvaise pratique.
     */
    public function __toString(): string
    {
        return $this->afficher();
    }
}
