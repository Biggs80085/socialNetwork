-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 23, 2021 at 06:08 PM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `network`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `id_bywho` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `content` text NOT NULL,
  `comment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `alpha3` varchar(3) NOT NULL,
  `nom_fr_fr` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `alpha3`, `nom_fr_fr`) VALUES
(1, 'AFG', 'Afghanistan'),
(2, 'ALB', 'Albanie'),
(3, 'ATA', 'Antarctique'),
(4, 'DZA', 'Algérie'),
(5, 'ASM', 'Samoa Américaines'),
(6, 'AND', 'Andorre'),
(7, 'AGO', 'Angola'),
(8, 'ATG', 'Antigua-et-Barbuda'),
(9, 'AZE', 'Azerbaïdjan'),
(10, 'ARG', 'Argentine'),
(11, 'AUS', 'Australie'),
(12, 'AUT', 'Autriche'),
(13, 'BHS', 'Bahamas'),
(14, 'BHR', 'Bahreïn'),
(15, 'BGD', 'Bangladesh'),
(16, 'ARM', 'Arménie'),
(17, 'BRB', 'Barbade'),
(18, 'BEL', 'Belgique'),
(19, 'BMU', 'Bermudes'),
(20, 'BTN', 'Bhoutan'),
(21, 'BOL', 'Bolivie'),
(22, 'BIH', 'Bosnie-Herzégovine'),
(23, 'BWA', 'Botswana'),
(24, 'BVT', 'Île Bouvet'),
(25, 'BRA', 'Brésil'),
(26, 'BLZ', 'Belize'),
(27, 'IOT', 'Territoire Britannique de l\'Océan Indien'),
(28, 'SLB', 'Îles Salomon'),
(29, 'VGB', 'Îles Vierges Britanniques'),
(30, 'BRN', 'Brunéi Darussalam'),
(31, 'BGR', 'Bulgarie'),
(32, 'MMR', 'Myanmar'),
(33, 'BDI', 'Burundi'),
(34, 'BLR', 'Bélarus'),
(35, 'KHM', 'Cambodge'),
(36, 'CMR', 'Cameroun'),
(37, 'CAN', 'Canada'),
(38, 'CPV', 'Cap-vert'),
(39, 'CYM', 'Îles Caïmanes'),
(40, 'CAF', 'République Centrafricaine'),
(41, 'LKA', 'Sri Lanka'),
(42, 'TCD', 'Tchad'),
(43, 'CHL', 'Chili'),
(44, 'CHN', 'Chine'),
(45, 'TWN', 'Taïwan'),
(46, 'CXR', 'Île Christmas'),
(47, 'CCK', 'Îles Cocos (Keeling)'),
(48, 'COL', 'Colombie'),
(49, 'COM', 'Comores'),
(50, 'MYT', 'Mayotte'),
(51, 'COG', 'République du Congo'),
(52, 'COD', 'République Démocratique du Congo'),
(53, 'COK', 'Îles Cook'),
(54, 'CRI', 'Costa Rica'),
(55, 'HRV', 'Croatie'),
(56, 'CUB', 'Cuba'),
(57, 'CYP', 'Chypre'),
(58, 'CZE', 'République Tchèque'),
(59, 'BEN', 'Bénin'),
(60, 'DNK', 'Danemark'),
(61, 'DMA', 'Dominique'),
(62, 'DOM', 'République Dominicaine'),
(63, 'ECU', 'Équateur'),
(64, 'SLV', 'El Salvador'),
(65, 'GNQ', 'Guinée Équatoriale'),
(66, 'ETH', 'Éthiopie'),
(67, 'ERI', 'Érythrée'),
(68, 'EST', 'Estonie'),
(69, 'FRO', 'Îles Féroé'),
(70, 'FLK', 'Îles (malvinas) Falkland'),
(71, 'SGS', 'Géorgie du Sud et les Îles Sandwich du Sud'),
(72, 'FJI', 'Fidji'),
(73, 'FIN', 'Finlande'),
(74, 'ALA', 'Îles Åland'),
(75, 'FRA', 'France'),
(76, 'GUF', 'Guyane Française'),
(77, 'PYF', 'Polynésie Française'),
(78, 'ATF', 'Terres Australes Françaises'),
(79, 'DJI', 'Djibouti'),
(80, 'GAB', 'Gabon'),
(81, 'GEO', 'Géorgie'),
(82, 'GMB', 'Gambie'),
(83, 'PSE', 'Territoire Palestinien Occupé'),
(84, 'DEU', 'Allemagne'),
(85, 'GHA', 'Ghana'),
(86, 'GIB', 'Gibraltar'),
(87, 'KIR', 'Kiribati'),
(88, 'GRC', 'Grèce'),
(89, 'GRL', 'Groenland'),
(90, 'GRD', 'Grenade'),
(91, 'GLP', 'Guadeloupe'),
(92, 'GUM', 'Guam'),
(93, 'GTM', 'Guatemala'),
(94, 'GIN', 'Guinée'),
(95, 'GUY', 'Guyana'),
(96, 'HTI', 'Haïti'),
(97, 'HMD', 'Îles Heard et Mcdonald'),
(98, 'VAT', 'Saint-Siège (état de la Cité du Vatican)'),
(99, 'HND', 'Honduras'),
(100, 'HKG', 'Hong-Kong'),
(101, 'HUN', 'Hongrie'),
(102, 'ISL', 'Islande'),
(103, 'IND', 'Inde'),
(104, 'IDN', 'Indonésie'),
(105, 'IRN', 'République Islamique d\'Iran'),
(106, 'IRQ', 'Iraq'),
(107, 'IRL', 'Irlande'),
(108, 'ISR', 'Israël'),
(109, 'ITA', 'Italie'),
(110, 'CIV', 'Côte d\'Ivoire'),
(111, 'JAM', 'Jamaïque'),
(112, 'JPN', 'Japon'),
(113, 'KAZ', 'Kazakhstan'),
(114, 'JOR', 'Jordanie'),
(115, 'KEN', 'Kenya'),
(116, 'PRK', 'République Populaire Démocratique de Corée'),
(117, 'KOR', 'République de Corée'),
(118, 'KWT', 'Koweït'),
(119, 'KGZ', 'Kirghizistan'),
(120, 'LAO', 'République Démocratique Populaire Lao'),
(121, 'LBN', 'Liban'),
(122, 'LSO', 'Lesotho'),
(123, 'LVA', 'Lettonie'),
(124, 'LBR', 'Libéria'),
(125, 'LBY', 'Jamahiriya Arabe Libyenne'),
(126, 'LIE', 'Liechtenstein'),
(127, 'LTU', 'Lituanie'),
(128, 'LUX', 'Luxembourg'),
(129, 'MAC', 'Macao'),
(130, 'MDG', 'Madagascar'),
(131, 'MWI', 'Malawi'),
(132, 'MYS', 'Malaisie'),
(133, 'MDV', 'Maldives'),
(134, 'MLI', 'Mali'),
(135, 'MLT', 'Malte'),
(136, 'MTQ', 'Martinique'),
(137, 'MRT', 'Mauritanie'),
(138, 'MUS', 'Maurice'),
(139, 'MEX', 'Mexique'),
(140, 'MCO', 'Monaco'),
(141, 'MNG', 'Mongolie'),
(142, 'MDA', 'République de Moldova'),
(143, 'MSR', 'Montserrat'),
(144, 'MAR', 'Maroc'),
(145, 'MOZ', 'Mozambique'),
(146, 'OMN', 'Oman'),
(147, 'NAM', 'Namibie'),
(148, 'NRU', 'Nauru'),
(149, 'NPL', 'Népal'),
(150, 'NLD', 'Pays-Bas'),
(151, 'ANT', 'Antilles Néerlandaises'),
(152, 'ABW', 'Aruba'),
(153, 'NCL', 'Nouvelle-Calédonie'),
(154, 'VUT', 'Vanuatu'),
(155, 'NZL', 'Nouvelle-Zélande'),
(156, 'NIC', 'Nicaragua'),
(157, 'NER', 'Niger'),
(158, 'NGA', 'Nigéria'),
(159, 'NIU', 'Niué'),
(160, 'NFK', 'Île Norfolk'),
(161, 'NOR', 'Norvège'),
(162, 'MNP', 'Îles Mariannes du Nord'),
(163, 'UMI', 'Îles Mineures Éloignées des États-Unis'),
(164, 'FSM', 'États Fédérés de Micronésie'),
(165, 'MHL', 'Îles Marshall'),
(166, 'PLW', 'Palaos'),
(167, 'PAK', 'Pakistan'),
(168, 'PAN', 'Panama'),
(169, 'PNG', 'Papouasie-Nouvelle-Guinée'),
(170, 'PRY', 'Paraguay'),
(171, 'PER', 'Pérou'),
(172, 'PHL', 'Philippines'),
(173, 'PCN', 'Pitcairn'),
(174, 'POL', 'Pologne'),
(175, 'PRT', 'Portugal'),
(176, 'GNB', 'Guinée-Bissau'),
(177, 'TLS', 'Timor-Leste'),
(178, 'PRI', 'Porto Rico'),
(179, 'QAT', 'Qatar'),
(180, 'REU', 'Réunion'),
(181, 'ROU', 'Roumanie'),
(182, 'RUS', 'Fédération de Russie'),
(183, 'RWA', 'Rwanda'),
(184, 'SHN', 'Sainte-Hélène'),
(185, 'KNA', 'Saint-Kitts-et-Nevis'),
(186, 'AIA', 'Anguilla'),
(187, 'LCA', 'Sainte-Lucie'),
(188, 'SPM', 'Saint-Pierre-et-Miquelon'),
(189, 'VCT', 'Saint-Vincent-et-les Grenadines'),
(190, 'SMR', 'Saint-Marin'),
(191, 'STP', 'Sao Tomé-et-Principe'),
(192, 'SAU', 'Arabie Saoudite'),
(193, 'SEN', 'Sénégal'),
(194, 'SYC', 'Seychelles'),
(195, 'SLE', 'Sierra Leone'),
(196, 'SGP', 'Singapour'),
(197, 'SVK', 'Slovaquie'),
(198, 'VNM', 'Viet Nam'),
(199, 'SVN', 'Slovénie'),
(200, 'SOM', 'Somalie'),
(201, 'ZAF', 'Afrique du Sud'),
(202, 'ZWE', 'Zimbabwe'),
(203, 'ESP', 'Espagne'),
(204, 'ESH', 'Sahara Occidental'),
(205, 'SDN', 'Soudan'),
(206, 'SUR', 'Suriname'),
(207, 'SJM', 'Svalbard etÎle Jan Mayen'),
(208, 'SWZ', 'Swaziland'),
(209, 'SWE', 'Suède'),
(210, 'CHE', 'Suisse'),
(211, 'SYR', 'République Arabe Syrienne'),
(212, 'TJK', 'Tadjikistan'),
(213, 'THA', 'Thaïlande'),
(214, 'TGO', 'Togo'),
(215, 'TKL', 'Tokelau'),
(216, 'TON', 'Tonga'),
(217, 'TTO', 'Trinité-et-Tobago'),
(218, 'ARE', 'Émirats Arabes Unis'),
(219, 'TUN', 'Tunisie'),
(220, 'TUR', 'Turquie'),
(221, 'TKM', 'Turkménistan'),
(222, 'TCA', 'Îles Turks et Caïques'),
(223, 'TUV', 'Tuvalu'),
(224, 'UGA', 'Ouganda'),
(225, 'UKR', 'Ukraine'),
(226, 'MKD', 'L\'ex-République Yougoslave de Macédoine'),
(227, 'EGY', 'Égypte'),
(228, 'GBR', 'Royaume-Uni'),
(229, 'IMN', 'Île de Man'),
(230, 'TZA', 'République-Unie de Tanzanie'),
(231, 'USA', 'États-Unis'),
(232, 'VIR', 'Îles Vierges des États-Unis'),
(233, 'BFA', 'Burkina Faso'),
(234, 'URY', 'Uruguay'),
(235, 'UZB', 'Ouzbékistan'),
(236, 'VEN', 'Venezuela'),
(237, 'WLF', 'Wallis et Futuna'),
(238, 'WSM', 'Samoa'),
(239, 'YEM', 'Yémen'),
(240, 'SCG', 'Serbie-et-Monténégro'),
(241, 'ZMB', 'Zambie');

-- --------------------------------------------------------

--
-- Table structure for table `cover_photo`
--

CREATE TABLE `cover_photo` (
  `id` int(11) NOT NULL,
  `id_adder` int(11) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

CREATE TABLE `friend` (
  `id` int(11) NOT NULL,
  `id_request` int(11) NOT NULL,
  `id_receive` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `id_blocker` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `liker`
--

CREATE TABLE `liker` (
  `id` int(11) NOT NULL,
  `id_bywho` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `date_like` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `messaging`
--

CREATE TABLE `messaging` (
  `id` int(11) NOT NULL,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  `message` text NOT NULL,
  `sending_date` datetime NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `id_receive` int(11) NOT NULL,
  `id_send` int(11) NOT NULL,
  `subject` varchar(20) NOT NULL,
  `link` varchar(255) NOT NULL,
  `date_notif` datetime NOT NULL,
  `view` int(11) NOT NULL DEFAULT '1',
  `state` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profil_photo`
--

CREATE TABLE `profil_photo` (
  `id` int(11) NOT NULL,
  `id_adder` int(11) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `publication`
--

CREATE TABLE `publication` (
  `id_pub` int(11) NOT NULL,
  `id_poster` int(11) NOT NULL,
  `content` text,
  `photo` varchar(100) DEFAULT NULL,
  `post_date` datetime NOT NULL,
  `confi_post` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `LastName` varchar(30) DEFAULT NULL,
  `Email` varchar(191) DEFAULT NULL,
  `Pw` text NOT NULL,
  `Birth` date NOT NULL,
  `Sexe` varchar(10) DEFAULT NULL,
  `question` varchar(100) NOT NULL,
  `ansewer` varchar(30) NOT NULL,
  `Country` varchar(30) NOT NULL DEFAULT 'Non Renseigné',
  `Situation` varchar(30) NOT NULL DEFAULT 'Non Renseigné',
  `Phone` varchar(10) NOT NULL DEFAULT 'XXXXXXXXXX',
  `profil_photo` varchar(255) NOT NULL,
  `cover_photo` varchar(50) NOT NULL,
  `sec_email` int(1) NOT NULL DEFAULT '0',
  `sec_date` int(1) NOT NULL DEFAULT '0',
  `sec_country` int(1) NOT NULL DEFAULT '0',
  `sec_situation` int(1) NOT NULL DEFAULT '0',
  `sec_phone` int(1) NOT NULL DEFAULT '0',
  `token_reset` varchar(30) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `connected` datetime DEFAULT NULL,
  `mode` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alpha3` (`alpha3`);

--
-- Indexes for table `cover_photo`
--
ALTER TABLE `cover_photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend`
--
ALTER TABLE `friend`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liker`
--
ALTER TABLE `liker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messaging`
--
ALTER TABLE `messaging`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil_photo`
--
ALTER TABLE `profil_photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`id_pub`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `cover_photo`
--
ALTER TABLE `cover_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friend`
--
ALTER TABLE `friend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `liker`
--
ALTER TABLE `liker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messaging`
--
ALTER TABLE `messaging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profil_photo`
--
ALTER TABLE `profil_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `publication`
--
ALTER TABLE `publication`
  MODIFY `id_pub` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;