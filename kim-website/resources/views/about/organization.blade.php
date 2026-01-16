@extends('layouts.app')

@section('title', 'Tim Kami - PT KIM')

@section('content')
<style>
/* Animated Background */
.animated-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    background-size: 200% 200%;
    animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {

    0%,
    100% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }
}

/* Header */
.team-header {
    background: transparent;
    color: white;
    padding: 120px 0 80px;
    text-align: center;
}

.page-title {
    font-size: 4rem;
    font-weight: 900;
    margin-bottom: 20px;
    text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    animation: fadeInDown 1s ease;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.page-subtitle {
    font-size: 1.4rem;
    opacity: 0.95;
    font-weight: 300;
    animation: fadeIn 1.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

/* Section */
.section {
    padding: 80px 0;
}

.section-bg-white {
    background: white;
    border-radius: 40px;
    padding: 80px 40px;
    margin: 0 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

.section-header {
    text-align: center;
    margin-bottom: 70px;
}

.section-badge {
    display: inline-block;
    padding: 10px 28px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.section-title {
    font-size: 3rem;
    font-weight: 900;
    color: #2d3748;
    margin-bottom: 15px;
}

.section-subtitle {
    font-size: 1.2rem;
    color: #718096;
    font-weight: 300;
}

/* Owner Card - REDESIGN LEBIH KEREN */
.owner-section {
    max-width: 1000px;
    margin: 0 auto 80px;
}

.owner-card {
    background: white;
    border-radius: 30px;
    padding: 0;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    position: relative;
    transition: transform 0.3s ease;
}

.owner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
}

.owner-card-inner {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 0;
    min-height: 350px;
}

.owner-photo-section {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.owner-photo-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.owner-photo-wrapper {
    position: relative;
    z-index: 2;
}

.owner-photo-wrapper img {
    width: 300px;
    height: 300px;
    border-radius: 20px;
    object-fit: cover;
    background: white;
    border: 4px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    transition: transform 0.5s ease;
}

.owner-card:hover .owner-photo-wrapper img {
    transform: scale(1.05) rotate(2deg);
}

.owner-info-section {
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
}

.owner-badge {
    display: inline-block;
    padding: 8px 20px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 20px;
    width: fit-content;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.owner-info-section h3 {
    font-size: 2.5rem;
    font-weight: 900;
    color: #2d3748;
    margin-bottom: 15px;
    line-height: 1.2;
}

.owner-icon {
    position: absolute;
    bottom: 30px;
    right: 30px;
    font-size: 5rem;
    color: #667eea;
    opacity: 0.08;
}

/* C-Level - REDESIGN DENGAN FOTO BESAR */
.c-level-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 40px;
    margin-bottom: 60px;
}

.executive-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    transition: all 0.4s ease;
    position: relative;
}

.executive-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
}

.exec-photo {
    position: relative;
    height: 320px;
    overflow: hidden;
    background: white;
    padding: 15px;
}

.exec-photo::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.2));
    z-index: 2;
    pointer-events: none;
}

.exec-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.5s ease;
}

.executive-card:hover .exec-photo img {
    transform: scale(1.1);
}

.exec-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 3;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: white;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.exec-badge.ceo {
    background: rgba(72, 187, 120, 0.9);
}

.exec-badge.coo {
    background: rgba(49, 130, 206, 0.9);
}

.exec-badge.cmo {
    background: rgba(214, 158, 46, 0.9);
}

.exec-badge.cto {
    background: rgba(159, 122, 234, 0.9);
}

.exec-info {
    padding: 30px;
    background: linear-gradient(to bottom, #ffffff, #f7fafc);
}

.exec-info h4 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.exec-title {
    color: #718096;
    font-size: 1rem;
    font-weight: 500;
}

.single-executive {
    max-width: 100%;
    margin: 0 auto 70px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 40px;
}

/* Department Cards - REDESIGN HORIZONTAL */
.department-heads-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 70px;
}

.dept-head-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s ease;
    display: flex;
    flex-direction: column;
}

.dept-head-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.dept-header {
    padding: 25px 25px 20px;
    text-align: center;
    background: linear-gradient(135deg, #f7fafc, #e6f7ff);
}

.dept-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.dept-name {
    font-size: 1rem;
    font-weight: 700;
    color: #2d3748;
    min-height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dept-photo-section {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: white;
    padding: 12px;
}

.dept-photo-section img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.5s ease;
}

.dept-head-card:hover .dept-photo-section img {
    transform: scale(1.15);
}

.dept-info {
    padding: 20px;
    text-align: center;
    background: linear-gradient(to bottom, #ffffff, #f7fafc);
}

.head-name {
    font-size: 1.05rem;
    font-weight: 600;
    color: #2d3748;
}

/* Junior Staff - REDESIGN DENGAN CARD BESAR */
.junior-staff-section {
    background: linear-gradient(135deg, #f7fafc 0%, #e6f7ff 100%);
    border-radius: 30px;
    padding: 60px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
}

.staff-section-title {
    font-size: 2rem;
    font-weight: 800;
    color: #2d3748;
    text-align: center;
    margin-bottom: 50px;
}

.junior-staff-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.junior-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s ease;
}

.junior-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.junior-photo {
    position: relative;
    height: 280px;
    overflow: hidden;
    background: white;
    padding: 12px;
}

.junior-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.5s ease;
}

.junior-card:hover .junior-photo img {
    transform: scale(1.1);
}

.junior-info {
    padding: 25px;
    text-align: center;
    background: linear-gradient(to bottom, #ffffff, #f7fafc);
}

.junior-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
}

/* Expert Cards - REDESIGN LEBIH BESAR */
.expert-staff-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 35px;
}

.expert-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s ease;
}

.expert-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.expert-photo {
    position: relative;
    height: 280px;
    overflow: hidden;
    background: white;
    padding: 12px;
}

.expert-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.5s ease;
}

.expert-card:hover .expert-photo img {
    transform: scale(1.1);
}

.expert-content {
    padding: 30px 25px;
    text-align: center;
    background: linear-gradient(to bottom, #ffffff, #f7fafc);
}

.expert-content h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
    line-height: 1.4;
}

.expert-expertise {
    font-size: 0.9rem;
    color: #718096;
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 992px) {
    .owner-card-inner {
        grid-template-columns: 1fr;
    }

    .owner-photo-section {
        padding: 60px 40px;
    }

    .c-level-grid,
    .single-executive,
    .department-heads-grid,
    .junior-staff-grid,
    .expert-staff-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* =========================
   MOBILE & TABLET OPTIMIZATION
========================= */

/* General Mobile */
@media (max-width: 768px) {

    body {
        overflow-x: hidden;
    }

    .section {
        padding: 60px 0;
    }

    .section-bg-white {
        padding: 40px 20px;
        margin: 0 10px;
        border-radius: 24px;
    }

    /* Header */
    .team-header {
        padding: 90px 0 60px;
    }

    .page-title {
        font-size: 2.2rem;
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: 1rem;
    }

    /* Section Titles */
    .section-title {
        font-size: 1.8rem;
    }

    .section-subtitle {
        font-size: 0.95rem;
    }

    /* OWNER CARD */
    .owner-card-inner {
        grid-template-columns: 1fr;
    }

    .owner-photo-section {
        padding: 40px 20px;
    }

    .owner-photo-wrapper img {
        width: 220px;
        height: 220px;
    }

    .owner-info-section {
        padding: 35px 25px;
        text-align: center;
    }

    .owner-info-section h3 {
        font-size: 1.8rem;
    }

    .owner-icon {
        display: none;
    }

    /* C-LEVEL */
    .c-level-grid,
    .single-executive {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .exec-photo {
        height: 260px;
    }

    .exec-info {
        padding: 22px;
        text-align: center;
    }

    .exec-info h4 {
        font-size: 1.2rem;
    }

    /* DEPARTMENT HEADS
       Horizontal scroll = UX modern di mobile */
    .department-heads-grid {
        display: flex;
        overflow-x: auto;
        gap: 20px;
        padding-bottom: 10px;
        scroll-snap-type: x mandatory;
    }

    .dept-head-card {
        min-width: 260px;
        scroll-snap-align: start;
    }

    /* JUNIOR STAFF */
    .junior-staff-section {
        padding: 40px 25px;
    }

    .junior-staff-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .junior-photo {
        height: 220px;
    }

    /* EXPERT STAFF */
    .expert-staff-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .expert-photo {
        height: 220px;
    }

    .expert-content {
        padding: 20px;
    }

}

/* Extra Small Devices */
@media (max-width: 480px) {

    .page-title {
        font-size: 1.9rem;
    }

    .section-title {
        font-size: 1.6rem;
    }

    .junior-staff-grid,
    .expert-staff-grid {
        grid-template-columns: 1fr;
    }

    .dept-head-card {
        min-width: 240px;
    }

}
</style>

<div class="animated-bg"></div>

<!-- Header -->
<section class="team-header">
    <div class="container">
        <h1 class="page-title">Our Super Team</h1>
        <p class="page-subtitle">Bertemu dengan orang-orang hebat di balik kesuksesan PT KIM</p>
    </div>
</section>

<!-- Our Super Team (1) -->
<section class="section">
    <div class="container">
        <div class="section-bg-white">
            <div class="section-header">
                <span class="section-badge">Our Super Team</span>
                <h2 class="section-title">Tim Inti Kami</h2>
                <p class="section-subtitle">Kepemimpinan yang kuat untuk kesuksesan bersama</p>
            </div>

            <!-- Owner - REDESIGN -->
            <div class="owner-section">
                <div class="owner-card">
                    <div class="owner-card-inner">
                        <div class="owner-photo-section">
                            <div class="owner-photo-wrapper">
                                <img src="{{ asset('images/team/prof-edy.png') }}" alt="Prof. Dr. H. Edi Suryadi"
                                    onerror="this.src='https://ui-avatars.com/api/?name=Prof+Edi+Suryadi&size=500&background=667eea&color=fff&bold=true&font-size=0.3'">
                            </div>
                        </div>
                        <div class="owner-info-section">
                            <i class="fas fa-crown owner-icon"></i>
                            <div class="owner-badge">Owner</div>
                            <h3>Prof. Dr. H. Edi Suryadi, M.Si.</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C-Level - REDESIGN -->
            <div class="c-level-grid">
                <div class="executive-card">
                    <div class="exec-photo">
                        <img src="{{ asset('images/team/yosep.png') }}" alt="Yosep Hernawan"
                            onerror="this.src='https://ui-avatars.com/api/?name=Yosep+Hernawan&size=600&background=48bb78&color=fff&bold=true&font-size=0.3'">
                        <div class="exec-badge ceo">CEO</div>
                    </div>
                    <div class="exec-info">
                        <h4>Yosep Hernawan, ST., MM., IPM</h4>
                        <p class="exec-title">Chief Executive Officer</p>
                    </div>
                </div>

                <div class="executive-card">
                    <div class="exec-photo">
                        <img src="{{ asset('images/team/rasto.png') }}" alt="Dr. Rasto"
                            onerror="this.src='https://ui-avatars.com/api/?name=Dr+Rasto&size=600&background=3182ce&color=fff&bold=true&font-size=0.3'">
                        <div class="exec-badge coo">COO</div>
                    </div>
                    <div class="exec-info">
                        <h4>Dr. Rasto, M.Pd.</h4>
                        <p class="exec-title">Chief Operating Officer</p>
                    </div>
                </div>
            </div>

            <!-- CMO & CTO -->
            <div class="single-executive">
                <div class="executive-card">
                    <div class="exec-photo">
                        <img src="{{ asset('images/team/gilang.png') }}" alt="Gilang Garnadi"
                            onerror="this.src='https://ui-avatars.com/api/?name=Gilang+Garnadi&size=600&background=d69e2e&color=fff&bold=true&font-size=0.3'">
                        <div class="exec-badge cmo">CMO</div>
                    </div>
                    <div class="exec-info">
                        <h4>Gilang Garnadi Suryadi, S.Si., M.T.</h4>
                        <p class="exec-title">Chief Marketing Officer</p>
                    </div>
                </div>
                <div class="executive-card">
                    <div class="exec-photo">
                        <img src="{{ asset('images/team/farhan.png') }}" alt="Farhan Muzhaffar"
                            onerror="this.src='https://ui-avatars.com/api/?name=Farhan+Muzhaffar&size=600&background=9f7aea&color=fff&bold=true&font-size=0.3'">
                        <div class="exec-badge cto">CTO</div>
                    </div>
                    <div class="exec-info">
                        <h4>Farhan Muzhaffar Tiras Putra, S.Kom.</h4>
                        <p class="exec-title">Chief Technical Officer</p>
                    </div>
                </div>
            </div>

            <!-- Department Heads - REDESIGN -->
            <div class="department-heads-grid">
                <div class="dept-head-card">
                    <div class="dept-header">
                        <div class="dept-icon" style="background: linear-gradient(135deg, #805ad5, #6b46c1);">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h5 class="dept-name">Information & Technology</h5>
                    </div>
                    <div class="dept-photo-section">
                        <img src="{{ asset('images/team/anjar.png') }}" alt="Anjar Suprayogi"
                            onerror="this.src='https://ui-avatars.com/api/?name=Anjar+Suprayogi&size=400&background=805ad5&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="dept-info">
                        <p class="head-name">Anjar Suprayogi, M.T.</p>
                    </div>
                </div>

                <div class="dept-head-card">
                    <div class="dept-header">
                        <div class="dept-icon" style="background: linear-gradient(135deg, #e53e3e, #c53030);">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h5 class="dept-name">Financial & Accounting</h5>
                    </div>
                    <div class="dept-photo-section">
                        <img src="{{ asset('images/team/yosan.png') }}" alt="Yosan Krisna"
                            onerror="this.src='https://ui-avatars.com/api/?name=Yosan+Krisna&size=400&background=e53e3e&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="dept-info">
                        <p class="head-name">Yosan Krisna, S.Ds.</p>
                    </div>
                </div>

                <div class="dept-head-card">
                    <div class="dept-header">
                        <div class="dept-icon" style="background: linear-gradient(135deg, #38b2ac, #319795);">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="dept-name">Human Resources Dept</h5>
                    </div>
                    <div class="dept-photo-section">
                        <img src="{{ asset('images/team/neneng.png') }}" alt="Neneng Isti"
                            onerror="this.src='https://ui-avatars.com/api/?name=Neneng+Isti&size=400&background=38b2ac&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="dept-info">
                        <p class="head-name">Neneng Isti Heliani, ST.</p>
                    </div>
                </div>
            </div>

            <!-- Junior Staff - REDESIGN -->
            <div class="junior-staff-section">
                <h4 class="staff-section-title">
                    <i class="fas fa-star" style="color: #667eea; margin-right: 10px;"></i>
                    Junior Staff - Digital Marketing
                </h4>
                <div class="junior-staff-grid">
                    <div class="junior-card">
                        <div class="junior-photo">
                            <img src="{{ asset('images/team/fahlevi.png') }}" alt="Fahlevi Permana"
                                onerror="this.src='https://ui-avatars.com/api/?name=Fahlevi+Permana&size=500&background=667eea&color=fff&bold=true&font-size=0.3'">
                        </div>
                        <div class="junior-info">
                            <p class="junior-name">Fahlevi Permana</p>
                        </div>
                    </div>

                    <div class="junior-card">
                        <div class="junior-photo">
                            <img src="{{ asset('images/team/hilma.png') }}" alt="Hilma Habibah"
                                onerror="this.src='https://ui-avatars.com/api/?name=Hilma+Habibah&size=500&background=764ba2&color=fff&bold=true&font-size=0.3'">
                        </div>
                        <div class="junior-info">
                            <p class="junior-name">Hilma Habibah</p>
                        </div>
                    </div>

                    <div class="junior-card">
                        <div class="junior-photo">
                            <img src="{{ asset('images/team/tessa.png') }}" alt="Tessa Ismi"
                                onerror="this.src='https://ui-avatars.com/api/?name=Tessa+Ismi&size=500&background=48bb78&color=fff&bold=true&font-size=0.3'">
                        </div>
                        <div class="junior-info">
                            <p class="junior-name">Tessa Ismi Maharani</p>
                        </div>
                    </div>

                    <div class="junior-card">
                        <div class="junior-photo">
                            <img src="{{ asset('images/team/nabila.png') }}" alt="Nabila Syahtani"
                                onerror="this.src='https://ui-avatars.com/api/?name=Nabila+Syahtani&size=500&background=3182ce&color=fff&bold=true&font-size=0.3'">
                        </div>
                        <div class="junior-info">
                            <p class="junior-name">Nabila Syahtani</p>
                        </div>
                    </div>

                    <div class="junior-card">
                        <div class="junior-photo">
                            <img src="{{ asset('images/team/sefty.png') }}" alt="Sefty Mustika"
                                onerror="this.src='https://ui-avatars.com/api/?name=Sefty+Mustika&size=500&background=d69e2e&color=fff&bold=true&font-size=0.3'">
                        </div>
                        <div class="junior-info">
                            <p class="junior-name">Sefty Mustika</p>
                        </div>
                    </div>

                    <div class="junior-card">
                        <div class="junior-photo">
                            <img src="{{ asset('images/team/doni.png') }}" alt="Doni Hamdani"
                                onerror="this.src='https://ui-avatars.com/api/?name=Doni+Hamdani&size=500&background=805ad5&color=fff&bold=true&font-size=0.3'">
                        </div>
                        <div class="junior-info">
                            <p class="junior-name">Doni Hamdani</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Super Team (2) - Expert Staff -->
<section class="section" style="padding-bottom: 100px;">
    <div class="container">
        <div class="section-bg-white">
            <div class="section-header">
                <span class="section-badge">Our Super Team</span>
                <h2 class="section-title">Expert Staff</h2>
                <p class="section-subtitle">Para ahli yang membimbing strategi dan visi perusahaan</p>
            </div>

            <div class="expert-staff-grid">
                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/prof-edy.png') }}" alt="Prof. Dr. H. Edi Suryadi"
                            onerror="this.src='https://ui-avatars.com/api/?name=Prof+Edi+Suryadi&size=500&background=667eea&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Prof. Dr. H. Edi Suryadi, M.Si.</h4>
                        <p class="expert-expertise">Komunikasi</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/prof-nugraha.png') }}" alt="Prof. Dr. H. Nugraha"
                            onerror="this.src='https://ui-avatars.com/api/?name=Prof+Nugraha&size=500&background=764ba2&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Prof. Dr. H. Nugraha, SE. Ak. M.Si, CA, CPA., CFP</h4>
                        <p class="expert-expertise">Keuangan, Akuntansi, Pajak</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/dr-toni.png') }}" alt="Dr. Toni"
                            onerror="this.src='https://ui-avatars.com/api/?name=Dr+Toni+Heryana&size=500&background=48bb78&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Dr. Toni Heryana. MM</h4>
                        <p class="expert-expertise">Keuangan, Akuntansi, Pajak</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/dr-baban.png') }}" alt="Dr. Baban"
                            onerror="this.src='https://ui-avatars.com/api/?name=Dr+Baban+Sobandi&size=500&background=3182ce&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Dr. Baban Sobandi MSi</h4>
                        <p class="expert-expertise">Keuangan, Akuntansi, Pajak</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/dr-joni.png') }}" alt="Dr. Joni"
                            onerror="this.src='https://ui-avatars.com/api/?name=Dr+Joni+Dawud&size=500&background=d69e2e&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Dr. Joni Dawud DEA</h4>
                        <p class="expert-expertise">Keuangan, Akuntansi, Pajak</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/rasto.png') }}" alt="Dr. Rasto"
                            onerror="this.src='https://ui-avatars.com/api/?name=Dr+Rasto&size=600&background=805ad5&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Dr. Rasto, M.Pd.</h4>
                        <p class="expert-expertise">Manajemen Proses Bisnis</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/agus.png') }}" alt="Agus Rakhmat"
                            onerror="this.src='https://ui-avatars.com/api/?name=Agus+Rakhmat&size=500&background=38b2ac&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Agus Rakhmat, SH, MSi</h4>
                        <p class="expert-expertise">Pajak daerah & kendaraan bermotor</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/rakhmat.png') }}" alt="Rakhmat Supriatna"
                            onerror="this.src='https://ui-avatars.com/api/?name=Rakhmat+Supriatna&size=500&background=e53e3e&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Rakhmat Supriatna, S.E., M.Si.</h4>
                        <p class="expert-expertise">Pajak daerah & kendaraan bermotor</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/bambang.png') }}" alt="Bambang Yanudi"
                            onerror="this.src='https://ui-avatars.com/api/?name=Bambang+Yanudi&size=500&background=dd6b20&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Bambang Yanudi</h4>
                        <p class="expert-expertise">Pajak daerah & kendaraan bermotor</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/hadiat.png') }}" alt="Hadiat Supriatna"
                            onerror="this.src='https://ui-avatars.com/api/?name=Hadiat+Supriatna&size=500&background=667eea&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Hadiat Supriatna</h4>
                        <p class="expert-expertise">Pajak daerah & kendaraan bermotor</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/yosep.png') }}" alt="Yosep Hernawan"
                            onerror="this.src='https://ui-avatars.com/api/?name=Yosep+Hernawan&size=600&background=48bb78&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Yosep Hernawan, ST., MM., IPM.</h4>
                        <p class="expert-expertise">ISO 9001: 2015, ISO 21000:2015</p>
                    </div>
                </div>

                <div class="expert-card">
                    <div class="expert-photo">
                        <img src="{{ asset('images/team/anjar.png') }}" alt="Anjar Suprayogi"
                            onerror="this.src='https://ui-avatars.com/api/?name=Anjar+Suprayogi&size=500&background=9f7aea&color=fff&bold=true&font-size=0.3'">
                    </div>
                    <div class="expert-content">
                        <h4>Anjar Suprayogi, MT.</h4>
                        <p class="expert-expertise">Elektronika dan Mekatronika</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection