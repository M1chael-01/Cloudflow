CREATE DATABASE IF NOT EXISTS cloudflow_orders;
USE cloudflow_orders;

-- Database: `cloudflow_orders`

-- --------------------------------------------------------

-- Table structure for table `one_order`

CREATE TABLE `one_order` (
  `id` int(11) NOT NULL,
  `goods_services` text NOT NULL,
  `delivery` text NOT NULL,
  `billing` text NOT NULL,
  `date` datetime NOT NULL,
  `accepted` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `one_order`

INSERT INTO `one_order` (`id`, `goods_services`, `delivery`, `billing`, `date`, `accepted`) VALUES
(85, '[{\"name\":\"Switch 10-port\",\"price\":\"175.99\",\"category\":\"Síťová zařízení\",\"more_info\":\"S pěti ethernetovými porty 10\\/100Mbps můžete rychle rozšířit svou kabelovou síť. Přepínač MERCUSYS MS105 je flexibilní a plně kompatibilní s počítači, tiskárnami, IP kamerami, chytrými televizory, herními konzolemi a dalšími funkcemi. To je ideální pro domácnost, malou kancelář a dokonce i ubytovnu.\",\"description\":\"Stolní přepínač, 5x 10\\/100 Mbps RJ45 port, \",\"properties\":{\"ports\":\"10\",\"speed\":\"10Gb\\/s\",\"management\":\"yep\",\"type\":\"Ethernet-yes\"},\"id\":4,\"image\":\"https:\\/\\/mc-static.fast.eu\\/pics\\/45\\/45017314\\/45017314-sixforty.webp?3269749501\",\"images\":[\"https:\\/\\/mc-static.fast.eu\\/pics\\/45\\/45017314\\/45017314-sixforty.webp?3269749501%22\",\".\\/images\\/Snímek obrazovky 2025-03-05 074408.png\",\".\\/images\\/Snímek obrazovky 2025-03-04 174113.png\"],\"quantity\":1},{\"name\":\"Kabel CAT 5e\",\"price\":\"203.99\",\"category\":\"Síťová zařízení\",\"more_info\":\"Kabel CAT 5e je ideální pro připojení zařízení k domácí nebo kancelářské síti. Nabízí spolehlivý přenos dat při rychlostech až 1Gb\\/s. Tento kabel je zpětně kompatibilní s CAT 5 a poskytuje stabilní připojení pro každodenní používání.\",\"description\":\"Ethernetový kabel pro připojení zařízení k síti.\",\"properties\":{\"type\":\"Ethernet\",\"category\":\"CAT 5e\",\"speed\":\"1Gb\\/s\",\"length\":\"N\\/A\"},\"id\":7,\"image\":\".\\/images\\/TP21_20_1.jpg\",\"images\":[\".\\/images\\/\"],\"quantity\":1},{\"name\":\"Router Z500\",\"price\":\"8999.99\",\"category\":\"Síťová zařízení\",\"more_info\":\"Router Z500 je navržen pro velké kanceláře a profesionální použití. S podporou Wi-Fi 6 a rychlostí až 10Gb\\/s zajišťuje vysokou šířku pásma pro náročné aplikace, videokonference a streamování. Jeho moderní design a výkon z něj dělají ideální volbu pro firmy, které potřebují stabilní a rychlé připojení.\",\"description\":\"Vysokorychlostní router pro velké kanceláře s Wi-Fi 6.\",\"properties\":{\"Wi-Fi standard\":\"Wi-Fi 6\",\"speed\":\"10Gb\\/s\",\"ports\":\"Gigabit Ethernet\",\"security\":\"WPA3\"},\"id\":8,\"image\":\"https:\\/\\/cdn.originalky.cz\\/images\\/0\\/43476ab94a0183c2\\/25\\/tp-link-archer-mr200-4g-lte-wifi-ac750-router-4xfe-ports.jpg?hash=163675886\",\"images\":[\".\\/images\\/\"],\"quantity\":3}]', '{\"deliveryId\":\"1\",\"city\":\"Brno\",\"state\":\"Česká republika\",\"postal_code\":\"0000\",\"street\":\"šalina\",\"deliveryComp\":\"PPL\",\"price\":\"120\"}', '{\"first_name\":\"Michael\",\"last_name\":\"Tvrdík\",\"email\":\"tvrdikmichael@gmail.com\",\"phone\":\"gg\",\"notes\":\"\"}', '2025-03-10 15:49:37', 'yes');

-- (Insert the remaining data here)

-- Indexes for dumped tables

-- Indexes for table `one_order`
ALTER TABLE `one_order`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT for dumped tables
ALTER TABLE `one_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

COMMIT;
