-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 17, 2023 at 07:19 PM
-- Server version: 10.6.14-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `Ads`
--

CREATE TABLE `Ads` (
  `ID` int(11) NOT NULL,
  `Image` longtext NOT NULL,
  `Link` longtext NOT NULL,
  `TimeRun` longtext NOT NULL,
  `Active` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Badges`
--

CREATE TABLE `Badges` (
  `UserID` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `Position` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Banner`
--

CREATE TABLE `Banner` (
  `Text` longtext NOT NULL,
  `Color` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Banner`
--

INSERT INTO `Banner` (`Text`, `Color`) VALUES
('test', 'Red');

-- --------------------------------------------------------

--
-- Table structure for table `BlogPosts`
--

CREATE TABLE `BlogPosts` (
  `ID` int(11) NOT NULL,
  `Title` longtext NOT NULL,
  `Body` longtext NOT NULL,
  `Poster` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Configuration`
--

CREATE TABLE `Configuration` (
  `Register` longtext NOT NULL,
  `MaintenanceType` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Configuration`
--

INSERT INTO `Configuration` (`Register`, `MaintenanceType`) VALUES
('true', 'Lockdown');

-- --------------------------------------------------------

--
-- Table structure for table `FRs`
--

CREATE TABLE `FRs` (
  `ID` int(11) NOT NULL,
  `SenderID` int(11) NOT NULL,
  `ReceiveID` int(11) NOT NULL,
  `Active` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GroupAllies`
--

CREATE TABLE `GroupAllies` (
  `ID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `OtherGroupID` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GroupEnemies`
--

CREATE TABLE `GroupEnemies` (
  `ID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `OtherGroupID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GroupMembers`
--

CREATE TABLE `GroupMembers` (
  `ID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

CREATE TABLE `Groups` (
  `ID` int(11) NOT NULL,
  `Name` longtext NOT NULL,
  `Description` longtext NOT NULL,
  `OwnerID` int(11) NOT NULL,
  `Logo` longtext NOT NULL,
  `LogoActive` int(11) NOT NULL DEFAULT 0,
  `GroupMembers` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GroupsLogo`
--

CREATE TABLE `GroupsLogo` (
  `ID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `Logo` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GroupsPending`
--

CREATE TABLE `GroupsPending` (
  `ID` int(11) NOT NULL,
  `Name` longtext NOT NULL,
  `Description` longtext NOT NULL,
  `OwnerID` int(11) NOT NULL,
  `Logo` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GroupWall`
--

CREATE TABLE `GroupWall` (
  `ID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `PosterID` int(11) NOT NULL,
  `Message` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Inventory`
--

CREATE TABLE `Inventory` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `File` longtext NOT NULL,
  `Type` longtext NOT NULL,
  `code1` longtext NOT NULL,
  `code2` longtext NOT NULL,
  `SerialNum` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `IPBans`
--

CREATE TABLE `IPBans` (
  `IP` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ItemComments`
--

CREATE TABLE `ItemComments` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Post` longtext NOT NULL,
  `time` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ItemDrafts`
--

CREATE TABLE `ItemDrafts` (
  `ID` int(11) NOT NULL,
  `Name` longtext NOT NULL,
  `File` longtext NOT NULL,
  `Type` longtext NOT NULL,
  `Price` longtext NOT NULL,
  `CreatorID` int(11) NOT NULL,
  `saletype` varchar(1337) NOT NULL,
  `numbersales` int(11) NOT NULL,
  `numberstock` int(11) NOT NULL,
  `sell` varchar(1337) NOT NULL DEFAULT 'yes',
  `Description` longtext NOT NULL,
  `CreationTime` longtext NOT NULL,
  `store` varchar(1337) NOT NULL DEFAULT 'regular',
  `timemake` longtext NOT NULL,
  `itemDeleted` int(11) NOT NULL,
  `SalePrices` int(11) NOT NULL,
  `NumberSold` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Items`
--

CREATE TABLE `Items` (
  `ID` int(11) NOT NULL,
  `Name` longtext NOT NULL,
  `File` longtext NOT NULL,
  `Type` longtext NOT NULL,
  `Price` longtext NOT NULL,
  `saletype` varchar(1337) NOT NULL,
  `numbersales` int(11) NOT NULL,
  `numberstock` int(11) NOT NULL,
  `sell` varchar(1337) NOT NULL DEFAULT 'yes',
  `Description` longtext NOT NULL,
  `CreationTime` longtext NOT NULL,
  `store` varchar(1337) NOT NULL DEFAULT 'regular',
  `timemake` longtext NOT NULL,
  `itemDeleted` int(11) NOT NULL DEFAULT 0,
  `SalePrices` int(11) NOT NULL DEFAULT 0,
  `NumberSold` int(11) NOT NULL,
  `CreatorID` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Logs`
--

CREATE TABLE `Logs` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Message` longtext NOT NULL,
  `Page` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Maintenance`
--

CREATE TABLE `Maintenance` (
  `Status` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Maintenance`
--

INSERT INTO `Maintenance` (`Status`) VALUES
('true');

-- --------------------------------------------------------

--
-- Table structure for table `PMs`
--

CREATE TABLE `PMs` (
  `ID` int(11) NOT NULL,
  `SenderID` int(11) NOT NULL,
  `ReceiveID` int(11) NOT NULL,
  `Title` longtext NOT NULL,
  `Body` longtext NOT NULL,
  `time` int(11) NOT NULL,
  `LookMessage` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PurchaseLog`
--

CREATE TABLE `PurchaseLog` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Item` longtext NOT NULL,
  `TypeStore` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Referrals`
--

CREATE TABLE `Referrals` (
  `ReferredID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Replies`
--

CREATE TABLE `Replies` (
  `ID` int(11) NOT NULL,
  `Body` longtext NOT NULL,
  `PosterID` int(11) NOT NULL,
  `tid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Reports`
--

CREATE TABLE `Reports` (
  `ID` int(11) NOT NULL,
  `Message` longtext NOT NULL,
  `OffenseID` longtext NOT NULL,
  `Link` longtext NOT NULL,
  `IP` longtext NOT NULL,
  `Content` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Sales`
--

CREATE TABLE `Sales` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Amount` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `SerialNum` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE `Staff` (
  `Username` mediumtext NOT NULL,
  `Rank` int(11) NOT NULL,
  `Job` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Threads`
--

CREATE TABLE `Threads` (
  `ID` int(11) NOT NULL,
  `Title` longtext NOT NULL,
  `Body` longtext NOT NULL,
  `PosterID` int(11) NOT NULL,
  `OriginalTitle` longtext NOT NULL,
  `OriginalBody` int(11) NOT NULL,
  `Locked` int(11) NOT NULL DEFAULT 0,
  `Type` varchar(1337) NOT NULL DEFAULT 'regular',
  `tid` int(11) NOT NULL,
  `bump` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Topics`
--

CREATE TABLE `Topics` (
  `ID` int(11) NOT NULL,
  `TopicName` longtext NOT NULL,
  `TopicDescription` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Topics`
--

INSERT INTO `Topics` (`ID`, `TopicName`, `TopicDescription`) VALUES
(1, 'test topic', 'test description');

-- --------------------------------------------------------

--
-- Table structure for table `TradeDrafts`
--

CREATE TABLE `TradeDrafts` (
  `ID` int(11) NOT NULL,
  `SenderID` int(11) NOT NULL,
  `ReceiveID` int(11) NOT NULL,
  `GetID1` int(11) NOT NULL,
  `GetID2` int(11) NOT NULL,
  `GetID3` int(11) NOT NULL,
  `GetID4` int(11) NOT NULL,
  `GetID5` int(11) NOT NULL,
  `LoseID1` int(11) NOT NULL,
  `LoseID2` int(11) NOT NULL,
  `LoseID3` int(11) NOT NULL,
  `LoseID4` int(11) NOT NULL,
  `LoseID5` int(11) NOT NULL,
  `tradeExpire` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `TradeRequests`
--

CREATE TABLE `TradeRequests` (
  `ID` int(11) NOT NULL,
  `SendorID` int(11) NOT NULL,
  `RecieverID` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `Bux` int(11) NOT NULL,
  `RequestedItem` longtext NOT NULL,
  `RequestedFile` int(11) NOT NULL,
  `Gems` int(11) NOT NULL,
  `Discount` int(11) NOT NULL,
  `TradeFile` int(11) NOT NULL,
  `TradeItem` int(11) NOT NULL,
  `Read` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `UserIPs`
--

CREATE TABLE `UserIPs` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `IP` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `UserItemComments`
--

CREATE TABLE `UserItemComments` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Post` longtext NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `Username` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Password` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ID` int(11) NOT NULL,
  `Rank` int(11) NOT NULL DEFAULT 0,
  `PowerAdmin` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'false',
  `Description` varchar(20000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none',
  `Email` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `IP` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `visitTick` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `expireTime` datetime NOT NULL,
  `PowerGame` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'false',
  `PowerImageModerator` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'false',
  `PowerForumModerator` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'false',
  `PowerArtist` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'false',
  `PowerMegaModerator` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'false',
  `OriginalName` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Eyes` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Mouth` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Hair` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Bottom` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Top` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Hat` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Shoes` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Accessory` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `forumflood` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Bux` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '15',
  `Rubies` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '10',
  `Background` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Body` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Avatar.png',
  `Ban` int(11) NOT NULL DEFAULT 0,
  `BanType` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `BanTime` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `BanDescription` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `BanLength` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Hash` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `SuccessReferrer` int(11) NOT NULL DEFAULT 0,
  `Premium` int(11) NOT NULL DEFAULT 0,
  `PremiumExpire` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `isTester` int(11) NOT NULL DEFAULT 0,
  `pviews` int(11) NOT NULL DEFAULT 0,
  `BanContent` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `PowerTop` varchar(1337) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `vipStart` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `vipEnd` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `vipsubscrid` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `adminID` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `room` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `myroomID` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `myroomIMG` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'templates/default/background.jpg	',
  `roomaccess` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `roomname` varchar(32) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `roommax` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '5',
  `roomMaxStart` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `roomMaxEnd` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `roommaxsubscrid` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `startX` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '100',
  `startY` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '180',
  `music` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'music/index.php',
  `avatar` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `avatara` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `avatarb` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `avatarc` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `avatar_x` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `avatar_y` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `online_time` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'nopic.jpg',
  `WallFlood` int(11) NOT NULL,
  `MainGroupID` int(11) NOT NULL,
  `userx` int(50) NOT NULL DEFAULT 5,
  `usery` int(50) NOT NULL DEFAULT 5,
  `gameid` int(50) NOT NULL,
  `CommentFlood` int(11) NOT NULL,
  `getBux` int(11) NOT NULL,
  `ingamenum` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `chatid` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `chatstatus` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ingame` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`Username`, `Password`, `ID`, `Rank`, `PowerAdmin`, `Description`, `Email`, `IP`, `visitTick`, `expireTime`, `PowerGame`, `PowerImageModerator`, `PowerForumModerator`, `PowerArtist`, `PowerMegaModerator`, `OriginalName`, `Eyes`, `Mouth`, `Hair`, `Bottom`, `Top`, `Hat`, `Shoes`, `Accessory`, `forumflood`, `Bux`, `Rubies`, `Background`, `Body`, `Ban`, `BanType`, `BanTime`, `BanDescription`, `BanLength`, `Hash`, `SuccessReferrer`, `Premium`, `PremiumExpire`, `isTester`, `pviews`, `BanContent`, `status`, `PowerTop`, `vipStart`, `vipEnd`, `vipsubscrid`, `adminID`, `room`, `myroomID`, `myroomIMG`, `roomaccess`, `roomname`, `roommax`, `roomMaxStart`, `roomMaxEnd`, `roommaxsubscrid`, `startX`, `startY`, `music`, `avatar`, `avatara`, `avatarb`, `avatarc`, `avatar_x`, `avatar_y`, `online_time`, `photo`, `WallFlood`, `MainGroupID`, `userx`, `usery`, `gameid`, `CommentFlood`, `getBux`, `ingamenum`, `chatid`, `chatstatus`, `ingame`) VALUES
('Isaac', '$2y$10$B8mujOLQbsM2aohOXjdqyeEfDdldfzqisnlmAH197hl4QGteJRKrq', 1, 5, 'true', 'iwebiwed', '', '', '1693100861', '2023-08-26 20:52:41', 'true', 'true', 'true', 'true', 'true', '', '', '', '', '', '', '', '', '', '1689352189', '26702', '10', '', 'Avatar.png', 0, '', '', '', '', '3be866f2c4bc33e343559677c2c6f1bd', 0, 1, 'unlimited', 0, 666, 'test', '0', '0', '0', '0', '0', '0', '', '', 'templates/default/background.jpg	', '1', '', '5', '0', '0', '0', '100', '180', 'music/index.php', '', '', '', '', '', '', '', 'nopic.jpg', 1690283305, 1, 5, 5, 0, 0, 1693187238, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `UserStore`
--

CREATE TABLE `UserStore` (
  `ID` int(11) NOT NULL,
  `Name` longtext NOT NULL,
  `File` longtext NOT NULL,
  `Type` longtext NOT NULL,
  `Price` int(11) NOT NULL,
  `CreatorID` int(11) NOT NULL,
  `saletype` varchar(1337) NOT NULL DEFAULT 'regular',
  `numbersales` varchar(50) NOT NULL DEFAULT 'regular',
  `numberstock` varchar(50) NOT NULL DEFAULT 'regular',
  `sell` varchar(50) NOT NULL DEFAULT 'yes',
  `ns` varchar(100) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT 0,
  `code1` longtext NOT NULL,
  `code2` longtext NOT NULL,
  `Description` longtext NOT NULL,
  `CreationTime` longtext NOT NULL,
  `store` varchar(1337) NOT NULL DEFAULT 'user',
  `itemDeleted` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Wall`
--

CREATE TABLE `Wall` (
  `ID` int(11) NOT NULL,
  `PosterID` int(11) NOT NULL,
  `Body` longtext NOT NULL,
  `time` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Ads`
--
ALTER TABLE `Ads`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Badges`
--
ALTER TABLE `Badges`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Banner`
--
ALTER TABLE `Banner`
  ADD UNIQUE KEY `Text` (`Text`) USING HASH;

--
-- Indexes for table `BlogPosts`
--
ALTER TABLE `BlogPosts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Configuration`
--
ALTER TABLE `Configuration`
  ADD UNIQUE KEY `Register` (`Register`) USING HASH;

--
-- Indexes for table `FRs`
--
ALTER TABLE `FRs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `GroupAllies`
--
ALTER TABLE `GroupAllies`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `GroupEnemies`
--
ALTER TABLE `GroupEnemies`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `GroupMembers`
--
ALTER TABLE `GroupMembers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Groups`
--
ALTER TABLE `Groups`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `GroupsLogo`
--
ALTER TABLE `GroupsLogo`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `GroupsPending`
--
ALTER TABLE `GroupsPending`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `GroupWall`
--
ALTER TABLE `GroupWall`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `IPBans`
--
ALTER TABLE `IPBans`
  ADD UNIQUE KEY `IP` (`IP`) USING HASH;

--
-- Indexes for table `ItemComments`
--
ALTER TABLE `ItemComments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ItemDrafts`
--
ALTER TABLE `ItemDrafts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Items`
--
ALTER TABLE `Items`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Logs`
--
ALTER TABLE `Logs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `PMs`
--
ALTER TABLE `PMs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `PurchaseLog`
--
ALTER TABLE `PurchaseLog`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Replies`
--
ALTER TABLE `Replies`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Reports`
--
ALTER TABLE `Reports`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Sales`
--
ALTER TABLE `Sales`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`Rank`);

--
-- Indexes for table `Threads`
--
ALTER TABLE `Threads`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Topics`
--
ALTER TABLE `Topics`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `TradeDrafts`
--
ALTER TABLE `TradeDrafts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `TradeRequests`
--
ALTER TABLE `TradeRequests`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `UserIPs`
--
ALTER TABLE `UserIPs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `UserItemComments`
--
ALTER TABLE `UserItemComments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `UserStore`
--
ALTER TABLE `UserStore`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Wall`
--
ALTER TABLE `Wall`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Ads`
--
ALTER TABLE `Ads`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Badges`
--
ALTER TABLE `Badges`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `BlogPosts`
--
ALTER TABLE `BlogPosts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `FRs`
--
ALTER TABLE `FRs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `GroupAllies`
--
ALTER TABLE `GroupAllies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `GroupEnemies`
--
ALTER TABLE `GroupEnemies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `GroupMembers`
--
ALTER TABLE `GroupMembers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Groups`
--
ALTER TABLE `Groups`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `GroupsLogo`
--
ALTER TABLE `GroupsLogo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `GroupsPending`
--
ALTER TABLE `GroupsPending`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `GroupWall`
--
ALTER TABLE `GroupWall`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ItemComments`
--
ALTER TABLE `ItemComments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ItemDrafts`
--
ALTER TABLE `ItemDrafts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1591;

--
-- AUTO_INCREMENT for table `Items`
--
ALTER TABLE `Items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Logs`
--
ALTER TABLE `Logs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `PMs`
--
ALTER TABLE `PMs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `PurchaseLog`
--
ALTER TABLE `PurchaseLog`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Replies`
--
ALTER TABLE `Replies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Reports`
--
ALTER TABLE `Reports`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `Sales`
--
ALTER TABLE `Sales`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Staff`
--
ALTER TABLE `Staff`
  MODIFY `Rank` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Threads`
--
ALTER TABLE `Threads`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Topics`
--
ALTER TABLE `Topics`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `TradeDrafts`
--
ALTER TABLE `TradeDrafts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `TradeRequests`
--
ALTER TABLE `TradeRequests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserIPs`
--
ALTER TABLE `UserIPs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `UserItemComments`
--
ALTER TABLE `UserItemComments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `UserStore`
--
ALTER TABLE `UserStore`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Wall`
--
ALTER TABLE `Wall`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
