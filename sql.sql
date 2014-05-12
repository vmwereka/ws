-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 12 Mai 2014 à 13:44
-- Version du serveur: 5.1.37
-- Version de PHP: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `ws`
--

-- --------------------------------------------------------

--
-- Structure de la table `feeds`
--

CREATE TABLE IF NOT EXISTS `feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `URL` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `idUser` int(3) NOT NULL,
  `status` enum('private','public') NOT NULL DEFAULT 'private',
  PRIMARY KEY (`id`,`URL`,`idUser`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `feeds`
--

INSERT INTO `feeds` (`id`, `URL`, `title`, `idUser`, `status`) VALUES
(1, 'http://feeds.bbci.co.uk/news/england/rss.xml', '', 1, 'public'),
(2, 'http://jsimp90.wordpress.com/feed/', '', 1, 'public'),
(5, 'http://feeds.bbci.co.uk/news/world/africa/rss.xml', '', 1, 'private'),
(4, 'http://rss.cnn.com/rss/edition.rss', '', 1, 'private'),
(16, 'http://feeds.feedburner.com/Rtlinfos-ALaUne', 'RTL Info - A la Une', 1, 'private'),
(8, 'http://jsimp90.wordpress.com/feed', '', 1, 'private'),
(15, 'http://www.nba.com/lakers/rss.xml', 'Los Angeles Lakers', 1, 'public'),
(14, 'http://www.nba.com/clippers/rss.xml', 'THE OFFICIAL SITE OF THE LOS ANGELES CLIPPERS', 1, 'private'),
(17, 'http://www.nba.com/topvideo/rss.xml', 'NBA.com -  Top Videos', 1, 'private'),
(18, 'http://www.nba.com/nbatvtop10/rss.xml', 'NBA.com -  NBATV Top 10', 1, 'private'),
(19, 'http://www.nba.com/rss/barkleyzone.rss', 'NBA.com - Video', 1, 'private'),
(23, 'http://www.nba.com/rss/sekou_smith.rss', 'NBA.COM - Sekou Smith', 1, 'private'),
(22, 'http://allball.blogs.nba.com/feed/', 'NBA.com | All Ball Blog with Lang Whitaker', 1, 'private'),
(24, 'http://www.nba.com/rss/sekou_smith.rss', 'NBA.COM - Sekou Smith', 1, 'private'),
(25, 'http://www.nba.com/rss/steve_aschburner.rss', 'NBA.COM - Steve Aschburner', 1, 'private'),
(26, 'http://www.nba.com/rss/steve_aschburner.rss', 'NBA.COM - Steve Aschburner', 1, 'private');
