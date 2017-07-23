-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2017 at 10:03 PM
-- Server version: 10.0.29-MariaDB-0ubuntu0.16.04.1
-- PHP Version: 7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_list`
--

CREATE TABLE `access_list` (
  `id` int(11) UNSIGNED NOT NULL,
  `role_id` int(2) UNSIGNED NOT NULL,
  `resource_id` int(11) UNSIGNED NOT NULL,
  `access_name` varchar(64) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `access_list`
--

INSERT INTO `access_list` (`id`, `role_id`, `resource_id`, `access_name`, `status`) VALUES
(1, 3, 1, '*', 1),
(2, 1, 8, '*', 1),
(3, 2, 8, '*', 1),
(4, 1, 9, '*', 1),
(5, 2, 9, '*', 1),
(6, 2, 10, 'logout', 1),
(7, 1, 10, 'login', 1),
(8, 1, 10, 'loginWithFacebook', 1),
(9, 1, 10, 'loginWithGoogle', 1),
(10, 1, 10, 'register', 1),
(11, 1, 10, 'confirmEmail', 1),
(12, 1, 10, 'forgotPassword', 1),
(13, 1, 10, 'resetPassword', 1),
(14, 1, 10, 'index', 1),
(15, 2, 10, 'index', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `metadata` varchar(2048) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL,
  `view_level` tinyint(4) NOT NULL,
  `parent_id` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `level` int(2) NOT NULL DEFAULT '0',
  `lft` int(5) NOT NULL DEFAULT '0',
  `rgt` int(5) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `modified_at` int(11) DEFAULT NULL,
  `modified_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `alias`, `description`, `metadata`, `hits`, `status`, `view_level`, `parent_id`, `level`, `lft`, `rgt`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
(1, 'News', 'news', '', 'news', 0, 1, 1, 0, 1, 2, 3, 1458162568, 1, 1474814410, 1),
(2, 'Articles', 'articles', 'Articles', 'articles', 0, 1, 1, 1, 1, 4, 5, 1458587556, 1, 1474814412, 1);

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `introtext` mediumtext,
  `fulltext` mediumtext,
  `metadata` varchar(2048) NOT NULL,
  `category` int(11) UNSIGNED NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL,
  `view_level` tinyint(4) NOT NULL,
  `published_at` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `modified_at` int(11) DEFAULT NULL,
  `modified_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `title`, `alias`, `introtext`, `fulltext`, `metadata`, `category`, `hits`, `featured`, `status`, `view_level`, `published_at`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
(1, 'Lorem ipsum dolor sit amet', 'lorem-ipsum', '&lt;p&gt;Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.&lt;/p&gt;', '&lt;p&gt;Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc&lt;/p&gt;', 'lorem,ipsum', 1, 0, 0, 1, 1, 1484776800, 1476904795, 1, 1485805039, 1),
(2, 'Sed ut perspiciatis', 'sed-ut-perspiciatis', '&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;By spite about do of do allow blush. Additions in conveying or collected objection in. Suffer few &lt;img class=&quot;&quot; style=&quot;float: left;&quot; src=&quot;../../../../media/749536-red-steel-2.jpg&quot; alt=&quot;&quot; width=&quot;212&quot; height=&quot;119&quot; /&gt;desire wonder her object hardly nearer. Abroad no chatty others my silent an. Fat way appear denote who wholly narrow gay settle. Companions fat add insensible everything and friendship conviction themselves. Theirs months ten had add narrow own.&lt;/span&gt;&lt;/p&gt;', '&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;&lt;img style=&quot;float: left;&quot; src=&quot;../../../../media/749536-red-steel-2.jpg&quot; width=&quot;100%&quot; /&gt;By spite about do of do allow blush. Additions in conveying or collected objection in. Suffer few desire wonder her object hardly nearer. Abroad no chatty others my silent an. Fat way appear denote who wholly narrow gay settle. Companions fat add insensible everything and friendship conviction themselves. Theirs months ten had add narrow own.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Was certainty remaining engrossed applauded sir how discovery. Settled opinion how enjoyed greater joy adapted too shy. Now properly surprise expenses interest nor replying she she. Bore tall nay many many time yet less. Doubtful for answered one fat indulged margaret sir shutters together. Ladies so in wholly around whence in at. Warmth he up giving oppose if. Impossible is dissimilar entreaties oh on terminated. Earnest studied article country ten respect showing had. But required offering him elegance son improved informed.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Finished her are its honoured drawings nor. Pretty see mutual thrown all not edward ten. Particular an boisterous up he reasonably frequently. Several any had enjoyed shewing studied two. Up intention remainder sportsmen behaviour ye happiness. Few again any alone style added abode ask. Nay projecting unpleasing boisterous eat discovered solicitude. Own six moments produce elderly pasture far arrival. Hold our year they ten upon. Gentleman contained so intention sweetness in on resolving.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Both rest of know draw fond post as. It agreement defective to excellent. Feebly do engage of narrow. Extensive repulsive belonging depending if promotion be zealously as. Preference inquietude ask now are dispatched led appearance. Small meant in so doubt hopes. Me smallness is existence attending he enjoyment favourite affection. Delivered is to ye belonging enjoyment preferred. Astonished and acceptance men two discretion. Law education recommend did objection how old.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;It sportsman earnestly ye preserved an on. Moment led family sooner cannot her window pulled any. Or raillery if improved landlord to speaking hastened differed he. Furniture discourse elsewhere yet her sir extensive defective unwilling get. Why resolution one motionless you him thoroughly. Noise is round to in it quick timed doors. Written address greatly get attacks inhabit pursuit our but. Lasted hunted enough an up seeing in lively letter. Had judgment out opinions property the supplied.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Smallest directly families surprise honoured am an. Speaking replying mistress him numerous she returned feelings may day. Evening way luckily son exposed get general greatly. Zealously prevailed be arranging do. Set arranging too dejection september happiness. Understood instrument or do connection no appearance do invitation. Dried quick round it or order. Add past see west felt did any. Say out noise you taste merry plate you share. My resolve arrived is we chamber be removal.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Gave read use way make spot how nor. In daughter goodness an likewise oh consider at procured wandered. Songs words wrong by me hills heard timed. Happy eat may doors songs. Be ignorant so of suitable dissuade weddings together. Least whole timed we is. An smallness deficient discourse do newspaper be an eagerness continued. Mr my ready guest ye after short at.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Promotion an ourselves up otherwise my. High what each snug rich far yet easy. In companions inhabiting mr principles at insensible do. Heard their sex hoped enjoy vexed child for. Prosperous so occasional assistance it discovered especially no. Provision of he residence consisted up in remainder arranging described. Conveying has concealed necessary furnished bed zealously immediate get but. Terminated as middletons or by instrument. Bred do four so your felt with. No shameless principle dependent household do.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;Boy favourable day can introduced sentiments entreaties. Noisier carried of in warrant because. So mr plate seems cause chief widen first. Two differed husbands met screened his. Bed was form wife out ask draw. Wholly coming at we no enable. Offending sir delivered questions now new met. Acceptance she interested new boisterous day discretion celebrated.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-size: 12pt;&quot;&gt;At ourselves direction believing do he departure. Celebrated her had sentiments understood are projection set. Possession ye no mr unaffected remarkably at. Wrote house in never fruit up. Pasture imagine my garrets an he. However distant she request behaved see nothing. Talking settled at pleased an of me brother weather.&lt;/span&gt;&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;', 'set,ut', 1, 61, 0, 1, 1, 0, 1476907131, 1, 1486913170, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `menu_type_id` int(11) UNSIGNED NOT NULL,
  `prepend` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `parent_id` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `level` int(2) NOT NULL,
  `lft` int(5) NOT NULL,
  `rgt` int(5) NOT NULL,
  `view_level` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `menu_type_id`, `prepend`, `title`, `path`, `status`, `parent_id`, `level`, `lft`, `rgt`, `view_level`) VALUES
(1, 0, 'fa fa-dashboard', 'Dashboard', 'admin', 1, 0, 1, 2, 3, 4),
(2, 0, 'fa fa-group', 'Users & Roles', '#', 1, 0, 1, 12, 19, 4),
(3, 0, 'fa fa-user', 'Users', 'admin/core/user/index', 1, 2, 2, 13, 14, 4),
(4, 0, 'fa fa-th-list', 'Menu Types', '#', 1, 0, 1, 20, 25, 4),
(5, 0, 'fa fa-circle-o', 'Menus', 'admin/core/menu-type/index', 1, 4, 2, 21, 22, 4),
(6, 0, 'fa fa-circle-o', 'Menu Items', 'admin/core/menu/index', 1, 4, 2, 23, 24, 4),
(7, 0, 'fa fa-list-alt', 'Web Tools', '#', 1, 0, 1, 26, 37, 4),
(8, 0, 'fa fa-circle-o', 'Modules', 'admin/tools/modules/index', 1, 7, 2, 27, 28, 4),
(9, 0, 'fa fa-circle-o', 'Controllers', 'admin/tools/controllers/index', 1, 7, 2, 29, 30, 4),
(10, 0, 'fa fa-circle-o', 'Models', 'admin/tools/models/index', 1, 7, 2, 31, 32, 4),
(11, 0, 'fa fa-circle-o', 'Migrations', 'admin/tools/migrations/index', 1, 7, 2, 33, 34, 4),
(12, 0, 'fa fa-circle-o', 'Scaffold', 'admin/tools/scaffold/index', 1, 7, 2, 35, 36, 4),
(20, 1, '', 'Home', '/', 1, 0, 1, 2, 3, 1),
(21, 1, '', 'Login', 'core/user/login', 1, 0, 1, 6, 7, 2),
(22, 1, '', 'Logout', 'core/user/logout', 1, 0, 1, 8, 9, 3),
(23, 1, '', 'Admin', 'admin', 1, 0, 1, 4, 5, 4),
(24, 0, 'fa fa-bullseye', 'Performance', '/admin/core/cache/index', 1, 0, 1, 38, 39, 4),
(25, 0, 'fa fa-th-large', 'Content', '#', 1, 0, 1, 4, 11, 4),
(26, 0, 'fa fa-file-text-o', 'Articles', 'admin/core/content/index', 1, 25, 2, 5, 6, 4),
(27, 0, 'fa fa-circle-o', 'Roles', 'admin/core/role/index', 1, 2, 2, 15, 16, 4),
(28, 0, 'fa fa-circle-o', 'View Levels', 'admin/core/view-level/index', 1, 2, 2, 17, 18, 4),
(29, 0, 'fa fa-circle-o', 'Categories', 'admin/core/category/index', 1, 25, 2, 7, 8, 4),
(30, 0, 'fa fa-th', 'Extensions', '#', 1, 0, 1, 40, 45, 4),
(31, 0, 'fa fa-circle-o', 'Widgets', 'admin/core/widget/index', 1, 30, 2, 43, 44, 4),
(32, 0, 'fa fa-circle-o', 'Modules', 'admin/core/module/index', 1, 30, 2, 41, 42, 4),
(33, 0, 'fa fa-folder', 'Media', 'admin/core/file-manager/index', 1, 25, 2, 9, 10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `menu_type`
--

CREATE TABLE `menu_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_type`
--

INSERT INTO `menu_type` (`id`, `title`, `description`) VALUES
(0, 'Admin', ''),
(1, 'Main menu', '');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `version` varchar(20) NOT NULL,
  `author` varchar(100) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `name`, `description`, `version`, `author`, `website`, `status`) VALUES
(1, 'Core', NULL, '0.1', 'Magnxpyr Network', 'http://www.magnxpyr.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE `resource` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`id`, `name`) VALUES
(1, '*'),
(11, 'module:core/admin-category'),
(12, 'module:core/admin-content'),
(2, 'module:core/admin-file-manager'),
(3, 'module:core/admin-index'),
(4, 'module:core/admin-menu'),
(5, 'module:core/admin-menu-type'),
(13, 'module:core/admin-module'),
(6, 'module:core/admin-role'),
(7, 'module:core/admin-user'),
(14, 'module:core/admin-view-level'),
(15, 'module:core/admin-widget'),
(16, 'module:core/content'),
(8, 'module:core/error'),
(17, 'module:core/file-manager'),
(9, 'module:core/index'),
(10, 'module:core/user');

-- --------------------------------------------------------

--
-- Table structure for table `resource_access`
--

CREATE TABLE `resource_access` (
  `resource_id` int(11) UNSIGNED NOT NULL,
  `access_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `resource_access`
--

INSERT INTO `resource_access` (`resource_id`, `access_name`) VALUES
(1, '*'),
(2, '*'),
(2, 'basic'),
(2, 'connector'),
(2, 'index'),
(3, '*'),
(3, 'index'),
(4, '*'),
(4, 'create'),
(4, 'delete'),
(4, 'edit'),
(4, 'index'),
(4, 'new'),
(4, 'save'),
(4, 'saveTree'),
(4, 'search'),
(5, '*'),
(5, 'create'),
(5, 'delete'),
(5, 'edit'),
(5, 'index'),
(5, 'new'),
(5, 'save'),
(6, '*'),
(6, 'create'),
(6, 'delete'),
(6, 'edit'),
(6, 'index'),
(6, 'new'),
(6, 'save'),
(6, 'search'),
(7, '*'),
(7, 'create'),
(7, 'delete'),
(7, 'edit'),
(7, 'index'),
(7, 'new'),
(7, 'save'),
(7, 'search'),
(8, '*'),
(8, 'show404'),
(8, 'show503'),
(9, '*'),
(9, 'index'),
(10, '*'),
(10, 'confirmEmail'),
(10, 'forgotPassword'),
(10, 'index'),
(10, 'login'),
(10, 'loginWithFacebook'),
(10, 'loginWithGoogle'),
(10, 'logout'),
(10, 'register'),
(10, 'resetPassword'),
(11, '*'),
(11, 'create'),
(11, 'delete'),
(11, 'edit'),
(11, 'index'),
(11, 'new'),
(11, 'save'),
(11, 'saveTree'),
(11, 'search'),
(12, '*'),
(12, 'create'),
(12, 'delete'),
(12, 'edit'),
(12, 'index'),
(12, 'new'),
(12, 'save'),
(12, 'search'),
(13, '*'),
(13, 'create'),
(13, 'delete'),
(13, 'edit'),
(13, 'index'),
(13, 'new'),
(13, 'save'),
(13, 'search'),
(14, '*'),
(14, 'create'),
(14, 'delete'),
(14, 'edit'),
(14, 'index'),
(14, 'new'),
(14, 'save'),
(14, 'search'),
(15, '*'),
(15, 'delete'),
(15, 'edit'),
(15, 'index'),
(15, 'new'),
(15, 'save'),
(15, 'search'),
(16, '*'),
(16, 'article'),
(16, 'category'),
(16, 'create'),
(16, 'delete'),
(16, 'edit'),
(16, 'index'),
(16, 'new'),
(16, 'save'),
(17, 'connector');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(2) UNSIGNED NOT NULL,
  `parent_id` int(2) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `parent_id`, `name`, `description`) VALUES
(1, 0, 'guest', NULL),
(2, 0, 'user', NULL),
(3, 0, 'admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `auth_token` varchar(32) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `facebook_id` varchar(20) DEFAULT NULL,
  `facebook_name` varchar(64) DEFAULT NULL,
  `facebook_data` text,
  `gplus_id` varchar(20) DEFAULT NULL,
  `gplus_name` varchar(64) DEFAULT NULL,
  `gplus_data` text,
  `reset_token` varchar(255) DEFAULT NULL,
  `role_id` tinyint(4) NOT NULL DEFAULT '2',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `visited_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `name`, `auth_token`, `password`, `facebook_id`, `facebook_name`, `facebook_data`, `gplus_id`, `gplus_name`, `gplus_data`, `reset_token`, `role_id`, `status`, `created_at`, `visited_at`) VALUES
(1, 'admin', 'chiriacstefan@gmail.com', 'Stefan', NULL, '$2a$12$BLegn0zeyVCPHScC1No6uOH4sr3RyPp2ihg0DWST5WnSpYgl5s.Pu', NULL, NULL, NULL, '11667481107019556262', 'Stefan Chiriac', 'O:34:"Google_Service_Oauth2_Userinfoplus":14:{s:25:"\0*\0internal_gapi_mappings";a:3:{s:10:"familyName";s:11:"family_name";s:9:"givenName";s:10:"given_name";s:13:"verifiedEmail";s:14:"verified_email";}s:5:"email";s:23:"chiriacstefan@gmail.com";s:10:"familyName";s:7:"Chiriac";s:6:"gender";N;s:9:"givenName";s:6:"Stefan";s:2:"hd";N;s:2:"id";s:21:"116674811070195562620";s:4:"link";s:45:"https://plus.google.com/116674811070195562620";s:6:"locale";s:5:"en-GB";s:4:"name";s:14:"Stefan Chiriac";s:7:"picture";s:92:"https://lh4.googleusercontent.com/-3cN0GmY45v0/AAAAAAAAAAI/AAAAAAAAADg/QZUZtoqK340/photo.jpg";s:13:"verifiedEmail";b:1;s:12:"\0*\0modelData";a:3:{s:14:"verified_email";b:1;s:10:"given_name";s:6:"Stefan";s:11:"family_name";s:7:"Chiriac";}s:12:"\0*\0processed";a:0:{}}', '88b1f3d50368efddf0394f3d0f93b475a05c04ee1f283068c9b9977b5c200f34', 3, 1, 1436439865, 1481658582),
(3, 'd6ea0fe7fa43a904', 'schiriac@tremend.ro', 'admin', NULL, 'admin', NULL, NULL, NULL, '11480333221948018503', 'Stefan Chiriac', 'O:34:"Google_Service_Oauth2_Userinfoplus":14:{s:25:"\0*\0internal_gapi_mappings";a:3:{s:10:"familyName";s:11:"family_name";s:9:"givenName";s:10:"given_name";s:13:"verifiedEmail";s:14:"verified_email";}s:5:"email";s:19:"schiriac@tremend.ro";s:10:"familyName";s:7:"Chiriac";s:6:"gender";s:4:"male";s:9:"givenName";s:6:"Stefan";s:2:"hd";s:10:"tremend.ro";s:2:"id";s:21:"114803332219480185032";s:4:"link";s:45:"https://plus.google.com/114803332219480185032";s:6:"locale";s:2:"en";s:4:"name";s:14:"Stefan Chiriac";s:7:"picture";s:92:"https://lh3.googleusercontent.com/-uCJ87QjGpeo/AAAAAAAAAAI/AAAAAAAAAAA/lJ-t5eoAhsw/photo.jpg";s:13:"verifiedEmail";b:1;s:12:"\0*\0modelData";a:3:{s:14:"verified_email";b:1;s:10:"given_name";s:6:"Stefan";s:11:"family_name";s:7:"Chiriac";}s:12:"\0*\0processed";a:0:{}}', NULL, 2, 1, 1481658799, 1481745037),
(4, 'd5b38cf6e1c7505c', '2841b0ec7f07a2b5@mg.com', 'admin', NULL, 'admin', '10207460732099832', 'Stefan Chiriac', 'a:2:{s:4:"name";s:14:"Stefan Chiriac";s:2:"id";s:17:"10207460732099832";}', NULL, NULL, NULL, NULL, 2, 1, 1481744349, 1481745031);

-- --------------------------------------------------------

--
-- Table structure for table `user_auth_tokens`
--

CREATE TABLE `user_auth_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` char(16) NOT NULL,
  `token` char(64) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_auth_tokens`
--

INSERT INTO `user_auth_tokens` (`id`, `selector`, `token`, `user_id`, `expires`) VALUES
(1, '3ad606a38df4300f', '1a782032cd74fbce822085cc65c55e25c9f629669ea537fda05c2bd23bc0ae28', 1, 1439031901),
(2, '47bb912ed6b81254', '8b46f836b98add44eaad1c7e7d360dee3ed7580c90790fa721b37ec15b0eb672', 1, 1445597076),
(3, 'f7bbf672969cf391', '3e2dce89c7d7a7b2c0a833759e007f5e6de080a31eb77ff6d72d6d742c8f99d6', 1, 1454614997),
(4, '99f2f48d2dc30bd5', 'c3517fb7356a20a0088fb70c7e0e80601ea1b04827b3a237be33b7b2f2168137', 1, 1455045487),
(5, '4e10a319dd6fb8c7', '7c044b1a6efb9fe543f3c4c99dd9a70fd5696c5ab856a480f2b0de4bfb66f3bc', 1, 1455306303),
(6, 'fb8aa8a83f6d5e98', 'e668fdb145b1ef9c283c3b657fdaa64f085aaae66a9657fadbf26cca1c1041f9', 1, 1460578568),
(7, 'e5e9697e17808370', '57a47c5bf9c4efa75a0fd391b105513153ef1b2615a9d51d273e434f9b18d88a', 1, 1460583479),
(8, 'f45d0b5e3008d151', '44bf3fc8cfb8337fdc5c23894a397c9fc47c4d08627642ca1453dc2708a90d09', 1, 1460750271),
(9, 'bafb433912af816d', '555fbbd3ff0b9fa591c22c84582d1a8d1692fb565ca8485b2d99dab1704a1e05', 1, 1460832782),
(10, '132c78dfd8922d2c', '815e426b081e398001e0ce36a9f63dfa50fe089e042edda0b1b94cbbe8757456', 1, 1461177488),
(11, '18ce89fecb6337c7', '9ecffe5c1a12a293b5be787ad51c5c5ddd31d2e700514d8e410f7201a11857b9', 1, 1461357326),
(12, '1f3bad1475d345fa', 'e3fb2b0bcb93a8aad190e81000df2cecb2dde1a3446c513c4031d35b7da7fef4', 1, 1461523099),
(13, '0553f1dcc9de60c6', '5a8ab76f15fa8fefdeda8456db498ac75bc756d66045eded36153f16654c7495', 1, 1461956748),
(14, 'ad7d8aa482682bc2', '2366b97bba4f64ed001d32199665393f16f99acdc6cb4b37a2a317d06a11418b', 1, 1462045658),
(15, 'e4ca53f8238f8825', 'fdb436d22485f4dcaaa63a22be49a9af2b5b1ad0dd0cdc7987a6d3e0ab7697ac', 1, 1462122569),
(16, '5a1d6f14ae2fa133', 'b222b6711cd15c83bfc4b7300a42e19e1227079b49455c137f42791734f3bfd5', 1, 1462130814),
(17, 'eb1a8f80868cd62f', '9b71e918818064d64655e98e356b051323e27f55caad677478cba2bf58186785', 1, 1462216009),
(18, '38796217f21e87b1', '02848f94282ee7d57c0b9058aec5c2957f68f6d23cd4c751f93ca0b4a54d6bf8', 1, 1462222750),
(19, '8ddb035c758dc518', '627640fd8a39c812122a2836c155dae6732f22cebd57c4c849cb6ac48c3d7054', 1, 1462269265),
(20, '64f947f7841283df', 'd702aa047ddeb901516b8bc721f1256ee0b88494f4789332f63790a8194dd2c6', 1, 1462295100),
(21, '70727180b877a85d', '6f732c81b698a6310c83be21042a73afd134bb2939b195afaf06264b8cb82ba4', 1, 1462389522),
(22, '16ddd9b8d5856b1c', 'b162d779d099d72df8c8c4522036bb3e0fac02ef311db835f3e41373e9da76f8', 1, 1462468130),
(23, 'f136056bfdb0ea06', 'a7ce37fae4362ca69feb42bc48f5465931c37828f341800a847ccfb31ce1db2d', 1, 1462470689),
(24, '34f4194f42c446d5', 'becbef32f781ff1e2a8772745862a59c8e8c6184fb95f46e81d03c99d516dbc1', 1, 1462471772),
(25, 'c1f934022eab31ac', '227b3ec92158e6056cd4a684d9fc54b42d7a71959e36351c9edcef3b42e4d986', 1, 1462555224),
(26, '449199496b6e2705', 'cbafb89bbafccd9f6f3dd10e0f83bc2759e97e6ccc6bbf07da5a4095f78e884e', 1, 1462560057),
(27, 'f46afcbf84c90d41', '15eefb4a892133178d352cabccf453c95849001077cbd3c81b3d411289fb1dfc', 1, 1462644657),
(28, '485881d927b31eec', '82b141247471e7e3c37e6fdbc54d7b42cee108a37b58055a840e678a8fae6eb0', 1, 1462646072),
(29, '6da4ff6f901779ba', 'bf77878e303d207a42b305e616bd53cfa81ab98655b2b7d9fadf8130581949c1', 1, 1462728947),
(30, '4dfad7f622367972', '45440c1a7b506c2e94763d4ce572a74b685396eb05fd6087a6d3cbf184697a62', 1, 1462991270),
(31, 'd307c8a893b29508', '47644b1435ab3ada555d5575d3ba4f9e004fca3b4236fc2ddebca54470893dd9', 1, 1463073230),
(32, '463397423dfb022c', '1028a3021bcfd4e1fb8f5f31716934b8b20188731c2ab333ef08e54f77a05e2c', 1, 1463074998),
(33, '8018d32aeaa37813', 'fc69a5cc16b047f4a26e3931961bc42c056207e78183f356845cb02bb8566db9', 1, 1463077874),
(36, '8f961c8d0cb86798', 'b90c6d8a2ed0e6956bb5888a33bc751c8bd2c30e9be6c59c66248ee80ef65c68', 1, 1464988632),
(37, '58fc508c026076db', '24a751ab326838276b8ddc25065dca71f796ad1fd171b117a43e299e8ecfd900', 1, 1464988844),
(38, '4cb948abe4290a85', '4c9aa32d842c8473565fd327e55e64bcdd8d9a3d6f80b458d19de240a8af497d', 1, 1465144826),
(46, '262542ad0a2b119e', 'ff580bd90b4292432adab67e38dadb21c7a5da3349a6c03966eb6bd31ee60d3e', 1, 1477337000),
(48, 'f2cf06aeda1f8b19', '567e49aab4d16f7c1eaa6d13656f8f9f1cfb9f4bceafae077d9bb63242f6f84c', 1, 1477406359),
(57, '7f90e202e9239193', '$2y$12$G5EphdbPPXcREqokYOtNVuNlnC08iijubNtY.VEkUGifHpindUnii', 1, 1484171471),
(60, '362d0b7fbb497d2e', '$2y$12$0jwp9o0yNiS4a7mAl93THOKcAY6VSp2NMlxThb1F7.LOG/jwdinmu', 1, 1484247413),
(61, '64f40f02567caa58', '$2y$12$LpGs6AtsBX2ZGjnMxHmomuSirwY.VQmnqimgKFHigOgMtSoUy1zky', 1, 1484339298),
(62, '1413f7d073df469c', '$2y$12$uh601TP80vNWG8GnDlgageVO2k23kkaDfBjR0OoqlmeywoJ.KjJIW', 1, 1488486363),
(64, '0b528cf8d06c5d9e', '$2y$12$qjXJGnMpnII6H8hquWsU7.iP.tfCmb0lkw6hZsxkBA5wVZa5kQJdi', 1, 1489695447),
(65, '6a887eb14c7b2a9d', '$2y$12$gsIXHTyISUK50ahLHvEPYe6sLFKEHlChLoyD78e0PBvHFoVTw3Aqa', 1, 1491074083),
(66, '382d791a121dac5d', '$2y$12$Z5Hv7SoC4hMxfM78LYNIxu5kzsBaWK3DD4V6JsfPMJEh0ouiQq1ui', 1, 1492554003);

-- --------------------------------------------------------

--
-- Table structure for table `user_email_confirmations`
--

CREATE TABLE `user_email_confirmations` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `token` char(64) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_email_confirmations`
--

INSERT INTO `user_email_confirmations` (`id`, `user_id`, `token`, `expires`) VALUES
(1, 2, '3dff1a0ecfe36470a7a50b7d342efc72f31561ff8eda4b662a34b2e66fd50c59', 1441712234);

-- --------------------------------------------------------

--
-- Table structure for table `user_reset_passwords`
--

CREATE TABLE `user_reset_passwords` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `token` char(64) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `view_level`
--

CREATE TABLE `view_level` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `roles` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `view_level`
--

INSERT INTO `view_level` (`id`, `name`, `roles`) VALUES
(1, 'Public', '[1,2,3]'),
(2, 'Guest', '[1]'),
(3, 'Registered', '[2,3]'),
(4, 'Administrator', '[3]');

-- --------------------------------------------------------

--
-- Table structure for table `widget`
--

CREATE TABLE `widget` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `params` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `version` varchar(20) NOT NULL,
  `author` varchar(100) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `widget`
--

INSERT INTO `widget` (`id`, `name`, `params`, `description`, `version`, `author`, `website`, `status`) VALUES
(1, 'GridView', NULL, '', '0.1', 'Magnxpyr Network', 'http://www.magnxpyr.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_list`
--
ALTER TABLE `access_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UNIQUE` (`role_id`,`resource_id`,`access_name`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UNIQUE` (`alias`),
  ADD KEY `INDEX` (`created_by`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UNIQUE` (`alias`),
  ADD KEY `INDEX` (`created_by`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_type`
--
ALTER TABLE `menu_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UNIQUE` (`name`);

--
-- Indexes for table `resource_access`
--
ALTER TABLE `resource_access`
  ADD PRIMARY KEY (`resource_id`,`access_name`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`,`parent_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`username`,`email`,`facebook_id`,`gplus_id`);

--
-- Indexes for table `user_auth_tokens`
--
ALTER TABLE `user_auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`selector`);

--
-- Indexes for table `user_email_confirmations`
--
ALTER TABLE `user_email_confirmations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UNIQUE` (`token`);

--
-- Indexes for table `user_reset_passwords`
--
ALTER TABLE `user_reset_passwords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UNIQUE` (`token`);

--
-- Indexes for table `view_level`
--
ALTER TABLE `view_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `widget`
--
ALTER TABLE `widget`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_list`
--
ALTER TABLE `access_list`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `menu_type`
--
ALTER TABLE `menu_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `resource`
--
ALTER TABLE `resource`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_auth_tokens`
--
ALTER TABLE `user_auth_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `user_email_confirmations`
--
ALTER TABLE `user_email_confirmations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_reset_passwords`
--
ALTER TABLE `user_reset_passwords`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `view_level`
--
ALTER TABLE `view_level`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `widget`
--
ALTER TABLE `widget`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
