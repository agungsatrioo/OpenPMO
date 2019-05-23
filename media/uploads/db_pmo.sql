-- phpMyAdmin SQL Dump
-- version 4.6.4deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 22, 2016 at 09:37 AM
-- Server version: 5.7.16-0ubuntu0.16.10.1
-- PHP Version: 7.0.8-3ubuntu3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pmo`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `bar_source` (IN `id_proyekIN` INT)  BEGIN
select SUM(case t_project_progress.status when 1 then 1 else 0 end) as nihil, SUM(case t_project_progress.status when 2 then 1 else 0 end) as selesai,SUM(case t_project_progress.status when 3 then 1 else 0 end) as review,sUM(case t_project_progress.status when 4 then 1 else 0 end) as in_progress,SUM(case t_project_progress.status when 5 then 1 else 0 end) as canceled, SUM(case t_project_progress.status when 6 then 1 else 0 end) as in_problem, count(*) as total from t_project_progress where id_project=id_proyekIN;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `cek_progres` (`id_proyekIN` INT) RETURNS INT(11) BEGIN
  DECLARE jml_sudah int;
  DECLARE jml_total int;
  
  select 
  SUM(case t_project_progress.status when 2 then 1 else 0 end),
  count(*)
  into jml_sudah, jml_total
  from t_project_progress
  where id_project = id_proyekIN
  and t_project_progress.status !=5;
  
  return (jml_sudah/jml_total)*100;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_progress_log`
--

CREATE TABLE `t_progress_log` (
  `id_log` int(254) NOT NULL,
  `id_progress` int(254) NOT NULL,
  `id_project` int(254) NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bagian` varchar(50) NOT NULL,
  `pic` varchar(72) NOT NULL,
  `status` int(10) NOT NULL,
  `deadline` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `problem_details` text NOT NULL,
  `log_type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_progress_log`
--

INSERT INTO `t_progress_log` (`id_log`, `id_progress`, `id_project`, `log_time`, `bagian`, `pic`, `status`, `deadline`, `tgl_selesai`, `problem_details`, `log_type`) VALUES
(1, 1, 1, '2016-11-18 00:44:24', 'Andalus', '1', 0, '0000-00-00', NULL, '', 'insert'),
(2, 2, 1, '2016-11-18 00:45:07', 'Indonesia', '1', 4, '2016-11-17', NULL, '', 'insert'),
(3, 2, 1, '2016-11-21 03:54:51', 'Indonesia', '1', 2, '2016-11-17', '2016-11-21', '', 'update'),
(4, 3, 1, '2016-12-21 09:27:09', 'Lindows', '1', 1, NULL, '0000-00-00', '', 'insert'),
(5, 1, 1, '2016-12-21 09:30:28', 'Andalus', '1', 1, '0000-00-00', NULL, '', 'update'),
(6, 1, 1, '2016-12-22 01:08:17', 'Andalus', '1', 5, '0000-00-00', NULL, '', 'update'),
(7, 2, 1, '2016-12-22 01:12:50', 'Indonesia', '2', 2, '2016-11-17', '0000-00-00', '', 'update'),
(8, 2, 1, '2016-12-22 01:15:24', 'Indonesia', '2', 2, '2016-11-17', '2016-12-01', '', 'update');

-- --------------------------------------------------------

--
-- Table structure for table `t_project`
--

CREATE TABLE `t_project` (
  `id_project` int(254) NOT NULL,
  `nama_proyek` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date NOT NULL,
  `pic` int(100) NOT NULL,
  `attachment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_project`
--

INSERT INTO `t_project` (`id_project`, `nama_proyek`, `start_date`, `deadline`, `pic`, `attachment`) VALUES
(1, 'India', '2016-11-17', '2016-12-30', 1, 'proyekan1.mmpz');

-- --------------------------------------------------------

--
-- Table structure for table `t_project_progress`
--

CREATE TABLE `t_project_progress` (
  `id_progress` int(254) NOT NULL,
  `id_project` int(254) NOT NULL,
  `bagian` varchar(50) NOT NULL,
  `pic` varchar(72) NOT NULL,
  `status` int(10) NOT NULL,
  `deadline` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `problem_details` text,
  `link` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_project_progress`
--

INSERT INTO `t_project_progress` (`id_progress`, `id_project`, `bagian`, `pic`, `status`, `deadline`, `tgl_selesai`, `problem_details`, `link`) VALUES
(1, 1, 'Andalus', '1', 5, '0000-00-00', NULL, '', 0),
(2, 1, 'Indonesia', '2', 2, '2016-11-17', '2016-12-01', '', 0),
(3, 1, 'Lindows', '1', 1, NULL, '0000-00-00', '', 0);

--
-- Triggers `t_project_progress`
--
DELIMITER $$
CREATE TRIGGER `trig_log` BEFORE UPDATE ON `t_project_progress` FOR EACH ROW BEGIN

INSERT INTO `t_progress_log` (`id_log`, `id_progress`, `id_project`, `log_time`, `bagian`, `pic`, `status`, `deadline`, `tgl_selesai`, `problem_details`, `log_type`) 
VALUES (NULL, new.id_progress, new.id_project, CURRENT_TIMESTAMP, new.bagian, new.pic, new.status, new.deadline, new.tgl_selesai, new.problem_details, 'update');

end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig_log_add` AFTER INSERT ON `t_project_progress` FOR EACH ROW BEGIN

INSERT INTO `t_progress_log` (`id_log`, `id_progress`, `id_project`, `log_time`, `bagian`, `pic`, `status`, `deadline`, `tgl_selesai`, `problem_details`, `log_type`) 
VALUES (NULL, new.id_progress, new.id_project, CURRENT_TIMESTAMP, new.bagian, new.pic, new.status, new.deadline, new.tgl_selesai, new.problem_details, 'insert');

end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_project_status`
--

CREATE TABLE `t_project_status` (
  `id_pstatus` int(11) NOT NULL,
  `status_name` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_project_status`
--

INSERT INTO `t_project_status` (`id_pstatus`, `status_name`) VALUES
(0, 'Null'),
(1, 'Pending'),
(2, 'Done'),
(3, 'Review'),
(4, 'In progress'),
(5, 'Canceled'),
(6, 'In problem');

-- --------------------------------------------------------

--
-- Table structure for table `t_project_subprogress`
--

CREATE TABLE `t_project_subprogress` (
  `id_subprogress` int(254) NOT NULL,
  `id_progress` int(254) NOT NULL,
  `bagian` varchar(50) NOT NULL,
  `pic` varchar(72) NOT NULL,
  `status` int(10) NOT NULL,
  `deadline` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `problem_details` text,
  `file_link` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_project_subprogress`
--

INSERT INTO `t_project_subprogress` (`id_subprogress`, `id_progress`, `bagian`, `pic`, `status`, `deadline`, `tgl_selesai`, `problem_details`, `file_link`) VALUES
(1, 1, 'memperbaiki kota', '1', 2, '2016-12-09', '2016-12-14', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE `t_user` (
  `id_user` int(12) NOT NULL,
  `username` varchar(100) NOT NULL,
  `fullname` varchar(254) NOT NULL,
  `password` varchar(100) NOT NULL,
  `img` text NOT NULL,
  `level` tinyint(2) NOT NULL,
  `about_me` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`id_user`, `username`, `fullname`, `password`, `img`, `level`, `about_me`) VALUES
(1, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 1, 'Lorem ipsum dolor sit amet'),
(2, 'ember.kosong', 'Ember', '1f386ec70e9f4090bb51e56fe1fbad5f', '', 2, 'Yang mana yaa....');

-- --------------------------------------------------------

--
-- Table structure for table `t_user_level`
--

CREATE TABLE `t_user_level` (
  `id_level` int(12) NOT NULL,
  `desc` varchar(100) NOT NULL,
  `can_alter` tinyint(1) NOT NULL,
  `can_delete` tinyint(1) NOT NULL,
  `alter_other` tinyint(1) NOT NULL,
  `delete_other` tinyint(1) NOT NULL,
  `project_create` tinyint(1) NOT NULL,
  `project_update` tinyint(1) NOT NULL,
  `project_update_other` tinyint(1) NOT NULL,
  `project_delete` tinyint(1) NOT NULL,
  `project_delete_other` tinyint(1) NOT NULL,
  `task_create` tinyint(1) NOT NULL,
  `task_update` tinyint(1) NOT NULL,
  `task_update_other` tinyint(1) NOT NULL,
  `task_delete` tinyint(1) NOT NULL,
  `task_delete_other` tinyint(1) NOT NULL,
  `subtask_create` tinyint(1) NOT NULL,
  `subtask_update` tinyint(1) NOT NULL,
  `subtask_update_other` tinyint(1) NOT NULL,
  `subtask_delete` tinyint(1) NOT NULL,
  `subtask_delete_other` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_user_level`
--

INSERT INTO `t_user_level` (`id_level`, `desc`, `can_alter`, `can_delete`, `alter_other`, `delete_other`, `project_create`, `project_update`, `project_update_other`, `project_delete`, `project_delete_other`, `task_create`, `task_update`, `task_update_other`, `task_delete`, `task_delete_other`, `subtask_create`, `subtask_update`, `subtask_update_other`, `subtask_delete`, `subtask_delete_other`) VALUES
(1, 'Super Admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'Project Manager', 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 1, 1, 0, 1, 0),
(3, 'Employee', 1, 1, 0, 0, 1, 1, 0, 1, 0, 1, 1, 0, 1, 0, 1, 1, 0, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_progress_log`
--
ALTER TABLE `t_progress_log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `t_project`
--
ALTER TABLE `t_project`
  ADD PRIMARY KEY (`id_project`);

--
-- Indexes for table `t_project_progress`
--
ALTER TABLE `t_project_progress`
  ADD PRIMARY KEY (`id_progress`);

--
-- Indexes for table `t_project_subprogress`
--
ALTER TABLE `t_project_subprogress`
  ADD PRIMARY KEY (`id_subprogress`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `t_user_level`
--
ALTER TABLE `t_user_level`
  ADD PRIMARY KEY (`id_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_progress_log`
--
ALTER TABLE `t_progress_log`
  MODIFY `id_log` int(254) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `t_project`
--
ALTER TABLE `t_project`
  MODIFY `id_project` int(254) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t_project_progress`
--
ALTER TABLE `t_project_progress`
  MODIFY `id_progress` int(254) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id_user` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
