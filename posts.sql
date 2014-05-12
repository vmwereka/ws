-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 30 Avril 2014 à 21:08
-- Version du serveur: 5.1.37
-- Version de PHP: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `ws`
--

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `URL` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `other` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id`, `URL`, `name`, `description`, `other`) VALUES
(1, 'hjhshh', 'hhdhdhdh', 'dhhdhdsh', 'dhdhhddh'),
(2, 'aaaaaaaaaaaaa', 'zzzzzzzzzzzzzzzz', 'eeeeeeeeeeeeeeeee', 'sssssssssssssss'),
(4, 'fdhdgfgsdfgh', 'sqhfgfqsgfq', '', 'dfghdfhdgfgzh'),
(5, 'last', 'last', 'last', 'last'),
(6, 'qqqqqqqqqqqqq', 'qqqqqqqqqqqqqq', 'qqqqqqqqqqqqqqq', 'qqqqqqqqqqqqqqqqq');
