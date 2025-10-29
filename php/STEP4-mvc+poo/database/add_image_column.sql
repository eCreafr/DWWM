-- Ajouter la colonne image à la table s2_articles_presse
ALTER TABLE s2_articles_presse
ADD COLUMN image VARCHAR(255) NULL AFTER date_publication;
