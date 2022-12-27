-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Des 2021 pada 09.53
-- Versi server: 10.4.13-MariaDB
-- Versi PHP: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_master`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(250) NOT NULL,
  `url` varchar(120) NOT NULL,
  `id_menu_induk` int(11) NOT NULL,
  `urutan` int(11) NOT NULL,
  `icon` varchar(120) DEFAULT NULL,
  `uuid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `url`, `id_menu_induk`, `urutan`, `icon`, `uuid`) VALUES
(1, 'App Setting', '#', 0, 1, NULL, 'ce2b23ea-3dec-321f-94c0-cce7644816e6'),
(2, 'Setting Menu', 'setting-menu', 1, 1, '', 'cc1c6f62-fdfc-30cc-a69f-9b2fa5c152d5'),
(3, 'Setting Role', 'setting-role', 1, 2, NULL, '3cd905ad-79e1-3f48-9b95-6a044a5606b5'),
(7, 'Manajemen User', '#', 0, 2, NULL, 'da0e2739-289b-386e-bf6e-af898155859d'),
(8, 'User Author', 'manajemen-user-author', 7, 2, NULL, '3054fbc4-1df2-3634-b5c3-265acc32ab86'),
(9, 'User Asesor', 'manajemen-user-asesor', 7, 3, NULL, 'bb8683a7-39c9-3fcd-b392-39924ee12d59'),
(10, 'User Student', 'manajemen-user-student', 7, 4, NULL, '739418ab-abd3-3bd8-9704-a07284e13e4d'),
(11, 'User Administrator', 'manajemen-user-admin', 7, 1, NULL, '74de577b-75cf-3ece-8bbd-148055cf607c');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(120) NOT NULL,
  `akses_global` int(11) NOT NULL DEFAULT 0,
  `uuid` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id_role`, `nama_role`, `akses_global`, `uuid`) VALUES
(1, 'Admin Sistem', 1, '06a77460-5ec7-3ebc-a6b8-f6c0c3965486'),
(3, 'Administrator', 0, 'e9b0e29a-60c2-33bd-ac5e-d943bc6e3783'),
(4, 'Author', 0, '766fc924-9e94-38c5-875c-e8f97657cba1'),
(5, 'Asesor', 0, '43b16dcf-d837-3975-859c-68f4f7ee814b'),
(7, 'Student', 0, '8350058c-6a02-3d97-9f4c-4aaf95323405');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_menu`
--

CREATE TABLE `role_menu` (
  `id_role_menu` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `ucc` int(11) NOT NULL DEFAULT 0,
  `ucu` int(11) NOT NULL DEFAULT 0,
  `ucd` int(11) NOT NULL DEFAULT 0,
  `uuid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data untuk tabel `role_menu`
--

INSERT INTO `role_menu` (`id_role_menu`, `id_role`, `id_menu`, `ucc`, `ucu`, `ucd`, `uuid`) VALUES
(1, 1, 2, 1, 1, 1, '9bc8ae0d-ec77-336f-a14e-35775d04165e'),
(2, 1, 3, 1, 1, 1, '51d9626d-4227-3c08-8b7f-5879d71fb096'),
(3, 1, 8, 1, 1, 1, '0a228153-6c51-3459-b46e-8469f8a536f8'),
(5, 1, 11, 1, 1, 1, 'ac9df84c-dc8b-35ac-a70e-41eb7d010aee'),
(6, 1, 10, 1, 1, 1, '08c6454f-b2eb-339e-aade-1d4ade5085be'),
(7, 1, 9, 1, 1, 1, 'b4863298-71a4-3daf-bebb-3d20301cc7f8'),
(8, 3, 8, 1, 1, 1, '52d4f27c-511c-3c67-848e-fa023ec258e1'),
(9, 3, 9, 1, 1, 1, '8ea1891d-ed44-3bb5-82f3-eb9404ee43da'),
(10, 3, 10, 1, 1, 1, 'f91ac5dc-ae7e-3989-a20d-08f9172053b5');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(120) NOT NULL,
  `password` varchar(250) NOT NULL,
  `nama_pengguna` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `telp` varchar(30) NOT NULL,
  `remember_token` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `uuid` varchar(50) NOT NULL,
  `avatar` varchar(120) DEFAULT 'user.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_pengguna`, `email`, `telp`, `remember_token`, `created_at`, `updated_at`, `uuid`, `avatar`) VALUES
(1, 'admin', '$2y$10$.TrCAb9sgIIUIWOqunLvxubUpHWNomCLNgyYOqvIQqqJRG4/HZ9Gu', 'Administrator Sistem', 'admin@sicerdas.com', '-', '6LsooOezeTGgBZE3eJcrN8HUjUObf8M7cJR9CQesdJJsOWAkqwfPgQoPpq49', '2021-11-25 14:43:30', '2021-11-25 14:43:31', '2362362734-23623623-734734-734', 'user.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_role`
--

CREATE TABLE `user_role` (
  `id_user_role` int(11) NOT NULL,
  `id_organisasi` int(11) NOT NULL DEFAULT 0,
  `id_role` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `uuid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data untuk tabel `user_role`
--

INSERT INTO `user_role` (`id_user_role`, `id_organisasi`, `id_role`, `id_user`, `uuid`) VALUES
(1, 0, 1, 1, 'dd5458e1-847a-3e5c-9cbc-bfcf966fb394');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`) USING BTREE,
  ADD KEY `uuid` (`uuid`) USING BTREE,
  ADD KEY `uuid_2` (`uuid`) USING BTREE;

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`) USING BTREE,
  ADD KEY `uuid` (`uuid`) USING BTREE;

--
-- Indeks untuk tabel `role_menu`
--
ALTER TABLE `role_menu`
  ADD PRIMARY KEY (`id_role_menu`) USING BTREE,
  ADD KEY `id_role` (`id_role`) USING BTREE,
  ADD KEY `id_menu` (`id_menu`) USING BTREE,
  ADD KEY `uuid` (`uuid`) USING BTREE;

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`) USING BTREE,
  ADD KEY `uuid` (`uuid`) USING BTREE;

--
-- Indeks untuk tabel `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id_user_role`) USING BTREE,
  ADD KEY `uuid` (`uuid`) USING BTREE;

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `role_menu`
--
ALTER TABLE `role_menu`
  MODIFY `id_role_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id_user_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
