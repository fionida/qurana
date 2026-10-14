<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyQurana - Mewujudkan Generasi Qur'ani Rabbaniyyin</title>
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <!-- CSS Utama -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#beranda" class="logo">
                <img src="https://images.unsplash.com/photo-1585032226651-759b368d7246?q=80&w=100&auto=format&fit=crop" alt="Logo MyQurana" class="logo-img">
                <div class="logo-text">
                    <span class="brand-name">myQurana</span>
                    <span class="brand-tagline">Membina Guru, Membina Generasi Qur'ani</span>
                </div>
            </a>
            <!-- Ditambahkan id "navMenu" untuk fungsionalitas toggle di HP -->
            <ul class="nav-menu" id="navMenu">
                <li><a href="#beranda" class="active">Beranda</a></li>
                <li><a href="#program">Program</a></li>
                <li><a href="#keunggulan">Keunggulan</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>
            <div class="nav-action">
                <a href="https://daftar.myqurana.org/" class="btn btn-cta" target="_blank" rel="noopener">Daftar <i class="fa-solid fa-arrow-right"></i></a>
                <!-- Ditambahkan id "menuToggle" -->
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
    </nav>

    <!-- ================= HERO SECTION ================= -->
    <section id="beranda" class="hero-section">
        <div class="container hero-grid">
            <div class="hero-text">
                <h1>Mewujudkan Generasi<br><span class="highlight-orange">Qur'ani</span> Rabbaniyyin</h1>
                <p>MyQurana adalah organisasi pendidikan Islami yang berfokus pada pembinaan tenaga pendidik Qur'an agar mampu membaca, mengajarkan, dan menginspirasi dengan metode Qurana.</p>
                <div class="hero-buttons">
                    <a href="#tentang" class="btn btn-primary">Pelajari Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                    <a href="#program" class="btn btn-secondary">Lihat Program <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="hero-image">
                <div class="image-frame">
                    <img src="https://assets-a1.kompasiana.com/items/album/2016/09/26/ilustrasi-lespripat99-com-57e88e28a6afbd1017b466bd.jpg" alt="Pendidik Al-Qur'an">
                </div>
            </div>
        </div>
    </section>

    <!-- ================= METODE SUMMARY BAR ================= -->
    <section class="summary-bar-section">
        <div class="container">
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="sum-icon"><i class="fa-solid fa-circle-check"></i></div>
                    <h3>Metode Teruji</h3>
                    <p>Metode Qurana telah digunakan oleh ribuan guru & lembaga pendidikan.</p>
                </div>
                <div class="summary-card">
                    <div class="sum-icon"><i class="fa-solid fa-book-open-reader"></i></div>
                    <h3>Sistematis & Mudah</h3>
                    <p>Materi disusun sistematis, sehingga dipahami dan diterapkan.</p>
                </div>
                <div class="summary-card">
                    <div class="sum-icon"><i class="fa-solid fa-users-gear"></i></div>
                    <h3>Pendampingan</h3>
                    <p>Peserta mendapatkan bimbingan dan evaluasi hingga kompeten.</p>
                </div>
                <div class="summary-card">
                    <div class="sum-icon"><i class="fa-solid fa-star"></i></div>
                    <h3>Sesuai Tajwid</h3>
                    <p>Mengajarkan sesuai kaidah tajwid dan bersanad yang terpercaya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TENTANG MYQURANA ================= -->
    <section id="tentang" class="about-section">
        <div class="container about-grid">
            <div class="about-image">
                <div class="dome-image-wrapper">
                    <img src="https://awsimages.detik.net.id/community/media/visual/2014/09/25/48e27ab7-b9bb-45c7-9918-b329dd64ca9f_169.jpg?w=600&q=90" alt="Al-Quran">
                </div>
            </div>
            <div class="about-text">
                <h2>Organisasi Pendidikan Islami<br>untuk <span class="highlight-orange">Pendidik Qur'an</span></h2>
                <p class="about-desc">MyQurana berkomitmen mencetak pendidik Qur'an yang profesional, berakhlak, dan mampu membimbing generasi menuju pribadi yang berilmu dan beradab.</p>
                
                <div class="vision-mission-list">
                    <div class="vml-item">
                        <div class="vml-icon"><i class="fa-solid fa-eye"></i></div>
                        <div class="vml-content">
                            <h3>Visi</h3>
                            <p>Menjadi organisasi pendidikan terdepan dalam membina pendidik Qur'an yang kompeten dan berakhlak mulia.</p>
                        </div>
                    </div>
                    <div class="vml-item">
                        <div class="vml-icon"><i class="fa-solid fa-bullseye"></i></div>
                        <div class="vml-content">
                            <h3>Misi</h3>
                            <p>Menyediakan pelatihan berkualitas dengan metode yang teruji, sistematis, dan sesuai kaidah tajwid.</p>
                        </div>
                    </div>
                    <div class="vml-item">
                        <div class="vml-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                        <div class="vml-content">
                            <h3>Nilai</h3>
                            <p>Ikhlas, Amanah, Profesional, Berkemajuan, dan Bermanfaat bagi umat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= KEUNGGULAN METODE ================= -->
    <section id="keunggulan" class="keunggulan-section">
        <div class="container text-center">
            <h2 class="section-title">Keunggulan <span class="highlight-orange">Metode Qurana</span></h2>
            
            <div class="keunggulan-grid">
                <div class="keunggulan-card">
                    <div class="k-icon"><i class="fa-solid fa-face-smile"></i></div>
                    <h3>Mudah Dipahami</h3>
                    <p>Bahasa sederhana dan sistem belajar bertahap untuk semua usia.</p>
                </div>
                <div class="keunggulan-card">
                    <div class="k-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>Metode Teruji</h3>
                    <p>Telah digunakan di berbagai lembaga dan komunitas.</p>
                </div>
                <div class="keunggulan-card">
                    <div class="k-icon"><i class="fa-solid fa-gem"></i></div>
                    <h3>Tajwid Terjaga</h3>
                    <p>Materi sesuai kaidah tajwid dan makharijul huruf.</p>
                </div>
                <div class="keunggulan-card">
                    <div class="k-icon"><i class="fa-solid fa-user-tie"></i></div>
                    <h3>Mentor Profesional</h3>
                    <p>Dibimbing oleh mentor berpengalaman dan bersertifikat.</p>
                </div>
                <div class="keunggulan-card">
                    <div class="k-icon"><i class="fa-solid fa-certificate"></i></div>
                    <h3>Bersertifikat Resmi</h3>
                    <p>Setiap kelulusan mendapatkan sertifikat resmi MyQurana.</p>
                </div>
                <div class="keunggulan-card">
                    <div class="k-icon"><i class="fa-solid fa-building-columns"></i></div>
                    <h3>Lembaga Mitra</h3>
                    <p>Dipercaya oleh ratusan lembaga di seluruh Indonesia.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TIMELINE METODE BELAJAR ================= -->
    <section id="metode" class="timeline-section">
        <div class="container text-center">
            <h2 class="section-title text-white">Belajar Sistematis, Praktis, dan Terarah</h2>
            
            <div class="timeline-wrapper">
                <div class="timeline-line"></div>
                <div class="timeline-grid">
                    <div class="timeline-item">
                        <div class="timeline-number-box">
                            <span class="arabic-accent">أ ب</span>
                            <div class="num-circle">01</div>
                        </div>
                        <h3>Mengenal Huruf</h3>
                        <p>Memahami bentuk dan bunyi huruf hijaiyah dengan benar.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-number-box">
                            <span class="arabic-accent">ق</span>
                            <div class="num-circle">02</div>
                        </div>
                        <h3>Makharijul Huruf</h3>
                        <p>Mempelajari tempat keluarnya huruf sesuai kaidah tajwid.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-number-box">
                            <span class="arabic-accent">ص</span>
                            <div class="num-circle">03</div>
                        </div>
                        <h3>Tahsin</h3>
                        <p>Memperbaiki bacaan agar lebih fasih dan sesuai aturan.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-number-box">
                            <span class="arabic-accent">م</span>
                            <div class="num-circle">04</div>
                        </div>
                        <h3>Tartil</h3>
                        <p>Membaca dengan tartil, tenang, dan penuh penghayatan.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-number-box">
                            <span class="arabic-accent">ع</span>
                            <div class="num-circle">05</div>
                        </div>
                        <h3>Pengajaran</h3>
                        <p>Mampu mengajarkan kepada orang lain dengan percaya diri.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-number-box">
                            <span class="arabic-accent">✓</span>
                            <div class="num-circle">06</div>
                        </div>
                        <h3>Evaluasi</h3>
                        <p>Evaluasi berkelanjutan untuk memastikan konsistensi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= PILIHAN PROGRAM ================= -->
    <section id="program" class="program-section">
        <div class="container text-center">
            <h2 class="section-title">Pilihan Program <span class="highlight-orange">Terbaik</span> untuk Anda</h2>
            
            <div class="program-grid">
                <div class="program-card">
                    <div class="p-img">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwZM49TGvMI93FFynpgzTbgflsHPcgnh2B5R6GvHmL3g&s" alt="Pelatihan Guru">
                    </div>
                    <div class="p-content">
                        <h3>Pelatihan Guru Qur'an</h3>
                        <p>Program dasar untuk menjadi guru Qur'an yang kompeten.</p>
                        <a href="#" class="btn-text">Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="program-card">
                    <div class="p-img">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQP_aEioicm7LVVgKh9wK2PX67jFtNZ-FWEmUDzPSiJHKfJlv8HFcrJPQJe&s=10" alt="Workshop">
                    </div>
                    <div class="p-content">
                        <h3>Kelas Metode Qurana</h3>
                        <p>Kelas khusus untuk mempelajari metode qurana.</p>
                        <a href="#" class="btn-text">Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="program-card">
                    <div class="p-img">
                        <img src="https://i.ytimg.com/vi/Sg-t33s3708/sddefault.jpg" alt="TOT">
                    </div>
                    <div class="p-content">
                        <h3>Training of Trainer</h3>
                        <p>Program lanjutan untuk menjadi trainer bersertifikat MyQurana.</p>
                        <a href="#" class="btn-text">Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="program-card">
                    <div class="p-img">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQDupCk0YtJZEZLXS5s-LUIBPySmh5dyCBBx33uW0_-lYjwJ9ZOWvnOE7o&s=10" alt="Pendampingan">
                    </div>
                    <div class="p-content">
                        <h3>Pendampingan Lembaga</h3>
                        <p>Mendampingi lembaga dalam implementasi metode Qurana.</p>
                        <a href="#" class="btn-text">Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= STATS BAR ================= -->
    <section class="stats-bar-section">
        <div class="container stats-grid">
            <div class="stats-item">
                <i class="fa-solid fa-users"></i>
                <div>
                    <h4>2.000+</h4>
                    <p>Guru Qur'an</p>
                </div>
            </div>
            <div class="stats-item">
                <i class="fa-solid fa-school"></i>
                <div>
                    <h4>150+</h4>
                    <p>Lembaga Mitra</p>
                </div>
            </div>
            <div class="stats-item">
                <i class="fa-solid fa-map-location-dot"></i>
                <div>
                    <h4>30+</h4>
                    <p>Kota di Indonesia</p>
                </div>
            </div>
            <div class="stats-item">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <div>
                    <h4>8+</h4>
                    <p>Tahun Pengalaman</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= GALERI KEGIATAN ================= -->
    <section id="galeri" class="galeri-section">
        <div class="container text-center">
            <h2 class="section-title">Momen Inspiratif Bersama MyQurana</h2>
            
            <div class="galeri-grid">
                <img src="https://storage.nu.or.id/storage/post/16_9/mid/1629090660.jpg" alt="Foto Galeri 1">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRkIF-5zQk7iQfdns1nfllPZo_aLVSKia6iOKpAQvz0fqEyEgXKOjNN_Kg&s=10" alt="Foto Galeri 2">
                <img src="https://asset.kompas.com/crops/Xpk8lyRpXXFaYRGFHPfo8MDVbjY=/0x0:780x520/1200x800/data/photo/2024/02/05/65c09def68d32.jpg" alt="Foto Galeri 3">
                <img src="https://almasoem.sch.id/wp-content/uploads/2021/10/03-pesantren-005.jpg" alt="Foto Galeri 4">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ2G8RWdrRpfm3TgXr9sAxALX49Q9Lk-t9nor1u0eUymQ&s=10" alt="Foto Galeri 5">
                <img src="https://image.jpnn.com/resize/570x380-80/arsip/normal/2023/08/25/tuan-guru-sahabat-ganjar-mengajarkan-tilawah-al-quran-untuk-l2na.jpg" alt="Foto Galeri 6">
            </div>
            <!-- <a href="#" class="btn btn-primary margin-top-40">Lihat Galeri Lengkap <i class="fa-solid fa-arrow-right"></i></a> -->
        </div>
    </section>

   <!-- ================= TESTIMONI (MODERN GRID) ================= -->
    <section id="testimoni" class="testimoni-section">
        <div class="container text-center">
            <h2 class="section-title">Cerita Sukses Para Pendidik</h2>
            
            <div class="testimoni-modern-grid">
                <!-- Kartu 1 (Highlight/Besar) -->
                <div class="testi-modern-card featured">
                    <div class="testi-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testi-text">“Metode Qurana sangat membantu saya dalam mengajar. Materinya mudah dipahami dan sistemnya tertata. Alhamdulillah, murid-murid saya sekarang lebih cepat dan benar dalam membaca Al-Qur'an. Program pendampingannya luar biasa membantu!”</p>
                    <div class="testi-user">
                        <img src="https://img.lazcdn.com/g/p/7d11e3d40c33693cfd31df1a37d8187b.jpg_720x720q80.jpg" alt="Ustadzah Aisyah">
                        <div class="testi-info">
                            <h4>Ustadzah Aisyah</h4>
                            <p>Guru Qur'an - Bandung</p>
                        </div>
                    </div>
                </div>

                <!-- Kartu 2 -->
                <div class="testi-modern-card">
                    <div class="testi-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testi-text">“Sebagai pengelola TPQ, pelatihan TOT dari MyQurana memberikan standardisasi mengajar yang baru untuk guru-guru kami.”</p>
                    <div class="testi-user">
                        <img src="https://i.pinimg.com/736x/b8/7e/2d/b87e2d9bc73ec43a34e5fca45938cb1e.jpg" alt="Ustadz Ridwan">
                        <div class="testi-info">
                            <h4>Ustadz Ridwan</h4>
                            <p>Pengelola TPQ - Jakarta</p>
                        </div>
                    </div>
                </div>

                <!-- Kartu 3 -->
                <div class="testi-modern-card">
                    <div class="testi-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testi-text">“Sangat sistematis! Penjelasan makharijul huruf dikemas dengan analogi sederhana yang langsung nempel di ingatan.”</p>
                    <div class="testi-user">
                        <img src="https://i.pinimg.com/736x/d1/6c/6c/d16c6cdfbd4d0d7bbde5d5b160ab74c7.jpg" alt="Ustadzah Fatimah">
                        <div class="testi-info">
                            <h4>Ustadzah Fatimah</h4>
                            <p>Pengajar Privat - Surabaya</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FAQ SECTION ================= -->
    <section id="faq" class="faq-section">
        <div class="container">
            <div class="faq-grid">
                <div class="faq-left">
                    <img src="assets\img\gallery\asking-help-solutions-question-mark-symbol-with-chat-bubble (1).png" alt="FAQ Decor">
                </div>
                <div class="faq-right">
                    <h2>Pertanyaan yang Sering Diajukan</h2>
                    
                    <div class="accordion-group">
                        <div class="accordion-item active">
                            <div class="accordion-header">
                                <h3>Apa itu Metode Qurana?</h3>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="accordion-body">
                                <p>Metode Qurana adalah metode pembelajaran Al-Qur'an terstruktur yang fokus pada peningkatan kualitas tajwid, makharijul huruf, dan pembinaan pengajar secara komprehensif.</p>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <h3>Apakah mendapatkan sertifikat setelah lulus?</h3>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="accordion-body">
                                <p>Ya, setiap peserta yang menyelesaikan program dan lulus ujian kelayakan akan menerima sertifikat resmi kompetensi dari MyQurana.</p>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <h3>Berapa lama durasi pelatihan?</h3>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="accordion-body">
                                <p>Durasi bervariasi bergantung pada jenis program yang diambil, biasanya berlangsung selama 4 hingga 8 pertemuan intensif.</p>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <h3>Apakah bisa diikuti secara online?</h3>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="accordion-body">
                                <p>Kami menyediakan opsi kelas online melalui platform Zoom Meeting interaktif serta bimbingan intensif melalui platform chat khusus.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER BANNER & INFO ================= -->
    <section class="banner-cta-section">
        <div class="container">
            <div class="banner-cta-card">
                <h2>Bergabunglah Bersama Ribuan Pendidik Qur'an Mewujudkan <span class="highlight-orange">Generasi Qur'ani Yang Rabbaniyyin</span></h2>
                <a href="https://daftar.myqurana.org/" class="btn btn-cta orange-large" target="_blank" rel="noopener">Daftar Sekarang <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <footer id="kontak" class="footer-section">
        <div class="container text-center">
            <h2 class="section-title">Kami Siap Membantu Anda</h2>
            
            <div class="footer-contacts-grid">
                <div class="contact-box">
                    <div class="c-icon"><i class="fa-solid fa-map-location"></i></div>
                    <h4>Alamat</h4>
                    <p>Jl. Laksda Adi Sucipto No. 334A, Pandanwangi, Kota Malang</p>
                </div>
                <div class="contact-box">
                    <div class="c-icon"><i class="fa-brands fa-whatsapp"></i></div>
                    <h4>WhatsApp</h4>
                    <p>+62 8953 8221 0808</p>
                </div>
                <div class="contact-box">
                    <div class="c-icon"><i class="fa-regular fa-envelope"></i></div>
                    <h4>Email</h4>
                    <p>foundationqurana92@gmail.com</p>
                </div>
            </div>
            
            <div class="footer-copyright">
                <p>&copy; 2026 MyQurana. All Rights Reserved. Built for Generasi Qur'ani.</p>
            </div>
        </div>
    </footer>

    <!-- Script fungsional untuk FAQ Accordion & Mobile Hamburger Menu -->
    <script>
        // Fungsionalitas FAQ Accordion
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const item = header.parentElement;
                
                // Menutup accordion lain yang sedang terbuka (opsional, agar lebih rapi)
                document.querySelectorAll('.accordion-item').forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
                
                item.classList.toggle('active');
            });
        });

        // Fungsionalitas Hamburger Menu Mobile
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('show');
            // Ganti ikon hamburger ke 'x' saat menu aktif
            const icon = menuToggle.querySelector('i');
            if (navMenu.classList.contains('show')) {
                icon.className = 'fa-solid fa-xmark';
            } else {
                icon.className = 'fa-solid fa-bars';
            }
        });

        // Menutup menu mobile otomatis jika salah satu link diklik
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('show');
                menuToggle.querySelector('i').className = 'fa-solid fa-bars';
            });
        });
    </script>
</body>
</html>