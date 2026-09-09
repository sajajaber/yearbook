    @extends('public.layout')

    @section('title', 'Yearbook Home')

    @section('extra-css')

    <style>
        /* =========================================================
        DIGITAL YEARBOOK — EDITORIAL DESIGN SYSTEM
        ========================================================= */

        :root {
            --yb-ink: #071a33;
            --yb-blue: #0b3b72;
            --yb-blue-soft: #eaf2fa;
            --yb-gold: #d7a83e;
            --yb-gold-light: #f4dfaa;
            --yb-paper: #f5f2eb;
            --yb-white: #ffffff;
            --yb-muted: #687589;
            --yb-line: rgba(7, 26, 51, 0.14);
            --yb-dark-line: rgba(255, 255, 255, 0.18);
            --yb-serif: "Merriweather", Georgia, serif;
            --yb-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        .yearbook-wrapper {
            background: var(--yb-paper);
            color: var(--yb-ink);
            font-family: var(--yb-sans);
            overflow: hidden;
        }

        .yb-container {
            width: min(1240px, calc(100% - 48px));
            margin: 0 auto;
        }

        /* =========================================================
        HERO / COVER
        ========================================================= */

        .yb-cover {
            min-height: min(900px, 92vh);
            background: var(--yb-ink);
            color: var(--yb-white);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: stretch;
        }

        .yb-cover::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg,
                    rgba(7, 26, 51, 0.97) 0%,
                    rgba(7, 26, 51, 0.82) 42%,
                    rgba(7, 26, 51, 0.28) 72%,
                    rgba(7, 26, 51, 0.52) 100%);
            z-index: 2;
            pointer-events: none;
        }

        .yb-cover-image {
            position: absolute;
            inset: 0;
            z-index: 1;
        }

        .yb-cover-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: saturate(0.82);
            transform: scale(1.02);
        }

        .yb-cover-empty {
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 75% 30%, rgba(215, 168, 62, 0.18), transparent 25%),
                linear-gradient(135deg, #071a33, #0b3b72);
        }

        .yb-cover-grid {
            position: absolute;
            inset: 0;
            z-index: 3;
            pointer-events: none;
            opacity: 0.25;
            background-image:
                linear-gradient(rgba(255, 255, 255, .08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .08) 1px, transparent 1px);
            background-size: 80px 80px;
        }

        .yb-cover-inner {
            position: relative;
            z-index: 4;
            width: min(1240px, calc(100% - 48px));
            margin: 0 auto;
            min-height: min(900px, 92vh);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 42px 0 50px;
        }

        .yb-cover-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .yb-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--yb-white);
        }

        .yb-brand-mark {
            width: 48px;
            height: 48px;
            border: 1px solid rgba(255, 255, 255, .55);
            display: grid;
            place-items: center;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .yb-brand-text {
            font-size: 0.68rem;
            line-height: 1.4;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .yb-edition {
            text-align: right;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, .72);
        }

        .yb-edition strong {
            display: block;
            color: var(--yb-gold-light);
            font-size: 0.82rem;
            margin-top: 5px;
        }

        .yb-cover-main {
            max-width: 920px;
            margin-top: auto;
            margin-bottom: auto;
            padding: 80px 0;
        }

        .yb-cover-kicker {
            display: flex;
            align-items: center;
            gap: 14px;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--yb-gold-light);
            margin-bottom: 24px;
        }

        .yb-cover-kicker::before {
            content: "";
            width: 42px;
            height: 1px;
            background: var(--yb-gold);
        }

        .yb-cover-title {
            font-family: var(--yb-serif);
            font-size: clamp(4rem, 10vw, 9.5rem);
            line-height: 0.84;
            letter-spacing: -0.065em;
            font-weight: 900;
            margin: 0;
            max-width: 950px;
        }

        .yb-cover-title span {
            color: var(--yb-gold-light);
            font-style: italic;
            font-weight: 400;
        }

        .yb-cover-description {
            max-width: 520px;
            margin-top: 34px;
            font-size: 1.05rem;
            line-height: 1.75;
            color: rgba(255, 255, 255, .76);
        }

        .yb-cover-actions {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-top: 34px;
            flex-wrap: wrap;
        }

        .yb-cover-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 14px 22px;
            background: var(--yb-gold);
            color: var(--yb-ink);
            text-decoration: none;
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            transition: transform .25s ease, background .25s ease;
        }

        .yb-cover-link:hover {
            transform: translateY(-3px);
            background: var(--yb-gold-light);
        }

        .yb-cover-link-arrow {
            font-size: 1rem;
        }

        .yb-cover-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-top: 1px solid var(--yb-dark-line);
            padding-top: 18px;
        }

        .yb-cover-caption {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.7px;
            color: rgba(255, 255, 255, .55);
        }

        .yb-cover-scroll {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, .65);
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .yb-scroll-line {
            width: 55px;
            height: 1px;
            background: rgba(255, 255, 255, .5);
        }

        /* =========================================================
        INTRODUCTION
        ========================================================= */

        .yb-introduction {
            padding: 130px 0 110px;
            background: var(--yb-paper);
        }

        .yb-intro-grid {
            display: grid;
            grid-template-columns: 0.7fr 1.3fr;
            gap: 80px;
            align-items: start;
        }

        .yb-overline {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--yb-gold);
            margin-bottom: 16px;
        }

        .yb-intro-side {
            position: sticky;
            top: 110px;
        }

        .yb-intro-number {
            font-family: var(--yb-serif);
            font-size: clamp(5rem, 10vw, 9rem);
            line-height: .75;
            color: var(--yb-blue);
            font-weight: 900;
            letter-spacing: -0.08em;
            margin: 20px 0;
        }

        .yb-intro-side p {
            max-width: 260px;
            color: var(--yb-muted);
            font-size: .86rem;
            line-height: 1.7;
        }

        .yb-intro-heading {
            font-family: var(--yb-serif);
            font-size: clamp(2.3rem, 5vw, 5rem);
            line-height: 1.02;
            letter-spacing: -.055em;
            margin: 0;
            max-width: 760px;
        }

        .yb-intro-heading em {
            color: var(--yb-blue);
            font-weight: 400;
        }

        .yb-intro-copy {
            max-width: 680px;
            margin-top: 32px;
            font-size: 1.05rem;
            line-height: 1.9;
            color: var(--yb-muted);
        }

        /* =========================================================
        NUMBERS
        ========================================================= */

        .yb-numbers {
            background: var(--yb-blue);
            color: var(--yb-white);
            padding: 75px 0;
            position: relative;
        }

        .yb-numbers::after {
            content: "THE YEAR";
            position: absolute;
            right: -10px;
            bottom: -34px;
            font-size: clamp(5rem, 14vw, 13rem);
            font-weight: 900;
            letter-spacing: -.08em;
            color: rgba(255, 255, 255, .035);
            pointer-events: none;
            line-height: .8;
        }

        .yb-number-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        .yb-number-item {
            padding: 10px 50px;
            border-left: 1px solid var(--yb-dark-line);
        }

        .yb-number-item:first-child {
            border-left: 0;
            padding-left: 0;
        }

        .yb-number-value {
            font-family: var(--yb-serif);
            font-size: clamp(3.3rem, 6vw, 6rem);
            line-height: .9;
            letter-spacing: -.06em;
            color: var(--yb-gold-light);
            margin-bottom: 15px;
        }

        .yb-number-label {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: .68rem;
            font-weight: 800;
            color: rgba(255, 255, 255, .7);
        }

        /* =========================================================
        YEAR TIMELINE
        ========================================================= */

        .yb-timeline-section {
            padding: 135px 0 145px;
            background: var(--yb-paper);
        }

        .yb-section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 30px;
            margin-bottom: 80px;
        }

        .yb-section-header h2 {
            font-family: var(--yb-serif);
            font-size: clamp(2.7rem, 6vw, 6rem);
            line-height: .9;
            letter-spacing: -.065em;
            margin: 0;
        }

        .yb-section-header h2 em {
            color: var(--yb-blue);
            font-weight: 400;
        }

        .yb-section-header-note {
            max-width: 300px;
            color: var(--yb-muted);
            font-size: .82rem;
            line-height: 1.65;
            text-align: right;
        }

        .yb-timeline {
            position: relative;
            padding-left: 95px;
        }

        .yb-timeline::before {
            content: "";
            position: absolute;
            left: 25px;
            top: 0;
            bottom: 0;
            width: 1px;
            background: var(--yb-line);
        }

        .yb-event {
            position: relative;
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 45px;
            padding: 0 0 75px;
        }

        .yb-event:last-child {
            padding-bottom: 0;
        }

        .yb-event::before {
            content: "";
            position: absolute;
            left: -75px;
            top: 5px;
            width: 11px;
            height: 11px;
            background: var(--yb-paper);
            border: 2px solid var(--yb-gold);
            border-radius: 50%;
            box-shadow: 0 0 0 7px var(--yb-paper);
        }

        .yb-event-date {
            font-family: var(--yb-serif);
            font-size: 1.05rem;
            color: var(--yb-blue);
            line-height: 1.3;
        }

        .yb-event-date span {
            display: block;
            font-family: var(--yb-sans);
            text-transform: uppercase;
            font-size: .62rem;
            font-weight: 800;
            letter-spacing: 1.8px;
            color: var(--yb-muted);
            margin-bottom: 7px;
        }

        .yb-event-content {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 270px;
            gap: 30px;
            align-items: start;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--yb-line);
        }

        .yb-event:last-child .yb-event-content {
            border-bottom: 0;
        }

        .yb-event-title {
            font-family: var(--yb-serif);
            font-size: clamp(1.5rem, 2.5vw, 2.4rem);
            line-height: 1.12;
            letter-spacing: -.04em;
            margin: 0 0 13px;
        }

        .yb-event-description {
            font-size: .88rem;
            line-height: 1.7;
            color: var(--yb-muted);
            max-width: 600px;
            margin: 0;
        }

        .yb-event-image {
            width: 100%;
            aspect-ratio: 1.35 / 1;
            object-fit: cover;
            display: block;
            filter: saturate(.88);
        }

        .yb-event-placeholder {
            width: 100%;
            aspect-ratio: 1.35 / 1;
            background: var(--yb-blue-soft);
            display: grid;
            place-items: center;
            color: var(--yb-blue);
            font-size: .65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .yb-event-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--yb-blue);
            text-decoration: none;
            text-transform: uppercase;
            font-size: .65rem;
            letter-spacing: 1.5px;
            font-weight: 800;
            margin-top: 22px;
        }

        /* =========================================================
        PEOPLE / GRADUATES
        ========================================================= */

        .yb-people {
            background: var(--yb-white);
            padding: 130px 0;
        }

        .yb-people-heading {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            align-items: end;
            margin-bottom: 70px;
        }

        .yb-people-heading h2 {
            font-family: var(--yb-serif);
            font-size: clamp(3rem, 7vw, 7rem);
            line-height: .84;
            letter-spacing: -.07em;
            margin: 0;
        }

        .yb-people-heading h2 span {
            color: var(--yb-blue);
            font-style: italic;
            font-weight: 400;
        }

        .yb-people-heading p {
            max-width: 410px;
            margin: 0 0 5px auto;
            color: var(--yb-muted);
            font-size: .9rem;
            line-height: 1.8;
        }

        .yb-graduation-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 18px;
        }

        .yb-graduation-card {
            position: relative;
            min-height: 330px;
            overflow: hidden;
            text-decoration: none;
            color: var(--yb-white);
            background: var(--yb-blue);
        }

        .yb-graduation-card:nth-child(1) {
            grid-column: span 7;
        }

        .yb-graduation-card:nth-child(2) {
            grid-column: span 5;
        }

        .yb-graduation-card:nth-child(3) {
            grid-column: span 5;
        }

        .yb-graduation-card:nth-child(4) {
            grid-column: span 7;
        }

        .yb-graduation-image,
        .yb-graduation-placeholder {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .yb-graduation-image {
            object-fit: cover;
            transition: transform .7s cubic-bezier(.16, 1, .3, 1);
        }

        .yb-graduation-card:hover .yb-graduation-image {
            transform: scale(1.06);
        }

        .yb-graduation-placeholder {
            background: linear-gradient(135deg, var(--yb-blue), var(--yb-ink));
        }

        .yb-graduation-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg,
                    rgba(7, 26, 51, 0) 25%,
                    rgba(7, 26, 51, .86) 100%);
        }

        .yb-graduation-info {
            position: absolute;
            z-index: 2;
            left: 28px;
            right: 28px;
            bottom: 26px;
        }

        .yb-graduation-date {
            font-size: .63rem;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: var(--yb-gold-light);
            font-weight: 800;
            display: block;
            margin-bottom: 8px;
        }

        .yb-graduation-title {
            font-family: var(--yb-serif);
            font-size: clamp(1.25rem, 2.5vw, 2rem);
            line-height: 1.05;
            margin: 0;
        }

        .yb-graduation-arrow {
            position: absolute;
            z-index: 3;
            top: 24px;
            right: 24px;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(255, 255, 255, .45);
            display: grid;
            place-items: center;
            font-size: 1rem;
        }

        /* =========================================================
        MOMENTS / PHOTO MOSAIC
        ========================================================= */

        .yb-moments {
            padding: 135px 0;
            background: var(--yb-paper);
        }

        .yb-moments-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 55px;
        }

        .yb-moments-header h2 {
            font-family: var(--yb-serif);
            font-size: clamp(3rem, 7vw, 7rem);
            line-height: .85;
            letter-spacing: -.07em;
            margin: 0;
        }

        .yb-moments-header p {
            color: var(--yb-muted);
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            margin: 0;
        }

        .yb-photo-mosaic {
            display: grid;
            grid-template-columns: 1.1fr .65fr .95fr;
            grid-template-rows: 250px 180px;
            gap: 15px;
        }

        .yb-mosaic-item {
            position: relative;
            overflow: hidden;
            background: var(--yb-blue-soft);
        }

        .yb-mosaic-item:nth-child(1) {
            grid-row: span 2;
        }

        .yb-mosaic-item:nth-child(2) {
            grid-row: span 1;
        }

        .yb-mosaic-item:nth-child(3) {
            grid-row: span 2;
        }

        .yb-mosaic-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .7s cubic-bezier(.16, 1, .3, 1);
        }

        .yb-mosaic-item:hover img {
            transform: scale(1.06);
        }

        .yb-mosaic-label {
            position: absolute;
            left: 16px;
            bottom: 15px;
            background: var(--yb-white);
            color: var(--yb-ink);
            padding: 8px 11px;
            font-size: .6rem;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            font-weight: 800;
        }

        /* =========================================================
        CAMPUS / WORLD
        ========================================================= */

        .yb-campus {
            background: var(--yb-ink);
            color: var(--yb-white);
            padding: 130px 0;
            position: relative;
        }

        .yb-campus-header {
            display: grid;
            grid-template-columns: .65fr 1.35fr;
            gap: 70px;
            margin-bottom: 70px;
        }

        .yb-campus-index {
            font-family: var(--yb-serif);
            font-size: clamp(5rem, 10vw, 10rem);
            line-height: .75;
            color: var(--yb-gold-light);
            letter-spacing: -.08em;
        }

        .yb-campus-header h2 {
            font-family: var(--yb-serif);
            font-size: clamp(3rem, 6vw, 6rem);
            line-height: .9;
            letter-spacing: -.07em;
            margin: 0;
        }

        .yb-campus-header h2 em {
            color: var(--yb-gold-light);
            font-weight: 400;
        }

        .yb-campus-header p {
            color: rgba(255, 255, 255, .62);
            max-width: 600px;
            line-height: 1.8;
            font-size: .9rem;
            margin-top: 25px;
        }

        .yb-campus-strip {
            display: flex;
            overflow-x: auto;
            gap: 14px;
            padding-bottom: 15px;
            scrollbar-width: thin;
            scrollbar-color: var(--yb-gold) transparent;
        }

        .yb-campus-strip::-webkit-scrollbar {
            height: 3px;
        }

        .yb-campus-strip::-webkit-scrollbar-thumb {
            background: var(--yb-gold);
        }

        .yb-campus-name {
            flex: 0 0 auto;
            padding: 20px 27px;
            border: 1px solid var(--yb-dark-line);
            color: rgba(255, 255, 255, .7);
            text-transform: uppercase;
            letter-spacing: 1.8px;
            font-size: .68rem;
            font-weight: 800;
            white-space: nowrap;
            transition: all .25s ease;
        }

        .yb-campus-name:hover {
            border-color: var(--yb-gold);
            color: var(--yb-gold-light);
            transform: translateY(-3px);
        }

        /* =========================================================
        ARCHIVE
        ========================================================= */

        .yb-archive {
            background: var(--yb-gold);
            color: var(--yb-ink);
            padding: 120px 0 130px;
        }

        .yb-archive-inner {
            display: grid;
            grid-template-columns: 1fr .7fr;
            gap: 100px;
            align-items: end;
        }

        .yb-archive-heading {
            font-family: var(--yb-serif);
            font-size: clamp(3.5rem, 8vw, 8rem);
            line-height: .82;
            letter-spacing: -.075em;
            margin: 0;
        }

        .yb-archive-heading em {
            font-weight: 400;
        }

        .yb-archive-copy {
            max-width: 390px;
            font-size: .9rem;
            line-height: 1.8;
            margin: 28px 0 0;
        }

        .yb-archive-link {
            display: inline-flex;
            align-items: center;
            gap: 16px;
            margin-top: 30px;
            padding: 17px 25px;
            border: 1px solid var(--yb-ink);
            color: var(--yb-ink);
            text-decoration: none;
            text-transform: uppercase;
            font-size: .66rem;
            font-weight: 900;
            letter-spacing: 1.7px;
            transition: background .25s ease, color .25s ease;
        }

        .yb-archive-link:hover {
            background: var(--yb-ink);
            color: var(--yb-gold-light);
        }

        .yb-archive-years {
            border-top: 1px solid rgba(7, 26, 51, .3);
        }

        .yb-archive-year {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 19px 0;
            border-bottom: 1px solid rgba(7, 26, 51, .3);
            text-decoration: none;
            color: var(--yb-ink);
            font-family: var(--yb-serif);
            font-size: 1.35rem;
            transition: padding .25s ease;
        }

        .yb-archive-year:hover {
            padding-left: 10px;
        }

        .yb-archive-year span:last-child {
            font-family: var(--yb-sans);
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* =========================================================
        FOOTER
        ========================================================= */

        .yb-footer {
            background: var(--yb-ink);
            color: var(--yb-white);
            padding: 70px 0 35px;
        }

        .yb-footer-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 50px;
            padding-bottom: 60px;
            border-bottom: 1px solid var(--yb-dark-line);
        }

        .yb-footer-title {
            font-family: var(--yb-serif);
            font-size: clamp(3rem, 8vw, 8rem);
            line-height: .8;
            letter-spacing: -.07em;
            margin: 0;
        }

        .yb-footer-title span {
            color: var(--yb-gold-light);
            font-style: italic;
            font-weight: 400;
        }

        .yb-footer-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 15px;
        }

        .yb-footer-actions p {
            color: rgba(255, 255, 255, .55);
            font-size: .78rem;
            max-width: 280px;
            line-height: 1.7;
            text-align: right;
            margin: 0;
        }

        .yb-footer-mail {
            color: var(--yb-gold-light);
            text-decoration: none;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            font-weight: 800;
        }

        .yb-footer-bottom {
            padding-top: 25px;
            display: flex;
            justify-content: space-between;
            color: rgba(255, 255, 255, .4);
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* =========================================================
        RESPONSIVE
        ========================================================= */

        @media (max-width: 900px) {

            .yb-intro-grid,
            .yb-campus-header,
            .yb-archive-inner {
                grid-template-columns: 1fr;
                gap: 45px;
            }

            .yb-intro-side {
                position: static;
            }

            .yb-number-grid {
                grid-template-columns: 1fr;
                gap: 35px;
            }

            .yb-number-item,
            .yb-number-item:first-child {
                border-left: 0;
                border-top: 1px solid var(--yb-dark-line);
                padding: 30px 0 0;
            }

            .yb-number-item:first-child {
                border-top: 0;
                padding-top: 0;
            }

            .yb-section-header,
            .yb-moments-header {
                display: block;
            }

            .yb-section-header-note {
                text-align: left;
                margin-top: 20px;
            }

            .yb-timeline {
                padding-left: 45px;
            }

            .yb-timeline::before {
                left: 5px;
            }

            .yb-event {
                grid-template-columns: 1fr;
                gap: 20px;
                padding-bottom: 55px;
            }

            .yb-event::before {
                left: -45px;
            }

            .yb-event-content {
                grid-template-columns: 1fr;
            }

            .yb-people-heading {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .yb-people-heading p {
                margin-left: 0;
            }

            .yb-graduation-card:nth-child(n) {
                grid-column: span 12;
            }

            .yb-photo-mosaic {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 260px 200px;
            }

            .yb-mosaic-item:nth-child(1),
            .yb-mosaic-item:nth-child(3) {
                grid-row: span 2;
            }

            .yb-footer-top {
                display: block;
            }

            .yb-footer-actions {
                align-items: flex-start;
                margin-top: 45px;
            }

            .yb-footer-actions p {
                text-align: left;
            }
        }

        @media (max-width: 600px) {

            .yb-container,
            .yb-cover-inner {
                width: min(100% - 30px, 1240px);
            }

            .yb-cover {
                min-height: 760px;
            }

            .yb-cover-inner {
                min-height: 760px;
                padding-top: 25px;
                padding-bottom: 30px;
            }

            .yb-cover-top {
                align-items: flex-start;
            }

            .yb-edition {
                display: none;
            }

            .yb-cover-title {
                font-size: clamp(3.7rem, 19vw, 6rem);
            }

            .yb-cover-description {
                font-size: .92rem;
            }

            .yb-introduction,
            .yb-timeline-section,
            .yb-people,
            .yb-moments,
            .yb-campus {
                padding: 90px 0;
            }

            .yb-numbers {
                padding: 60px 0;
            }

            .yb-photo-mosaic {
                display: flex;
                flex-direction: column;
            }

            .yb-mosaic-item {
                min-height: 260px;
            }

            .yb-cover-bottom {
                display: block;
            }

            .yb-cover-scroll {
                margin-top: 18px;
            }

            .yb-footer-bottom {
                display: block;
                line-height: 2;
            }
        }

        /* Reduced motion accessibility */

        /* =========================================================
   Page transitions + scroll reveals
   ========================================================= */
        .yb-cover {
            height: 100svh;
            min-height: 0;
            max-height: 100svh;
            overflow: hidden;
        }

        .yb-cover-image,
        .yb-cover-image img {
            height: 100%;
        }

        .yb-cover-image img {
            object-fit: cover;
        }

        /* Navbar: transparent over the hero, visible after scrolling. */
        .yb-nav {
            background: transparent !important;
            box-shadow: none !important;
            transition:
                background-color .35s ease,
                backdrop-filter .35s ease,
                -webkit-backdrop-filter .35s ease,
                box-shadow .35s ease,
                padding .35s ease,
                height .35s ease;
        }

        .yb-nav.is-scrolled {
            background: rgba(20, 20, 20, .90) !important;
            -webkit-backdrop-filter: blur(12px);
            backdrop-filter: blur(12px);
            box-shadow: 0 6px 24px rgba(0, 0, 0, .12) !important;
        }

        /* Slightly reduce navbar vertical space after scrolling. */
        .yb-nav.is-scrolled {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }

        /* First-load animation. */
        .yearbook-wrapper.page-animations .yb-cover-image img {
            animation: ybHeroImageIn 1.25s cubic-bezier(.16, 1, .3, 1) both;
        }

        .yearbook-wrapper.page-animations .yb-cover-kicker {
            animation: ybFadeUp .75s .08s cubic-bezier(.16, 1, .3, 1) both;
        }

        .yearbook-wrapper.page-animations .yb-cover-title {
            animation: ybFadeUp .85s .18s cubic-bezier(.16, 1, .3, 1) both;
        }

        .yearbook-wrapper.page-animations .yb-cover-description {
            animation: ybFadeUp .8s .32s cubic-bezier(.16, 1, .3, 1) both;
        }

        .yearbook-wrapper.page-animations .yb-cover-actions {
            animation: ybFadeUp .8s .45s cubic-bezier(.16, 1, .3, 1) both;
        }

        .yearbook-wrapper.page-animations .yb-cover-bottom {
            animation: ybFadeUp .8s .58s cubic-bezier(.16, 1, .3, 1) both;
        }

        @keyframes ybHeroImageIn {
            from {
                opacity: 0;
                transform: scale(1.07);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes ybFadeUp {
            from {
                opacity: 0;
                transform: translateY(26px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scroll reveal. */
        .scroll-animations-ready .yb-reveal {
            opacity: 0;
            transform: translateY(38px);
            transition:
                opacity .75s ease,
                transform .85s cubic-bezier(.16, 1, .3, 1);
            transition-delay: var(--yb-delay, 0ms);
            will-change: opacity, transform;
        }

        .scroll-animations-ready .yb-reveal.is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }

        .yb-scroll-pulse {
            animation: ybScrollPulse 2.2s ease-in-out infinite;
        }

        @keyframes ybScrollPulse {

            0%,
            100% {
                opacity: .55;
                transform: translateY(0);
            }

            50% {
                opacity: 1;
                transform: translateY(5px);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .yearbook-wrapper.page-animations .yb-cover-image img,
            .yearbook-wrapper.page-animations .yb-cover-kicker,
            .yearbook-wrapper.page-animations .yb-cover-title,
            .yearbook-wrapper.page-animations .yb-cover-description,
            .yearbook-wrapper.page-animations .yb-cover-actions,
            .yearbook-wrapper.page-animations .yb-cover-bottom {
                animation: none !important;
            }

            .scroll-animations-ready .yb-reveal {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }

            .yb-scroll-pulse {
                animation: none !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
            }
        }
    </style>

    @endsection

    @section('content')

    <div class="yearbook-wrapper">

        {{-- =====================================================
        01 — COVER
        ===================================================== --}}
        <section class="yb-cover" id="top">

            <div class="yb-cover-image">
                @if($heroImages->isNotEmpty())
                <img
                    src="{{ asset('storage/' . $heroImages->first()->path) }}"
                    alt="Yearbook collection">
                @else
                <div class="yb-cover-empty"></div>
                @endif
            </div>

            <div class="yb-cover-grid"></div>

            <div class="yb-cover-inner">

                <div class="yb-cover-top">

                    <a href="{{ url('/') }}" class="yb-brand">
                        <div class="yb-brand-mark">LIU</div>
                        <div class="yb-brand-text">
                            Digital<br>
                            Yearbook
                        </div>
                    </a>

                    <div class="yb-edition">
                        Current edition
                        <strong>
                            {{ $currentYear->title ?? 'Archive Edition' }}
                        </strong>
                    </div>

                </div>


                <div class="yb-cover-main">

                    <div class="yb-cover-kicker">
                        {{ $currentYear->title ?? 'Archive Edition' }}
                    </div>

                    @if($currentYear)
                    <h1 class="yb-cover-title">
                        A year<br>
                        <span>worth</span><br>
                        remembering.
                    </h1>
                    @else
                    <h1 class="yb-cover-title">
                        The<br>
                        <span>yearbook</span><br>
                        archive.
                    </h1>
                    @endif

                    <p class="yb-cover-description">
                        A living collection of the people, places, celebrations,
                        and moments that shaped our university year.
                    </p>

                    <div class="yb-cover-actions">

                        <a
                            href="{{ route('public.timeline') }}"
                            class="yb-cover-link">
                            Enter the yearbook
                            <span class="yb-cover-link-arrow">↘</span>
                        </a>

                        <a
                            href="{{ route('public.graduates') }}"
                            style="color: rgba(255,255,255,.75); text-decoration:none; font-size:.68rem; text-transform:uppercase; letter-spacing:1.5px; font-weight:800;">
                            Meet the class →
                        </a>

                    </div>

                </div>


                <div class="yb-cover-bottom">

                    <div class="yb-cover-caption">
                        People · Places · Moments · 2026
                    </div>

                    <div class="yb-cover-scroll">
                        Scroll to explore
                        <span class="yb-scroll-line"></span>
                    </div>

                </div>

            </div>
        </section>


        {{-- =====================================================
        02 — INTRODUCTION
        ===================================================== --}}
        <section class="yb-introduction">

            <div class="yb-container">

                <div class="yb-intro-grid">

                    <div class="yb-intro-side">

                        <div class="yb-overline">
                            The year in perspective
                        </div>

                        <div class="yb-intro-number">
                            {{ $currentYear ? '01' : '00' }}
                        </div>

                        <p>
                            Every academic year leaves behind more than dates
                            and ceremonies. It leaves stories.
                        </p>

                    </div>


                    <div>

                        <h2 class="yb-intro-heading">
                            This is more than an archive.
                            <em>This is what the year looked like.</em>
                        </h2>

                        <p class="yb-intro-copy">
                            Explore the people who graduated, the events that
                            brought the community together, and the moments that
                            became part of our shared history.
                        </p>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
        03 — NUMBERS
        ===================================================== --}}
        <section class="yb-numbers">

            <div class="yb-container">

                <div class="yb-number-grid">

                    <div class="yb-number-item">
                        <div class="yb-number-value">
                            {{ $stats['undergraduates'] ?? 0 }}
                        </div>
                        <div class="yb-number-label">
                            Undergraduate graduates
                        </div>
                    </div>

                    <div class="yb-number-item">
                        <div class="yb-number-value">
                            {{ $stats['graduates'] ?? 0 }}
                        </div>
                        <div class="yb-number-label">
                            Postgraduate graduates
                        </div>
                    </div>

                    <div class="yb-number-item">
                        <div class="yb-number-value">
                            {{ $stats['events'] ?? 0 }}
                        </div>
                        <div class="yb-number-label">
                            Campus events
                        </div>
                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
        04 — THE YEAR / EVENTS TIMELINE
        ===================================================== --}}
        @if($featuredEvents->isNotEmpty())

        <section class="yb-timeline-section" id="timeline">

            <div class="yb-container">

                <div class="yb-section-header">

                    <div>
                        <div class="yb-overline">
                            Chapter one
                        </div>

                        <h2>
                            The year<br>
                            <em>unfolds.</em>
                        </h2>
                    </div>

                    <p class="yb-section-header-note">
                        A selection of moments, celebrations and events
                        that defined the academic year.
                    </p>

                </div>


                <div class="yb-timeline">

                    @foreach($featuredEvents as $event)

                    <article class="yb-event">

                        <div class="yb-event-date">

                            <span>
                                Event
                            </span>

                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d') }}

                        </div>


                        <div class="yb-event-content">

                            <div>

                                <h3 class="yb-event-title">
                                    {{ $event->title }}
                                </h3>

                                @if($event->description)
                                <p class="yb-event-description">
                                    {{ Str::limit($event->description, 180) }}
                                </p>
                                @endif

                                <a
                                    href="{{ route('public.event.detail', $event->id) }}"
                                    class="yb-event-link">
                                    View this moment
                                    <span>→</span>
                                </a>

                            </div>


                            <div>

                                @php
                                $image = $event->media->first();
                                @endphp

                                @if($image)

                                <img
                                    src="{{ asset('storage/' . $image->path) }}"
                                    alt="{{ $event->title }}"
                                    class="yb-event-image"
                                    loading="lazy">

                                @else

                                <div class="yb-event-placeholder">
                                    No image available
                                </div>

                                @endif

                            </div>

                        </div>

                    </article>

                    @endforeach

                </div>

            </div>

        </section>

        @endif


        {{-- =====================================================
        05 — GRADUATIONS / PEOPLE
        ===================================================== --}}
        @if($graduations->isNotEmpty())

        <section class="yb-people" id="people">

            <div class="yb-container">

                <div class="yb-people-heading">

                    <div>
                        <div class="yb-overline">
                            Chapter two
                        </div>

                        <h2>
                            The<br>
                            <span>people.</span>
                        </h2>
                    </div>

                    <p>
                        Behind every ceremony is a collection of people,
                        ambitions, friendships and stories. Explore the
                        graduation moments that marked the end of one
                        chapter and the beginning of another.
                    </p>

                </div>


                <div class="yb-graduation-grid">

                    @foreach($graduations as $graduation)

                    <a
                        href="{{ route('public.graduation.detail', $graduation->id) }}"
                        class="yb-graduation-card">

                        @php
                        $image = $graduation->media->first();
                        @endphp

                        @if($image)

                        <img
                            src="{{ asset('storage/' . $image->path) }}"
                            alt="{{ $graduation->name ?? 'Graduation Ceremony' }}"
                            class="yb-graduation-image"
                            loading="lazy">

                        @else

                        <div class="yb-graduation-placeholder"></div>

                        @endif


                        <div class="yb-graduation-arrow">
                            ↗
                        </div>


                        <div class="yb-graduation-info">

                            <span class="yb-graduation-date">
                                {{ \Carbon\Carbon::parse($graduation->created_at)->format('F Y') }}
                            </span>

                            <h3 class="yb-graduation-title">
                                {{ $graduation->name ?? 'Graduation Ceremony' }}
                            </h3>

                        </div>

                    </a>

                    @endforeach

                </div>

            </div>

        </section>

        @endif


        {{-- =====================================================
        06 — MOMENTS / VISUAL ARCHIVE
        ===================================================== --}}
        @if($heroImages->isNotEmpty())

        <section class="yb-moments">

            <div class="yb-container">

                <div class="yb-moments-header">

                    <h2>
                        Moments.
                    </h2>

                    <p>
                        From the collection
                    </p>

                </div>


                <div class="yb-photo-mosaic">

                    @foreach($heroImages->take(3) as $index => $heroImage)

                    <div class="yb-mosaic-item">

                        <img
                            src="{{ asset('storage/' . $heroImage->path) }}"
                            alt="Yearbook moment {{ $index + 1 }}"
                            loading="lazy">

                        <span class="yb-mosaic-label">
                            {{ sprintf('%02d', $index + 1) }}
                            / Yearbook
                        </span>

                    </div>

                    @endforeach

                    @if($heroImages->count() < 3)

                        @for($i=$heroImages->count(); $i < 3; $i++)

                            <div class="yb-mosaic-item">

                            <div
                                style="
                                    width:100%;
                                    height:100%;
                                    background:
                                        linear-gradient(
                                            135deg,
                                            #0b3b72,
                                            #071a33
                                        );
                                "></div>

                            <span class="yb-mosaic-label">
                                {{ sprintf('%02d', $i + 1) }}
                                / Yearbook
                            </span>

                </div>

                @endfor

                @endif

            </div>

    </div>

    </section>

    @endif


    {{-- =====================================================
        07 — CAMPUSES
        ===================================================== --}}
    <section class="yb-campus">

        <div class="yb-container">

            <div class="yb-campus-header">

                <div class="yb-campus-index">
                    10
                </div>

                <div>

                    <div class="yb-overline">
                        Chapter three
                    </div>

                    <h2>
                        One university.<br>
                        <em>Many places.</em>
                    </h2>

                    <p>
                        From Lebanon to the wider world, the year was
                        experienced across campuses, communities and
                        classrooms. Explore the places that make up
                        the LIU story.
                    </p>

                </div>

            </div>


            <div class="yb-campus-strip">

                <span class="yb-campus-name">Beirut</span>
                <span class="yb-campus-name">Bekaa</span>
                <span class="yb-campus-name">Saida</span>
                <span class="yb-campus-name">Nabatieh</span>
                <span class="yb-campus-name">Tripoli</span>
                <span class="yb-campus-name">Mount Lebanon</span>
                <span class="yb-campus-name">Tyre</span>
                <span class="yb-campus-name">Rayak</span>
                <span class="yb-campus-name">Akkar</span>
                <span class="yb-campus-name">Yemen</span>
                <span class="yb-campus-name">Senegal</span>
                <span class="yb-campus-name">Mauritania</span>

            </div>

        </div>

    </section>


    {{-- =====================================================
        08 — ARCHIVE
        ===================================================== --}}
    <section class="yb-archive">

        <div class="yb-container">

            <div class="yb-archive-inner">

                <div>

                    <div class="yb-overline">
                        The collection
                    </div>

                    <h2 class="yb-archive-heading">
                        The year<br>
                        <em>doesn't end here.</em>
                    </h2>

                    <p class="yb-archive-copy">
                        Yesterday becomes history. Explore the archive
                        and discover the people, moments and milestones
                        that came before this edition.
                    </p>

                    <a
                        href="{{ route('public.archive') }}"
                        class="yb-archive-link">
                        Explore the archive
                        <span>↗</span>
                    </a>

                </div>


                <div class="yb-archive-years">

                    @if($currentYear)

                    <a
                        href="{{ route('public.timeline') }}"
                        class="yb-archive-year">
                        <span>{{ $currentYear->title }}</span>
                        <span>Current edition →</span>
                    </a>

                    @endif

                    <a
                        href="{{ route('public.archive') }}"
                        class="yb-archive-year">
                        <span>Previous editions</span>
                        <span>Explore →</span>
                    </a>

                    <a
                        href="{{ route('public.graduates') }}"
                        class="yb-archive-year">
                        <span>Graduate directory</span>
                        <span>People →</span>
                    </a>

                    <a
                        href="{{ route('public.events') }}"
                        class="yb-archive-year">
                        <span>Event collection</span>
                        <span>Moments →</span>
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        09 — FOOTER / FINAL STATEMENT
        ===================================================== --}}
    <footer class="yb-footer">

        <div class="yb-container">

            <div class="yb-footer-top">

                <h2 class="yb-footer-title">
                    Remember<br>
                    <span>this year.</span>
                </h2>


                <div class="yb-footer-actions">

                    <p>
                        Have questions about submissions, photo archives,
                        yearbook content or previous editions?
                    </p>

                    <a
                        href="mailto:yearbook@university.edu"
                        class="yb-footer-mail">
                        Contact the yearbook committee →
                    </a>

                </div>

            </div>


            <div class="yb-footer-bottom">

                <span>
                    LIU Digital Yearbook
                </span>

                <span>
                    {{ $currentYear->title ?? 'Archive Edition' }}
                </span>

                <span>
                    People · Places · Moments
                </span>

            </div>

        </div>

    </footer>
    ```

    </div>
    @endsection

    @section('extra-js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.querySelector('.yearbook-wrapper');

            /* First-entry animation. */
            if (wrapper) {
                requestAnimationFrame(function() {
                    wrapper.classList.add('page-animations');
                });
            }

            /* Navbar: transparent at the top, visible after a small scroll. */
            const nav = document.querySelector('.yb-nav');

            function updateNavbar() {
                if (!nav) return;
                nav.classList.toggle('is-scrolled', window.scrollY > 24);
            }

            updateNavbar();
            window.addEventListener('scroll', updateNavbar, {
                passive: true
            });

            /* Smooth reveal for content as it enters the viewport. */
            const revealItems = document.querySelectorAll(
                '.yb-intro-side, .yb-intro-heading, .yb-intro-copy, ' +
                '.yb-number-item, .yb-section-header, .yb-event, ' +
                '.yb-people-heading, .yb-graduation-card, .yb-moments-header, ' +
                '.yb-mosaic-item, .yb-campus-header, .yb-campus-name, ' +
                '.yb-archive-inner, .yb-footer-top'
            );

            revealItems.forEach(function(item, index) {
                item.classList.add('yb-reveal');
                item.style.setProperty(
                    '--yb-delay',
                    Math.min(index * 45, 360) + 'ms'
                );
            });

            if (!wrapper) return;

            wrapper.classList.add('scroll-animations-ready');

            if (!('IntersectionObserver' in window)) {
                revealItems.forEach(function(item) {
                    item.classList.add('is-visible');
                });
                return;
            }

            const observer = new IntersectionObserver(
                function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.12,
                    rootMargin: '0px 0px -8% 0px'
                }
            );

            revealItems.forEach(function(item) {
                observer.observe(item);
            });

            const scrollHint = document.querySelector('.yb-cover-scroll');
            if (scrollHint) {
                scrollHint.classList.add('yb-scroll-pulse');
            }
        });
    </script>

    @endsection