 
import shutil
import re
import os
source_dir = r"c:\Kodingers\phpin"
target_dir = r"c:\Kodingers\phpin_friend"

if not os.path.exists(target_dir):
    os.makedirs(target_dir)

files_to_copy = [
    "db_techfix.sql",
    "koneksi.php",
    "stylee.css",
    "index.php",
    "login.php",
    "register.php",
    "cek_login.php",
    "logout.php",
    "dashboard.php",
    "form_booking.php",
    "form_laptop.php",
    "form_servis.php",
    "form_sparepart.php",
    "edit_sparepart.php",
    "nota_pembayaran.php",
    "laporan_keuangan.php",
    "aksi.php"
]

file_rename_map = {
    "db_techfix.sql": "db_pccare.sql",
    "stylee.css": "tema.css",
    "aksi.php": "kontroler.php",
    "dashboard.php": "beranda.php",
    "form_laptop.php": "data_device.php",
    "form_booking.php": "buat_antrean.php",
    "form_sparepart.php": "inventaris_part.php",
    "edit_sparepart.php": "ubah_part.php",
    "form_servis.php": "layanan_jasa.php",
    "nota_pembayaran.php": "cetak_struk.php",
    "laporan_keuangan.php": "laporan_profit.php"
}

content_replacements = {
    "db_techfix": "db_pccare",
    "TechFix & Parts": "Klinik PC & Gadget",
    "TechFix": "PCCare",
    "stylee.css": "tema.css",
    "aksi.php": "kontroler.php",
    "dashboard.php": "beranda.php",
    "form_laptop.php": "data_device.php",
    "form_booking.php": "buat_antrean.php",
    "form_sparepart.php": "inventaris_part.php",
    "edit_sparepart.php": "ubah_part.php",
    "form_servis.php": "layanan_jasa.php",
    "nota_pembayaran.php": "cetak_struk.php",
    "laporan_keuangan.php": "laporan_profit.php",
    
    # PHP variables
    "$koneksi": "$db_conn",
    "$query": "$sql_run",
    "$row": "$baris",
    "$q_nota": "$q_struk",
    "$q_part": "$q_komponen",
    "$id_booking": "$id_antrean",
    
    # CSS
    'class="container"': 'class="wrapper-utama"',
    '.container ': '.wrapper-utama ',
    '.container{': '.wrapper-utama{',
    'class="nav"': 'class="menu-nav"',
    '.nav ': '.menu-nav ',
    '.nav{': '.menu-nav{',
    'class="navright"': 'class="menu-kanan"',
    '.navright': '.menu-kanan',
    'class="navleft"': 'class="menu-kiri"',
    '.navleft': '.menu-kiri',
    'class="btn"': 'class="tombol"',
    'class="btn ': 'class="tombol ',
    '.btn ': '.tombol ',
    '.btn{': '.tombol{',
    
    # Colors
    "#2c3e50": "#1e3a8a",
    "#3498db": "#059669",
    "#ecf0f1": "#f3f4f6"
}

for sf in files_to_copy:
    src_path = os.path.join(source_dir, sf)
    dest_name = file_rename_map.get(sf, sf)
    dest_path = os.path.join(target_dir, dest_name)
    
    if os.path.exists(src_path):
        with open(src_path, "r", encoding="utf-8") as file:
            content = file.read()
            
        for k, v in content_replacements.items():
            content = content.replace(k, v)
            
        with open(dest_path, "w", encoding="utf-8") as file:
            file.write(content)

print(f"Project successfully cloned and obfuscated at {target_dir}")
