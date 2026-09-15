<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Project PPWD2026 - M. Sholchin Tafsir</title>
    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #070a13;
            --card-glass: rgba(18, 24, 43, 0.65);
            --card-border: rgba(255, 255, 255, 0.12);
            --card-border-hover: rgba(255, 255, 255, 0.35);
            --primary-glow: #38bdf8;
            --secondary-glow: #818cf8;
            --accent-pink: #f43f5e;
            --accent-purple: #a855f7;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            padding: 3.5rem 1.5rem;
        }

        /* =========================================
           DYNAMIC MOVING BACKGROUND ELEMENTS
           ========================================= */
        .ambient-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        /* Subtle modern tech grid */
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, #000 40%, transparent 95%);
            -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, #000 40%, transparent 95%);
        }

        /* Floating Gradient Glowing Orbs (Dynamic Ambient) */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.65;
            mix-blend-mode: screen;
            animation: orbFloat 18s infinite alternate ease-in-out;
        }

        .orb-1 {
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.75) 0%, rgba(99, 102, 241, 0.4) 50%, transparent 75%);
            top: -5%;
            left: 8%;
            animation-duration: 16s;
        }

        .orb-2 {
            width: 560px;
            height: 560px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.7) 0%, rgba(168, 85, 247, 0.35) 50%, transparent 75%);
            bottom: -10%;
            right: 8%;
            animation-duration: 20s;
            animation-delay: -4s;
        }

        .orb-3 {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.55) 0%, rgba(56, 189, 248, 0.25) 50%, transparent 75%);
            top: 40%;
            left: 50%;
            animation-duration: 17s;
            animation-delay: -8s;
        }

        @keyframes orbFloat {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(50px, -35px) scale(1.12);
            }
            66% {
                transform: translate(-35px, 45px) scale(0.95);
            }
            100% {
                transform: translate(40px, 25px) scale(1.06);
            }
        }

        /* Floating Geometric Dust Particles */
        .floating-particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.8);
            animation: floatParticle 14s infinite linear;
        }

        .p1 { width: 3px; height: 3px; top: 18%; left: 22%; animation-duration: 15s; }
        .p2 { width: 4px; height: 4px; top: 68%; left: 14%; animation-duration: 20s; animation-delay: -3s; }
        .p3 { width: 3px; height: 3px; top: 38%; left: 82%; animation-duration: 17s; animation-delay: -7s; }
        .p4 { width: 5px; height: 5px; top: 82%; left: 68%; animation-duration: 22s; animation-delay: -2s; }
        .p5 { width: 3px; height: 3px; top: 12%; left: 78%; animation-duration: 18s; animation-delay: -5s; }

        @keyframes floatParticle {
            0% { transform: translateY(0) translateX(0); opacity: 0; }
            25% { opacity: 0.9; }
            75% { opacity: 0.9; }
            100% { transform: translateY(-130px) translateX(35px); opacity: 0; }
        }

        /* =========================================
           MAIN CONTENT & TYPOGRAPHY
           ========================================= */
        .main-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 960px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .badge-portal {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #93c5fd;
            margin-bottom: 1.2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #38bdf8;
            border-radius: 50%;
            box-shadow: 0 0 10px #38bdf8;
            animation: dotPulse 2s infinite;
        }

        @keyframes dotPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.6; }
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2.3rem, 5vw, 3.4rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 0.8rem;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #ffffff 30%, #cbd5e1 60%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: clamp(0.9rem, 2vw, 1.05rem);
            color: var(--text-muted);
            font-weight: 400;
            letter-spacing: 0.02em;
            max-width: 600px;
            margin: 0 auto;
        }

        .student-pill {
            display: inline-block;
            margin-top: 0.6rem;
            font-weight: 600;
            color: #e2e8f0;
            background: rgba(255, 255, 255, 0.04);
            padding: 4px 14px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.85rem;
            letter-spacing: 0.04em;
        }

        /* =========================================
           GLASSMORPHISM CARDS CONTAINER
           ========================================= */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.2rem;
            width: 100%;
        }

        /* =========================================
           PREMIUM GLASSMORPHISM CARD WITH BLING BLING
           ========================================= */
        .glass-card {
            position: relative;
            background: var(--card-glass);
            border-radius: 28px;
            padding: 2.5rem 2.2rem;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(24px) saturate(190%);
            -webkit-backdrop-filter: blur(24px) saturate(190%);
            border: 1px solid var(--card-border);
            box-shadow: 
                0 20px 40px -15px rgba(0, 0, 0, 0.7),
                inset 0 1px 1px 0 rgba(255, 255, 255, 0.18),
                inset 0 0 20px 0 rgba(255, 255, 255, 0.02);
            transition: all 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            cursor: pointer;
        }

        /* Ambient subtle inner gradient per card */
        .card-profil {
            --accent-theme: #38bdf8;
            --accent-theme-end: #6366f1;
            --glow-color: rgba(56, 189, 248, 0.35);
        }

        .card-donasi {
            --accent-theme: #ec4899;
            --accent-theme-end: #f43f5e;
            --glow-color: rgba(236, 72, 153, 0.35);
        }

        /* Dynamic Border Highlight / Light Rail */
        .glass-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 28px;
            padding: 1.5px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25), transparent 40%, rgba(255, 255, 255, 0.05) 80%, var(--accent-theme));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0.6;
            transition: opacity 0.4s ease;
        }

        /* =========================================
           BLING BLING (SHINE & SPARKLE) EFFECT
           ========================================= */
        /* 1. Sweeping Bright Beam of Light across card */
        .light-sweep {
            position: absolute;
            top: -60%;
            left: -80%;
            width: 70%;
            height: 220%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.03) 30%,
                rgba(255, 255, 255, 0.35) 50%,
                rgba(255, 255, 255, 0.65) 52%,
                rgba(255, 255, 255, 0.35) 54%,
                rgba(255, 255, 255, 0.03) 70%,
                transparent 100%
            );
            transform: rotate(30deg);
            pointer-events: none;
            opacity: 0.6;
            animation: continuousSweep 7s infinite cubic-bezier(0.4, 0, 0.2, 1);
            transition: opacity 0.3s ease;
        }

        .card-donasi .light-sweep {
            animation-delay: 3.5s;
        }

        @keyframes continuousSweep {
            0% {
                left: -100%;
                opacity: 0;
            }
            15% {
                opacity: 0.8;
            }
            35% {
                left: 170%;
                opacity: 0;
            }
            100% {
                left: 170%;
                opacity: 0;
            }
        }

        /* 2. Sparkle Star Elements (Diamond Glint / Bling) */
        .sparkle {
            position: absolute;
            pointer-events: none;
            color: #ffffff;
            opacity: 0;
            transform: scale(0) rotate(0deg);
            z-index: 10;
            filter: drop-shadow(0 0 8px #ffffff) drop-shadow(0 0 18px var(--accent-theme));
        }

        .sparkle-svg {
            width: 100%;
            height: 100%;
            fill: #ffffff;
        }

        /* Sparkle placements */
        .sparkle-top-right {
            top: 20px;
            right: 22px;
            width: 26px;
            height: 26px;
            animation: sparkleGlint 3.2s infinite ease-in-out;
        }

        .sparkle-top-left {
            top: 22px;
            left: 24px;
            width: 18px;
            height: 18px;
            animation: sparkleGlint 3.8s infinite ease-in-out 1.2s;
        }

        .sparkle-mid-right {
            top: 52%;
            right: 18px;
            width: 16px;
            height: 16px;
            animation: sparkleGlint 4.2s infinite ease-in-out 0.6s;
        }

        .sparkle-bottom-right {
            bottom: 24px;
            right: 26px;
            width: 22px;
            height: 22px;
            animation: sparkleGlint 3.5s infinite ease-in-out 2.1s;
        }

        @keyframes sparkleGlint {
            0%, 100% {
                opacity: 0;
                transform: scale(0) rotate(0deg);
            }
            35% {
                opacity: 0;
                transform: scale(0.3) rotate(30deg);
            }
            50% {
                opacity: 1;
                transform: scale(1.3) rotate(90deg);
            }
            65% {
                opacity: 0.95;
                transform: scale(0.85) rotate(135deg);
            }
            80% {
                opacity: 0;
                transform: scale(0) rotate(180deg);
            }
        }

        /* Radial Spotlight behind mouse/card */
        .card-spotlight {
            position: absolute;
            inset: 0;
            border-radius: 28px;
            background: radial-gradient(circle 260px at var(--mouse-x, 50%) var(--mouse-y, 30%), var(--glow-color), transparent 100%);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s ease;
        }

        /* CARD HOVER STATE */
        .glass-card:hover {
            transform: translateY(-12px) scale(1.025);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 
                0 32px 64px -12px rgba(0, 0, 0, 0.85),
                0 0 45px 5px var(--glow-color),
                inset 0 1px 2px 0 rgba(255, 255, 255, 0.5);
        }

        .glass-card:hover::before {
            opacity: 1;
        }

        .glass-card:hover .card-spotlight {
            opacity: 1;
        }

        /* Intensify sparkles on hover */
        .glass-card:hover .sparkle {
            animation-duration: 1.6s !important;
            filter: drop-shadow(0 0 12px #ffffff) drop-shadow(0 0 26px var(--accent-theme));
        }

        .glass-card:hover .light-sweep {
            animation: sweepFast 1.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes sweepFast {
            0% { left: -100%; opacity: 0; }
            30% { opacity: 1; }
            100% { left: 180%; opacity: 0; }
        }

        /* =========================================
           CUSTOM VECTOR ICON BADGE (NO EMOJIS)
           ========================================= */
        .icon-container {
            width: 76px;
            height: 76px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.8rem;
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 
                0 12px 24px -6px rgba(0, 0, 0, 0.4),
                inset 0 1px 1px 0 rgba(255, 255, 255, 0.2);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
        }

        .card-profil .icon-container {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.15) 0%, rgba(99, 102, 241, 0.2) 100%);
            border-color: rgba(56, 189, 248, 0.3);
        }

        .card-donasi .icon-container {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.15) 0%, rgba(244, 63, 94, 0.2) 100%);
            border-color: rgba(236, 72, 153, 0.3);
        }

        .icon-svg {
            width: 38px;
            height: 38px;
            transition: transform 0.4s ease, filter 0.4s ease;
        }

        .card-profil .icon-svg {
            color: #38bdf8;
            filter: drop-shadow(0 4px 12px rgba(56, 189, 248, 0.4));
        }

        .card-donasi .icon-svg {
            color: #fb7185;
            filter: drop-shadow(0 4px 12px rgba(244, 63, 94, 0.4));
        }

        .glass-card:hover .icon-container {
            transform: scale(1.1) rotate(-4deg);
            box-shadow: 0 15px 30px -5px var(--glow-color);
        }

        .glass-card:hover .icon-svg {
            transform: scale(1.08);
            filter: drop-shadow(0 6px 16px rgba(255, 255, 255, 0.6));
        }

        /* =========================================
           CARD CONTENT
           ========================================= */
        .card-tag {
            align-self: flex-start;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 4px 10px;
            border-radius: 6px;
            margin-bottom: 0.75rem;
        }

        .card-profil .card-tag {
            background: rgba(56, 189, 248, 0.12);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.25);
        }

        .card-donasi .card-tag {
            background: rgba(236, 72, 153, 0.12);
            color: #f472b6;
            border: 1px solid rgba(236, 72, 153, 0.25);
        }

        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #ffffff;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-desc {
            font-size: 0.95rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        /* =========================================
           ACTION BUTTON WITH SLEEK SHINE
           ========================================= */
        .card-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.4rem;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 600;
            font-size: 0.92rem;
            letter-spacing: 0.02em;
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }

        .action-text {
            color: #e2e8f0;
            transition: color 0.3s ease;
        }

        .action-arrow {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.3s ease;
            color: #94a3b8;
        }

        .glass-card:hover .card-action {
            background: linear-gradient(135deg, var(--accent-theme) 0%, var(--accent-theme-end) 100%);
            border-color: transparent;
            box-shadow: 0 8px 20px -4px var(--glow-color);
        }

        .glass-card:hover .action-text {
            color: #ffffff;
        }

        .glass-card:hover .action-arrow {
            transform: translateX(5px);
            color: #ffffff;
        }

        /* =========================================
           FOOTER
           ========================================= */
        footer {
            margin-top: 4.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .footer-tech {
            font-size: 0.78rem;
            color: #475569;
        }

        /* Responsive tweaks */
        @media (max-width: 640px) {
            body {
                padding: 2rem 1.2rem;
            }
            .cards-grid {
                grid-template-columns: 1fr;
            }
            .glass-card {
                padding: 2rem 1.6rem;
            }
        }
    </style>
</head>
<body>

    <!-- Dynamic Moving Background Ambient Scene -->
    <div class="ambient-canvas">
        <div class="bg-grid"></div>
        <div class="glow-orb orb-1"></div>
        <div class="glow-orb orb-2"></div>
        <div class="glow-orb orb-3"></div>
        
        <!-- Subtle floating particle specks -->
        <div class="floating-particle p1"></div>
        <div class="floating-particle p2"></div>
        <div class="floating-particle p3"></div>
        <div class="floating-particle p4"></div>
        <div class="floating-particle p5"></div>
    </div>

    <!-- Main Content Container -->
    <div class="main-wrapper">
        
        <!-- Header Section -->
        <header>
            <div class="badge-portal">
                <span class="pulse-dot"></span>
                <span>Workspace Hub • Web Dasar</span>
            </div>
            <h1>Project Directory</h1>
            <p class="subtitle">Pusat navigasi tugas praktikum pemrograman web terintegrasi.</p>
            <span class="student-pill">MUHAMAD SHOLCHIN TAFSIR SRILINTANG • H1101251016</span>
        </header>

        <!-- Project Cards with Glassmorphism & Bling Sparkle Effect -->
        <div class="cards-grid">
            
            <!-- CARD 1: Profil Pribadi -->
            <a href="profil/" class="glass-card card-profil" id="card-profil">
                <!-- Bling Bling Diamond Sparkles -->
                <div class="sparkle sparkle-top-right">
                    <svg class="sparkle-svg" viewBox="0 0 24 24">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                    </svg>
                </div>
                <div class="sparkle sparkle-top-left">
                    <svg class="sparkle-svg" viewBox="0 0 24 24">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                    </svg>
                </div>
                <div class="sparkle sparkle-mid-right">
                    <svg class="sparkle-svg" viewBox="0 0 24 24">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                    </svg>
                </div>
                <div class="sparkle sparkle-bottom-right">
                    <svg class="sparkle-svg" viewBox="0 0 24 24">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                    </svg>
                </div>

                <!-- Light Sweeping Sheen (Bling Glare) -->
                <div class="light-sweep"></div>
                <!-- Interactive Mouse Spotlight -->
                <div class="card-spotlight"></div>

                <!-- Custom Vector Icon: User / Developer Portfolio -->
                <div class="icon-container">
                    <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Modern Developer / User Profile SVG -->
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <polyline points="16 11 18 13 22 9"></polyline>
                    </svg>
                </div>

                <div class="card-tag">Praktikum 01</div>
                <h2 class="card-title">
                    Web Profil Pribadi
                </h2>
                <p class="card-desc">
                    Halaman profil portofolio interaktif yang memuat biodata lengkap, riwayat pendidikan, jadwal pelajaran, tabel hobi, dan form feedback.
                </p>

                <div class="card-action">
                    <span class="action-text">Kunjungi Website</span>
                    <span class="action-arrow">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </span>
                </div>
            </a>

            <!-- CARD 2: Sistem Donasi Sederhana -->
            <a href="donasi sederhana/" class="glass-card card-donasi" id="card-donasi">
                <!-- Bling Bling Diamond Sparkles -->
                <div class="sparkle sparkle-top-right">
                    <svg class="sparkle-svg" viewBox="0 0 24 24">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                    </svg>
                </div>
                <div class="sparkle sparkle-top-left">
                    <svg class="sparkle-svg" viewBox="0 0 24 24">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                    </svg>
                </div>
                <div class="sparkle sparkle-mid-right">
                    <svg class="sparkle-svg" viewBox="0 0 24 24">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                    </svg>
                </div>
                <div class="sparkle sparkle-bottom-right">
                    <svg class="sparkle-svg" viewBox="0 0 24 24">
                        <path d="M12 0L14.5 9.5L24 12L14.5 14.5L12 24L9.5 14.5L0 12L9.5 9.5L12 0Z"/>
                    </svg>
                </div>

                <!-- Light Sweeping Sheen (Bling Glare) -->
                <div class="light-sweep"></div>
                <!-- Interactive Mouse Spotlight -->
                <div class="card-spotlight"></div>

                <!-- Custom Vector Icon: Giving / Heart & Donation -->
                <div class="icon-container">
                    <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Modern Heart & Giving Hands SVG -->
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                        <path d="M12 5v6"></path>
                        <path d="M9 8h6"></path>
                    </svg>
                </div>

                <div class="card-tag">Praktikum 02 • PHP & MySQL</div>
                <h2 class="card-title">
                    Sistem Donasi Online
                </h2>
                <p class="card-desc">
                    Aplikasi manajemen transaksi donasi berbasis PHP dan MySQL database. Dilengkapi form pengiriman, rekap tabel riwayat, dan aksi hapus data.
                </p>

                <div class="card-action">
                    <span class="action-text">Kunjungi Website</span>
                    <span class="action-arrow">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </span>
                </div>
            </a>

        </div>

        <!-- Footer Info -->
        <footer>
            <p>&copy; 2026 Praktikum Pemrograman Web Dasar. Dikembangkan untuk keperluan perkuliahan.</p>
            <p class="footer-tech">Glassmorphism UI • SVG Icons • Hardware Accelerated CSS Animations</p>
        </footer>

    </div>

    <!-- Mouse Tracking for Dynamic Spotlight Reflex Effect -->
    <script>
        document.querySelectorAll('.glass-card').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    </script>
</body>
</html>
