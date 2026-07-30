<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 2 — NIVEAU 1 — Migration de Livre vers l'héritage
 *
 * C'EST LE FICHIER LE PLUS IMPORTANT DE LA JOURNÉE.
 * Prenez le temps de comprendre ce que vous y faites : tout le reste
 * de l'étape 2 en découle, et vous vous servirez de VOTRE Livre comme
 * modèle pour écrire Dvd et JeuVideo au niveau 2.
 *
 * ==================================================================
 * VOTRE LIVRE.PHP DE L'ÉTAPE 1 RESSEMBLAIT À CECI :
 * ==================================================================
 *
 *   class Livre
 *   {
 *       public function __construct(
 *           private string $titre,      <- commun a tous les documents
 *           private string $auteur,     <- specifique au livre
 *           private int    $annee       <- commun a tous les documents
 *       ) {}
 *
 *       public function getTitre(): string  { return $this->titre;  }
 *       public function getAuteur(): string { return $this->auteur; }
 *       public function getAnnee(): int     { return $this->annee;  }
 *   }
 *
 * ==================================================================
 * CE QUE VOUS ALLEZ EN FAIRE
 * ==================================================================
 *
 * Ouvrez Document.php a cote : il porte DEJA $titre, $annee,
 * $disponible, getTitre() et getAnnee().
 *
 * Donc dans Livre, tout cela DISPARAIT. Ne reste que ce qui est
 * specifique au livre : l'auteur.
 *
 * C'est cela, factoriser : le code commun remonte UNE FOIS dans le
 * parent, au lieu d'etre recopie dans chaque fille.
 *
 * ==================================================================
 * TODO 1 a 5 — dans l'ordre. Testez avec `php index.php` apres chacun.
 * ==================================================================
 */


/**
 * TODO 1 — LA DECLARATION DE CLASSE
 *
 * Completez la ligne ci-dessous pour que Livre :
 *   - HERITE de Document                -> mot-cle `extends`
 *   - IMPLEMENTE le contrat Empruntable -> mot-cle `implements`
 *
 * Syntaxe :  class X extends Parent implements Interface
 *
 * [PIEGE] L'ordre compte : `extends` AVANT `implements`.
 * Et il n'y a qu'UN SEUL extends possible (heritage simple), alors
 * qu'on peut implementer autant d'interfaces qu'on veut.
 */
class Livre                                    // <- TODO 1 : a completer
{
    /**
     * TODO 2 — LE CONSTRUCTEUR
     *
     * a) La signature recoit trois parametres :
     *      - string $titre   -> appartient au PARENT : PAS de `private` devant
     *      - int    $annee   -> idem, PAS de `private`
     *      - $auteur         -> specifique au livre : celui-ci GARDE son `private`
     *
     *    Regle a retenir : on ne met `private`/`protected` devant un parametre
     *    QUE si la propriete appartient a cette classe-ci. Sinon on la
     *    redeclare par-dessus celle du parent — erreur classique qui casse
     *    l'interet meme de l'heritage.
     *
     * b) Le corps doit confier titre et annee au constructeur du parent :
     *
     *      parent::__construct($titre, $annee);
     *
     * [PIEGE] Oublier cette ligne est L'ERREUR N.1 de cette etape.
     * Symptome : « Typed property Document::$titre must not be accessed
     * before initialization ». Traduisez ce message : il dit exactement
     * ce qui manque.
     */
    public function __construct(
        // TODO 2a : vos trois parametres ici
    ) {
        // TODO 2b : l'appel au constructeur parent ici
    }

    /**
     * TODO 3 — Le getter de l'auteur.
     *
     * Recopiez-le depuis votre Livre.php de l'etape 1 : l'auteur est
     * la seule propriete qui reste a Livre, donc c'est le seul getter
     * qui subsiste ici.
     *
     * Remarquez que getTitre() et getAnnee() ont disparu : ils sont
     * HERITES de Document. Vous n'avez PAS a les reecrire — et le test
     * index.php les appellera quand meme avec succes. Verifiez-le,
     * c'est le meilleur moyen de comprendre ce qu'apporte l'heritage.
     */
    public function getAuteur(): string
    {
        // TODO 3 : votre code ici
    }

    /**
     * TODO 4 — LA METHODE ABSTRAITE DU PARENT
     *
     * Document declare `abstract public function getDescription(): string;`
     * sans corps. Toute classe fille DOIT en fournir une implementation,
     * sinon PHP refuse de charger la classe.
     *
     * Retournez une chaine au format EXACT :
     *   Dune (1965), de Frank Herbert
     *
     * [NOTE] Vous pouvez ecrire $this->titre et $this->annee ici, bien
     * qu'elles soient declarees dans Document : c'est precisement ce que
     * permet la visibilite `protected`. Si elles etaient `private` dans
     * le parent, cette ligne echouerait.
     *
     * Fonction utile : sprintf('%s (%d), de %s', ...)
     */
    public function getDescription(): string
    {
        // TODO 4 : votre code ici
    }

    // =================================================================
    // TODO 5 — LE CONTRAT Empruntable
    // =================================================================
    //
    // L'interface Empruntable impose deux methodes. Ecrivez-les ici :
    //
    //   - emprunter() : passe $this->disponible a false, retourne void
    //   - rendre()    : passe $this->disponible a true,  retourne void
    //
    // [NOTE] $disponible vient aussi de Document, en protected.
    //
    // [TEST] Tant que ces deux methodes manquent, PHP refuse la classe :
    // « Class Livre contains 2 abstract methods... ». C'est la
    // demonstration en direct de ce qu'est un CONTRAT — PHP a verifie
    // votre engagement et l'a fait respecter.

    // TODO 5 : vos deux methodes ici
}
