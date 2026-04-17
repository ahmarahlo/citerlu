-- Insert Users
INSERT INTO users (username, password, role) VALUES 
('budi', 'budi', 'Pelanggan'),
('siti', 'siti', 'Pelanggan'),
('andi', 'andi', 'Pelanggan');

-- Insert Laptop (Assuming User IDs are 3, 4, 5 since 1 and 2 are Admin, Teknisi)
INSERT INTO laptop (nama_laptop, jenis, id_pemilik) VALUES 
('Asus ROG Strix', 'Gaming', 3),
('Lenovo ThinkPad X1', 'Ultrabook', 4),
('MacBook Pro M2', 'Laptop', 5),
('Acer Nitro 5', 'Gaming', 3);

-- Insert Servis
INSERT INTO servis (nama_servis, harga_servis) VALUES 
('Instal Ulang Windows', 150000),
('Pembersihan Kipas & Pasta', 250000),
('Ganti Keyboard', 100000),
('Ganti LCD Screen', 200000),
('Cek Mati Total', 50000);

-- Insert Sparepart
INSERT INTO sparepart (sku, nama_sparepart, jumlah, harga_beli, harga_jual) VALUES 
('RAM8GB', 'RAM Corsair 8GB DDR4', 15, 300000, 450000),
('SSD512', 'SSD Samsung 512GB', 10, 500000, 750000),
('KB-ASUS', 'Keyboard Asus ROG', 5, 200000, 350000),
('BATT-LEN', 'Baterai Lenovo X1', 3, 600000, 850000),
('PSTA', 'Thermal Paste Grizzly', 20, 100000, 150000);

-- Insert Booking
-- id_laptop 1, 2, 3, 4
-- id_servis 1, 2, 3, 4, 5
INSERT INTO booking (tanggal, id_laptop, id_servis, status) VALUES 
('2024-05-01', 1, 2, 'Selesai'),
('2024-05-02', 2, 4, 'Selesai'),
('2024-05-05', 3, 1, 'Menunggu Antrean'),
('2024-05-06', 4, 3, 'Menunggu Sparepart');

-- Insert Detail Servis
INSERT INTO detail_servis (id_booking, id_sparepart, qty, harga_satuan) VALUES 
(1, 5, 1, 150000),
(2, 2, 1, 750000),
(4, 3, 1, 350000);

-- Insert Nota Pembayaran
-- Booking 1: Jasa = 250000, Part = 150000 -> Total = 400000
-- Booking 2: Jasa = 200000, Part = 750000 -> Total = 950000
INSERT INTO nota_pembayaran (id_booking, total_biaya, tanggal_selesai, garansi_servis_habis, garansi_sparepart_habis) VALUES 
(1, 400000, '2024-05-03', '2024-06-02', '2025-05-03'),
(2, 950000, '2024-05-04', '2024-06-03', '2025-05-04');
