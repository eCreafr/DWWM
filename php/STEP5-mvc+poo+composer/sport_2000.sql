-- Export généré par export_db.php
-- Date: 2025-12-26 17:04:32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


-- Structure de la table `s2_abonnes`
DROP TABLE IF EXISTS `s2_abonnes`;
CREATE TABLE `s2_abonnes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `mail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pswd` varchar(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `age` int NOT NULL,
  `genre` enum('Homme','Femme','Autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pays` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_abonnement` date NOT NULL,
  `date_fin_abonnement` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `s2_abonnes`
INSERT INTO `s2_abonnes` VALUES ('1', 'Dupont', 'Jean', 'jean.dupont@example.com', 'e10adc3949ba59abbe56e057f20f883e', '25', 'Homme', 'France', '2024-01-01', '2024-12-31');
INSERT INTO `s2_abonnes` VALUES ('2', 'Martin', 'Julie', 'julie.martin@example.com', 'e10adc3949ba59abbe56e057f20f883e', '30', 'Femme', 'Canada', '2024-03-01', '2024-12-31');
INSERT INTO `s2_abonnes` VALUES ('3', 'Doe', 'John', 'john.doe@example.com', '123', '35', 'Homme', 'États-Unis', '2024-05-01', '2025-05-01');


-- Structure de la table `s2_administrateurs`
DROP TABLE IF EXISTS `s2_administrateurs`;
CREATE TABLE `s2_administrateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `droits` enum('lecteur','editeur','admin') DEFAULT 'lecteur',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `s2_administrateurs`
INSERT INTO `s2_administrateurs` VALUES ('1', 'admin1', 'e10adc3949ba59abbe56e057f20f883e', 'admin');
INSERT INTO `s2_administrateurs` VALUES ('2', 'editeur1', 'e10adc3949ba59abbe56e057f20f883e', 'editeur');


-- Structure de la table `s2_articles_presse`
DROP TABLE IF EXISTS `s2_articles_presse`;
CREATE TABLE `s2_articles_presse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contenu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `auteur` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_publication` date NOT NULL,
  `match_id` int DEFAULT NULL,
  `image` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `s2_articles_presse`
INSERT INTO `s2_articles_presse` VALUES ('1', 'Le PSG triomphe à domicile ! whaouh !', 'Le Paris Saint-Germain a offert une performance de ouf dominante avec un score de 3-1 contre l\'OM. Les supporters étaient en liesse.\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? Pariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? Pariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!', 'Jean Dupont', '2024-11-02', '1', '');
INSERT INTO `s2_articles_presse` VALUES ('2', 'Monaco et Lyon se neutralisent', 'Un match captivant entre l\'AS Monaco et l\'Olympique Lyonnais, se soldant par un score de 2-2. Deux égalisations incroyables ont marqué ce duel.\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? \r\n\r\nPariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? \r\nPariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!', 'Alice Martin', '2024-10-21', '2', '');
INSERT INTO `s2_articles_presse` VALUES ('3', 'RC Lens brille à la dernière minute, ils sont forts !', 'Dans un match tendu, le RC Lens a marqué un but crucial à la 89e minute pour s\'imposer face au Lille OSC.\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? Pariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? Pariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!', 'Paul Girard', '2024-10-16', '3', '');
INSERT INTO `s2_articles_presse` VALUES ('4', 'Un thriller à Rennes', 'Le Stade Rennais a battu le FC Nantes dans un match palpitant qui a vu sept buts marqués et des retournements spectaculaires.\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? Pariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? Pariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!', 'Julie Bernard', '2024-09-26', '4', '');
INSERT INTO `s2_articles_presse` VALUES ('5', 'Le Golf Frisbee à Libourne : Une Activité Ludique et écologique !\r\n\r\n', 'Situé dans la magnifique région de Libourne, le golf frisbee est une activité en plein essor, parfaite pour les amateurs de sport et de nature. Ce jeu combine l’adresse du frisbee et la stratégie du golf traditionnel, le tout dans un cadre verdoyant. Le principe est simple : à la place de clubs et de balles, les joueurs utilisent un frisbee qu’ils doivent lancer dans des paniers métalliques. Chaque lancer est comptabilisé, et l’objectif est de terminer le parcours avec le moins de coups possibles.\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? Pariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!\r\n\r\nLorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum temporibus, repellat incidunt neque accusantium odio, obcaecati tenetur omnis sint aperiam amet explicabo ab sunt consequatur vero molestiae? Dolore earum exercitationem id sit? Quidem non quasi corporis rem blanditiis, id illum totam eaque eum ad beatae ab dicta quae vero iure officia nostrum molestiae molestias? Pariatur incidunt unde ad minima assumenda cum rerum earum commodi eum, culpa esse ab officiis itaque praesentium eligendi obcaecati! Et quidem ipsa quibusdam veniam officia, beatae eius, maiores, consequuntur expedita non omnis. Voluptatum voluptate, voluptas enim, veritatis obcaecati sapiente iure architecto recusandae asperiores vel quo exercitationem!\r\n\r\nLe parcours de Libourne, niché dans un parc naturel, propose un tracé de 18 trous conçu pour tous les niveaux. Que vous soyez débutant ou joueur confirmé, vous pourrez tester votre précision tout en profitant des paysages locaux. Les obstacles naturels comme les arbres et les étangs rendent chaque trou unique et stimulant. En outre, cette activité est accessible financièrement, ce qui en fait une excellente alternative aux loisirs traditionnels.\r\n\r\nLe golf frisbee est également respectueux de l’environnement. Contrairement au golf classique, il nécessite peu d’aménagements et ne consomme ni eau ni produits chimiques pour l’entretien du parcours. C’est une activité idéale pour promouvoir l’écoresponsabilité tout en s’amusant.\r\n\r\nLes clubs de Libourne organisent régulièrement des tournois, attirant des passionnés de toute la région Nouvelle-Aquitaine. Ces événements sont une occasion conviviale de découvrir ce sport et de rencontrer d’autres amateurs.\r\n\r\nEn conclusion, le golf frisbee à Libourne est bien plus qu’un simple loisir : c’est une aventure sportive, écologique et sociale. Que vous soyez en famille, entre amis ou en solo, n’hésitez pas à essayer cette activité originale qui allie adresse, détente et nature. Une expérience à ne pas manquer lors de votre passage en Gironde !', 'Raphaël LANG', '2024-12-01', '0', '');
INSERT INTO `s2_articles_presse` VALUES ('10', 'Le cyclocross à Libourne : Une Activité salissante !!!', 'lorem flemme Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum minus enim ipsa incidunt dicta, sunt reprehenderit ducimus molestiae, dignissimos officiis voluptate necessitatibus error vel numquam? Dolores repellat dignissimos cumque corporis nobis nemo quasi libero perferendis consequatur animi quas neque in quae atque nisi, at accusamus? Dolorum perspiciatis error libero quas molestiae optio excepturi et magnam! Neque adipisci cumque deserunt corporis culpa doloribus totam dolorem laboriosam earum assumenda, officiis blanditiis tenetur nesciunt praesentium distinctio cupiditate reprehenderit nostrum? Mollitia expedita illo commodi. Voluptatem iste, laborum, non officia dolores architecto quisquam pariatur ducimus dolore minima illum modi laudantium veniam cum esse quidem ea repudiandae provident consequuntur quae in eaque odit. Ratione, deserunt inventore, minus aspernatur vel enim illum ut minima cum ea corrupti fugiat nobis incidunt fugit quo ullam possimus at culpa vero veniam aperiam eaque dolore provident sint? Aut, quia corporis eos dolor veritatis nemo officia. Qui fuga earum officia alias aut! Recusandae perspiciatis iste ad animi aut architecto accusamus mollitia maxime in necessitatibus quaerat, velit perferendis beatae consectetur vitae deserunt? Doloribus, omnis laudantium? Consequuntur praesentium repellat minima necessitatibus maxime minus recusandae dicta voluptatum harum, aut saepe cupiditate quasi ratione soluta laboriosam totam accusantium deleniti, vitae eveniet vero repellendus exercitationem facilis animi?', 'Raphaël LANG', '2024-12-10', '0', '');
INSERT INTO `s2_articles_presse` VALUES ('21', 'Free fight  à Libourne contre Ste Foy !', 'tournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémentetournoi d\'exception malgré une météo peu clémente', 'Raphaël LANG', '2024-12-10', '8', '');
INSERT INTO `s2_articles_presse` VALUES ('24', 'Monaco et Lyon se neutralisent, match nul', 'coucou                Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum minus enim ipsa incidunt dicta, sunt reprehenderit ducimus molestiae, dignissimos officiis voluptate necessitatibus error vel numquam? Dolores repellat dignissimos cumque corporis nobis nemo quasi libero perferendis consequatur animi quas neque in quae atque nisi, at accusamus? Dolorum perspiciatis error libero quas molestiae optio excepturi et magnam! Neque adipisci cumque deserunt corporis culpa doloribus totam dolorem laboriosam earum assumenda, officiis blanditiis tenetur nesciunt praesentium distinctio cupiditate reprehenderit nostrum? Mollitia expedita illo commodi. Voluptatem iste, laborum, non officia dolores architecto quisquam pariatur ducimus dolore minima illum modi laudantium veniam cum esse quidem ea repudiandae provident consequuntur quae in eaque odit. Ratione, deserunt inventore, minus aspernatur vel enim illum ut minima cum ea corrupti fugiat nobis incidunt fugit quo ullam possimus at culpa vero veniam aperiam eaque dolore provident sint? Aut, quia corporis eos dolor veritatis nemo officia. Qui fuga earum officia alias aut! Recusandae perspiciatis iste ad animi aut architecto accusamus mollitia maxime in necessitatibus quaerat, velit perferendis beatae consectetur vitae deserunt? Doloribus, omnis laudantium? Consequuntur praesentium repellat minima necessitatibus maxime minus recusandae dicta voluptatum harum, aut saepe cupiditate quasi ratione soluta laboriosam totam accusantium deleniti, vitae eveniet vero repellendus exercitationem facilis animi?', 'Raphaël LANG', '2024-12-10', '7', '');
INSERT INTO `s2_articles_presse` VALUES ('27', 'Le Golf Frisbee à Libourne,  Une Activité Écologique', 'eh bey c\'est bieng  !', 'Raphaël LANG', '2024-12-11', '9', '');
INSERT INTO `s2_articles_presse` VALUES ('46', 'ejqtjqeej', 'erejrsj', 'Raphaël LANG', '2025-10-07', '0', '');
INSERT INTO `s2_articles_presse` VALUES ('42', 'Test d\'ajout d\'image', 'on récupère l\'id de l\'article ajouté puis on renomme l\'image importée', 'rapha', '2025-10-03', '0', '');
INSERT INTO `s2_articles_presse` VALUES ('43', 'Le PSG triomphe à domicile ! whaouh !', 'pas d\'inspi fzgzeg z', 'test ajout image 2', '2025-10-03', '15', '');


-- Structure de la table `s2_resultats_sportifs`
DROP TABLE IF EXISTS `s2_resultats_sportifs`;
CREATE TABLE `s2_resultats_sportifs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipe1` varchar(100) NOT NULL,
  `equipe2` varchar(100) NOT NULL,
  `score` varchar(10) NOT NULL,
  `resume` text NOT NULL,
  `lieu` varchar(100) NOT NULL,
  `date_match` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `s2_resultats_sportifs`
INSERT INTO `s2_resultats_sportifs` VALUES ('1', 'Paris Saint-Germain', 'Olympique de Marseille', '3-1', 'Match intense avec des buts spectaculaires et une domination globale du PSG.', 'Paris', '2024-11-01');
INSERT INTO `s2_resultats_sportifs` VALUES ('2', 'AS Monaco', 'Olympique Lyonnais', '2-2', 'Un match équilibré avec deux égalisations spectaculaires.', 'Monaco', '2024-10-20');
INSERT INTO `s2_resultats_sportifs` VALUES ('3', 'RC Lens', 'Lille OSC', '1-0', 'Un but décisif à la 89e minute offre une victoire au RC Lens.', 'Lens', '2024-10-15');
INSERT INTO `s2_resultats_sportifs` VALUES ('4', 'Stade Rennais', 'FC Nantes', '4-3', 'Un thriller à Rennes avec des retournements de situation constants.', 'Rennes', '2024-09-25');
INSERT INTO `s2_resultats_sportifs` VALUES ('9', 'Sainte Foy', 'Lyon', '0-1', 'aute du joueur français', 'Libourne parc des Dagueys', '2024-12-11');
INSERT INTO `s2_resultats_sportifs` VALUES ('8', 'Monaco', 'Lyon', '1-1', '', 'Lyon', '2024-12-10');
INSERT INTO `s2_resultats_sportifs` VALUES ('7', 'Sainte Foy', 'Libourne', '12-75', 'RAS c\'est un match moyen par jour de pluie', 'Libourne parc des Dagueys', '2024-12-10');
INSERT INTO `s2_resultats_sportifs` VALUES ('15', 'DWWM', 'CDUI', '4-2', 'un beau match !', 'La Teste', '2025-10-03');


-- Structure de la table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pswd` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` varchar(20) NOT NULL,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `users`
INSERT INTO `users` VALUES ('2', 'Jane Doe', 'jane@example.com', '$2y$10$L31Y/R4ueZVqfdNl8kIdnuDTQrdc1V2ylfCOc5ygoH9wQLTw1hAcy', 'USER', NULL, '0');
INSERT INTO `users` VALUES ('1', 'Raphael Lang', 'raphael.lang@gmail.com', '$2y$10$L31Y/R4ueZVqfdNl8kIdnuDTQrdc1V2ylfCOc5ygoH9wQLTw1hAcy', 'ADMIN', 'KGMDJJNXJO7RAPUNEAWXGKGQ7CHVJDNT', '1');
INSERT INTO `users` VALUES ('3', 'Raphaël Lang', 'raphael@ecrea.fr', '$2y$10$L31Y/R4ueZVqfdNl8kIdnuDTQrdc1V2ylfCOc5ygoH9wQLTw1hAcy', 'USER', NULL, '0');
INSERT INTO `users` VALUES ('4', 'Raph.aël Lang', 'raph.ael.lang@gmail.com', '$2y$10$L31Y/R4ueZVqfdNl8kIdnuDTQrdc1V2ylfCOc5ygoH9wQLTw1hAcy', 'USER', NULL, '0');

COMMIT;
