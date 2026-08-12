<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Peminatan;
use App\Models\MataPelajaran;
use App\Models\NilaiRapor;
use App\Models\KategoriKecerdasan;
use App\Models\PertanyaanKecerdasan;
use App\Models\JawabanKecerdasan;
use App\Models\PertanyaanMinat;
use App\Models\JawabanMinat;
use App\Models\RumpunJurusan;
use App\Models\Jurusan;
use App\Models\KriteriaSaw;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Peminatan SMAN 11 Garut
        $f1 = Peminatan::create([
            'kode' => 'F1',
            'nama' => 'Sosial-Ekonomi & Teknologi Digital',
            'deskripsi' => 'Fokus pembelajaran rumpun sosial, ekonomi, bisnis digital, sosiologi, dan sejarah.',
            'daftar_kelas' => 'XI-1, XI-2, XI-3'
        ]);

        $f2 = Peminatan::create([
            'kode' => 'F2',
            'nama' => 'Sains Murni & Hitungan Kuat',
            'deskripsi' => 'Fokus pembelajaran sains murni, kedokteran, hitungan matematis, fisika, dan kimia.',
            'daftar_kelas' => 'XI-4, XI-5, XI-6'
        ]);

        $f3 = Peminatan::create([
            'kode' => 'F3',
            'nama' => 'Ilmu Hayati, Bahasa Inggris & Teknologi',
            'deskripsi' => 'Fokus ilmu hayati, kesehatan, sastra inggris tingkat lanjut, dan teknologi pertanian/kelautan.',
            'daftar_kelas' => 'XI-7, XI-8, XI-9'
        ]);

        $f4 = Peminatan::create([
            'kode' => 'F4',
            'nama' => 'Teknik & Teknologi',
            'deskripsi' => 'Fokus rekayasa teknik pertambangan, mesin, elektro, ilmu komputer, dan sains data.',
            'daftar_kelas' => 'XI-10, XI-11'
        ]);

        $f5 = Peminatan::create([
            'kode' => 'F5',
            'nama' => 'Geografi, Sosial & Teknologi',
            'deskripsi' => 'Fokus geografi, pemetaan wilayah, sistem informasi geografis, sosial dan hukum.',
            'daftar_kelas' => 'XI-12'
        ]);

        // 2. Seed Mata Pelajaran Umum (Wajib K10 - K12)
        $mapelUmum = [
            ['kode' => 'MPU-01', 'nama' => 'Pendidikan Agama & Budi Pekerti'],
            ['kode' => 'MPU-02', 'nama' => 'Pendidikan Pancasila'],
            ['kode' => 'MPU-03', 'nama' => 'Bahasa Indonesia'],
            ['kode' => 'MPU-04', 'nama' => 'Matematika'],
            ['kode' => 'MPU-05', 'nama' => 'Bahasa Inggris'],
            ['kode' => 'MPU-06', 'nama' => 'PJOK'],
            ['kode' => 'MPU-07', 'nama' => 'Sejarah'],
            ['kode' => 'MPU-08', 'nama' => 'Seni'],
            ['kode' => 'MPU-09', 'nama' => 'Bahasa Sunda (Muatan Lokal)'],
        ];

        $mapelModels = [];
        foreach ($mapelUmum as $mu) {
            $mapelModels[$mu['kode']] = MataPelajaran::create([
                'kode' => $mu['kode'],
                'nama' => $mu['nama'],
                'kategori' => 'umum',
                'peminatan_id' => null,
            ]);
        }

        // Mapel Pilihan F1
        $mapelF1 = [
            ['kode' => 'MPF1-01', 'nama' => 'Sosiologi'],
            ['kode' => 'MPF1-02', 'nama' => 'Ekonomi'],
            ['kode' => 'MPF1-03', 'nama' => 'Sejarah Tingkat Lanjut'],
            ['kode' => 'MPF1-04', 'nama' => 'Informatika'],
            ['kode' => 'MPF1-05', 'nama' => 'Prakarya & Kewirausahaan'],
        ];
        foreach ($mapelF1 as $m) {
            $mapelModels[$m['kode']] = MataPelajaran::create([
                'kode' => $m['kode'],
                'nama' => $m['nama'],
                'kategori' => 'pilihan',
                'peminatan_id' => $f1->id,
            ]);
        }

        // Mapel Pilihan F2
        $mapelF2 = [
            ['kode' => 'MPF2-01', 'nama' => 'Biologi'],
            ['kode' => 'MPF2-02', 'nama' => 'Fisika'],
            ['kode' => 'MPF2-03', 'nama' => 'Kimia'],
            ['kode' => 'MPF2-04', 'nama' => 'Matematika Tingkat Lanjut'],
            ['kode' => 'MPF2-05', 'nama' => 'Prakarya & Kewirausahaan (F2)'],
        ];
        foreach ($mapelF2 as $m) {
            $mapelModels[$m['kode']] = MataPelajaran::create([
                'kode' => $m['kode'],
                'nama' => $m['nama'],
                'kategori' => 'pilihan',
                'peminatan_id' => $f2->id,
            ]);
        }

        // Mapel Pilihan F3
        $mapelF3 = [
            ['kode' => 'MPF3-01', 'nama' => 'Biologi (F3)'],
            ['kode' => 'MPF3-02', 'nama' => 'Bahasa Inggris Tingkat Lanjut'],
            ['kode' => 'MPF3-03', 'nama' => 'Kimia (F3)'],
            ['kode' => 'MPF3-04', 'nama' => 'Informatika (F3)'],
            ['kode' => 'MPF3-05', 'nama' => 'Prakarya & Kewirausahaan (F3)'],
        ];
        foreach ($mapelF3 as $m) {
            $mapelModels[$m['kode']] = MataPelajaran::create([
                'kode' => $m['kode'],
                'nama' => $m['nama'],
                'kategori' => 'pilihan',
                'peminatan_id' => $f3->id,
            ]);
        }

        // Mapel Pilihan F4
        $mapelF4 = [
            ['kode' => 'MPF4-01', 'nama' => 'Fisika (F4)'],
            ['kode' => 'MPF4-02', 'nama' => 'Kimia (F4)'],
            ['kode' => 'MPF4-03', 'nama' => 'Matematika Tingkat Lanjut (F4)'],
            ['kode' => 'MPF4-04', 'nama' => 'Informatika (F4)'],
            ['kode' => 'MPF4-05', 'nama' => 'Prakarya & Kewirausahaan (F4)'],
        ];
        foreach ($mapelF4 as $m) {
            $mapelModels[$m['kode']] = MataPelajaran::create([
                'kode' => $m['kode'],
                'nama' => $m['nama'],
                'kategori' => 'pilihan',
                'peminatan_id' => $f4->id,
            ]);
        }

        // Mapel Pilihan F5
        $mapelF5 = [
            ['kode' => 'MPF5-01', 'nama' => 'Sosiologi (F5)'],
            ['kode' => 'MPF5-02', 'nama' => 'Ekonomi (F5)'],
            ['kode' => 'MPF5-03', 'nama' => 'Geografi'],
            ['kode' => 'MPF5-04', 'nama' => 'Informatika (F5)'],
            ['kode' => 'MPF5-05', 'nama' => 'Prakarya & Kewirausahaan (F5)'],
        ];
        foreach ($mapelF5 as $m) {
            $mapelModels[$m['kode']] = MataPelajaran::create([
                'kode' => $m['kode'],
                'nama' => $m['nama'],
                'kategori' => 'pilihan',
                'peminatan_id' => $f5->id,
            ]);
        }

        // 3. Seed Users (Guru BK & Siswa)
        $guruBk = User::create([
            'name' => 'Dra. Hj. Fitriani, M.Pd (Guru BK)',
            'email' => 'admin@sman11garut.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru_bk',
        ]);

        $siswaList = [
            ['name' => 'Budi Santoso', 'email' => 'budi@siswa.sman11garut.sch.id', 'nisn' => '0051234501', 'kelas' => 'XI-1', 'peminatan_id' => $f1->id],
            ['name' => 'Siti Rahma', 'email' => 'siti@siswa.sman11garut.sch.id', 'nisn' => '0051234502', 'kelas' => 'XI-4', 'peminatan_id' => $f2->id],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@siswa.sman11garut.sch.id', 'nisn' => '0051234503', 'kelas' => 'XI-7', 'peminatan_id' => $f3->id],
            ['name' => 'Rizky Pratama', 'email' => 'rizky@siswa.sman11garut.sch.id', 'nisn' => '0051234504', 'kelas' => 'XI-10', 'peminatan_id' => $f4->id],
            ['name' => 'Maya Indah', 'email' => 'maya@siswa.sman11garut.sch.id', 'nisn' => '0051234505', 'kelas' => 'XI-12', 'peminatan_id' => $f5->id],
        ];

        $createdSiswa = [];
        foreach ($siswaList as $s) {
            $createdSiswa[] = User::create([
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'nisn' => $s['nisn'],
                'kelas' => $s['kelas'],
                'peminatan_id' => $s['peminatan_id'],
            ]);
        }

        // Seed Sample Rapor for Budi Santoso (F1) across semesters 1..5
        foreach ($createdSiswa as $siswa) {
            // Get common mapel + specific choice mapel for student's peminatan
            $availableMapel = MataPelajaran::whereNull('peminatan_id')
                ->orWhere('peminatan_id', $siswa->peminatan_id)
                ->get();

            foreach ($availableMapel as $mp) {
                for ($sem = 1; $sem <= 5; $sem++) {
                    NilaiRapor::create([
                        'siswa_id' => $siswa->id,
                        'mata_pelajaran_id' => $mp->id,
                        'semester' => $sem,
                        'nilai' => rand(78, 96),
                    ]);
                }
            }
        }

        // 4. Seed Kategori Kecerdasan Majemuk (Howard Gardner)
        $kategoriKecerdasanData = [
            ['kode' => 'K01', 'nama' => 'Linguistik / Verbal', 'deskripsi' => 'Kemampuan mengolah kata, menulis, membaca, serta mengekspresikan gagasan secara lisan dan tulisan.'],
            ['kode' => 'K02', 'nama' => 'Logis / Matematika', 'deskripsi' => 'Kemampuan bernalar secara analitis, memecahkan masalah angka, pola logika, dan algoritma.'],
            ['kode' => 'K03', 'nama' => 'Visual / Spasial', 'deskripsi' => 'Kemampuan membayangkan bentuk 3 dimensi, mendesain grafis, memetakan ruang, dan arsitektur.'],
            ['kode' => 'K04', 'nama' => 'Kinestetik', 'deskripsi' => 'Kemampuan menggunakan koordinasi tubuh, fisik, olah otot, dan keterampilan tangan.'],
            ['kode' => 'K05', 'nama' => 'Musikal', 'deskripsi' => 'Peka terhadap irama, nada, melodi, serta ekspresi nada suara.'],
            ['kode' => 'K06', 'nama' => 'Interpersonal', 'deskripsi' => 'Kemampuan berinteraksi, berempati, memimpin, berkomunikasi, dan bekerjasama dalam tim.'],
            ['kode' => 'K07', 'nama' => 'Intrapersonal', 'deskripsi' => 'Kesadaran diri tinggi, mandiri, memahami emosi diri, dan memiliki target pribadi yang jelas.'],
            ['kode' => 'K08', 'nama' => 'Naturalis', 'deskripsi' => 'Kepekaan terhadap alam, keanekaragaman hayati, lingkungan hidup, dan sains murni.'],
        ];

        $katModels = [];
        foreach ($kategoriKecerdasanData as $kkd) {
            $katModels[$kkd['kode']] = KategoriKecerdasan::create($kkd);
        }

        // Seed Pertanyaan Kecerdasan Majemuk
        $pertanyaanKecerdasanList = [
            // Linguistik
            ['kat' => 'K01', 'q' => 'Saya sangat menikmati membaca buku, menulis karangan, atau menyusun tata bahasa.'],
            ['kat' => 'K01', 'q' => 'Saya mudah mengingat kata-kata baru dan mampu menyampaikan ide secara runtut dan jelas.'],
            // Logis Matematika
            ['kat' => 'K02', 'q' => 'Saya menyukai perhitungan matematis, analisis logika data, dan teka-teki logika.'],
            ['kat' => 'K02', 'q' => 'Saya selalu mencari sebab-akibat matematis dalam menyelesaikan permasalahan kompleks.'],
            // Visual Spasial
            ['kat' => 'K03', 'q' => 'Saya mudah memahami peta, diagram, sketsa 3D, serta senang dengan desain visual.'],
            ['kat' => 'K03', 'q' => 'Saya sering membayangkan posisi objek ruang dan tata letak secara visual di pikiran saya.'],
            // Kinestetik
            ['kat' => 'K04', 'q' => 'Saya menyukai kegiatan olahraga, perakitan fisik, atau eksperimen langsung di lapangan.'],
            // Musikal
            ['kat' => 'K05', 'q' => 'Saya peka terhadap nada, harmoni musik, dan sering mengingat lagu dengan cepat.'],
            // Interpersonal
            ['kat' => 'K06', 'q' => 'Saya mudah bergaul, pandai bernegosiasi, serta tanggap terhadap perasaan orang lain.'],
            ['kat' => 'K06', 'q' => 'Saya menyukai diskusi kelompok dan memimpin koordinasi suatu organisasi/kegiatan.'],
            // Intrapersonal
            ['kat' => 'K07', 'q' => 'Saya secara teratur merefleksikan kelebihan dan kekurangan diri untuk mencapai target impian.'],
            // Naturalis
            ['kat' => 'K08', 'q' => 'Saya sangat tertarik mengamati fenomena alam, tumbuh-tumbuhan, hewan, dan ekosistem.'],
        ];

        $pertanyaanKecerdasanModels = [];
        foreach ($pertanyaanKecerdasanList as $pk) {
            $pertanyaanKecerdasanModels[] = PertanyaanKecerdasan::create([
                'kategori_kecerdasan_id' => $katModels[$pk['kat']]->id,
                'pertanyaan' => $pk['q'],
            ]);
        }

        // Fill sample Jawaban Kecerdasan for all siswa
        foreach ($createdSiswa as $siswa) {
            foreach ($pertanyaanKecerdasanModels as $pkModel) {
                JawabanKecerdasan::create([
                    'siswa_id' => $siswa->id,
                    'pertanyaan_kecerdasan_id' => $pkModel->id,
                    'skor' => rand(3, 5),
                ]);
            }
        }

        // 5. Seed Pertanyaan Minat Bakat
        $pertanyaanMinatList = [
            ['kategori' => 'Ekonomi & Bisnis', 'q' => 'Berapa besar ketertarikan Anda pada pengelolaan keuangan, strategi pemasaran, dan dunia wirausaha?'],
            ['kategori' => 'Sosial & Humaniora', 'q' => 'Berapa besar ketertarikan Anda pada isu-isu hukum, politik, sosiologi, dan hubungan internasional?'],
            ['kategori' => 'Kesehatan & Ilmu Hayati', 'q' => 'Berapa besar ketertarikan Anda pada bidang pelayanan medis, farmasi, keperawatan, dan kesehatan masyarakat?'],
            ['kategori' => 'Sains & Teknik', 'q' => 'Berapa besar ketertarikan Anda pada perancangan bangunan, mesin, elektro, dan teknologi rekayasa murni?'],
            ['kategori' => 'Komputasi & Sistem', 'q' => 'Berapa besar ketertarikan Anda pada pemograman komputer, pembuatan aplikasi, sains data, dan teknologi informasi?'],
            ['kategori' => 'Bahasa & Sastra', 'q' => 'Berapa besar ketertarikan Anda pada penguasaan bahasa asing, penerjemahan, dan kebudayaan internasional?'],
            ['kategori' => 'Geografi & Pemetaan', 'q' => 'Berapa besar ketertarikan Anda pada studi wilayah geografi, pemetaan digital GIS, dan eksplorasi bumi?'],
        ];

        $pertanyaanMinatModels = [];
        foreach ($pertanyaanMinatList as $pm) {
            $pertanyaanMinatModels[] = PertanyaanMinat::create([
                'kategori_target' => $pm['kategori'],
                'pertanyaan' => $pm['q'],
            ]);
        }

        // Fill sample Jawaban Minat for all siswa
        foreach ($createdSiswa as $siswa) {
            foreach ($pertanyaanMinatModels as $pmModel) {
                JawabanMinat::create([
                    'siswa_id' => $siswa->id,
                    'pertanyaan_minat_id' => $pmModel->id,
                    'skor' => rand(3, 5),
                ]);
            }
        }

        // 6. Seed Rumpun & Jurusan Kuliah SMAN 11 Garut
        // F1 Rumpun & Jurusan
        $rF1_1 = RumpunJurusan::create(['peminatan_id' => $f1->id, 'nama' => 'Rumpun Ekonomi & Bisnis']);
        $jF1_1 = [
            ['nama' => 'Akuntansi', 'deskripsi' => 'Studi analisis laporan keuangan, perpajakan, audit, dan pembukuan bisnis.', 'prospek' => 'Auditor, Accountant, Tax Specialist, Financial Analyst'],
            ['nama' => 'Manajemen', 'deskripsi' => 'Studi pengelolaan sumber daya organisasi, strategi bisnis, pemasaran, dan operasional.', 'prospek' => 'Manager, Business Development, HR Manager, Entrepreneur'],
            ['nama' => 'Ekonomi Pembangunan', 'deskripsi' => 'Studi perumusan kebijakan publik, ekonomi makro/mikro, serta pembangunan daerah.', 'prospek' => 'Analitis Ekonomi, Konsultan Pembangunan, Pegawai Bappeda/Kemenkeu'],
            ['nama' => 'Bisnis Digital', 'deskripsi' => 'Studi inovasi bisnis berbasis e-commerce, startup technology, dan analisa data pasar.', 'prospek' => 'Digital Marketer, Growth Hacker, Startup Founder, E-Commerce Specialist'],
            ['nama' => 'Pariwisata', 'deskripsi' => 'Studi manajemen destinasi pariwisata, hospitality, dan event management.', 'prospek' => 'Tourism Consultant, Destination Manager, Event Planner'],
        ];
        foreach ($jF1_1 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF1_1->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K02']->id, // Logis
                'mapel_utama_id' => $mapelModels['MPF1-02']->id, // Ekonomi
            ]);
        }

        $rF1_2 = RumpunJurusan::create(['peminatan_id' => $f1->id, 'nama' => 'Rumpun Sosial & Humaniora']);
        $jF1_2 = [
            ['nama' => 'Sosiologi', 'deskripsi' => 'Studi dinamika struktur sosial masyarakat, budaya, dan resolusi konflik sosial.', 'prospek' => 'Sosiolog, Peneliti Sosial, NGO Specialist, Konsultan Publik'],
            ['nama' => 'Hubungan Internasional', 'deskripsi' => 'Studi diplomasi antar negara, organisasi global, dan hukum internasional.', 'prospek' => 'Diplomat, Analyst HI, NGO Specialist, Journalist'],
            ['nama' => 'Ilmo Komunikasi', 'deskripsi' => 'Studi penyiaran, jurnalistik, hubungan masyarakat (PR), dan periklanan media.', 'prospek' => 'Public Relations, Journalist, Content Producer, Media Planner'],
            ['nama' => 'Hukum', 'deskripsi' => 'Studi perundang-undangan, hukum perdata/pidana, serta sistem keadilan negara.', 'prospek' => 'Pengacara, Hakim, Jaksa, Legal Counsel Perusahaan'],
            ['nama' => 'Sejarah', 'deskripsi' => 'Studi rekam jejak peristiwa masa lalu, naskah kuno, dan arsip kebudayaan.', 'prospek' => 'Sejarawan, Arsiparis, Peneliti Kebudayaan, Museum Curator'],
            ['nama' => 'Arkeologi', 'deskripsi' => 'Studi penemuan peninggalan purbakala dan artefak sejarah.', 'prospek' => 'Arkeolog, Peneliti Purbakala, Konservator Cagar Budaya'],
            ['nama' => 'Ilmu Politik', 'deskripsi' => 'Studi sistem tata negara, partai politik, pemilu, dan kebijakan pemerintahan.', 'prospek' => 'Analis Politik, Staf Ahli DPR/DPRD, Konsultan Politik'],
        ];
        foreach ($jF1_2 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF1_2->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K01']->id, // Linguistik
                'mapel_utama_id' => $mapelModels['MPF1-01']->id, // Sosiologi
            ]);
        }

        $rF1_3 = RumpunJurusan::create(['peminatan_id' => $f1->id, 'nama' => 'Rumpun Terapan']);
        $jF1_3 = [
            ['nama' => 'Administrasi Bisnis', 'deskripsi' => 'Studi tatakelola surat menyurat bisnis, operasional kantor, dan manajemen berkas.', 'prospek' => 'Business Administrator, Office Manager, Operation Executive'],
            ['nama' => 'Kewirausahaan', 'deskripsi' => 'Studi pembentukan usaha baru, rancangan produk kreatif, dan pitching investor.', 'prospek' => 'Wirausahawan, Business Founder, Product Owner'],
            ['nama' => 'Manajemen Pemasaran', 'deskripsi' => 'Studi teknik menjual, analisis perilaku konsumen, dan branding media.', 'prospek' => 'Marketing Manager, Brand Strategist, Sales Executive'],
        ];
        foreach ($jF1_3 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF1_3->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K06']->id, // Interpersonal
                'mapel_utama_id' => $mapelModels['MPF1-05']->id, // Prakarya
            ]);
        }

        // F2 Rumpun & Jurusan
        $rF2_1 = RumpunJurusan::create(['peminatan_id' => $f2->id, 'nama' => 'Kesehatan & Ilmu Hayati']);
        $jF2_1 = [
            ['nama' => 'Kedokteran', 'deskripsi' => 'Pendidikan ilmu medis diagnosis penyakit, pencegahan, dan pengobatan kesehatan manusia.', 'prospek' => 'Dokter Umum, Dokter Spesialis, Peneliti Medis'],
            ['nama' => 'Kedokteran Gigi', 'deskripsi' => 'Pendidikan kesehatan jaringan mulut, gigi, dan perawatan estetika gigi.', 'prospek' => 'Dokter Gigi, Spesialis Ortodonti, Peneliti Gigi'],
            ['nama' => 'Farmasi', 'deskripsi' => 'Studi formulasi obat-obatan, dosis kimia medis, dan pengawasan mutu obat.', 'prospek' => 'Apoteker, Quality Control Farmasi, Formulator Obat'],
            ['nama' => 'Keperawatan', 'deskripsi' => 'Studi asuhan keperawatan pasien rawat inap/jalan dan pertolongan pertama kesehatan.', 'prospek' => 'Perawat Rumah Sakit, Homecare Specialist, Clinical Educator'],
            ['nama' => 'Gizi', 'deskripsi' => 'Studi analisis nutrisi makanan, dietetika klinis, dan pola makan sehat.', 'prospek' => 'Ahli Gizi, Nutrisionis Rumah Sakit, Dietitian'],
            ['nama' => 'Kesehatan Masyarakat', 'deskripsi' => 'Studi promosi kesehatan lingkungan, epidemiologi wabah, dan manajemen RS.', 'prospek' => 'Epidemiolog, Penyuluh Kesehatan, Administrator RS'],
            ['nama' => 'Biologi', 'deskripsi' => 'Studi keanekaragaman sel hayati, struktur mikroorganisme, dan sains alam murni.', 'prospek' => 'Biolog, Peneliti LIPI/BRIN, Lab Analyst'],
            ['nama' => 'Biokimia', 'deskripsi' => 'Studi proses kimiawi di dalam tubuh makhluk hidup.', 'prospek' => 'Peneliti Biokimia, Biochemist Lab Analyst'],
            ['nama' => 'Bioteknologi', 'deskripsi' => 'Studi pemanfaatan organisme biologis untuk rekayasa genetik dan teknologi pangan.', 'prospek' => 'Bioteknolog, Geneticist, R&D Bio-Industry'],
        ];
        foreach ($jF2_1 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF2_1->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K08']->id, // Naturalis
                'mapel_utama_id' => $mapelModels['MPF2-01']->id, // Biologi
            ]);
        }

        $rF2_2 = RumpunJurusan::create(['peminatan_id' => $f2->id, 'nama' => 'Sains, Teknik & Hitungan']);
        $jF2_2 = [
            ['nama' => 'Kimia', 'deskripsi' => 'Studi reaksi zat unsur kimia, analisis molekul, dan industri pemrosesan bahan.', 'prospek' => 'Chemist, R&D Industri, QC Specialist'],
            ['nama' => 'Fisika', 'deskripsi' => 'Studi hukum alam material, energi, gelombang elektromagnetik, dan fisika medis.', 'prospek' => 'Fisikawan, Fisikawan Medis, Data Analyst'],
            ['nama' => 'Teknik Kimia', 'deskripsi' => 'Studi skala industri pabrik kimia, distilasi, dan pengolahan minyak bumi.', 'prospek' => 'Process Engineer, Chemical Plant Manager'],
            ['nama' => 'Teknik Lingkungan', 'deskripsi' => 'Studi rekayasa pengolahan limbah, instalasi air bersih, dan analisis AMDAL.', 'prospek' => 'Environmental Engineer, AMDAL Consultant'],
            ['nama' => 'Teknik Industri', 'deskripsi' => 'Studi efisiensi sistem produksi pabrik, rantai pasok (supply chain), dan ergonomi.', 'prospek' => 'Industrial Engineer, Supply Chain Analyst, Production Planner'],
            ['nama' => 'Statistika', 'deskripsi' => 'Studi pengumpulan data angka, pemodelan statistik, probabilitas, dan estimasi.', 'prospek' => 'Statistisi, Data Scientist, Risk Analyst'],
            ['nama' => 'Aktuaria', 'deskripsi' => 'Studi kalkulasi risiko keuangan perasuransian dan dana pensiun secara matematis.', 'prospek' => 'Aktuaris, Insurance Risk Analyst'],
            ['nama' => 'Teknik Sipil', 'deskripsi' => 'Studi struktur beton, jembatan, jalan raya, dan konstruksi gedung tinggi.', 'prospek' => 'Structural Engineer, Kontraktor, Project Manager Konstruksi'],
        ];
        foreach ($jF2_2 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF2_2->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K02']->id, // Logis Matematika
                'mapel_utama_id' => $mapelModels['MPF2-04']->id, // Mat Tkt Lanjut
            ]);
        }

        // F3 Rumpun & Jurusan
        $rF3_1 = RumpunJurusan::create(['peminatan_id' => $f3->id, 'nama' => 'Kesehatan']);
        $jF3_1 = [
            ['nama' => 'Kebidanan', 'deskripsi' => 'Pendidikan kesehatan reproduksi wanita, proses kehamilan, dan persalinan.', 'prospek' => 'Bidan Praktik, Bidan Rumah Sakit, Penyuluh Kesehatan Ibu & Anak'],
            ['nama' => 'Fisioterapi', 'deskripsi' => 'Studi pemulihan fungsi gerak fisik cedera otot/tulang melalui terapi fisik.', 'prospek' => 'Fisioterapis Olahraga, Rehab Medis Specialist'],
            ['nama' => 'Kesehatan & Keselamatan Kerja (K3)', 'deskripsi' => 'Studi pencegahan kecelakaan kerja di pabrik/proyek dan higiene industri.', 'prospek' => 'Safety Officer, K3 Auditor, HSE Manager'],
        ];
        foreach ($jF3_1 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF3_1->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K08']->id,
                'mapel_utama_id' => $mapelModels['MPF3-01']->id,
            ]);
        }

        $rF3_2 = RumpunJurusan::create(['peminatan_id' => $f3->id, 'nama' => 'Bahasa']);
        $jF3_2 = [
            ['nama' => 'Sastra Inggris', 'deskripsi' => 'Studi kebudayaan, linguistik, analisis karya sastra, dan penguasaan Bahasa Inggris.', 'prospek' => 'Translator, Interpreter, Copywriter, Content Writer'],
            ['nama' => 'Pendidikan Bahasa Inggris', 'deskripsi' => 'Pendidikan pedoman pengajaran Bahasa Inggris untuk sekolah dan lembaga kursus.', 'prospek' => 'Guru Bahasa Inggris, Dosen, Curriculum Developer'],
        ];
        foreach ($jF3_2 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF3_2->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K01']->id,
                'mapel_utama_id' => $mapelModels['MPF3-02']->id,
            ]);
        }

        $rF3_3 = RumpunJurusan::create(['peminatan_id' => $f3->id, 'nama' => 'Sains dan Teknologi']);
        $jF3_3 = [
            ['nama' => 'Kehutanan', 'deskripsi' => 'Studi konservasi kawasan hutan, manajemen biodiversitas, dan ekologi hutan.', 'prospek' => 'Polisi Hutan, Konservasionis, R&D Kehutanan'],
            ['nama' => 'Teknologi Industri Pertanian', 'deskripsi' => 'Studi pengolahan hasil komoditas pertanian menjadi barang industri bernilai tinggi.', 'prospek' => 'Agro-Industry Manager, Quality Control Pangan'],
            ['nama' => 'Ilmu Kelautan', 'deskripsi' => 'Studi ekosistem pesisir, perairan samudera, dan keanekaragaman laut.', 'prospek' => 'Oceanographer, Marine Scientist, Peneliti Kelautan'],
            ['nama' => 'Peternakan', 'deskripsi' => 'Studi pemuliaan ternak, pakan ternak, dan teknologi hasil peternakan.', 'prospek' => 'Peternak Modern, Farm Manager, Feed Specialist'],
            ['nama' => 'Perikanan', 'deskripsi' => 'Studi budidaya ikan (akuakultur), manajemen perikanan tangkap, dan pasca panen.', 'prospek' => 'Aquaculture Specialist, Fisheries Officer'],
        ];
        foreach ($jF3_3 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF3_3->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K08']->id,
                'mapel_utama_id' => $mapelModels['MPF3-01']->id,
            ]);
        }

        // F4 Rumpun & Jurusan
        $rF4_1 = RumpunJurusan::create(['peminatan_id' => $f4->id, 'nama' => 'Teknik & Rekayasa']);
        $jF4_1 = [
            ['nama' => 'Teknik Pertambangan', 'deskripsi' => 'Studi eksplorasi mineral bumi, penggalian tambang, dan geoteknik tambang.', 'prospek' => 'Mining Engineer, Mine Planner, Geotechnical Specialist'],
            ['nama' => 'Teknik Mesin', 'deskripsi' => 'Studi konversi energi, mekanika bahan, perancangan mesin, dan otomotif.', 'prospek' => 'Mechanical Engineer, Automotive Specialist, Maintenance Engineer'],
            ['nama' => 'Teknik Elektro', 'deskripsi' => 'Studi arus kuat/kelektrikan, energi terbarukan, dan telekomunikasi.', 'prospek' => 'Electrical Engineer, Power System Analyst, Telecom Engineer'],
            ['nama' => 'Arsitektur', 'deskripsi' => 'Studi seni perancangan denah estetika ruangan, bangunan, dan tata kota.', 'prospek' => 'Arsitek, Interior Designer, Urban Planner'],
            ['nama' => 'Teknologi Pangan', 'deskripsi' => 'Studi pengawetan makanan, keamanan pangan, dan pengolahan bahan pangan.', 'prospek' => 'Food Technologist, Quality Assurance, R&D Pangan'],
        ];
        foreach ($jF4_1 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF4_1->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K03']->id, // Visual Spasial
                'mapel_utama_id' => $mapelModels['MPF4-01']->id, // Fisika
            ]);
        }

        $rF4_2 = RumpunJurusan::create(['peminatan_id' => $f4->id, 'nama' => 'Komputasi & Sistem']);
        $jF4_2 = [
            ['nama' => 'Teknik Informatika', 'deskripsi' => 'Studi rekayasa perangkat lunak, algoritma pemrograman, dan kecerdasan buatan (AI).', 'prospek' => 'Software Engineer, Full Stack Developer, AI Specialist'],
            ['nama' => 'Ilmu Komputer', 'deskripsi' => 'Studi teori komputasi murni, analisis algoritma komplek, dan cyber security.', 'prospek' => 'Computer Scientist, Security Analyst, Backend Developer'],
            ['nama' => 'Sistem Informasi', 'deskripsi' => 'Studi integrasi teknologi informasi dengan proses bisnis organisasi.', 'prospek' => 'System Analyst, IT Project Manager, ERP Specialist'],
            ['nama' => 'Teknik Komputer', 'deskripsi' => 'Studi arsitektur komputer, integrasi hardware mikroprosesor, dan IoT.', 'prospek' => 'Embedded Systems Engineer, Hardware Engineer, IoT Developer'],
            ['nama' => 'Rekayasa Perangkat Lunak', 'deskripsi' => 'Studi metodologi pembuatan software berkualitas skala besar.', 'prospek' => 'Software Architect, QA Automation Engineer, DevOps Engineer'],
            ['nama' => 'Sains Data', 'deskripsi' => 'Studi ekstraksi pengetahuan dari big data menggunakan statistik dan machine learning.', 'prospek' => 'Data Scientist, Machine Learning Engineer, Big Data Analyst'],
        ];
        foreach ($jF4_2 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF4_2->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K02']->id, // Logis
                'mapel_utama_id' => $mapelModels['MPF4-04']->id, // Informatika
            ]);
        }

        // F5 Rumpun & Jurusan
        $rF5_1 = RumpunJurusan::create(['peminatan_id' => $f5->id, 'nama' => 'Geografi & Pemetaan']);
        $jF5_1 = [
            ['nama' => 'Pendidikan Geografi', 'deskripsi' => 'Pendidikan pengajaran bidang fenomena geosfer dan kebumian.', 'prospek' => 'Guru Geografi, Edukator Kebumian'],
            ['nama' => 'Sistem Informasi Geografis', 'deskripsi' => 'Studi pemrosesan data spasial digital untuk pemetaan wilayah darat dan laut.', 'prospek' => 'GIS Specialist, Spatial Data Analyst, Cartographer'],
            ['nama' => 'Pemetaan Wilayah Geografis', 'deskripsi' => 'Studi pengukuran kontur lahan, survei pemetaan topografi, dan tata ruang.', 'prospek' => 'Surveyor Lahan, Konsultan Tata Ruang, Petugas BPN'],
        ];
        foreach ($jF5_1 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF5_1->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K03']->id,
                'mapel_utama_id' => $mapelModels['MPF5-03']->id, // Geografi
            ]);
        }

        $rF5_2 = RumpunJurusan::create(['peminatan_id' => $f5->id, 'nama' => 'Sosial & Humaniora (F5)']);
        $jF5_2 = [
            ['nama' => 'Psikologi', 'deskripsi' => 'Studi perilaku mental manusia, konseling psikologis, dan asesmen kepribadian.', 'prospek' => 'Psikolog Klinis/HRD, Konselor, Asesor Psikologi'],
            ['nama' => 'Kesejahteraan Sosial', 'deskripsi' => 'Studi penanganan komunitatif bagi kelompok rentan dan pelayanan sosial.', 'prospek' => 'Pekerja Sosial, Community Development Officer'],
            ['nama' => 'Ilmu Pemerintahan', 'deskripsi' => 'Studi birokrasi pemerintahan daerah, pelayanan umum, dan kebijakan publik.', 'prospek' => 'Aparatur Sipil Negara (ASN), Analis Pemerintahan'],
        ];
        foreach ($jF5_2 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF5_2->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K06']->id,
                'mapel_utama_id' => $mapelModels['MPF5-01']->id,
            ]);
        }

        $rF5_3 = RumpunJurusan::create(['peminatan_id' => $f5->id, 'nama' => 'Ekonomi & Terapan (F5)']);
        $jF5_3 = [
            ['nama' => 'Keuangan Perbankan', 'deskripsi' => 'Studi manajemen operasional bank, kredit perbankan, dan analisis risiko pinjaman.', 'prospek' => 'Banker, Credit Analyst, Customer Service Bank'],
        ];
        foreach ($jF5_3 as $j) {
            Jurusan::create([
                'rumpun_id' => $rF5_3->id,
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi'],
                'prospek_kerja' => $j['prospek'],
                'kategori_kecerdasan_utama_id' => $katModels['K02']->id,
                'mapel_utama_id' => $mapelModels['MPF5-02']->id,
            ]);
        }

        // 7. Seed Default Kriteria SAW
        KriteriaSaw::create([
            'kode' => 'C1',
            'nama' => 'Nilai Rapor Semester 1–5',
            'bobot' => 0.40, // 40%
            'jenis' => 'benefit',
            'deskripsi' => 'Rata-rata nilai akumulasi rapor semester 1 sampai 5 (Mata pelajaran umum & pilihan yang relevan).'
        ]);

        KriteriaSaw::create([
            'kode' => 'C2',
            'nama' => 'Tes Kecerdasan Majemuk',
            'bobot' => 0.30, // 30%
            'jenis' => 'benefit',
            'deskripsi' => 'Hasil skor kecerdasan majemuk (Multiple Intelligences) yang sesuai dengan tipe jurusan.'
        ]);

        KriteriaSaw::create([
            'kode' => 'C3',
            'nama' => 'Tes Minat Bakat',
            'bobot' => 0.30, // 30%
            'jenis' => 'benefit',
            'deskripsi' => 'Hasil persentase minat bakat siswa terhadap bidang rumpun jurusan.'
        ]);
    }
}
