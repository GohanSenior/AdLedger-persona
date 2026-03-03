-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 26, 2026 at 02:39 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adledger_persona`
--

-- --------------------------------------------------------

--
-- Table structure for table `associer`
--

CREATE TABLE `associer` (
  `id_persona` int NOT NULL,
  `id_criterion` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `associer`
--

INSERT INTO `associer` (`id_persona`, `id_criterion`) VALUES
(6, 1),
(40, 1),
(46, 1),
(6, 2),
(40, 2),
(6, 3),
(40, 3),
(18, 4),
(24, 4),
(30, 4),
(43, 4),
(44, 4),
(18, 5),
(24, 5),
(44, 5),
(8, 6),
(8, 7),
(9, 8),
(9, 9),
(19, 9),
(46, 9),
(47, 9),
(10, 10),
(10, 11),
(11, 12),
(11, 13),
(12, 14),
(12, 15),
(17, 16),
(26, 16),
(29, 16),
(17, 17),
(26, 17),
(29, 17),
(15, 18),
(15, 19),
(6, 20),
(38, 20),
(40, 20),
(6, 21),
(40, 21),
(18, 22),
(24, 22),
(44, 22),
(18, 23),
(24, 23),
(30, 23),
(40, 23),
(43, 23),
(44, 23),
(8, 24),
(8, 25),
(8, 26),
(9, 27),
(19, 27),
(9, 28),
(10, 29),
(19, 29),
(38, 29),
(45, 29),
(10, 30),
(45, 30),
(10, 31),
(11, 32),
(11, 33),
(42, 33),
(12, 34),
(12, 35),
(12, 36),
(17, 37),
(26, 37),
(29, 37),
(17, 38),
(26, 38),
(29, 38),
(15, 39),
(15, 40),
(6, 42),
(19, 42),
(6, 43),
(18, 44),
(24, 44),
(35, 44),
(37, 44),
(44, 44),
(18, 45),
(24, 45),
(44, 45),
(8, 46),
(36, 46),
(42, 46),
(8, 47),
(9, 48),
(9, 49),
(10, 50),
(19, 50),
(10, 51),
(38, 51),
(45, 51),
(11, 52),
(11, 53),
(12, 54),
(38, 54),
(12, 55),
(30, 55),
(43, 55),
(17, 56),
(26, 56),
(29, 56),
(17, 57),
(26, 57),
(29, 57),
(40, 57),
(15, 58),
(15, 59),
(40, 59),
(6, 60),
(17, 60),
(26, 60),
(29, 60),
(45, 60),
(46, 60),
(6, 61),
(19, 61),
(45, 61),
(6, 62),
(35, 62),
(37, 62),
(18, 63),
(24, 63),
(30, 63),
(36, 63),
(43, 63),
(44, 63),
(46, 63),
(18, 64),
(24, 64),
(30, 64),
(43, 64),
(44, 64),
(18, 65),
(24, 65),
(44, 65),
(8, 66),
(17, 66),
(26, 66),
(29, 66),
(8, 67),
(11, 67),
(42, 67),
(8, 68),
(19, 68),
(45, 68),
(9, 69),
(36, 69),
(9, 70),
(42, 70),
(46, 70),
(9, 71),
(10, 72),
(10, 73),
(35, 73),
(37, 73),
(38, 73),
(40, 73),
(10, 74),
(11, 75),
(15, 75),
(46, 75),
(11, 76),
(38, 76),
(45, 76),
(12, 77),
(30, 77),
(38, 77),
(40, 77),
(43, 77),
(12, 78),
(40, 78),
(12, 79),
(17, 80),
(26, 80),
(29, 80),
(42, 80),
(15, 81),
(15, 82),
(19, 83),
(19, 84),
(19, 85),
(40, 85),
(30, 86),
(43, 86),
(30, 87),
(43, 87),
(30, 88),
(43, 88),
(30, 89),
(43, 89),
(30, 90),
(43, 90),
(30, 91),
(43, 91),
(30, 92),
(43, 92),
(30, 93),
(43, 93),
(35, 94),
(37, 94),
(35, 95),
(37, 95),
(35, 96),
(36, 96),
(37, 96),
(35, 97),
(37, 97),
(36, 98),
(42, 98),
(38, 99),
(38, 100),
(45, 101),
(45, 102),
(45, 103),
(45, 104),
(45, 105),
(45, 106),
(45, 107),
(46, 108),
(46, 109),
(46, 110),
(47, 110),
(46, 111),
(46, 112),
(46, 113),
(46, 114),
(46, 115),
(46, 116),
(46, 117),
(47, 117),
(47, 118),
(47, 119),
(47, 120),
(47, 121),
(47, 122),
(47, 123),
(47, 124),
(47, 125),
(47, 126),
(47, 127),
(47, 128),
(47, 129),
(47, 130),
(47, 131);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id_company` int NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `company_address` varchar(50) NOT NULL,
  `company_zipcode` varchar(5) NOT NULL,
  `company_city` varchar(50) NOT NULL,
  `logo_url` varchar(50) DEFAULT NULL,
  `unique_brand` tinyint(1) NOT NULL DEFAULT '1',
  `id_company_responsable` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id_company`, `company_name`, `company_address`, `company_zipcode`, `company_city`, `logo_url`, `unique_brand`, `id_company_responsable`) VALUES
(2, 'Afpa Evreux', 'Boulevard du Président Allende', '27001', 'Evreux', 'assets/logo/6979f89f6f6e6_1769601183.jpg', 1, NULL),
(6, 'Toile de Com', '10 rue des Fours à Chaux', '14310', 'Villers Bocage', 'assets/logo/699570fa67bab_1771401466.png', 1, NULL),
(7, 'AFPA Caen', 'rue de Rosel', '14000', 'Caen', 'assets/logo/69957631be6bb_1771402801.jpg', 1, NULL),
(8, 'Toile de Com', '10 rue des Fours à Chaux', '14310', 'Villers-Bocage', 'assets/logo/6989bd22c8919_1770634530.webp', 1, NULL),
(9, 'Noname', '123 rue du sans nom', '14000', 'Caen', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `id_criterion` int NOT NULL,
  `criterion_description` text NOT NULL,
  `id_criteria_type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`id_criterion`, `criterion_description`, `id_criteria_type`) VALUES
(1, 'organiser ses projets', 1),
(2, 'suivre les deadlines', 1),
(3, 'coordonner les équipes', 1),
(4, 'augmenter son portefeuille clients', 1),
(5, 'suivre ses prospects', 1),
(6, 'comprendre les utilisateurs', 1),
(7, 'améliorer l’expérience produit', 1),
(8, 'mesurer l’impact de ses campagnes', 1),
(9, 'optimiser ses budgets', 1),
(10, 'augmenter le trafic qualifié', 1),
(11, 'réduire le coût d’acquisition', 1),
(12, 'suivre l’engagement', 1),
(13, 'analyser les tendances', 1),
(14, 'surveiller l’état du parc informatique', 1),
(15, 'anticiper les pannes', 1),
(16, 'réduire le temps de résolution', 1),
(17, 'suivre les tickets', 1),
(18, 'surveiller les équipements', 1),
(19, 'intervenir rapidement', 1),
(20, 'utilise Trello/Asana', 2),
(21, 'communique beaucoup par Slack', 2),
(22, 'passe beaucoup d’appels', 2),
(23, 'utilise un CRM quotidiennement', 2),
(24, 'interviews', 2),
(25, 'tests utilisateurs', 2),
(26, 'wireframes', 2),
(27, 'analyse les KPIs chaque semaine', 2),
(28, 'compare les canaux', 2),
(29, 'utilise Google Ads', 2),
(30, 'utilise Meta Ads', 2),
(31, 'dashboards', 2),
(32, 'publie quotidiennement', 2),
(33, 'suit les statistiques', 2),
(34, 'vérifie les logs', 2),
(35, 'suit les alertes', 2),
(36, 'documente les incidents', 2),
(37, 'analyse les récurrences', 2),
(38, 'répartit les tâches', 2),
(39, 'utilise des outils de monitoring', 2),
(40, 'fait des diagnostics', 2),
(42, 'surcharge d’informations', 3),
(43, 'manque de vision globale', 3),
(44, 'informations clients dispersées', 3),
(45, 'suivi irrégulier', 3),
(46, 'manque de données réelles', 3),
(47, 'contraintes techniques', 3),
(48, 'manque de centralisation', 3),
(49, 'reporting manuel', 3),
(50, 'données éparpillées', 3),
(51, 'difficulté à suivre plusieurs campagnes', 3),
(52, 'manque de vision long terme', 3),
(53, 'outils trop fragmentés', 3),
(54, 'trop d’outils différents', 3),
(55, 'manque de visibilité globale', 3),
(56, 'manque d’historique clair', 3),
(57, 'priorisation difficile', 3),
(58, 'alertes trop nombreuses', 3),
(59, 'manque de corrélation entre incidents', 3),
(60, 'organisé', 4),
(61, 'exigeant', 4),
(62, 'orienté résultats', 4),
(63, 'sociable', 4),
(64, 'persévérant', 4),
(65, 'compétitif', 4),
(66, 'empathique', 4),
(67, 'créatif', 4),
(68, 'analytique', 4),
(69, 'dynamique', 4),
(70, 'curieux', 4),
(71, 'orienté performance', 4),
(72, 'analytique', 4),
(73, 'rigoureux', 4),
(74, 'stratégique', 4),
(75, 'réactif', 4),
(76, 'communicant', 4),
(77, 'méthodique', 4),
(78, 'calme', 4),
(79, 'logique', 4),
(80, 'orienté solution', 4),
(81, 'précis', 4),
(82, 'autonome', 4),
(83, 'Comprendre les performances des campagnes multi‑canales', 1),
(84, 'Automatiser les rapports pour gagner du temps', 1),
(85, 'Perte de temps dans les exports manuels', 3),
(86, 'identifier rapidement les prospects les plus prometteurs', 1),
(87, 'améliorer le suivi des campagnes commerciales', 1),
(88, 'automatiser une partie de la relance et du reporting', 1),
(89, 'consulte ses mails quotidiennement', 2),
(90, 'prépare ses rendez-vous clients la veille avec des notes structurées', 2),
(91, 'perte de temps dans la saisie manuelle d’informations', 3),
(92, 'difficulté à prioriser les prospects quand la charge augmente', 3),
(93, 'outils internes parfois trop rigides ou mal adaptés à ses besoins', 3),
(94, 'Centraliser les données marketing pour gagner du temps', 1),
(95, 'Analyse ses tableaux de bord chaque matin', 2),
(96, 'Travaille en étroite collaboration avec les équipes commerciales', 2),
(97, 'Difficulté à obtenir une vision unifiée du parcours client', 3),
(98, 'Aider ses clients à améliorer leur présence en ligne', 1),
(99, 'Structurer et planifier les campagnes de communication pour éviter les retards', 1),
(100, 'Obtenir une vision claire des performances pour ajuster les budgets', 1),
(101, 'Augmenter la visibilité de la marque sur les réseaux sociaux', 1),
(102, 'Générer des leads qualifiés pour l’équipe commerciale', 1),
(103, 'Optimiser les campagnes publicitaires', 1),
(104, 'Consulte les performances marketing chaque matin', 2),
(105, 'Manque de temps pour analyser les données en profondeur', 3),
(106, 'Pression de la direction pour justifier les budgets marketing', 3),
(107, 'Difficulté à segmenter correctement les audiences', 3),
(108, 'Gagner du temps dans ses choix d’achat', 1),
(109, 'Très active sur Instagram, Pinterest et TikTok', 2),
(110, 'Consulte systématiquement les avis clients avant d’acheter', 2),
(111, 'Achète en ligne plusieurs fois par mois, souvent via mobile', 2),
(112, 'Sensible aux contenus visuels : vidéos courtes, avant/après, démonstrations', 2),
(113, 'Trop d’offres similaires, difficile de faire un choix rapide', 3),
(114, 'Méfiance envers les publicités trop “vendeuses”', 3),
(115, 'Manque de clarté sur les prix, les engagements et les conditions', 3),
(116, 'Perte de temps à comparer les produits', 3),
(117, 'Pragmatique', 4),
(118, 'Simplifier son quotidien grâce à des solutions rapides et fiables', 1),
(119, 'Éviter les pertes de temps dans ses achats ou démarches', 1),
(120, 'Trouver des produits pratiques, robustes et faciles à utiliser', 1),
(121, 'Utilise principalement son smartphone pour ses achats', 2),
(122, 'Achète en ligne 1 à 2 fois par mois, souvent en soirée', 2),
(123, 'Suit quelques influenceurs tech pour rester informé des nouveautés', 2),
(124, 'Déteste les interfaces compliquées ou les processus d’achat trop longs', 3),
(125, 'Perd patience face aux publicités trop vagues ou trop techniques', 3),
(126, 'Se méfie des offres “trop belles pour être vraies”', 3),
(127, 'N’aime pas comparer pendant des heures : il veut aller à l’essentiel', 3),
(128, 'Efficace', 4),
(129, 'Rationnel', 4),
(130, 'Responsable', 4),
(131, 'Fiable', 4);

-- --------------------------------------------------------

--
-- Table structure for table `criteria_types`
--

CREATE TABLE `criteria_types` (
  `id_criteria_type` int NOT NULL,
  `criteria_type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `criteria_types`
--

INSERT INTO `criteria_types` (`id_criteria_type`, `criteria_type_name`) VALUES
(1, 'Objectifs'),
(2, 'Comportements'),
(3, 'Difficultés'),
(4, 'Traits');

-- --------------------------------------------------------

--
-- Table structure for table `operations`
--

CREATE TABLE `operations` (
  `id_operation` int NOT NULL,
  `operation_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `operations`
--

INSERT INTO `operations` (`id_operation`, `operation_name`) VALUES
(1, 'Suivi des campagnes marketing'),
(2, 'Suivi Technique'),
(4, 'Soldes');

-- --------------------------------------------------------

--
-- Table structure for table `personas`
--

CREATE TABLE `personas` (
  `id_persona` int NOT NULL,
  `persona_lastname` varchar(50) NOT NULL,
  `persona_firstname` varchar(50) NOT NULL,
  `persona_age` int NOT NULL,
  `persona_sexe` enum('homme','femme','autre') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `avatar_options` text,
  `persona_city` varchar(50) NOT NULL,
  `persona_job` varchar(50) NOT NULL,
  `persona_created_at` date NOT NULL,
  `is_type` tinyint(1) NOT NULL DEFAULT '0',
  `typed` tinyint(1) NOT NULL DEFAULT '0',
  `id_persona_original` int DEFAULT NULL,
  `id_operation` int DEFAULT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `personas`
--

INSERT INTO `personas` (`id_persona`, `persona_lastname`, `persona_firstname`, `persona_age`, `persona_sexe`, `avatar_options`, `persona_city`, `persona_job`, `persona_created_at`, `is_type`, `typed`, `id_persona_original`, `id_operation`, `id_user`) VALUES
(1, 'Dupont', 'Jean', 20, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"shaggy\",\"facialHair\":\"beardLight\",\"facialHairProbability\":50}', 'Caen', 'technicien', '2026-01-28', 0, 0, NULL, 2, 1),
(3, 'Martin', 'Martine', 33, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"dreads\",\"facialHairProbability\":0}', 'Villers-Bocage', 'Fleuriste', '2026-01-29', 0, 0, NULL, NULL, 1),
(4, 'Dupont', 'Marie', 34, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"twinkle\",\"eyes\":\"wink\",\"skinColor\":\"614335\",\"top\":\"straight01\",\"facialHairProbability\":0,\"accessories\":\"wayfarers\"}', 'Cherbourg', 'Responsable marketing', '2026-01-29', 0, 0, NULL, 1, 1),
(5, 'Leclerc', 'Thomas', 28, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"top\":\"shortWaved\",\"facialHair\":\"moustacheMagnum\",\"facialHairProbability\":50,\"accessories\":\"prescription02\",\"eyes\":\"closed\",\"mouth\":\"default\"}', 'Nantes', 'Technicien support informatique', '2026-01-29', 0, 0, NULL, NULL, 1),
(6, 'Martin', 'Claire', 42, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"top\":\"straight01\",\"facialHairProbability\":0,\"accessories\":\"kurt\",\"eyes\":\"wink\"}', 'Lyon', 'Cheffe de projet digita', '2026-01-29', 0, 0, NULL, NULL, 1),
(8, 'Rahmani', 'Amina', 26, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"curly\",\"facialHairProbability\":0}', 'Paris', 'UX Designer', '2026-01-29', 0, 0, NULL, NULL, 1),
(9, 'Lambert', 'Sophie', 29, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"shavedSides\",\"facialHairProbability\":0,\"accessories\":\"round\",\"accessoriesProbability\":30,\"eyes\":\"wink\"}', 'Bordeaux', 'Chargée de communication', '2026-01-29', 0, 0, NULL, 1, 1),
(10, 'Perrin', 'Hugo', 37, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"shaggy\",\"facialHair\":\"beardLight\",\"facialHairProbability\":50}', 'Toulouse', 'Responsable acquisition digitale', '2026-01-29', 0, 0, NULL, 1, 1),
(11, 'Marchal', 'Élodie', 33, 'autre', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"facialHairProbability\":50,\"eyes\":\"closed\",\"accessories\":\"prescription02\"}', 'Strasbourg', 'Social Media Manager', '2026-01-29', 0, 0, NULL, 1, 1),
(12, 'Robert', 'Maxime', 40, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"top\":\"shortFlat\",\"facialHair\":\"beardMajestic\",\"facialHairProbability\":50,\"accessories\":\"round\",\"eyes\":\"default\"}', 'Caen', 'Administrateur système', '2026-01-29', 0, 0, NULL, 2, 1),
(15, 'Giraud', 'Nicolas', 30, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"top\":\"fro\",\"facialHair\":\"beardLight\",\"facialHairProbability\":50,\"accessories\":\"sunglasses\",\"eyes\":\"eyeRoll\"}', 'Rennes', 'Technicien réseau', '2026-02-02', 0, 0, NULL, 2, 1),
(16, 'Lefèvre', 'Nadia', 34, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"serious\",\"eyes\":\"winkWacky\",\"top\":\"bigHair\",\"facialHairProbability\":0,\"accessories\":\"round\"}', 'Rennes', 'Responsable Performance Marketing', '2026-02-02', 0, 0, NULL, 1, 1),
(17, 'Benetti', 'Laura', 35, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"twinkle\",\"eyes\":\"default\",\"skinColor\":\"ffdbb4\",\"top\":\"shavedSides\",\"facialHairProbability\":0,\"accessories\":\"prescription01\"}', 'Marseille', 'Responsable support technique', '2026-02-04', 0, 1, NULL, 2, 1),
(18, 'Caron', 'Julien', 31, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"serious\",\"eyes\":\"winkWacky\",\"top\":\"shortCurly\",\"facialHair\":\"beardMedium\",\"facialHairProbability\":50,\"accessories\":\"prescription02\"}', 'Lille', 'Commercial B2B', '2026-02-04', 0, 1, NULL, NULL, 1),
(19, 'Lefèvre', 'Thomas', 29, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"eyes\":\"wink\",\"skinColor\":\"edb98a\",\"top\":\"shortFlat\",\"facialHair\":\"moustacheFancy\",\"facialHairProbability\":50,\"accessories\":\"sunglasses\"}', 'Nantes', 'Analyste Marketing', '2026-02-04', 0, 0, NULL, 1, 1),
(24, 'Caron', 'Julien', 31, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"top\":\"shortFlat\",\"facialHair\":\"beardMajestic\",\"facialHairProbability\":50,\"accessories\":\"round\",\"eyes\":\"squint\"}', 'Lille', 'Commercial B2B', '2026-02-09', 1, 0, 18, NULL, 1),
(26, 'Benetti', 'Laura', 35, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"bun\",\"facialHairProbability\":0}', 'Marseille', 'Responsable support technique', '2026-02-09', 1, 0, 17, NULL, 1),
(29, 'Benetti', 'Laura', 35, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"bun\",\"facialHairProbability\":0}', 'Marseille', 'Responsable support technique', '2026-02-09', 0, 1, NULL, NULL, 5),
(30, 'Perrin', 'Nicolas', 41, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"shaggy\",\"facialHair\":\"moustacheMagnum\",\"facialHairProbability\":50}', 'Lyon', 'Responsable commercial dans une entreprise B2B', '2026-02-09', 1, 0, 25, NULL, 1),
(35, 'Delcourt', 'Marc', 42, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"top\":\"theCaesarAndSidePart\",\"facialHair\":\"moustacheMagnum\",\"facialHairProbability\":50,\"accessories\":\"sunglasses\",\"eyes\":\"happy\"}', 'Lyon', 'Chef de projet marketing', '2026-02-11', 0, 1, NULL, NULL, 5),
(36, 'Moretti', 'Claire', 29, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"top\":\"straightAndStrand\",\"facialHairProbability\":0,\"accessories\":\"prescription02\",\"eyes\":\"default\"}', 'Montpellier', 'Consultante en stratégie digitale', '2026-02-11', 0, 0, NULL, NULL, 5),
(37, 'Delcourt', 'Marc', 42, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"top\":\"shortRound\",\"facialHair\":\"beardMajestic\",\"facialHairProbability\":50,\"accessories\":\"prescription02\",\"eyes\":\"squint\"}', 'Lyon', 'Chef de projet marketing', '2026-02-11', 1, 0, 35, NULL, 1),
(38, 'Perrin', 'Lucas', 41, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"top\":\"fro\",\"facialHair\":\"beardMedium\",\"facialHairProbability\":50,\"accessories\":\"sunglasses\",\"eyes\":\"winkWacky\",\"mouth\":\"default\"}', 'Nantes', 'Chef de projet communication', '2026-02-12', 0, 0, NULL, NULL, 1),
(40, 'Doe', 'John', 50, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"twinkle\",\"eyes\":\"wink\",\"skinColor\":\"d08b5b\",\"top\":\"shaggyMullet\",\"facialHair\":\"beardMajestic\",\"facialHairProbability\":50,\"accessories\":\"sunglasses\"}', 'Paris', 'Inconnu', '2026-02-17', 0, 0, NULL, NULL, 1),
(42, 'BLIN', 'Mathilde', 35, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"twinkle\",\"eyes\":\"winkWacky\",\"skinColor\":\"fd9841\",\"top\":\"shavedSides\",\"facialHairProbability\":0,\"accessories\":\"prescription01\"}', 'CAEN', 'Coiffeuse', '2026-02-18', 0, 0, NULL, 4, 5),
(43, 'Perrin', 'Nicolas', 41, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"mouth\":\"smile\",\"top\":\"shaggy\",\"facialHair\":\"moustacheMagnum\",\"facialHairProbability\":50}', 'Lyon', 'Responsable commercial dans une entreprise B2B', '2026-02-24', 0, 1, NULL, NULL, 5),
(44, 'Caron', 'Julien', 31, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"smile\",\"top\":\"shortFlat\",\"facialHair\":\"beardMajestic\",\"facialHairProbability\":50,\"accessories\":\"round\",\"eyes\":\"squint\"}', 'Lille', 'Commercial B2B', '2026-02-25', 0, 1, NULL, NULL, 8),
(45, 'Martin', 'Clarisse', 34, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"default\",\"eyes\":\"winkWacky\",\"skinColor\":\"fd9841\",\"top\":\"bob\",\"facialHairProbability\":0,\"accessories\":\"kurt\"}', 'Rennes', 'Responsable Marketing Digital', '2026-02-25', 0, 0, NULL, NULL, 8),
(46, 'Delcourt', 'Sophie', 29, 'femme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"twinkle\",\"eyes\":\"default\",\"skinColor\":\"614335\",\"top\":\"straight02\",\"facialHairProbability\":0,\"accessories\":\"kurt\"}', 'Nantes', 'Chargée de communication', '2026-02-26', 0, 0, NULL, NULL, 8),
(47, 'Morel', 'Julien', 41, 'homme', '{\"size\":128,\"backgroundColor\":\"ed6da6\",\"radius\":50,\"accessoriesProbability\":30,\"mouth\":\"default\",\"eyes\":\"side\",\"skinColor\":\"edb98a\",\"top\":\"shortRound\",\"facialHair\":\"beardLight\",\"facialHairProbability\":50,\"accessories\":\"round\"}', 'Angers', 'Technicien support informatique', '2026-02-26', 0, 0, NULL, NULL, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `client` tinyint(1) NOT NULL DEFAULT '1',
  `boss` tinyint(1) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `id_company` int NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `lastname`, `firstname`, `email`, `phone`, `client`, `boss`, `enabled`, `role`, `id_company`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'admin1', '$2y$10$dYj/31nRTAWBkE/gFJCppe8OvZ9/Fh2HO8eCbrMyKjK1ytMG.PWAa', 'Gatti', 'Jerome', 'jerome.gatti@hotmail.fr', '07 78 20 22 58', 1, 1, 1, 'admin', 2, NULL, NULL),
(5, 'user2', '$2y$10$ppKhhBcAUsLvOngdeNrvm.uazyDOtEKIX/m7kPPdurrzrK7omnzci', 'BLEVIN', 'Caroline', 'doe.john@mail.com', '0616072739', 1, 1, 1, 'user', 6, NULL, NULL),
(6, 'user3', '$2y$10$7HtAklAUDVvNVtN0UTSWY.q6r9j6YO57A87fnN8si2S2zpCwpqwSy', 'Bin', 'Damien', 'bin.damien@mail.com', NULL, 1, 1, 1, 'user', 7, NULL, NULL),
(7, 'admin2', '$2y$10$zoqm9OHaGbCRB2Vyv8Swt.sZQv/PdtpehgLyYRb1jmo58ZKvHSGBy', 'Cosson', 'Fabrice', 'cosson.fabrice@mail.com', NULL, 1, 1, 1, 'admin', 8, NULL, NULL),
(8, 'user4', '$2y$10$w/C48J.C8igzdn0b35wfUeJrpCTHdKVeIUv52c9KULDpc8qOmqo8m', 'Doe', 'John', 'doe.jo@mail.com', NULL, 1, 1, 1, 'user', 9, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `associer`
--
ALTER TABLE `associer`
  ADD PRIMARY KEY (`id_persona`,`id_criterion`),
  ADD KEY `id_criterion` (`id_criterion`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id_company`);

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id_criterion`),
  ADD KEY `id_criteria_type` (`id_criteria_type`);

--
-- Indexes for table `criteria_types`
--
ALTER TABLE `criteria_types`
  ADD PRIMARY KEY (`id_criteria_type`);

--
-- Indexes for table `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`id_operation`);

--
-- Indexes for table `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `id_operation` (`id_operation`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id_company` (`id_company`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_reset_token` (`reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id_company` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id_criterion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `criteria_types`
--
ALTER TABLE `criteria_types`
  MODIFY `id_criteria_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `operations`
--
ALTER TABLE `operations`
  MODIFY `id_operation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `associer`
--
ALTER TABLE `associer`
  ADD CONSTRAINT `associer_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
  ADD CONSTRAINT `associer_ibfk_2` FOREIGN KEY (`id_criterion`) REFERENCES `criteria` (`id_criterion`);

--
-- Constraints for table `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `criteria_ibfk_1` FOREIGN KEY (`id_criteria_type`) REFERENCES `criteria_types` (`id_criteria_type`);

--
-- Constraints for table `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`id_operation`) REFERENCES `operations` (`id_operation`),
  ADD CONSTRAINT `personas_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
