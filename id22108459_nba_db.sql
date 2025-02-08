-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 03 mai 2024 à 14:02
-- Version du serveur : 10.5.20-MariaDB
-- Version de PHP : 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `id22108459_nba_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `Id_user` int(11) NOT NULL,
  `Id_joueur` int(11) NOT NULL,
  `Texte` text NOT NULL,
  `note` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`Id_user`, `Id_joueur`, `Texte`, `note`) VALUES
(17, 1, 'La plus grande carrière de tous les temps !', 10),
(17, 2, 'Il a définitivement changer le jeu.', 8),
(17, 5, 'Le goat', 10),
(17, 6, 'RIP to the mamba', 7),
(17, 8, 'Le meilleur trashtalker de l\'histoire !', 9),
(17, 9, 'top !', 9),
(17, 10, 'Un move INDEFENDABLE', 9),
(17, 11, 'Un joueur impressionnant mais d\'une autre époque', 7),
(17, 12, 'N\'as jamais réussi à gagner seul ', 6),
(17, 13, 'Le prime le plus inarrêtable de l\'histoire !', 8),
(17, 14, 'Le plus grand gagnant de notre sport', 8),
(17, 15, 'Beaucoup trop sous-coté', 9),
(17, 16, 'Merci la retraite de MJ', 7),
(17, 21, 'The logo', 6),
(17, 22, 'Trop oublier dans l\'histoire', 7),
(17, 23, 'une régularité hors norme avec son pote Stockton', 5),
(17, 24, 'leader des bad boys ', 6),
(17, 25, 'Mr.Triple double', 6),
(17, 26, 'Des bagues grace à Duncan mais une grosse carriere', 6),
(17, 27, 'Top 5 arriere de tous les temps.', 6),
(17, 28, 'Un poissard de legende', 5),
(17, 29, 'Dr.J', 6),
(17, 30, 'Bon basketteur, analyste bancale', 5),
(17, 31, 'Ma legende, Merci pour 2011 wunderkid', 10),
(17, 32, 'Big ticket', 5),
(17, 33, 'meilleur passeur de tous les temps mais pas de bagues dommage', 7),
(17, 34, 'Le premier MVP', 6),
(17, 35, 'Souvent oublié mais inévitable dans l\'histoire des celtics', 5),
(17, 36, 'Merci jojo', 4),
(17, 37, 'Le \"point god\" sans aucune bague ', 3),
(17, 38, 'Une dominance folle mais un nom impossible à écrire', 7),
(17, 39, 'Le plus grand knicks de tous les temps', 4),
(17, 40, '\"He steps over Tyron Lue\"', 6),
(17, 41, 'Individuellement très fort mais collectivement très faible.', 3),
(17, 42, 'Il a volé un MVP mais pour le reste rien à redire', 4),
(17, 43, 'La sous marque de Jordan', 3),
(17, 44, 'Un grand artisan du succès de Bill Russell', 4),
(17, 45, 'On dirait qu\'il jouait contre des enfants ', 3),
(17, 46, '10/10 en playoffs mais 2/10 le reste du temps ça fait 6', 6),
(17, 47, 'The human highlight', 3),
(17, 48, 'Sans commentaire', 2),
(17, 49, 'Basketteur formidable mais humain discutable', 4),
(17, 50, 'Même combat que Westbrook', 3),
(17, 51, 'Le meilleur Frenchie en attendant Wembanyama', 5),
(17, 52, 'C\'est Grue', 6),
(17, 53, 'Il a un nom marrant', 3),
(17, 54, 'Le potentiel pour être numéro 1 mais c\'est trop tôt pour l\'instant', 7),
(17, 55, 'Inconnu au bataillon', 2),
(17, 56, 'Grosse carrier pour peu de succès', 2),
(17, 57, 'un joueur atypique sur tous les points', 6),
(18, 1, 'My king, my lord', 10),
(18, 2, 'Le seul avoir autant tenu tête à LeBron', 8),
(18, 5, 'Clairement deuxième derrière mon roi', 9),
(18, 31, 'Il a privé LeBron d\'un three-peat', 7);

-- --------------------------------------------------------

--
-- Structure de la table `joueurs`
--

CREATE TABLE `joueurs` (
  `Id_joueur` int(11) NOT NULL,
  `NomJoueur` text NOT NULL,
  `PrenomJoueur` text NOT NULL,
  `NbBague` int(11) NOT NULL,
  `AnneeDraft` year(4) NOT NULL,
  `NbAnnee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `joueurs`
--

INSERT INTO `joueurs` (`Id_joueur`, `NomJoueur`, `PrenomJoueur`, `NbBague`, `AnneeDraft`, `NbAnnee`) VALUES
(1, 'James', 'LeBron', 4, '2003', 20),
(2, 'Curry', 'Stephen', 4, '2009', 14),
(5, 'Jordan', 'Michael', 6, '1984', 16),
(6, 'Bryant', 'Kobe', 5, '1996', 20),
(8, 'Bird', 'Larry', 3, '1978', 12),
(9, 'Johnson', 'Magic', 5, '1978', 12),
(10, 'Abdul-Jabbar', 'Kareem', 6, '1969', 19),
(11, 'Chamberlain', 'Wilt', 2, '1959', 13),
(12, 'Durant', 'Kevin', 2, '2007', 16),
(13, 'O\'Neal', 'Shaquille', 4, '1992', 19),
(14, 'Russell', 'Bill', 11, '1956', 13),
(15, 'Duncan', 'Tim', 5, '1997', 19),
(16, 'Olajuwon', 'Hakeem', 2, '1984', 18),
(21, 'West', 'Jerry', 1, '1960', 14),
(22, 'Malone', 'Moses', 1, '1976', 19),
(23, 'Malone', 'Karl', 0, '1985', 19),
(24, 'Thomas', 'Isiah', 2, '1981', 13),
(25, 'Robertson', 'Oscar', 1, '1960', 14),
(26, 'Robinson', 'David', 2, '1989', 14),
(27, 'Wade', 'Dwayne', 3, '2003', 16),
(28, 'Baylor', 'Elgin', 0, '1958', 14),
(29, 'Erving', 'Julius', 1, '1972', 11),
(30, 'Barkley', 'Charles', 0, '1984', 16),
(31, 'Nowitzki', 'Dirk', 1, '1998', 21),
(32, 'Garnett', 'Kevin', 1, '1995', 21),
(33, 'Stockton', 'John', 0, '1984', 19),
(34, 'Pettit', 'Bob', 1, '1954', 11),
(35, 'Havlicek', 'John', 8, '1962', 16),
(36, 'Pippen', 'Scottie', 6, '1987', 17),
(37, 'Paul', 'Chris', 0, '2005', 19),
(38, 'Antetokounmpo', 'Giannis', 1, '2013', 11),
(39, 'Ewing', 'Patrick', 0, '1985', 17),
(40, 'Iverson', 'Allen', 0, '1996', 14),
(41, 'Westbrook', 'Russell', 0, '2008', 16),
(42, 'Nash', 'Steve', 0, '1996', 19),
(43, 'Drexler', 'Clyde', 1, '1983', 15),
(44, 'Cousy', 'Bob', 6, '1950', 13),
(45, 'Mikan', 'George', 5, '1947', 9),
(46, 'Leonard', 'Kawhi', 2, '2011', 13),
(47, 'Wilkins', 'Dominique', 0, '1982', 17),
(48, 'Frazier', 'Walt', 2, '1967', 13),
(49, 'Kidd', 'Jason', 1, '1994', 19),
(50, 'Harden', 'James', 0, '2009', 15),
(51, 'Parker', 'Tony', 4, '2001', 18),
(52, 'jokic', 'Nikola', 1, '2015', 9),
(53, 'McAdoo', 'Bob', 2, '1972', 14),
(54, 'Doncic', 'Luka', 0, '2018', 6),
(55, 'Hayes', 'Elvin', 1, '1968', 16),
(56, 'Anthony', 'Carmelo', 0, '2003', 19),
(57, 'Rodman', 'Dennis', 5, '1986', 14);

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `Id_joueur` int(11) NOT NULL,
  `Id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`Id_joueur`, `Id_user`) VALUES
(1, 17),
(1, 18),
(2, 17),
(2, 18),
(5, 17),
(5, 18),
(5, 19),
(6, 18),
(6, 19),
(8, 17),
(8, 18),
(8, 19),
(9, 18),
(10, 18),
(11, 18),
(12, 18),
(13, 17),
(13, 18),
(14, 18),
(15, 17),
(15, 18),
(16, 18),
(21, 18),
(22, 18),
(23, 18),
(24, 18),
(25, 18),
(26, 18),
(27, 18),
(28, 18),
(29, 18),
(30, 18),
(31, 17),
(31, 18),
(32, 18),
(33, 18),
(34, 18),
(35, 18),
(36, 18),
(37, 18),
(38, 17),
(38, 18),
(39, 18),
(40, 17),
(40, 18),
(41, 18),
(42, 18),
(43, 18),
(44, 18),
(45, 18),
(45, 19),
(46, 17),
(46, 18),
(47, 18),
(48, 18),
(49, 17),
(49, 18),
(50, 18),
(51, 17),
(51, 18),
(52, 18),
(53, 18),
(54, 17),
(54, 18),
(55, 18),
(56, 18),
(57, 18);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `Id_user` int(11) NOT NULL,
  `Pseudo` text NOT NULL,
  `Mdp` text NOT NULL,
  `Photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`Id_user`, `Pseudo`, `Mdp`, `Photo`) VALUES
(17, 'LukaFanboy', 'luka', 'i.png'),
(18, 'LeBronCasual', 'LeBron', '1120.vresize.350.350.medium.77.png'),
(19, 'CTmieuxavant', 'CT', 's-l1200.png'),
(20, 'Tokita', 'Tokita', 'listing-icon-heading.png');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`Id_user`,`Id_joueur`),
  ADD KEY `Id_user` (`Id_user`),
  ADD KEY `Id_joueur` (`Id_joueur`);

--
-- Index pour la table `joueurs`
--
ALTER TABLE `joueurs`
  ADD PRIMARY KEY (`Id_joueur`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`Id_joueur`,`Id_user`),
  ADD KEY `Id_joueur` (`Id_joueur`),
  ADD KEY `Id_user` (`Id_user`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`Id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `joueurs`
--
ALTER TABLE `joueurs`
  MODIFY `Id_joueur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `Id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`Id_joueur`) REFERENCES `joueurs` (`Id_joueur`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`Id_user`) REFERENCES `utilisateurs` (`Id_user`);

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`Id_user`) REFERENCES `utilisateurs` (`Id_user`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`Id_joueur`) REFERENCES `joueurs` (`Id_joueur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
