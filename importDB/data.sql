CREATE DATABASE IF NOT EXISTS cloudflow_data;
USE cloudflow_data;

-- Database: `cloudflow_data`

-- --------------------------------------------------------

-- Table structure for table `data`

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `folder_name` text NOT NULL,
  `disk_name` text NOT NULL,
  `files` text NOT NULL,
  `backup` text NOT NULL,
  `users` text NOT NULL,
  `last_change` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `data`

-- (Insert the data here if applicable)

-- Indexes for dumped tables

-- Indexes for table `data`
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT for dumped tables
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

COMMIT;
