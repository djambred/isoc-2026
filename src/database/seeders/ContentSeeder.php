<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Partner;
use App\Models\Section;
use App\Models\SectionItem;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHomeSections();
        $this->seedAboutSections();
        $this->seedProgramsSections();
        $this->seedEventsSections();
        $this->seedOurPartnerSections();
        $this->seedTeamMembers();
        $this->seedPartners();
        $this->seedEvents();
        $this->seedSiteSettings();
    }

    private function seedHomeSections(): void
    {
        // Hero
        $hero = Section::create([
            'page' => 'home',
            'key' => 'hero',
            'title' => ['id' => 'We believe the Internet changes lives.', 'en' => 'We believe the Internet changes lives.'],
            'subtitle' => ['id' => 'Internet Society Indonesia Jakarta Chapter', 'en' => 'Internet Society Indonesia Jakarta Chapter'],
            'description' => ['id' => 'But only when people can access, trust, and use it safely. Kami mendukung pengembangan internet yang berkelanjutan, inklusif, dan aman untuk setiap warga Indonesia.', 'en' => 'But only when people can access, trust, and use it safely. We support the development of a sustainable, inclusive, and safe internet for every Indonesian citizen.'],
            'button_text' => ['id' => 'Pelajari Lebih Lanjut', 'en' => 'Learn More'],
            'button_url' => '/about',
            'secondary_button_text' => ['id' => 'Program Kami', 'en' => 'Our Programs'],
            'secondary_button_url' => '/programs',
            'order' => 1,
            'is_active' => true,
        ]);

        // Mission
        $mission = Section::create([
            'page' => 'home',
            'key' => 'mission',
            'title' => ['id' => 'Misi Internet Society', 'en' => 'Internet Society Mission'],
            'subtitle' => ['id' => 'Misi Kami', 'en' => 'Our Mission'],
            'description' => ['id' => 'Internet Society mendukung dan mempromosikan pengembangan Internet sebagai infrastruktur teknis global, sumber daya untuk memperkaya kehidupan masyarakat, dan kekuatan untuk kebaikan dalam masyarakat.', 'en' => 'The Internet Society supports and promotes the development of the Internet as a global technical infrastructure, a resource for enriching people\'s lives, and a force for good in society.'],
            'order' => 2,
            'is_active' => true,
        ]);

        $missionItems = [
            ['icon' => 'public', 'icon_color' => 'blue', 'title' => ['id' => 'Akses Universal', 'en' => 'Universal Access'], 'description' => ['id' => 'Memperluas akses internet untuk semua orang, terutama di komunitas yang kurang terlayani di Indonesia.', 'en' => 'Expanding internet access for everyone, especially underserved communities in Indonesia.']],
            ['icon' => 'shield', 'icon_color' => 'teal', 'title' => ['id' => 'Keamanan & Privasi', 'en' => 'Security & Privacy'], 'description' => ['id' => 'Mempromosikan praktik keamanan siber dan melindungi hak-hak digital pengguna internet Indonesia.', 'en' => 'Promoting cybersecurity practices and protecting the digital rights of Indonesian internet users.']],
            ['icon' => 'diversity_3', 'icon_color' => 'navy', 'title' => ['id' => 'Pengembangan Komunitas', 'en' => 'Community Development'], 'description' => ['id' => 'Membangun komunitas yang kuat dan terhubung untuk mendorong inovasi dan kolaborasi digital.', 'en' => 'Building strong, connected communities to drive digital innovation and collaboration.']],
        ];

        foreach ($missionItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $mission->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Featured Programs
        $fp = Section::create([
            'page' => 'home',
            'key' => 'featured_programs',
            'title' => ['id' => 'Program Unggulan', 'en' => 'Featured Programs'],
            'subtitle' => ['id' => 'Program Kami', 'en' => 'Our Programs'],
            'button_text' => ['id' => 'Lihat Semua Program', 'en' => 'View All Programs'],
            'order' => 3,
            'is_active' => true,
        ]);

        $fpItems = [
            ['title' => ['id' => 'Internet Governance Forum (IGF)', 'en' => 'Internet Governance Forum (IGF)'], 'description' => ['id' => 'Forum tata kelola internet global yang mempertemukan pemangku kepentingan untuk berdiskusi tentang kebijakan internet.', 'en' => 'A global internet governance forum that brings together stakeholders to discuss internet policy.'], 'icon_color' => 'blue', 'extra_data' => ['category' => 'Global Initiative']],
            ['title' => ['id' => 'Literasi Digital Indonesia', 'en' => 'Indonesian Digital Literacy'], 'description' => ['id' => 'Program peningkatan literasi digital untuk masyarakat Indonesia, fokus pada keamanan siber dan penggunaan internet yang bertanggung jawab.', 'en' => 'A program to improve digital literacy for Indonesian society, focusing on cybersecurity and responsible internet use.'], 'icon_color' => 'teal', 'extra_data' => ['category' => 'Local Program']],
        ];

        foreach ($fpItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $fp->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Impact Stats
        $stats = Section::create([
            'page' => 'home',
            'key' => 'impact_stats',
            'title' => ['id' => 'Dampak Kami', 'en' => 'Our Impact'],
            'subtitle' => ['id' => 'Statistik', 'en' => 'Statistics'],
            'order' => 4,
            'is_active' => true,
        ]);

        $statItems = [
            ['title' => ['id' => '500+', 'en' => '500+'], 'description' => ['id' => 'Anggota Aktif', 'en' => 'Active Members'], 'extra_data' => ['value' => '500+']],
            ['title' => ['id' => '50+', 'en' => '50+'], 'description' => ['id' => 'Program Terlaksana', 'en' => 'Programs Completed'], 'extra_data' => ['value' => '50+']],
            ['title' => ['id' => '10K+', 'en' => '10K+'], 'description' => ['id' => 'Peserta Pelatihan', 'en' => 'Training Participants'], 'extra_data' => ['value' => '10K+']],
            ['title' => ['id' => '20+', 'en' => '20+'], 'description' => ['id' => 'Mitra Strategis', 'en' => 'Strategic Partners'], 'extra_data' => ['value' => '20+']],
        ];

        foreach ($statItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $stats->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Closing
        Section::create([
            'page' => 'home',
            'key' => 'closing',
            'title' => ['id' => 'Internet is for Everyone', 'en' => 'Internet is for Everyone'],
            'subtitle' => ['id' => 'Mari bergabung bersama kami untuk membangun internet yang lebih baik untuk Indonesia.', 'en' => 'Join us in building a better internet for Indonesia.'],
            'description' => ['id' => 'Bersama-sama, kita dapat memastikan bahwa internet tetap menjadi sumber daya global yang terbuka, terhubung secara global, aman, dan dapat dipercaya.', 'en' => 'Together, we can ensure that the internet remains an open, globally connected, secure, and trustworthy global resource.'],
            'order' => 5,
            'is_active' => true,
        ]);
    }

    private function seedAboutSections(): void
    {
        // Hero
        Section::create([
            'page' => 'about',
            'key' => 'hero',
            'title' => ['id' => 'Tentang ISOC Indonesia Jakarta Chapter', 'en' => 'About ISOC Indonesia Jakarta Chapter'],
            'subtitle' => ['id' => 'Tentang Kami', 'en' => 'About Us'],
            'description' => ['id' => 'Kami adalah bagian dari Internet Society, organisasi global yang mendukung pengembangan internet yang terbuka, aman, dan dapat diakses oleh semua orang.', 'en' => 'We are part of the Internet Society, a global organization supporting the development of an open, secure, and accessible internet for everyone.'],
            'order' => 1,
            'is_active' => true,
        ]);

        // History
        Section::create([
            'page' => 'about',
            'key' => 'history',
            'title' => ['id' => 'Sejarah & Perjalanan Kami', 'en' => 'Our History & Journey'],
            'subtitle' => ['id' => 'Sejarah', 'en' => 'History'],
            'description' => ['id' => 'Internet Society Indonesia Jakarta Chapter didirikan sebagai bagian dari jaringan global Internet Society. Sejak awal, kami telah berkomitmen untuk mempromosikan pengembangan internet yang berkelanjutan dan inklusif di Indonesia.

Melalui berbagai program, pelatihan, dan kolaborasi, kami telah membantu ribuan orang memahami pentingnya internet yang aman dan terbuka.', 'en' => 'Internet Society Indonesia Jakarta Chapter was established as part of the global Internet Society network. Since the beginning, we have been committed to promoting sustainable and inclusive internet development in Indonesia.

Through various programs, training, and collaborations, we have helped thousands of people understand the importance of a safe and open internet.'],
            'order' => 2,
            'is_active' => true,
        ]);

        // Community
        $community = Section::create([
            'page' => 'about',
            'key' => 'community',
            'title' => ['id' => 'Komunitas Kami', 'en' => 'Our Community'],
            'subtitle' => ['id' => 'Komunitas', 'en' => 'Community'],
            'description' => ['id' => 'Kami memiliki komunitas yang beragam dan aktif, terdiri dari profesional IT, akademisi, pembuat kebijakan, dan masyarakat umum yang peduli terhadap perkembangan internet di Indonesia.', 'en' => 'We have a diverse and active community of IT professionals, academics, policymakers, and the general public who care about internet development in Indonesia.'],
            'order' => 3,
            'is_active' => true,
        ]);

        $communityItems = [
            ['icon' => 'engineering', 'icon_color' => 'blue', 'title' => ['id' => 'Profesional IT', 'en' => 'IT Professionals'], 'description' => ['id' => 'Jaringan profesional teknologi informasi yang berkontribusi pada pengembangan infrastruktur internet.', 'en' => 'A network of IT professionals contributing to internet infrastructure development.']],
            ['icon' => 'school', 'icon_color' => 'teal', 'title' => ['id' => 'Akademisi', 'en' => 'Academics'], 'description' => ['id' => 'Kolaborasi dengan institusi pendidikan untuk penelitian dan pengembangan internet.', 'en' => 'Collaboration with educational institutions for internet research and development.']],
            ['icon' => 'gavel', 'icon_color' => 'navy', 'title' => ['id' => 'Pembuat Kebijakan', 'en' => 'Policy Makers'], 'description' => ['id' => 'Dialog dengan pemangku kepentingan kebijakan untuk regulasi internet yang adil.', 'en' => 'Dialogue with policy stakeholders for fair internet regulation.']],
            ['icon' => 'groups', 'icon_color' => 'blue', 'title' => ['id' => 'Masyarakat Umum', 'en' => 'General Public'], 'description' => ['id' => 'Program edukasi untuk meningkatkan literasi digital masyarakat Indonesia.', 'en' => 'Educational programs to improve digital literacy for Indonesian society.']],
            ['icon' => 'business', 'icon_color' => 'teal', 'title' => ['id' => 'Sektor Bisnis', 'en' => 'Business Sector'], 'description' => ['id' => 'Kerjasama dengan sektor swasta untuk inovasi dan pengembangan teknologi.', 'en' => 'Collaboration with the private sector for innovation and technology development.']],
            ['icon' => 'volunteer_activism', 'icon_color' => 'navy', 'title' => ['id' => 'Relawan', 'en' => 'Volunteers'], 'description' => ['id' => 'Jaringan relawan yang berdedikasi untuk mendukung misi internet untuk semua.', 'en' => 'A network of dedicated volunteers supporting the internet for all mission.']],
        ];

        foreach ($communityItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $community->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Ecosystem
        Section::create([
            'page' => 'about',
            'key' => 'ecosystem',
            'title' => ['id' => 'Ekosistem Kami', 'en' => 'Our Ecosystem'],
            'subtitle' => ['id' => 'Ekosistem', 'en' => 'Ecosystem'],
            'description' => ['id' => 'Sebagai bagian dari jaringan global Internet Society, kami beroperasi dalam ekosistem yang luas yang menghubungkan chapter-chapter di seluruh dunia. Kami bekerja sama dengan berbagai organisasi internasional, pemerintah, dan komunitas lokal untuk memastikan internet tetap menjadi sumber daya yang terbuka dan dapat diakses.', 'en' => 'As part of the global Internet Society network, we operate in a vast ecosystem that connects chapters worldwide. We collaborate with various international organizations, governments, and local communities to ensure the internet remains an open and accessible resource.'],
            'order' => 4,
            'is_active' => true,
        ]);

        // Focus Areas
        $focus = Section::create([
            'page' => 'about',
            'key' => 'focus_areas',
            'title' => ['id' => 'Fokus Area', 'en' => 'Focus Areas'],
            'subtitle' => ['id' => 'Area Fokus', 'en' => 'Focus Areas'],
            'description' => ['id' => 'Kami memfokuskan upaya kami pada area-area kunci yang berdampak langsung pada perkembangan internet di Indonesia.', 'en' => 'We focus our efforts on key areas that directly impact internet development in Indonesia.'],
            'order' => 5,
            'is_active' => true,
        ]);

        $focusItems = [
            ['icon' => 'lock', 'icon_color' => 'teal', 'title' => ['id' => 'Keamanan Internet', 'en' => 'Internet Security'], 'description' => ['id' => 'Mempromosikan praktik keamanan siber untuk melindungi pengguna internet Indonesia.', 'en' => 'Promoting cybersecurity practices to protect Indonesian internet users.']],
            ['icon' => 'wifi', 'icon_color' => 'blue', 'title' => ['id' => 'Akses Internet', 'en' => 'Internet Access'], 'description' => ['id' => 'Memperluas jangkauan internet ke daerah-daerah terpencil di Indonesia.', 'en' => 'Expanding internet reach to remote areas in Indonesia.']],
            ['icon' => 'policy', 'icon_color' => 'navy', 'title' => ['id' => 'Tata Kelola Internet', 'en' => 'Internet Governance'], 'description' => ['id' => 'Berpartisipasi dalam dialog kebijakan internet nasional dan internasional.', 'en' => 'Participating in national and international internet policy dialogue.']],
            ['icon' => 'school', 'icon_color' => 'teal', 'title' => ['id' => 'Literasi Digital', 'en' => 'Digital Literacy'], 'description' => ['id' => 'Program edukasi untuk meningkatkan pemahaman masyarakat tentang internet.', 'en' => 'Educational programs to improve public understanding of the internet.']],
            ['icon' => 'hub', 'icon_color' => 'blue', 'title' => ['id' => 'Infrastruktur', 'en' => 'Infrastructure'], 'description' => ['id' => 'Mendukung pengembangan infrastruktur internet yang tangguh dan andal.', 'en' => 'Supporting the development of resilient and reliable internet infrastructure.']],
            ['icon' => 'diversity_2', 'icon_color' => 'navy', 'title' => ['id' => 'Inklusi Digital', 'en' => 'Digital Inclusion'], 'description' => ['id' => 'Memastikan semua orang memiliki kesempatan yang sama untuk mengakses dan menggunakan internet.', 'en' => 'Ensuring everyone has equal opportunity to access and use the internet.']],
            ['icon' => 'eco', 'icon_color' => 'teal', 'title' => ['id' => 'Internet Berkelanjutan', 'en' => 'Sustainable Internet'], 'description' => ['id' => 'Mempromosikan penggunaan internet yang ramah lingkungan dan berkelanjutan.', 'en' => 'Promoting environmentally friendly and sustainable internet use.']],
        ];

        foreach ($focusItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $focus->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Vision & Mission
        $vm = Section::create([
            'page' => 'about',
            'key' => 'vision_mission',
            'title' => ['id' => 'Visi & Misi', 'en' => 'Vision & Mission'],
            'subtitle' => ['id' => 'Tujuan Kami', 'en' => 'Our Purpose'],
            'description' => ['id' => 'Komitmen kami untuk membangun internet yang lebih baik untuk Indonesia.', 'en' => 'Our commitment to building a better internet for Indonesia.'],
            'order' => 6,
            'is_active' => true,
        ]);

        $vmItems = [
            ['icon' => 'visibility', 'title' => ['id' => 'Visi', 'en' => 'Vision'], 'description' => ['id' => 'Mewujudkan internet yang terbuka, terhubung secara global, aman, dan dapat dipercaya untuk setiap orang di Indonesia.', 'en' => 'Realizing an open, globally connected, secure, and trustworthy internet for everyone in Indonesia.']],
            ['icon' => 'rocket_launch', 'title' => ['id' => 'Misi', 'en' => 'Mission'], 'description' => ['id' => 'Mendukung dan mempromosikan pengembangan internet sebagai infrastruktur teknis global melalui edukasi, kolaborasi, dan advokasi kebijakan yang inklusif.', 'en' => 'Supporting and promoting the development of the internet as a global technical infrastructure through education, collaboration, and inclusive policy advocacy.']],
        ];

        foreach ($vmItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $vm->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Resources
        $resources = Section::create([
            'page' => 'about',
            'key' => 'resources',
            'title' => ['id' => 'Sumber Daya Kami', 'en' => 'Our Resources'],
            'subtitle' => ['id' => 'Sumber Daya', 'en' => 'Resources'],
            'description' => ['id' => 'Akses berbagai sumber daya yang kami sediakan untuk mendukung pengembangan internet di Indonesia.', 'en' => 'Access various resources we provide to support internet development in Indonesia.'],
            'order' => 7,
            'is_active' => true,
        ]);

        $resourceItems = [
            ['icon' => 'article', 'icon_color' => 'blue', 'title' => ['id' => 'Publikasi & Riset', 'en' => 'Publications & Research'], 'description' => ['id' => 'Laporan, white paper, dan hasil penelitian tentang perkembangan internet di Indonesia.', 'en' => 'Reports, white papers, and research results on internet development in Indonesia.']],
            ['icon' => 'play_circle', 'icon_color' => 'teal', 'title' => ['id' => 'Video & Webinar', 'en' => 'Videos & Webinars'], 'description' => ['id' => 'Rekaman presentasi, diskusi panel, dan webinar tentang isu-isu internet.', 'en' => 'Recordings of presentations, panel discussions, and webinars on internet issues.']],
            ['icon' => 'menu_book', 'icon_color' => 'navy', 'title' => ['id' => 'Materi Pelatihan', 'en' => 'Training Materials'], 'description' => ['id' => 'Modul dan materi pelatihan untuk peningkatan kapasitas digital.', 'en' => 'Modules and training materials for digital capacity building.']],
            ['icon' => 'analytics', 'icon_color' => 'blue', 'title' => ['id' => 'Data & Statistik', 'en' => 'Data & Statistics'], 'description' => ['id' => 'Data dan statistik terkini tentang penetrasi dan penggunaan internet di Indonesia.', 'en' => 'Current data and statistics on internet penetration and usage in Indonesia.']],
            ['icon' => 'forum', 'icon_color' => 'teal', 'title' => ['id' => 'Forum Diskusi', 'en' => 'Discussion Forum'], 'description' => ['id' => 'Platform diskusi online untuk berbagi pengetahuan dan pengalaman.', 'en' => 'Online discussion platform for sharing knowledge and experience.']],
            ['icon' => 'download', 'icon_color' => 'navy', 'title' => ['id' => 'Toolkit & Panduan', 'en' => 'Toolkits & Guides'], 'description' => ['id' => 'Perangkat dan panduan praktis untuk implementasi proyek internet.', 'en' => 'Practical tools and guides for internet project implementation.']],
        ];

        foreach ($resourceItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $resources->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Team
        Section::create([
            'page' => 'about',
            'key' => 'team',
            'title' => ['id' => 'Tim Pengurus', 'en' => 'Board of Directors'],
            'subtitle' => ['id' => 'Tim Kami', 'en' => 'Our Team'],
            'description' => ['id' => 'Kenali orang-orang di balik ISOC Indonesia Jakarta Chapter.', 'en' => 'Meet the people behind ISOC Indonesia Jakarta Chapter.'],
            'order' => 8,
            'is_active' => true,
        ]);
    }

    private function seedProgramsSections(): void
    {
        // Hero
        $hero = Section::create([
            'page' => 'programs',
            'key' => 'hero',
            'title' => ['id' => 'ISOC 2030 Strategy', 'en' => 'ISOC 2030 Strategy'],
            'subtitle' => ['id' => 'Program & Inisiatif', 'en' => 'Programs & Initiatives'],
            'description' => ['id' => 'Strategi kami menuju internet yang lebih baik di tahun 2030, dengan fokus pada akses, keamanan, dan tata kelola internet yang inklusif.', 'en' => 'Our strategy towards a better internet by 2030, focusing on access, security, and inclusive internet governance.'],
            'order' => 1,
            'is_active' => true,
        ]);

        $heroItems = [
            ['icon' => 'language', 'title' => ['id' => 'Global Impact', 'en' => 'Global Impact'], 'description' => ['id' => 'Berkontribusi pada inisiatif global Internet Society untuk memastikan internet tetap terbuka dan aman.', 'en' => 'Contributing to global Internet Society initiatives to ensure the internet remains open and secure.']],
            ['icon' => 'location_on', 'title' => ['id' => 'Local Action', 'en' => 'Local Action'], 'description' => ['id' => 'Mengimplementasikan program-program yang disesuaikan dengan kebutuhan komunitas internet Indonesia.', 'en' => 'Implementing programs tailored to the needs of the Indonesian internet community.']],
        ];

        foreach ($heroItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $hero->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Global Programs
        $global = Section::create([
            'page' => 'programs',
            'key' => 'global_programs',
            'title' => ['id' => 'Program Global', 'en' => 'Global Programs'],
            'subtitle' => ['id' => 'Inisiatif Global', 'en' => 'Global Initiatives'],
            'description' => ['id' => 'Program-program yang merupakan bagian dari inisiatif global Internet Society.', 'en' => 'Programs that are part of global Internet Society initiatives.'],
            'order' => 2,
            'is_active' => true,
        ]);

        $globalItems = [
            ['icon' => 'forum', 'icon_color' => 'blue', 'title' => ['id' => 'Internet Governance Forum', 'en' => 'Internet Governance Forum'], 'description' => ['id' => 'Forum tata kelola internet untuk dialog multi-stakeholder tentang kebijakan internet.', 'en' => 'Internet governance forum for multi-stakeholder dialogue on internet policy.']],
            ['icon' => 'router', 'icon_color' => 'teal', 'title' => ['id' => 'MANRS (Mutually Agreed Norms for Routing Security)', 'en' => 'MANRS (Mutually Agreed Norms for Routing Security)'], 'description' => ['id' => 'Inisiatif untuk meningkatkan keamanan routing internet global.', 'en' => 'Initiative to improve global internet routing security.']],
            ['icon' => 'encrypted', 'icon_color' => 'navy', 'title' => ['id' => 'Encryption & Privacy', 'en' => 'Encryption & Privacy'], 'description' => ['id' => 'Kampanye global untuk melindungi enkripsi dan privasi pengguna internet.', 'en' => 'Global campaign to protect encryption and internet user privacy.']],
            ['icon' => 'hub', 'icon_color' => 'blue', 'title' => ['id' => 'Internet Exchange Points (IXP)', 'en' => 'Internet Exchange Points (IXP)'], 'description' => ['id' => 'Mendukung pengembangan IXP untuk meningkatkan konektivitas internet lokal.', 'en' => 'Supporting IXP development to improve local internet connectivity.']],
            ['icon' => 'diversity_3', 'icon_color' => 'teal', 'title' => ['id' => 'Community Networks', 'en' => 'Community Networks'], 'description' => ['id' => 'Membantu komunitas membangun jaringan internet mereka sendiri.', 'en' => 'Helping communities build their own internet networks.']],
            ['icon' => 'school', 'icon_color' => 'navy', 'title' => ['id' => 'Capacity Building', 'en' => 'Capacity Building'], 'description' => ['id' => 'Program peningkatan kapasitas untuk profesional internet di Indonesia.', 'en' => 'Capacity building programs for internet professionals in Indonesia.']],
        ];

        foreach ($globalItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $global->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Featured Program
        Section::create([
            'page' => 'programs',
            'key' => 'featured_program',
            'title' => ['id' => 'Literasi Digital Indonesia', 'en' => 'Indonesian Digital Literacy'],
            'subtitle' => ['id' => 'Program Unggulan', 'en' => 'Featured Program'],
            'description' => ['id' => 'Program komprehensif untuk meningkatkan literasi digital masyarakat Indonesia. Mencakup pelatihan keamanan siber dasar, privasi online, dan penggunaan internet yang bertanggung jawab.

Program ini telah menjangkau lebih dari 5.000 peserta di seluruh Indonesia melalui workshop tatap muka dan pelatihan online.', 'en' => 'A comprehensive program to improve digital literacy for Indonesian society. Covering basic cybersecurity training, online privacy, and responsible internet use.

This program has reached over 5,000 participants across Indonesia through in-person workshops and online training.'],
            'button_text' => ['id' => 'Pelajari Lebih Lanjut', 'en' => 'Learn More'],
            'order' => 3,
            'is_active' => true,
        ]);

        // Local Programs
        $local = Section::create([
            'page' => 'programs',
            'key' => 'local_programs',
            'title' => ['id' => 'Program Lokal', 'en' => 'Local Programs'],
            'subtitle' => ['id' => 'Inisiatif Lokal', 'en' => 'Local Initiatives'],
            'description' => ['id' => 'Program-program yang dirancang khusus untuk kebutuhan komunitas internet Indonesia.', 'en' => 'Programs specifically designed for the needs of the Indonesian internet community.'],
            'order' => 4,
            'is_active' => true,
        ]);

        $localItems = [
            ['icon' => 'security', 'icon_color' => 'teal', 'title' => ['id' => 'Keamanan Siber untuk UMKM', 'en' => 'Cybersecurity for SMEs'], 'description' => ['id' => 'Pelatihan keamanan siber khusus untuk pelaku usaha mikro, kecil, dan menengah.', 'en' => 'Cybersecurity training specifically for micro, small, and medium enterprises.']],
            ['icon' => 'child_care', 'icon_color' => 'blue', 'title' => ['id' => 'Internet Aman untuk Anak', 'en' => 'Safe Internet for Children'], 'description' => ['id' => 'Program edukasi keamanan internet untuk anak-anak dan orang tua.', 'en' => 'Internet safety education program for children and parents.']],
            ['icon' => 'wifi_tethering', 'icon_color' => 'navy', 'title' => ['id' => 'Community Network Workshop', 'en' => 'Community Network Workshop'], 'description' => ['id' => 'Workshop teknis untuk membangun jaringan komunitas di daerah terpencil.', 'en' => 'Technical workshop to build community networks in remote areas.']],
            ['icon' => 'developer_board', 'icon_color' => 'teal', 'title' => ['id' => 'Tech Talk Series', 'en' => 'Tech Talk Series'], 'description' => ['id' => 'Seri diskusi teknologi dengan para ahli dan praktisi internet.', 'en' => 'Technology discussion series with internet experts and practitioners.']],
            ['icon' => 'female', 'icon_color' => 'blue', 'title' => ['id' => 'Women in Tech', 'en' => 'Women in Tech'], 'description' => ['id' => 'Program pemberdayaan perempuan di bidang teknologi informasi.', 'en' => 'Women empowerment program in information technology.']],
            ['icon' => 'code', 'icon_color' => 'navy', 'title' => ['id' => 'Coding Bootcamp', 'en' => 'Coding Bootcamp'], 'description' => ['id' => 'Bootcamp pemrograman intensif untuk pemuda Indonesia.', 'en' => 'Intensive coding bootcamp for Indonesian youth.']],
        ];

        foreach ($localItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $local->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Collaboration
        $collab = Section::create([
            'page' => 'programs',
            'key' => 'collaboration',
            'title' => ['id' => 'Kolaborasi Multi-Stakeholder', 'en' => 'Multi-Stakeholder Collaboration'],
            'subtitle' => ['id' => 'Kolaborasi', 'en' => 'Collaboration'],
            'description' => ['id' => 'Kami bekerja sama dengan berbagai pemangku kepentingan untuk mencapai dampak yang lebih besar.', 'en' => 'We work with various stakeholders to achieve greater impact.'],
            'order' => 5,
            'is_active' => true,
        ]);

        $collabItems = [
            ['icon' => 'account_balance', 'title' => ['id' => 'Pemerintah', 'en' => 'Government'], 'description' => ['id' => 'Bermitra dengan kementerian dan lembaga pemerintah untuk kebijakan internet yang inklusif.', 'en' => 'Partnering with government ministries and agencies for inclusive internet policy.']],
            ['icon' => 'business_center', 'title' => ['id' => 'Sektor Swasta', 'en' => 'Private Sector'], 'description' => ['id' => 'Kolaborasi dengan perusahaan teknologi untuk inovasi dan pengembangan kapasitas.', 'en' => 'Collaboration with technology companies for innovation and capacity building.']],
            ['icon' => 'school', 'title' => ['id' => 'Akademia', 'en' => 'Academia'], 'description' => ['id' => 'Kerjasama penelitian dan pengembangan dengan universitas dan lembaga riset.', 'en' => 'Research and development collaboration with universities and research institutions.']],
            ['icon' => 'public', 'title' => ['id' => 'Organisasi Internasional', 'en' => 'International Organizations'], 'description' => ['id' => 'Bermitra dengan organisasi internasional untuk proyek dan inisiatif bersama.', 'en' => 'Partnering with international organizations for joint projects and initiatives.']],
            ['icon' => 'groups', 'title' => ['id' => 'Komunitas Lokal', 'en' => 'Local Communities'], 'description' => ['id' => 'Bekerja langsung dengan komunitas lokal untuk memastikan program sesuai kebutuhan.', 'en' => 'Working directly with local communities to ensure programs meet their needs.']],
            ['icon' => 'newspaper', 'title' => ['id' => 'Media', 'en' => 'Media'], 'description' => ['id' => 'Kerjasama dengan media untuk meningkatkan kesadaran tentang isu-isu internet.', 'en' => 'Collaboration with media to raise awareness about internet issues.']],
        ];

        foreach ($collabItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $collab->id, 'order' => $i + 1, 'is_active' => true]));
        }

        // Impact Multipliers
        $impact = Section::create([
            'page' => 'programs',
            'key' => 'impact_multipliers',
            'title' => ['id' => 'Pengganda Dampak', 'en' => 'Impact Multipliers'],
            'subtitle' => ['id' => 'Dampak', 'en' => 'Impact'],
            'description' => ['id' => 'Strategi kami untuk memaksimalkan dampak program dan inisiatif.', 'en' => 'Our strategy to maximize the impact of programs and initiatives.'],
            'order' => 6,
            'is_active' => true,
        ]);

        $impactItems = [
            ['icon' => 'campaign', 'icon_color' => 'blue', 'title' => ['id' => 'Advokasi Kebijakan', 'en' => 'Policy Advocacy'], 'description' => ['id' => 'Mendorong kebijakan yang mendukung internet terbuka dan aman melalui dialog dengan pembuat kebijakan.', 'en' => 'Promoting policies that support an open and secure internet through dialogue with policymakers.']],
            ['icon' => 'trending_up', 'icon_color' => 'teal', 'title' => ['id' => 'Skala & Replikasi', 'en' => 'Scale & Replication'], 'description' => ['id' => 'Memperluas jangkauan program yang berhasil ke lebih banyak komunitas di seluruh Indonesia.', 'en' => 'Expanding the reach of successful programs to more communities across Indonesia.']],
            ['icon' => 'handshake', 'icon_color' => 'navy', 'title' => ['id' => 'Kemitraan Strategis', 'en' => 'Strategic Partnerships'], 'description' => ['id' => 'Membangun kemitraan jangka panjang untuk keberlanjutan program dan dampak yang lebih besar.', 'en' => 'Building long-term partnerships for program sustainability and greater impact.']],
            ['icon' => 'lightbulb', 'icon_color' => 'blue', 'title' => ['id' => 'Inovasi & Teknologi', 'en' => 'Innovation & Technology'], 'description' => ['id' => 'Memanfaatkan teknologi terbaru untuk meningkatkan efektivitas program dan jangkauan.', 'en' => 'Leveraging the latest technology to improve program effectiveness and reach.']],
        ];

        foreach ($impactItems as $i => $item) {
            SectionItem::create(array_merge($item, ['section_id' => $impact->id, 'order' => $i + 1, 'is_active' => true]));
        }
    }

    private function seedEventsSections(): void
    {
        // Hero
        Section::create([
            'page' => 'events',
            'key' => 'hero',
            'title' => ['id' => 'Events & Kegiatan', 'en' => 'Events & Activities'],
            'subtitle' => ['id' => 'Events', 'en' => 'Events'],
            'description' => ['id' => 'Ikuti berbagai kegiatan dan acara yang diselenggarakan oleh ISOC Indonesia Jakarta Chapter.', 'en' => 'Join various activities and events organized by ISOC Indonesia Jakarta Chapter.'],
            'order' => 1,
            'is_active' => true,
        ]);

        // More Events
        Section::create([
            'page' => 'events',
            'key' => 'more_events',
            'title' => ['id' => 'Kegiatan Lainnya', 'en' => 'More Events'],
            'subtitle' => ['id' => 'Jelajahi', 'en' => 'Explore'],
            'order' => 2,
            'is_active' => true,
        ]);

        // Upcoming
        Section::create([
            'page' => 'events',
            'key' => 'upcoming',
            'title' => ['id' => 'Jadwal Mendatang', 'en' => 'Upcoming Schedule'],
            'subtitle' => ['id' => 'Segera Hadir', 'en' => 'Coming Soon'],
            'order' => 3,
            'is_active' => true,
        ]);
    }

    private function seedOurPartnerSections(): void
    {
        // Hero
        Section::create([
            'page' => 'ourpartner',
            'key' => 'hero',
            'title' => ['id' => 'Mitra & Kolaborasi', 'en' => 'Partners & Collaboration'],
            'subtitle' => ['id' => 'Mitra Kami', 'en' => 'Our Partners'],
            'description' => ['id' => 'Kami bekerja sama dengan berbagai organisasi di tingkat nasional dan internasional untuk mendukung pengembangan internet di Indonesia.', 'en' => 'We collaborate with various organizations at the national and international level to support internet development in Indonesia.'],
            'order' => 1,
            'is_active' => true,
        ]);

        // International
        Section::create([
            'page' => 'ourpartner',
            'key' => 'international',
            'title' => ['id' => 'Mitra Internasional', 'en' => 'International Partners'],
            'subtitle' => ['id' => 'Global', 'en' => 'Global'],
            'description' => ['id' => 'Organisasi internasional yang bekerja sama dengan kami untuk memajukan internet global.', 'en' => 'International organizations collaborating with us to advance the global internet.'],
            'order' => 2,
            'is_active' => true,
        ]);

        // National
        Section::create([
            'page' => 'ourpartner',
            'key' => 'national',
            'title' => ['id' => 'Mitra Nasional', 'en' => 'National Partners'],
            'subtitle' => ['id' => 'Nasional', 'en' => 'National'],
            'description' => ['id' => 'Organisasi dan institusi nasional yang mendukung misi kami di Indonesia.', 'en' => 'National organizations and institutions supporting our mission in Indonesia.'],
            'order' => 3,
            'is_active' => true,
        ]);

        // CTA
        Section::create([
            'page' => 'ourpartner',
            'key' => 'cta',
            'title' => ['id' => 'Tertarik Bermitra?', 'en' => 'Interested in Partnering?'],
            'description' => ['id' => 'Kami selalu terbuka untuk kolaborasi baru. Hubungi kami untuk mendiskusikan peluang kemitraan.', 'en' => 'We are always open to new collaborations. Contact us to discuss partnership opportunities.'],
            'button_text' => ['id' => 'Hubungi Kami', 'en' => 'Contact Us'],
            'button_url' => 'mailto:info@isoc-jkt.id',
            'order' => 4,
            'is_active' => true,
        ]);
    }

    private function seedTeamMembers(): void
    {
        $members = [
            ['name' => 'Tinuk Andriyanti', 'position' => ['id' => 'Ketua Umum', 'en' => 'Chairperson'], 'order' => 1],
            ['name' => 'Diah Aryanti', 'position' => ['id' => 'Bendahara', 'en' => 'Treasurer'], 'order' => 2],
            ['name' => 'Bayu Sulistiyanto', 'position' => ['id' => 'Sekretaris', 'en' => 'Secretary'], 'order' => 3],
            ['name' => 'Agnes P', 'position' => ['id' => 'Sekretariat', 'en' => 'Secretariat'], 'order' => 4],
            ['name' => 'Wahyu N', 'position' => ['id' => 'Sekretariat', 'en' => 'Secretariat'], 'order' => 5],
        ];

        foreach ($members as $member) {
            TeamMember::create(array_merge($member, ['is_active' => true]));
        }
    }

    private function seedPartners(): void
    {
        $international = [
            ['name' => ['id' => 'Internet Society (ISOC)', 'en' => 'Internet Society (ISOC)'], 'subtitle' => ['id' => 'Organisasi Induk', 'en' => 'Parent Organization'], 'url' => 'https://www.internetsociety.org', 'type' => 'international', 'order' => 1],
            ['name' => ['id' => 'APNIC', 'en' => 'APNIC'], 'subtitle' => ['id' => 'Asia Pacific Network Information Centre', 'en' => 'Asia Pacific Network Information Centre'], 'url' => 'https://www.apnic.net', 'type' => 'international', 'order' => 2],
            ['name' => ['id' => 'ICANN', 'en' => 'ICANN'], 'subtitle' => ['id' => 'Internet Corporation for Assigned Names and Numbers', 'en' => 'Internet Corporation for Assigned Names and Numbers'], 'url' => 'https://www.icann.org', 'type' => 'international', 'order' => 3],
            ['name' => ['id' => 'IETF', 'en' => 'IETF'], 'subtitle' => ['id' => 'Internet Engineering Task Force', 'en' => 'Internet Engineering Task Force'], 'url' => 'https://www.ietf.org', 'type' => 'international', 'order' => 4],
        ];

        $national = [
            ['name' => ['id' => 'Kemenkominfo', 'en' => 'Ministry of Communication and Information'], 'subtitle' => ['id' => 'Kementerian Komunikasi dan Informatika', 'en' => 'Ministry of Communication and Information Technology'], 'url' => 'https://www.kominfo.go.id', 'type' => 'national', 'order' => 1],
            ['name' => ['id' => 'APJII', 'en' => 'APJII'], 'subtitle' => ['id' => 'Asosiasi Penyelenggara Jasa Internet Indonesia', 'en' => 'Indonesian Internet Service Provider Association'], 'url' => 'https://www.apjii.or.id', 'type' => 'national', 'order' => 2],
            ['name' => ['id' => 'PANDI', 'en' => 'PANDI'], 'subtitle' => ['id' => 'Pengelola Nama Domain Internet Indonesia', 'en' => 'Indonesia Domain Name Registry'], 'url' => 'https://www.pandi.id', 'type' => 'national', 'order' => 3],
            ['name' => ['id' => 'ID-CERT', 'en' => 'ID-CERT'], 'subtitle' => ['id' => 'Indonesia Computer Emergency Response Team', 'en' => 'Indonesia Computer Emergency Response Team'], 'url' => 'https://www.cert.or.id', 'type' => 'national', 'order' => 4],
        ];

        foreach (array_merge($international, $national) as $partner) {
            Partner::create(array_merge($partner, ['is_active' => true]));
        }
    }

    private function seedEvents(): void
    {
        Event::create([
            'title' => ['id' => 'Internet Governance Forum (IGF) 2024', 'en' => 'Internet Governance Forum (IGF) 2024'],
            'description' => ['id' => 'Forum tata kelola internet terbesar yang mempertemukan pemangku kepentingan dari seluruh dunia untuk berdiskusi tentang masa depan internet.', 'en' => 'The largest internet governance forum bringing together stakeholders from around the world to discuss the future of the internet.'],
            'category' => ['id' => 'Konferensi', 'en' => 'Conference'],
            'date' => '2024-12-15',
            'time_info' => ['id' => '09:00 - 17:00 WIB', 'en' => '09:00 - 17:00 WIB'],
            'location' => ['id' => 'Riyadh, Arab Saudi', 'en' => 'Riyadh, Saudi Arabia'],
            'location_type' => 'hybrid',
            'capacity_info' => ['id' => '5.000+ Peserta', 'en' => '5,000+ Participants'],
            'registration_open' => true,
            'is_featured' => true,
            'is_active' => true,
            'order' => 1,
        ]);

        Event::create([
            'title' => ['id' => 'Workshop Keamanan Siber', 'en' => 'Cybersecurity Workshop'],
            'description' => ['id' => 'Workshop praktis tentang keamanan siber untuk profesional dan pelaku UMKM di Jakarta.', 'en' => 'Practical cybersecurity workshop for professionals and SME practitioners in Jakarta.'],
            'category' => ['id' => 'Workshop', 'en' => 'Workshop'],
            'date' => '2025-01-20',
            'time_info' => ['id' => '13:00 - 17:00 WIB', 'en' => '13:00 - 17:00 WIB'],
            'location' => ['id' => 'Jakarta', 'en' => 'Jakarta'],
            'location_type' => 'offline',
            'registration_open' => true,
            'is_featured' => false,
            'is_active' => true,
            'order' => 2,
        ]);

        Event::create([
            'title' => ['id' => 'Tech Talk: AI & Internet Governance', 'en' => 'Tech Talk: AI & Internet Governance'],
            'description' => ['id' => 'Diskusi tentang dampak kecerdasan buatan terhadap tata kelola internet dan kebijakan digital.', 'en' => 'Discussion on the impact of artificial intelligence on internet governance and digital policy.'],
            'category' => ['id' => 'Diskusi', 'en' => 'Discussion'],
            'date' => '2025-02-10',
            'time_info' => ['id' => '19:00 - 21:00 WIB', 'en' => '19:00 - 21:00 WIB'],
            'location' => ['id' => 'Online (Zoom)', 'en' => 'Online (Zoom)'],
            'location_type' => 'online',
            'registration_open' => true,
            'is_featured' => false,
            'is_active' => true,
            'order' => 3,
        ]);

        Event::create([
            'title' => ['id' => 'Pelatihan Literasi Digital', 'en' => 'Digital Literacy Training'],
            'description' => ['id' => 'Program pelatihan literasi digital untuk guru dan tenaga pendidik di wilayah Jabodetabek.', 'en' => 'Digital literacy training program for teachers and educators in the Greater Jakarta area.'],
            'category' => ['id' => 'Pelatihan', 'en' => 'Training'],
            'date' => '2025-03-15',
            'location' => ['id' => 'Jakarta Selatan', 'en' => 'South Jakarta'],
            'location_type' => 'offline',
            'registration_open' => true,
            'is_featured' => false,
            'is_active' => true,
            'order' => 4,
        ]);

        Event::create([
            'title' => ['id' => 'ISOC Indonesia Annual Meeting 2025', 'en' => 'ISOC Indonesia Annual Meeting 2025'],
            'description' => ['id' => 'Pertemuan tahunan ISOC Indonesia Jakarta Chapter untuk membahas rencana dan strategi tahun 2025.', 'en' => 'Annual meeting of ISOC Indonesia Jakarta Chapter to discuss 2025 plans and strategy.'],
            'category' => ['id' => 'Pertemuan', 'en' => 'Meeting'],
            'date' => '2025-07-20',
            'location' => ['id' => 'Jakarta', 'en' => 'Jakarta'],
            'location_type' => 'hybrid',
            'registration_open' => true,
            'is_featured' => false,
            'is_active' => true,
            'order' => 5,
        ]);
    }

    private function seedSiteSettings(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => ['id' => 'ISOC Indonesia Jakarta Chapter', 'en' => 'ISOC Indonesia Jakarta Chapter'], 'group' => 'general'],
            ['key' => 'footer_description', 'value' => ['id' => 'Mendukung pengembangan internet yang berkelanjutan, inklusif, aman, dan mudah diakses.', 'en' => 'Supporting the development of a sustainable, inclusive, safe, and accessible internet.'], 'group' => 'footer'],
            ['key' => 'social_instagram', 'value' => ['id' => 'https://www.instagram.com/isoc.jkt/', 'en' => 'https://www.instagram.com/isoc.jkt/'], 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => ['id' => 'https://www.linkedin.com/company/internet-society-chapter-jakarta-ina/', 'en' => 'https://www.linkedin.com/company/internet-society-chapter-jakarta-ina/'], 'group' => 'social'],
            ['key' => 'contact_email', 'value' => ['id' => 'secretariat@isoc.id', 'en' => 'info@isoc-jkt.id'], 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
