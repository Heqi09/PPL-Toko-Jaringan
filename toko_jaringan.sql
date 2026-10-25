-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Okt 2026 pada 15.05
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_jaringan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keranjang`
--

INSERT INTO `keranjang` (`id`, `user_id`, `produk_id`, `qty`) VALUES
(1, 3, 55, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `kategori`, `harga`, `stok`, `deskripsi`, `brand`, `gambar`) VALUES
(1, 'Router MikroTik RB951Ui-2HnD', 'Router', 750000.00, 15, 'Routerboard indoor hemat biaya dengan 5 port Ethernet dan Wi-Fi bawaan 2.4GHz.', 'MikroTik', 'default.jpg'),
(2, 'Switch TP-Link TL-SG1008D 8-Port Gigabit', 'Switch', 350000.00, 20, 'Unmanaged switch 8 port Gigabit RJ45 untuk jaringan kantor dan rumah.', 'TP-Link', 'default.jpg'),
(3, 'Kabel UTP Belden Cat6 305m Original', 'Kabel UTP', 1850000.00, 8, 'Kabel jaringan UTP Cat6 100% tembaga dengan panjang 305 meter.', 'Belden', 'default.jpg'),
(4, 'Access Point Ubiquiti UniFi AP AC Lite', 'Access Point', 1450000.00, 10, 'Access point dual-band 802.11ac dengan kecepatan hingga 867 Mbps.', 'Ubiquiti', 'default.jpg'),
(5, 'Tang Krimping RJ45 Sanfix Heavy Duty', 'Aksesori', 120000.00, 25, 'Tang crimping tangguh untuk pemotongan dan pengupasan kabel konektor RJ45.', 'Sanfix', 'default.jpg'),
(6, 'Konektor RJ45 Cat6 AMP CommScope (100 pcs)', 'Aksesori', 175000.00, 30, 'Konektor RJ45 berkualitas tinggi untuk kabel UTP Cat6 isi 100 pcs.', 'AMP CommScope', 'default.jpg'),
(7, 'TP-Link TL-WR840N Wireless Router 300Mbps', 'Router', 195000.00, 22, 'Router Wi-Fi 2,4 GHz 300 Mbps untuk rumah atau usaha kecil; dilengkapi 4 port LAN.', 'TP-Link', 'p001_tp_link_tl_wr840n_wireless_router_300mbps.jpg'),
(8, 'TP-Link Archer C6 AC1200 Gigabit Router', 'Router', 485000.00, 16, 'Router dual-band AC1200 dengan port Gigabit Ethernet untuk jaringan rumah dan kantor.', 'TP-Link', 'p002_tp_link_archer_c6_ac1200_gigabit_router.jpg'),
(9, 'TP-Link Archer AX53 AX3000 Wi-Fi 6 Router', 'Router', 1095000.00, 11, 'Router Wi-Fi 6 dual-band AX3000, mendukung koneksi cepat dan banyak perangkat.', 'TP-Link', 'p003_tp_link_archer_ax53_ax3000_wi_fi_6_router.jpg'),
(10, 'TP-Link EAP225 AC1350 Access Point', 'Access Point', 845000.00, 12, 'Access point dual-band AC1350 untuk pemasangan plafon dan jaringan bisnis Omada.', 'TP-Link', 'p004_tp_link_eap225_ac1350_access_point.jpg'),
(11, 'Ubiquiti UniFi U6 Lite Wi-Fi 6 Access Point', 'Access Point', 1950000.00, 9, 'Access point Wi-Fi 6 UniFi dengan desain plafon dan manajemen jaringan terpusat.', 'Ubiquiti', 'p005_ubiquiti_unifi_u6_lite_wi_fi_6_access_point.jpg'),
(12, 'TP-Link TL-SG108 8-Port Gigabit Switch', 'Switch', 325000.00, 25, 'Switch desktop unmanaged 8 port RJ45 Gigabit untuk jaringan LAN.', 'TP-Link', 'p006_tp_link_tl_sg108_8_port_gigabit_switch.jpg'),
(13, 'TP-Link TL-SG116 16-Port Gigabit Switch', 'Switch', 890000.00, 13, 'Switch unmanaged 16 port Gigabit untuk ekspansi jaringan kabel.', 'TP-Link', 'p007_tp_link_tl_sg116_16_port_gigabit_switch.jpg'),
(14, 'TP-Link TL-SG2428P 24-Port PoE+ Managed Switch', 'Switch', 5495000.00, 5, 'Switch managed Omada 24 port Gigabit dengan PoE+ dan port uplink SFP.', 'TP-Link', 'p008_tp_link_tl_sg2428p_24_port_poe_managed_switch.jpg'),
(15, 'Ubiquiti UniFi US-24 24-Port Gigabit Switch', 'Switch', 4295000.00, 5, 'Switch UniFi 24 port Gigabit untuk infrastruktur jaringan kantor.', 'Ubiquiti', 'p009_ubiquiti_unifi_us_24_24_port_gigabit_switch.jpg'),
(16, 'MikroTik CRS328-24P-4S+RM PoE Switch', 'Switch', 7995000.00, 4, 'Switch 24 port Gigabit PoE dengan 4 slot SFP+ 10G dan fitur manajemen RouterOS/SwOS.', 'MikroTik', 'p010_mikrotik_crs328_24p_4s_rm_poe_switch.jpg'),
(17, 'Tenda TEF1005D 5-Port Fast Ethernet Switch', 'Switch', 89000.00, 35, 'Switch unmanaged 5 port 10/100 Mbps untuk jaringan LAN sederhana.', 'Tenda', 'p011_tenda_tef1005d_5_port_fast_ethernet_switch.jpg'),
(18, 'Tenda TEG1008D 8-Port Gigabit Switch', 'Switch', 265000.00, 19, 'Switch desktop 8 port Gigabit dengan pengoperasian plug-and-play.', 'Tenda', 'p012_tenda_teg1008d_8_port_gigabit_switch.jpg'),
(19, 'ZTE F660 GPON ONT Wi-Fi', 'Router', 275000.00, 14, 'Perangkat ONT GPON dengan fungsi router untuk akses jaringan fiber optik; kompatibilitas bergantung pada ISP.', 'ZTE', 'p013_zte_f660_gpon_ont_wi_fi.jpg'),
(20, 'Huawei HG8245H GPON ONT Router', 'Router', 325000.00, 11, 'ONT GPON dengan Wi-Fi dan port LAN untuk terminasi jaringan fiber; aktivasi bergantung ISP.', 'Huawei', 'p014_huawei_hg8245h_gpon_ont_router.jpg'),
(21, 'TP-Link TL-SM311LS SFP Single Mode 1.25G', 'Aksesoris', 325000.00, 12, 'Modul SFP 1,25 Gbps single-mode untuk koneksi fiber optik kompatibel.', 'TP-Link', 'p015_tp_link_tl_sm311ls_sfp_single_mode_1_25g.jpg'),
(22, 'MikroTik S+85DLC03D SFP+ 10G Module', 'Aksesoris', 995000.00, 8, 'Transceiver SFP+ 10Gbps multimode untuk koneksi jarak pendek dengan fiber LC.', 'MikroTik', 'p016_mikrotik_s_85dlc03d_sfp_10g_module.jpg'),
(23, 'AMP CommScope Keystone Jack Cat6 RJ45', 'Aksesoris', 65000.00, 50, 'Keystone jack RJ45 Cat6 untuk pemasangan outlet data dan patch panel.', 'AMP CommScope', 'p017_amp_commscope_keystone_jack_cat6_rj45.jpg'),
(24, 'Belden Keystone Jack Cat6A RJ45', 'Aksesoris', 95000.00, 35, 'Keystone jack RJ45 kategori 6A untuk instalasi jaringan terstruktur.', 'Belden', 'p018_belden_keystone_jack_cat6a_rj45.jpg'),
(25, 'AMP CommScope Konektor RJ45 Cat6 STP Shielded 50 pcs', 'Aksesoris', 195000.00, 18, 'Paket konektor RJ45 shielded Cat6 untuk instalasi kabel jaringan STP, isi 50 buah.', 'AMP CommScope', 'p019_amp_commscope_konektor_rj45_cat6_stp_shielded_50.jpg'),
(26, 'RJ45 Boot Pelindung Konektor Biru 100 pcs', 'Aksesoris', 45000.00, 70, 'Pelindung karet boot RJ45 warna biru untuk mengurangi tekanan pada konektor, isi 100 buah.', 'Generic', 'p020_rj45_boot_pelindung_konektor_biru_100_pcs.jpg'),
(27, 'D-Link Kabel UTP Cat6 305 Meter', 'Kabel UTP', 1250000.00, 10, 'Kabel LAN UTP Cat6 305 meter dalam boks untuk instalasi jaringan gedung.', 'D-Link', 'p021_d_link_kabel_utp_cat6_305_meter.jpg'),
(28, 'Belden Kabel UTP Cat6A 305 Meter', 'Kabel UTP', 2850000.00, 7, 'Kabel jaringan Cat6A panjang 305 meter untuk instalasi data berkinerja tinggi.', 'Belden', 'p022_belden_kabel_utp_cat6a_305_meter.jpg'),
(29, 'Belden Kabel Cat6 Outdoor 305 Meter', 'Kabel UTP', 2350000.00, 9, 'Kabel jaringan Cat6 outdoor 305 meter dengan selubung untuk penggunaan luar ruangan.', 'Belden', 'p023_belden_kabel_cat6_outdoor_305_meter.jpg'),
(30, 'Fiber Optic Patch Cord Single Mode SC-LC 3 Meter', 'Aksesoris', 75000.00, 48, 'Patch cord fiber optik single-mode konektor SC ke LC panjang 3 meter.', 'Generic', 'p024_fiber_optic_patch_cord_single_mode_sc_lc_3_meter.jpg'),
(31, 'Fiber Optic Patch Cord Multimode LC-LC 3 Meter', 'Aksesoris', 95000.00, 40, 'Patch cord fiber optik multimode konektor LC ke LC panjang 3 meter.', 'Generic', 'p025_fiber_optic_patch_cord_multimode_lc_lc_3_meter.jpg'),
(32, 'Faceplate RJ45 Cat6 1 Port', 'Aksesoris', 18000.00, 85, 'Pelat outlet data dinding dengan satu lubang port keystone RJ45.', 'Generic', 'p026_faceplate_rj45_cat6_1_port.jpg'),
(33, 'Faceplate RJ45 Cat6 2 Port', 'Aksesoris', 22000.00, 75, 'Pelat outlet jaringan dinding untuk dua port keystone RJ45.', 'Generic', 'p027_faceplate_rj45_cat6_2_port.jpg'),
(34, 'Surface Box RJ45 2 Port', 'Aksesoris', 28000.00, 65, 'Kotak permukaan untuk pemasangan outlet jaringan RJ45 1 sampai 2 port.', 'Generic', 'p028_surface_box_rj45_2_port.jpg'),
(35, 'Patch Panel Cat6 24 Port 1U Rackmount', 'Aksesoris', 675000.00, 13, 'Patch panel Cat6 24 port untuk terminasi kabel jaringan pada rak 19 inci.', 'Generic', 'p029_patch_panel_cat6_24_port_1u_rackmount.jpg'),
(36, 'Rack Server 12U Wallmount 600x450 mm', 'Aksesoris', 1650000.00, 6, 'Kabinet rack jaringan 12U tipe wallmount untuk switch, patch panel, dan perangkat jaringan.', 'Generic', 'p030_rack_server_12u_wallmount_600x450_mm.jpg'),
(37, 'Rack Server 27U Standing 600x800 mm', 'Aksesoris', 4750000.00, 3, 'Kabinet rack standing 27U untuk penataan perangkat jaringan dan server.', 'Generic', 'p031_rack_server_27u_standing_600x800_mm.jpg'),
(38, 'PDU 8 Port 1U Rackmount', 'Aksesoris', 425000.00, 21, 'Power distribution unit rackmount 1U dengan delapan outlet listrik.', 'Generic', 'p032_pdu_8_port_1u_rackmount.jpg'),
(39, 'APC Back-UPS BX1200MI 1200VA', 'Aksesoris', 1950000.00, 8, 'UPS untuk membantu menjaga suplai daya perangkat jaringan saat gangguan listrik.', 'APC', 'p033_apc_back_ups_bx1200mi_1200va.jpg'),
(40, 'MikroTik hEX S RB760iGS Router', 'Router', 1095000.00, 11, 'Router kabel dengan 5 port Gigabit Ethernet dan slot SFP untuk jaringan kantor.', 'MikroTik', 'p034_mikrotik_hex_s_rb760igs_router.jpg'),
(41, 'MikroTik RB4011iGS+RM 10-Port Router', 'Router', 3650000.00, 6, 'Router performa tinggi dengan 10 port Gigabit dan satu port SFP+.', 'MikroTik', 'p035_mikrotik_rb4011igs_rm_10_port_router.jpg'),
(42, 'MikroTik hAP ac2 Dual Band Router', 'Router', 1295000.00, 13, 'Router nirkabel dual-band AC1200 dengan lima port Gigabit Ethernet.', 'MikroTik', 'p036_mikrotik_hap_ac2_dual_band_router.jpg'),
(43, 'TP-Link Omada ER605 Gigabit VPN Router', 'Router', 895000.00, 11, 'Router VPN Gigabit untuk kebutuhan manajemen jaringan bisnis Omada.', 'TP-Link', 'p037_tp_link_omada_er605_gigabit_vpn_router.jpg'),
(44, 'TP-Link Omada OC200 Hardware Controller', 'Aksesoris', 1550000.00, 7, 'Controller perangkat keras untuk manajemen terpusat perangkat TP-Link Omada.', 'TP-Link', 'p038_tp_link_omada_oc200_hardware_controller.jpg'),
(45, 'Ubiquiti UniFi Cloud Key Gen2 UCK-G2', 'Aksesoris', 3450000.00, 4, 'Controller UniFi Cloud Key generasi kedua untuk pengelolaan perangkat jaringan UniFi.', 'Ubiquiti', 'p039_ubiquiti_unifi_cloud_key_gen2_uck_g2.jpg'),
(46, 'Ubiquiti NanoBeam M5 CPE Outdoor 5GHz', 'Access Point', 1450000.00, 8, 'CPE wireless outdoor 5GHz untuk koneksi point-to-point dan point-to-multipoint.', 'Ubiquiti', 'p040_ubiquiti_nanobeam_m5_cpe_outdoor_5ghz.jpg'),
(47, 'MikroTik SXTsq 5 ac Outdoor CPE', 'Access Point', 1150000.00, 9, 'Perangkat CPE outdoor 5GHz 802.11ac untuk jaringan nirkabel jarak jauh.', 'MikroTik', 'p041_mikrotik_sxtsq_5_ac_outdoor_cpe.jpg'),
(48, 'TP-Link CPE510 Outdoor 5GHz', 'Access Point', 775000.00, 14, 'CPE outdoor 5GHz dengan antena directional untuk koneksi wireless jarak jauh.', 'TP-Link', 'p042_tp_link_cpe510_outdoor_5ghz.jpg'),
(49, 'TP-Link CPE220 Outdoor 2.4GHz', 'Access Point', 695000.00, 12, 'CPE outdoor 2,4GHz untuk jaringan wireless dan distribusi akses internet.', 'TP-Link', 'p043_tp_link_cpe220_outdoor_2_4ghz.jpg'),
(50, 'TP-Link RE305 AC1200 Wi-Fi Range Extender', 'Access Point', 375000.00, 23, 'Perluasan jangkauan sinyal Wi-Fi dual-band AC1200 di rumah atau kantor kecil.', 'TP-Link', 'p044_tp_link_re305_ac1200_wi_fi_range_extender.jpg'),
(51, 'TP-Link TL-POE150S Gigabit PoE Injector', 'Aksesoris', 255000.00, 24, 'PoE injector Gigabit untuk menyalurkan data dan daya lewat kabel Ethernet.', 'TP-Link', 'p045_tp_link_tl_poe150s_gigabit_poe_injector.jpg'),
(52, 'TP-Link TL-POE10R PoE Splitter', 'Aksesoris', 235000.00, 18, 'PoE splitter yang memisahkan daya dan data dari kabel Ethernet untuk perangkat kompatibel.', 'TP-Link', 'p046_tp_link_tl_poe10r_poe_splitter.jpg'),
(53, 'Proskit Tang Crimping RJ45 Cat5e Cat6', 'Aksesoris', 215000.00, 27, 'Tang krimping untuk pemasangan konektor RJ45 pada kabel jaringan.', 'Proskit', 'p047_proskit_tang_crimping_rj45_cat5e_cat6.jpg'),
(54, 'Proskit Punch Down Tool Krone 110', 'Aksesoris', 175000.00, 21, 'Alat punch down untuk terminasi kabel UTP ke jack keystone dan patch panel.', 'Proskit', 'p048_proskit_punch_down_tool_krone_110.jpg'),
(55, 'Noyafa NF-468 LAN Cable Tester RJ45 RJ11', 'Aksesoris', 185000.00, 22, 'Alat uji kontinuitas kabel jaringan RJ45 dan kabel telepon RJ11.', 'Noyafa', 'p049_noyafa_nf_468_lan_cable_tester_rj45_rj11.jpg'),
(56, 'Cable Ties Hitam 3.6x200 mm 100 pcs', 'Aksesoris', 35000.00, 95, 'Pengikat kabel nilon hitam panjang 200 mm untuk perapian instalasi jaringan, isi 100 buah.', 'Generic', 'p050_cable_ties_hitam_3_6x200_mm_100_pcs.jpg'),
(57, 'TP-Link TL-WR840N Wireless Router 300Mbps', 'Router', 195000.00, 22, 'Router Wi-Fi 2,4 GHz 300 Mbps untuk rumah atau usaha kecil; dilengkapi 4 port LAN.', 'TP-Link', 'p001_tp_link_tl_wr840n_wireless_router_300mbps.jpg'),
(58, 'TP-Link Archer C6 AC1200 Gigabit Router', 'Router', 485000.00, 16, 'Router dual-band AC1200 dengan port Gigabit Ethernet untuk jaringan rumah dan kantor.', 'TP-Link', 'p002_tp_link_archer_c6_ac1200_gigabit_router.jpg'),
(59, 'TP-Link Archer AX53 AX3000 Wi-Fi 6 Router', 'Router', 1095000.00, 11, 'Router Wi-Fi 6 dual-band AX3000, mendukung koneksi cepat dan banyak perangkat.', 'TP-Link', 'p003_tp_link_archer_ax53_ax3000_wi_fi_6_router.jpg'),
(60, 'TP-Link EAP225 AC1350 Access Point', 'Access Point', 845000.00, 12, 'Access point dual-band AC1350 untuk pemasangan plafon dan jaringan bisnis Omada.', 'TP-Link', 'p004_tp_link_eap225_ac1350_access_point.jpg'),
(61, 'Ubiquiti UniFi U6 Lite Wi-Fi 6 Access Point', 'Access Point', 1950000.00, 9, 'Access point Wi-Fi 6 UniFi dengan desain plafon dan manajemen jaringan terpusat.', 'Ubiquiti', 'p005_ubiquiti_unifi_u6_lite_wi_fi_6_access_point.jpg'),
(62, 'TP-Link TL-SG108 8-Port Gigabit Switch', 'Switch', 325000.00, 25, 'Switch desktop unmanaged 8 port RJ45 Gigabit untuk jaringan LAN.', 'TP-Link', 'p006_tp_link_tl_sg108_8_port_gigabit_switch.jpg'),
(63, 'TP-Link TL-SG116 16-Port Gigabit Switch', 'Switch', 890000.00, 13, 'Switch unmanaged 16 port Gigabit untuk ekspansi jaringan kabel.', 'TP-Link', 'p007_tp_link_tl_sg116_16_port_gigabit_switch.jpg'),
(64, 'TP-Link TL-SG2428P 24-Port PoE+ Managed Switch', 'Switch', 5495000.00, 5, 'Switch managed Omada 24 port Gigabit dengan PoE+ dan port uplink SFP.', 'TP-Link', 'p008_tp_link_tl_sg2428p_24_port_poe_managed_switch.jpg'),
(65, 'Ubiquiti UniFi US-24 24-Port Gigabit Switch', 'Switch', 4295000.00, 5, 'Switch UniFi 24 port Gigabit untuk infrastruktur jaringan kantor.', 'Ubiquiti', 'p009_ubiquiti_unifi_us_24_24_port_gigabit_switch.jpg'),
(66, 'MikroTik CRS328-24P-4S+RM PoE Switch', 'Switch', 7995000.00, 4, 'Switch 24 port Gigabit PoE dengan 4 slot SFP+ 10G dan fitur manajemen RouterOS/SwOS.', 'MikroTik', 'p010_mikrotik_crs328_24p_4s_rm_poe_switch.jpg'),
(67, 'Tenda TEF1005D 5-Port Fast Ethernet Switch', 'Switch', 89000.00, 35, 'Switch unmanaged 5 port 10/100 Mbps untuk jaringan LAN sederhana.', 'Tenda', 'p011_tenda_tef1005d_5_port_fast_ethernet_switch.jpg'),
(68, 'Tenda TEG1008D 8-Port Gigabit Switch', 'Switch', 265000.00, 19, 'Switch desktop 8 port Gigabit dengan pengoperasian plug-and-play.', 'Tenda', 'p012_tenda_teg1008d_8_port_gigabit_switch.jpg'),
(69, 'ZTE F660 GPON ONT Wi-Fi', 'Router', 275000.00, 14, 'Perangkat ONT GPON dengan fungsi router untuk akses jaringan fiber optik; kompatibilitas bergantung pada ISP.', 'ZTE', 'p013_zte_f660_gpon_ont_wi_fi.jpg'),
(70, 'Huawei HG8245H GPON ONT Router', 'Router', 325000.00, 11, 'ONT GPON dengan Wi-Fi dan port LAN untuk terminasi jaringan fiber; aktivasi bergantung ISP.', 'Huawei', 'p014_huawei_hg8245h_gpon_ont_router.jpg'),
(71, 'TP-Link TL-SM311LS SFP Single Mode 1.25G', 'Aksesoris', 325000.00, 12, 'Modul SFP 1,25 Gbps single-mode untuk koneksi fiber optik kompatibel.', 'TP-Link', 'p015_tp_link_tl_sm311ls_sfp_single_mode_1_25g.jpg'),
(72, 'MikroTik S+85DLC03D SFP+ 10G Module', 'Aksesoris', 995000.00, 8, 'Transceiver SFP+ 10Gbps multimode untuk koneksi jarak pendek dengan fiber LC.', 'MikroTik', 'p016_mikrotik_s_85dlc03d_sfp_10g_module.jpg'),
(73, 'AMP CommScope Keystone Jack Cat6 RJ45', 'Aksesoris', 65000.00, 50, 'Keystone jack RJ45 Cat6 untuk pemasangan outlet data dan patch panel.', 'AMP CommScope', 'p017_amp_commscope_keystone_jack_cat6_rj45.jpg'),
(74, 'Belden Keystone Jack Cat6A RJ45', 'Aksesoris', 95000.00, 35, 'Keystone jack RJ45 kategori 6A untuk instalasi jaringan terstruktur.', 'Belden', 'p018_belden_keystone_jack_cat6a_rj45.jpg'),
(75, 'AMP CommScope Konektor RJ45 Cat6 STP Shielded 50 pcs', 'Aksesoris', 195000.00, 18, 'Paket konektor RJ45 shielded Cat6 untuk instalasi kabel jaringan STP, isi 50 buah.', 'AMP CommScope', 'p019_amp_commscope_konektor_rj45_cat6_stp_shielded_50.jpg'),
(76, 'RJ45 Boot Pelindung Konektor Biru 100 pcs', 'Aksesoris', 45000.00, 70, 'Pelindung karet boot RJ45 warna biru untuk mengurangi tekanan pada konektor, isi 100 buah.', 'Generic', 'p020_rj45_boot_pelindung_konektor_biru_100_pcs.jpg'),
(77, 'D-Link Kabel UTP Cat6 305 Meter', 'Kabel UTP', 1250000.00, 10, 'Kabel LAN UTP Cat6 305 meter dalam boks untuk instalasi jaringan gedung.', 'D-Link', 'p021_d_link_kabel_utp_cat6_305_meter.jpg'),
(78, 'Belden Kabel UTP Cat6A 305 Meter', 'Kabel UTP', 2850000.00, 7, 'Kabel jaringan Cat6A panjang 305 meter untuk instalasi data berkinerja tinggi.', 'Belden', 'p022_belden_kabel_utp_cat6a_305_meter.jpg'),
(79, 'Belden Kabel Cat6 Outdoor 305 Meter', 'Kabel UTP', 2350000.00, 9, 'Kabel jaringan Cat6 outdoor 305 meter dengan selubung untuk penggunaan luar ruangan.', 'Belden', 'p023_belden_kabel_cat6_outdoor_305_meter.jpg'),
(80, 'Fiber Optic Patch Cord Single Mode SC-LC 3 Meter', 'Aksesoris', 75000.00, 48, 'Patch cord fiber optik single-mode konektor SC ke LC panjang 3 meter.', 'Generic', 'p024_fiber_optic_patch_cord_single_mode_sc_lc_3_meter.jpg'),
(81, 'Fiber Optic Patch Cord Multimode LC-LC 3 Meter', 'Aksesoris', 95000.00, 40, 'Patch cord fiber optik multimode konektor LC ke LC panjang 3 meter.', 'Generic', 'p025_fiber_optic_patch_cord_multimode_lc_lc_3_meter.jpg'),
(82, 'Faceplate RJ45 Cat6 1 Port', 'Aksesoris', 18000.00, 85, 'Pelat outlet data dinding dengan satu lubang port keystone RJ45.', 'Generic', 'p026_faceplate_rj45_cat6_1_port.jpg'),
(83, 'Faceplate RJ45 Cat6 2 Port', 'Aksesoris', 22000.00, 75, 'Pelat outlet jaringan dinding untuk dua port keystone RJ45.', 'Generic', 'p027_faceplate_rj45_cat6_2_port.jpg'),
(84, 'Surface Box RJ45 2 Port', 'Aksesoris', 28000.00, 65, 'Kotak permukaan untuk pemasangan outlet jaringan RJ45 1 sampai 2 port.', 'Generic', 'p028_surface_box_rj45_2_port.jpg'),
(85, 'Patch Panel Cat6 24 Port 1U Rackmount', 'Aksesoris', 675000.00, 13, 'Patch panel Cat6 24 port untuk terminasi kabel jaringan pada rak 19 inci.', 'Generic', 'p029_patch_panel_cat6_24_port_1u_rackmount.jpg'),
(86, 'Rack Server 12U Wallmount 600x450 mm', 'Aksesoris', 1650000.00, 6, 'Kabinet rack jaringan 12U tipe wallmount untuk switch, patch panel, dan perangkat jaringan.', 'Generic', 'p030_rack_server_12u_wallmount_600x450_mm.jpg'),
(87, 'Rack Server 27U Standing 600x800 mm', 'Aksesoris', 4750000.00, 3, 'Kabinet rack standing 27U untuk penataan perangkat jaringan dan server.', 'Generic', 'p031_rack_server_27u_standing_600x800_mm.jpg'),
(88, 'PDU 8 Port 1U Rackmount', 'Aksesoris', 425000.00, 21, 'Power distribution unit rackmount 1U dengan delapan outlet listrik.', 'Generic', 'p032_pdu_8_port_1u_rackmount.jpg'),
(89, 'APC Back-UPS BX1200MI 1200VA', 'Aksesoris', 1950000.00, 8, 'UPS untuk membantu menjaga suplai daya perangkat jaringan saat gangguan listrik.', 'APC', 'p033_apc_back_ups_bx1200mi_1200va.jpg'),
(90, 'MikroTik hEX S RB760iGS Router', 'Router', 1095000.00, 11, 'Router kabel dengan 5 port Gigabit Ethernet dan slot SFP untuk jaringan kantor.', 'MikroTik', 'p034_mikrotik_hex_s_rb760igs_router.jpg'),
(91, 'MikroTik RB4011iGS+RM 10-Port Router', 'Router', 3650000.00, 6, 'Router performa tinggi dengan 10 port Gigabit dan satu port SFP+.', 'MikroTik', 'p035_mikrotik_rb4011igs_rm_10_port_router.jpg'),
(92, 'MikroTik hAP ac2 Dual Band Router', 'Router', 1295000.00, 13, 'Router nirkabel dual-band AC1200 dengan lima port Gigabit Ethernet.', 'MikroTik', 'p036_mikrotik_hap_ac2_dual_band_router.jpg'),
(93, 'TP-Link Omada ER605 Gigabit VPN Router', 'Router', 895000.00, 11, 'Router VPN Gigabit untuk kebutuhan manajemen jaringan bisnis Omada.', 'TP-Link', 'p037_tp_link_omada_er605_gigabit_vpn_router.jpg'),
(94, 'TP-Link Omada OC200 Hardware Controller', 'Aksesoris', 1550000.00, 7, 'Controller perangkat keras untuk manajemen terpusat perangkat TP-Link Omada.', 'TP-Link', 'p038_tp_link_omada_oc200_hardware_controller.jpg'),
(95, 'Ubiquiti UniFi Cloud Key Gen2 UCK-G2', 'Aksesoris', 3450000.00, 4, 'Controller UniFi Cloud Key generasi kedua untuk pengelolaan perangkat jaringan UniFi.', 'Ubiquiti', 'p039_ubiquiti_unifi_cloud_key_gen2_uck_g2.jpg'),
(96, 'Ubiquiti NanoBeam M5 CPE Outdoor 5GHz', 'Access Point', 1450000.00, 8, 'CPE wireless outdoor 5GHz untuk koneksi point-to-point dan point-to-multipoint.', 'Ubiquiti', 'p040_ubiquiti_nanobeam_m5_cpe_outdoor_5ghz.jpg'),
(97, 'MikroTik SXTsq 5 ac Outdoor CPE', 'Access Point', 1150000.00, 9, 'Perangkat CPE outdoor 5GHz 802.11ac untuk jaringan nirkabel jarak jauh.', 'MikroTik', 'p041_mikrotik_sxtsq_5_ac_outdoor_cpe.jpg'),
(98, 'TP-Link CPE510 Outdoor 5GHz', 'Access Point', 775000.00, 14, 'CPE outdoor 5GHz dengan antena directional untuk koneksi wireless jarak jauh.', 'TP-Link', 'p042_tp_link_cpe510_outdoor_5ghz.jpg'),
(99, 'TP-Link CPE220 Outdoor 2.4GHz', 'Access Point', 695000.00, 12, 'CPE outdoor 2,4GHz untuk jaringan wireless dan distribusi akses internet.', 'TP-Link', 'p043_tp_link_cpe220_outdoor_2_4ghz.jpg'),
(100, 'TP-Link RE305 AC1200 Wi-Fi Range Extender', 'Access Point', 375000.00, 23, 'Perluasan jangkauan sinyal Wi-Fi dual-band AC1200 di rumah atau kantor kecil.', 'TP-Link', 'p044_tp_link_re305_ac1200_wi_fi_range_extender.jpg'),
(101, 'TP-Link TL-POE150S Gigabit PoE Injector', 'Aksesoris', 255000.00, 24, 'PoE injector Gigabit untuk menyalurkan data dan daya lewat kabel Ethernet.', 'TP-Link', 'p045_tp_link_tl_poe150s_gigabit_poe_injector.jpg'),
(102, 'TP-Link TL-POE10R PoE Splitter', 'Aksesoris', 235000.00, 18, 'PoE splitter yang memisahkan daya dan data dari kabel Ethernet untuk perangkat kompatibel.', 'TP-Link', 'p046_tp_link_tl_poe10r_poe_splitter.jpg'),
(103, 'Proskit Tang Crimping RJ45 Cat5e Cat6', 'Aksesoris', 215000.00, 27, 'Tang krimping untuk pemasangan konektor RJ45 pada kabel jaringan.', 'Proskit', 'p047_proskit_tang_crimping_rj45_cat5e_cat6.jpg'),
(104, 'Proskit Punch Down Tool Krone 110', 'Aksesoris', 175000.00, 21, 'Alat punch down untuk terminasi kabel UTP ke jack keystone dan patch panel.', 'Proskit', 'p048_proskit_punch_down_tool_krone_110.jpg'),
(105, 'Noyafa NF-468 LAN Cable Tester RJ45 RJ11', 'Aksesoris', 185000.00, 22, 'Alat uji kontinuitas kabel jaringan RJ45 dan kabel telepon RJ11.', 'Noyafa', 'p049_noyafa_nf_468_lan_cable_tester_rj45_rj11.jpg'),
(106, 'Cable Ties Hitam 3.6x200 mm 100 pcs', 'Aksesoris', 35000.00, 95, 'Pengikat kabel nilon hitam panjang 200 mm untuk perapian instalasi jaringan, isi 100 buah.', 'Generic', 'p050_cable_ties_hitam_3_6x200_mm_100_pcs.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1g.9.c2iYdGj8kFfV31U/D9p2XnNl.O', 'admin', '2026-10-25 12:35:25'),
(2, 'Customer Test', 'customer', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1g.9.c2iYdGj8kFfV31U/D9p2XnNl.O', 'customer', '2026-10-25 12:35:25'),
(3, 'Riko tampati', 'riko', '$2y$10$.M/WolxXsGOBEkUd2NP9keHukHvQHH45VKG2kH0quChetvyYfjLau', 'admin', '2026-10-25 12:42:54');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
