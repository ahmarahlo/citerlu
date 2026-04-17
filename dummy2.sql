INSERT INTO booking (tanggal, id_laptop, id_servis, status) VALUES 
('2024-05-01', 4, 2, 'Selesai'),
('2024-05-02', 5, 4, 'Selesai'),
('2024-05-05', 6, 1, 'Menunggu Antrean'),
('2024-05-06', 7, 3, 'Menunggu Sparepart');

-- Detail Servis for booking 1, 2, 4
INSERT INTO detail_servis (id_booking, id_sparepart, qty, harga_satuan) VALUES 
(1, 5, 1, 150000),
(2, 2, 1, 750000),
(4, 3, 1, 350000);

-- Nota Pembayaran for 1 and 2
INSERT INTO nota_pembayaran (id_booking, total_biaya, tanggal_selesai, garansi_servis_habis, garansi_sparepart_habis) VALUES 
(1, 400000, '2024-05-03', '2024-06-02', '2025-05-03'),
(2, 950000, '2024-05-04', '2024-06-03', '2025-05-04');
