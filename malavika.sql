-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2025 at 09:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `malavika`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent','late') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `course_id`, `attendance_date`, `status`, `created_at`, `updated_at`) VALUES
(34, 1, 3, '2025-01-03', 'late', '2025-01-02 20:02:46', '2025-01-02 20:02:46'),
(35, 1, 3, '2025-01-03', 'present', '2025-01-02 20:10:31', '2025-01-02 20:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `attendancemarks`
--

CREATE TABLE `attendancemarks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attendance_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_description` text DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_description`, `teacher_id`, `created_at`, `updated_at`) VALUES
(3, 'ITI', 'skibidi', 1, '2024-10-21 17:04:34', '2024-10-21 17:04:34'),
(4, 'OS', 'asff', 4, '2024-10-23 19:42:16', '2024-10-23 19:42:16'),
(5, 'microprocessors', 'gsSgsgSG', 5, '2024-12-01 09:04:58', '2024-12-01 09:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `enrollment_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `student_id`, `course_id`, `enrollment_date`, `created_at`, `updated_at`) VALUES
(2, 1, 3, '2024-10-24', '2024-10-23 20:58:59', '2024-10-23 20:58:59'),
(3, 2, 3, '2024-11-10', '2024-11-10 09:31:31', '2024-11-10 09:31:31'),
(7, 1, 4, '2024-11-10', '2024-11-10 18:22:35', '2024-11-10 18:22:35');

-- --------------------------------------------------------

--
-- Table structure for table `internalexammarks`
--

CREATE TABLE `internalexammarks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` float NOT NULL,
  `exam_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internalexammarks`
--

INSERT INTO `internalexammarks` (`id`, `student_id`, `course_id`, `marks`, `exam_date`, `created_at`, `updated_at`) VALUES
(15, 1, 3, 7, '2025-01-03', '2025-01-02 20:01:53', '2025-01-02 20:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `modelexammarks`
--

CREATE TABLE `modelexammarks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` float NOT NULL,
  `exam_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modelexammarks`
--

INSERT INTO `modelexammarks` (`id`, `student_id`, `course_id`, `marks`, `exam_date`, `created_at`, `updated_at`) VALUES
(15, 1, 3, 7, '2025-01-03', '2025-01-02 20:01:53', '2025-01-02 20:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `projectmarks`
--

CREATE TABLE `projectmarks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` float NOT NULL,
  `project_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projectmarks`
--

INSERT INTO `projectmarks` (`id`, `student_id`, `course_id`, `marks`, `project_date`, `created_at`, `updated_at`) VALUES
(21, 1, 3, 6, '2025-01-03', '2025-01-02 20:02:32', '2025-01-02 20:02:32');

-- --------------------------------------------------------

--
-- Table structure for table `report_cards`
--

CREATE TABLE `report_cards` (
  `report_card_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `term` varchar(20) NOT NULL,
  `grade` char(2) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seminarmarks`
--

CREATE TABLE `seminarmarks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` float NOT NULL,
  `seminar_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seminarmarks`
--

INSERT INTO `seminarmarks` (`id`, `student_id`, `course_id`, `marks`, `seminar_date`, `created_at`, `updated_at`) VALUES
(21, 1, 3, 8, '2025-01-03', '2025-01-02 20:02:32', '2025-01-02 20:02:32');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `enrollment_date` date NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `address`, `phone_number`, `email`, `enrollment_date`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Hari', 'Krishnan', '2024-05-05', 'male', 'South Aduvassery', '346566598', 'hari@gmail.com', '2022-08-25', 5, '2024-10-21 10:24:50', '2024-11-30 18:03:13'),
(2, 'Malavika', 'Shibu', '2004-12-13', 'female', 'chowara', '4654347', 'malu@gmail.com', '2024-09-10', 9, '2024-11-10 09:30:18', '2024-11-10 09:30:18');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `first_name`, `last_name`, `email`, `phone_number`, `hire_date`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Julie M', ' David', 'julie@gmail.com', '65634689', '1988-07-21', 4, '2024-10-21 09:46:29', '2024-11-30 17:01:43'),
(4, 'leena', 'shekhar', 'leena@gmail.com', '322353', '2023-06-15', 8, '2024-10-23 19:34:08', '2024-10-23 19:34:08'),
(5, '', '', 'sufaira@gmail.com', '', '0000-00-00', 11, '2024-12-01 09:03:53', '2024-12-01 09:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `testpapermarks`
--

CREATE TABLE `testpapermarks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `marks` float NOT NULL,
  `exam_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testpapermarks`
--

INSERT INTO `testpapermarks` (`id`, `student_id`, `course_id`, `marks`, `exam_date`, `created_at`, `updated_at`) VALUES
(15, 1, 3, 3, '2025-01-03', '2025-01-02 20:01:53', '2025-01-02 20:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `email`, `created_at`, `updated_at`) VALUES
(3, 'admin', 'admin', 'admin', 'admin@gmail.com', '2024-10-21 09:19:44', '2024-10-21 09:19:44'),
(4, 'julie', 'jj', 'teacher', 'julie@gmail.com', '2024-10-21 09:46:29', '2024-10-21 09:46:29'),
(5, 'hari', 'hh', 'student', 'hari@gmail.com', '2024-10-21 10:24:50', '2024-12-01 10:28:15'),
(6, 'jassir', 'ja', 'teacher', 'jassir@gmail.com', '2024-10-21 17:02:16', '2024-10-21 17:02:16'),
(8, 'leena', 'll', 'teacher', 'leena@gmail.com', '2024-10-23 19:34:08', '2024-10-23 19:34:08'),
(9, 'malu', 'mm', 'student', 'malu@gmail.com', '2024-11-10 09:30:18', '2024-11-10 09:30:18'),
(11, '', 'ss', 'teacher', '', '2024-12-01 09:03:52', '2024-12-01 09:57:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `attendancemarks`
--
ALTER TABLE `attendancemarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `internalexammarks`
--
ALTER TABLE `internalexammarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `modelexammarks`
--
ALTER TABLE `modelexammarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `projectmarks`
--
ALTER TABLE `projectmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `report_cards`
--
ALTER TABLE `report_cards`
  ADD PRIMARY KEY (`report_card_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `seminarmarks`
--
ALTER TABLE `seminarmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `testpapermarks`
--
ALTER TABLE `testpapermarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `attendancemarks`
--
ALTER TABLE `attendancemarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `internalexammarks`
--
ALTER TABLE `internalexammarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `modelexammarks`
--
ALTER TABLE `modelexammarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `projectmarks`
--
ALTER TABLE `projectmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `report_cards`
--
ALTER TABLE `report_cards`
  MODIFY `report_card_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seminarmarks`
--
ALTER TABLE `seminarmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `testpapermarks`
--
ALTER TABLE `testpapermarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `attendancemarks`
--
ALTER TABLE `attendancemarks`
  ADD CONSTRAINT `attendancemarks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `attendancemarks_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `internalexammarks`
--
ALTER TABLE `internalexammarks`
  ADD CONSTRAINT `internalexammarks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `internalexammarks_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `modelexammarks`
--
ALTER TABLE `modelexammarks`
  ADD CONSTRAINT `modelexammarks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `modelexammarks_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `projectmarks`
--
ALTER TABLE `projectmarks`
  ADD CONSTRAINT `projectmarks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `projectmarks_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `report_cards`
--
ALTER TABLE `report_cards`
  ADD CONSTRAINT `report_cards_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `report_cards_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `seminarmarks`
--
ALTER TABLE `seminarmarks`
  ADD CONSTRAINT `seminarmarks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `seminarmarks_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `testpapermarks`
--
ALTER TABLE `testpapermarks`
  ADD CONSTRAINT `testpapermarks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `testpapermarks_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
