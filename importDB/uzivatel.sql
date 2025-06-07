CREATE DATABASE IF NOT EXISTS cloudflow_uzivatele;
USE cloudflow_uzivatele;

-- Database: `cloudflow_uzivatele`

-- --------------------------------------------------------

-- Table structure for table `uzivatel`

CREATE TABLE `uzivatel` (
  `id` int(11) NOT NULL,
  `jmeno` text NOT NULL,
  `email` text NOT NULL,
  `heslo` text NOT NULL,
  `account_type` text NOT NULL,
  `team` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `uzivatel`
-- (Data Insert Statements for `uzivatel` should go here)

-- Indexes for dumped tables

-- Indexes for table `uzivatel`
ALTER TABLE `uzivatel`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT for dumped tables
ALTER TABLE `uzivatel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;
