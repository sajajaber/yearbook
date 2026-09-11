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

        --yb-ease: cubic-bezier(.16, 1, .3, 1);
        --yb-ease-soft: cubic-bezier(.22, 1, .36, 1);
    }

    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        overflow-x: hidden;
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
        height: 100svh;
        min-height: 0;
        max-height: 100svh;
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

        transition: opacity 1.4s ease;
    }

    .yb-cover-image {
        position: absolute;
        inset: 0;
        z-index: 1;
        overflow: hidden;
    }

    .yb-cover-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;

        filter: saturate(0.82);
        transform: scale(1.08);

        will-change: transform, opacity, filter;
    }

    .yb-cover-empty {
        width: 100%;
        height: 100%;

        background:
            radial-gradient(circle at 75% 30%,
                rgba(215, 168, 62, 0.18),
                transparent 25%),
            linear-gradient(135deg,
                #071a33,
                #0b3b72);
    }

    .yb-cover-grid {
        position: absolute;
        inset: 0;
        z-index: 3;
        pointer-events: none;
        opacity: 0.25;

        background-image:
            linear-gradient(rgba(255, 255, 255, .08) 1px,
                transparent 1px),
            linear-gradient(90deg,
                rgba(255, 255, 255, .08) 1px,
                transparent 1px);

        background-size: 80px 80px;

        transition:
            transform 1.5s var(--yb-ease),
            opacity 1s ease;
    }

    .yb-cover-inner {
        position: relative;
        z-index: 4;

        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;

        min-height: 100svh;

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

        transition:
            background .35s ease,
            border-color .35s ease,
            transform .45s var(--yb-ease);
    }

    .yb-brand:hover .yb-brand-mark {
        background: var(--yb-gold);
        border-color: var(--yb-gold);
        color: var(--yb-ink);
        transform: rotate(-5deg) scale(1.05);
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
        gap: 15px;

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

        transition:
            width .8s var(--yb-ease);
    }

    .yearbook-wrapper.page-animations .yb-cover-kicker::before {
        width: 70px;
    }

    .yb-cover-title {
        font-family: var(--yb-serif);

        font-size: clamp(3.4rem, 7.5vw, 7.5rem);

        line-height: 0.95;

        letter-spacing: -0.045em;

        font-weight: 800;

        max-width: 900px;

        text-wrap: balance;
    }

    .yb-cover-title span {
        color: var(--yb-gold-light);
        font-weight: 400;
    }

    .yb-cover-description {
        max-width: 500px;

        margin-top: 28px;

        font-size: 1rem;

        line-height: 1.7;

        color: rgba(255, 255, 255, 0.78);
    }

    .yb-cover-actions {
        display: flex;
        align-items: center;
        gap: 24px;

        margin-top: 34px;

        flex-wrap: wrap;
    }

    .yb-cover-link {
        position: relative;
        overflow: hidden;

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

        transition:
            transform .45s var(--yb-ease),
            background .35s ease,
            box-shadow .35s ease;
    }

    .yb-cover-link::before {
        content: "";

        position: absolute;
        inset: 0;

        background:
            linear-gradient(110deg,
                transparent 20%,
                rgba(255, 255, 255, .28) 50%,
                transparent 80%);

        transform: translateX(-120%);

        transition:
            transform .8s var(--yb-ease);
    }

    .yb-cover-link:hover {
        transform: translateY(-4px);

        background: var(--yb-gold-light);

        box-shadow:
            0 15px 35px rgba(0, 0, 0, .18);
    }

    .yb-cover-link:hover::before {
        transform: translateX(120%);
    }

    .yb-cover-link-arrow {
        position: relative;
        z-index: 1;

        font-size: 1rem;

        transition:
            transform .45s var(--yb-ease);
    }

    .yb-cover-link:hover .yb-cover-link-arrow {
        transform: translate(4px, 4px);
    }

    .yb-cover-actions>a:last-child {
        position: relative;

        transition:
            color .3s ease,
            transform .4s var(--yb-ease);
    }

    .yb-cover-actions>a:last-child:hover {
        color: #fff !important;
        transform: translateX(4px);
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

        transition:
            width .5s var(--yb-ease);
    }

    .yb-cover-scroll:hover .yb-scroll-line {
        width: 85px;
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

        transition:
            transform .7s var(--yb-ease),
            color .4s ease;
    }

    .yb-intro-side:hover .yb-intro-number {
        transform: translateX(8px);
        color: var(--yb-gold);
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

        transition:
            transform .5s var(--yb-ease);
    }

    .yb-number-item:first-child {
        border-left: 0;
        padding-left: 0;
    }

    .yb-number-item:hover {
        transform: translateY(-5px);
    }

    .yb-number-value {
        font-family: var(--yb-serif);

        font-size: clamp(3.3rem, 6vw, 6rem);

        line-height: .9;

        letter-spacing: -.06em;

        color: var(--yb-gold-light);

        margin-bottom: 15px;

        transition:
            transform .5s var(--yb-ease),
            color .4s ease;
    }

    .yb-number-item:hover .yb-number-value {
        transform: translateY(-5px);
        color: #fff;
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

        transform-origin: top;

        transition:
            transform 1.5s var(--yb-ease);
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

        box-shadow:
            0 0 0 7px var(--yb-paper);

        transition:
            transform .45s var(--yb-ease),
            background .35s ease,
            box-shadow .35s ease;
    }

    .yb-event.is-visible::before {
        transform: scale(1.15);

        background: var(--yb-gold);

        box-shadow:
            0 0 0 7px var(--yb-paper),
            0 0 0 10px rgba(215, 168, 62, .12);
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

        transition:
            color .3s ease,
            transform .45s var(--yb-ease);
    }

    .yb-event-content:hover .yb-event-title {
        color: var(--yb-blue);
        transform: translateX(4px);
    }

    .yb-event-description {
        font-size: .88rem;

        line-height: 1.7;

        color: var(--yb-muted);

        max-width: 600px;

        margin: 0;
    }

    .yb-event-image,
    .yb-event-placeholder {
        width: 100%;

        aspect-ratio: 1.35 / 1;

        object-fit: cover;

        display: block;
    }

    .yb-event-image {
        filter: saturate(.88);

        transition:
            transform 1s var(--yb-ease),
            filter .6s ease;
    }

    .yb-event-content:hover .yb-event-image {
        transform: scale(1.025);
        filter: saturate(1);
    }

    .yb-event-placeholder {
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
        position: relative;

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

    .yb-event-link::after {
        content: "";

        position: absolute;

        left: 0;
        bottom: -6px;

        width: 100%;
        height: 1px;

        background: currentColor;

        transform: scaleX(0);

        transform-origin: right;

        transition:
            transform .45s var(--yb-ease);
    }

    .yb-event-link:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }

    .yb-event-link span {
        transition:
            transform .4s var(--yb-ease);
    }

    .yb-event-link:hover span {
        transform: translateX(5px);
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

        isolation: isolate;

        transition:
            transform .6s var(--yb-ease),
            box-shadow .6s ease;
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

    .yb-graduation-card:hover {
        transform: translateY(-8px);

        box-shadow:
            0 25px 60px rgba(7, 26, 51, .18);
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

        transition:
            transform 1.1s var(--yb-ease),
            filter .7s ease;
    }

    .yb-graduation-card:hover .yb-graduation-image {
        transform: scale(1.08);
        filter: saturate(1);
    }

    .yb-graduation-placeholder {
        background:
            linear-gradient(135deg,
                var(--yb-blue),
                var(--yb-ink));
    }

    .yb-graduation-card::after {
        content: "";

        position: absolute;

        inset: 0;

        background:
            linear-gradient(180deg,
                rgba(7, 26, 51, 0) 25%,
                rgba(7, 26, 51, .86) 100%);

        transition:
            background .6s ease;
    }

    .yb-graduation-card:hover::after {
        background:
            linear-gradient(180deg,
                rgba(7, 26, 51, .04) 15%,
                rgba(7, 26, 51, .94) 100%);
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

        transition:
            transform .45s var(--yb-ease),
            background .35s ease,
            border-color .35s ease,
            color .35s ease;
    }

    .yb-graduation-card:hover .yb-graduation-arrow {
        transform: rotate(45deg);

        background: var(--yb-gold);

        border-color: var(--yb-gold);

        color: var(--yb-ink);
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

        transition:
            box-shadow .5s ease;
    }

    .yb-mosaic-item:hover {
        box-shadow:
            0 20px 45px rgba(7, 26, 51, .15);
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

        transition:
            transform 1.1s var(--yb-ease),
            filter .7s ease;
    }

    .yb-mosaic-item:hover img {
        transform: scale(1.07);
        filter: saturate(1.05);
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

        transform: translateY(8px);

        opacity: 0;

        transition:
            transform .45s var(--yb-ease),
            opacity .35s ease;
    }

    .yb-mosaic-item:hover .yb-mosaic-label {
        transform: translateY(0);
        opacity: 1;
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

        transition:
            transform .8s var(--yb-ease);
    }

    .yb-campus:hover .yb-campus-index {
        transform: translateX(10px);
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


    /* =========================================================
        CAMPUS MARQUEE
        ========================================================= */

    .yb-campus-strip {
        position: relative;

        width: 100%;

        overflow: hidden;

        padding: 22px 0;

        border-top: 1px solid var(--yb-dark-line);
        border-bottom: 1px solid var(--yb-dark-line);

        /*
         * Soft fade at the left and right edges.
         */
        mask-image:
            linear-gradient(to right,
                transparent 0%,
                black 8%,
                black 92%,
                transparent 100%);

        -webkit-mask-image:
            linear-gradient(to right,
                transparent 0%,
                black 8%,
                black 92%,
                transparent 100%);
    }

    .yb-campus-track {
        display: flex;

        align-items: center;

        width: max-content;

        /*
         * Slow continuous movement.
         *
         * 70 seconds gives the marquee a subtle,
         * premium editorial feel.
         */
        animation:
            ybCampusMarquee 70s linear infinite;

        will-change: transform;
    }

    .yb-campus-name {
        flex: 0 0 auto;

        padding: 20px 27px;

        margin-right: 14px;

        border: 1px solid var(--yb-dark-line);

        color: rgba(255, 255, 255, .7);

        text-transform: uppercase;

        letter-spacing: 1.8px;

        font-size: .68rem;

        font-weight: 800;

        white-space: nowrap;

        transition:
            border-color .3s ease,
            color .3s ease,
            background .3s ease,
            transform .35s var(--yb-ease);
    }

    .yb-campus-name:hover {
        border-color: var(--yb-gold);

        color: var(--yb-gold-light);

        background: rgba(215, 168, 62, .08);

        transform: translateY(-5px);
    }

    /*
     * The two identical campus lists create the
     * seamless infinite loop.
     */
    @keyframes ybCampusMarquee {

        from {
            transform: translateX(-50%);
        }

        to {
            transform: translateX(0);
        }

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

        transition:
            background .35s ease,
            color .35s ease,
            transform .45s var(--yb-ease);
    }

    .yb-archive-link:hover {
        background: var(--yb-ink);

        color: var(--yb-gold-light);

        transform: translateY(-4px);
    }

    .yb-archive-link span {
        transition:
            transform .45s var(--yb-ease);
    }

    .yb-archive-link:hover span {
        transform: translate(4px, -4px);
    }

    .yb-archive-years {
        border-top: 1px solid rgba(7, 26, 51, .3);
    }

    .yb-archive-year {
        position: relative;

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 19px 0;

        border-bottom: 1px solid rgba(7, 26, 51, .3);

        text-decoration: none;

        color: var(--yb-ink);

        font-family: var(--yb-serif);

        font-size: 1.35rem;

        transition:
            padding-left .5s var(--yb-ease),
            background .35s ease;
    }

    .yb-archive-year::before {
        content: "";

        position: absolute;

        left: 0;
        bottom: 0;

        width: 0;
        height: 2px;

        background: var(--yb-ink);

        transition:
            width .55s var(--yb-ease);
    }

    .yb-archive-year:hover {
        padding-left: 14px;
    }

    .yb-archive-year:hover::before {
        width: 100%;
    }

    .yb-archive-year span:last-child {
        font-family: var(--yb-sans);

        font-size: .65rem;

        text-transform: uppercase;

        letter-spacing: 1.5px;

        transition:
            transform .4s var(--yb-ease);
    }

    .yb-archive-year:hover span:last-child {
        transform: translateX(5px);
    }


    /* =========================================================
        FOOTER
        ========================================================= */

    .public-footer {
        position: relative;

        margin-top: auto;

        overflow: hidden;

        background:
            linear-gradient(135deg,
                #002a5c 0%,
                #001d42 100%);

        color: #fff;

        border-top: 4px solid #ffb034;
    }

    .public-footer::before {
        content: "";

        position: absolute;

        width: 420px;
        height: 420px;

        right: -180px;
        top: -250px;

        border: 1px solid rgba(255, 176, 52, .18);

        border-radius: 50%;

        pointer-events: none;

        transition:
            transform 1.2s var(--yb-ease);
    }

    .public-footer:hover::before {
        transform: scale(1.08);
    }

    .public-footer::after {
        content: "";

        position: absolute;

        width: 250px;
        height: 250px;

        left: -160px;
        bottom: -180px;

        border: 1px solid rgba(255, 255, 255, .07);

        border-radius: 50%;

        pointer-events: none;
    }

    .public-footer-inner {
        position: relative;

        z-index: 1;

        max-width: 1280px;

        margin: 0 auto;

        padding: 58px 32px 28px;

        display: flex;

        flex-direction: column;

        width: 100%;
    }

    .public-footer-top {
        display: grid;

        grid-template-columns:
            minmax(260px, .85fr) minmax(0, 1.5fr);

        gap: 70px;

        width: 100%;

        padding-bottom: 44px;
    }

    .public-footer-brand {
        max-width: 360px;
    }

    .public-footer-brand .footer-eyebrow {
        margin: 0 0 13px;

        color: #ffce6b;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: 2px;

        text-transform: uppercase;
    }

    .public-footer-brand h2 {
        margin: 0;

        color: #fff;

        font-family: "Merriweather", Georgia, serif;

        font-size: clamp(23px, 3vw, 32px);

        line-height: 1.25;
    }

    .public-footer-brand p:not(.footer-eyebrow) {
        margin: 13px 0 0;

        color: #d8e3ef;

        font-family: "Merriweather", Georgia, serif;

        font-size: 14px;

        line-height: 1.6;
    }

    .footer-gold-line {
        width: 46px;
        height: 3px;

        margin-top: 24px;

        background: #ffb034;

        transition:
            width .7s var(--yb-ease);
    }

    .public-footer-brand:hover .footer-gold-line {
        width: 85px;
    }

    .public-footer-contact {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 26px 38px;
    }

    .footer-contact-item {
        display: flex;

        align-items: flex-start;

        gap: 14px;

        min-width: 0;
    }

    .footer-contact-icon {
        flex: 0 0 40px;

        width: 40px;
        height: 40px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border: 1px solid rgba(255, 176, 52, .35);

        background: rgba(255, 255, 255, .055);

        color: #ffce6b;

        transition:
            transform .4s var(--yb-ease),
            background .25s ease,
            border-color .25s ease;
    }

    .footer-contact-item:hover .footer-contact-icon {
        transform:
            translateY(-4px) rotate(-4deg);

        background: rgba(255, 176, 52, .12);

        border-color: rgba(255, 176, 52, .65);
    }

    .footer-contact-icon svg {
        width: 18px;
        height: 18px;

        stroke: currentColor;

        fill: none;

        stroke-width: 1.7;

        stroke-linecap: round;

        stroke-linejoin: round;
    }

    .footer-contact-copy {
        min-width: 0;
    }

    .footer-contact-copy strong {
        display: block;

        margin-bottom: 5px;

        color: #fff;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: 1.5px;

        text-transform: uppercase;
    }

    .footer-contact-copy a,
    .footer-contact-copy span {
        display: block;

        color: #d8e3ef;

        font-size: 12px;

        line-height: 1.55;

        overflow-wrap: anywhere;

        transition:
            color .2s ease;
    }

    .footer-contact-copy a:hover {
        color: #ffce6b;
    }

    .public-footer-bottom {
        display: flex;

        width: 100%;

        flex: 0 0 100%;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        margin-top: 0;

        padding-top: 22px;

        border-top: 1px solid rgba(216, 227, 239, .16);

        color: #aebfd2;

        font-size: 10px;

        letter-spacing: .5px;
    }

    .public-footer-bottom span {
        display: block;
    }

    .public-footer-bottom a {
        display: inline-block;

        color: #ffce6b;

        font-weight: 700;

        text-decoration: none;

        transition:
            color .25s ease,
            transform .4s var(--yb-ease);
    }

    .public-footer-bottom a:hover {
        color: #fff;

        transform: translateX(4px);
    }


    /* =========================================================
        PREMIUM SCROLL REVEALS
        ========================================================= */

    .scroll-animations-ready .yb-reveal {
        opacity: 0;

        transform:
            translate3d(0, 45px, 0);

        transition:
            opacity .85s ease,
            transform 1s var(--yb-ease);

        transition-delay:
            var(--yb-delay, 0ms);

        will-change:
            opacity,
            transform;
    }

    .scroll-animations-ready .yb-reveal.is-visible {
        opacity: 1;

        transform:
            translate3d(0, 0, 0);
    }


    /* =========================================================
        IMAGE REVEALS
        ========================================================= */

    .scroll-animations-ready .yb-reveal-img {
        opacity: 0;

        clip-path:
            inset(12% 0 12% 0);

        transform:
            scale(1.08);

        transition:
            opacity .9s ease,
            clip-path 1.1s var(--yb-ease),
            transform 1.2s var(--yb-ease);

        transition-delay:
            var(--yb-delay, 0ms);
    }

    .scroll-animations-ready .yb-reveal-img.is-visible {
        opacity: 1;

        clip-path:
            inset(0 0 0 0);

        transform:
            scale(1);
    }


    /* =========================================================
        GRADUATION / MOSAIC REVEAL
        ========================================================= */

    .scroll-animations-ready .yb-graduation-card,
    .scroll-animations-ready .yb-mosaic-item {
        opacity: 0;

        transform:
            translate3d(0, 35px, 0) scale(.96);

        transition:
            opacity .8s ease,
            transform 1s var(--yb-ease);

        transition-delay:
            var(--yb-delay, 0ms);
    }

    .scroll-animations-ready .yb-graduation-card.is-visible,
    .scroll-animations-ready .yb-mosaic-item.is-visible {
        opacity: 1;

        transform:
            translate3d(0, 0, 0) scale(1);
    }


    /* =========================================================
        LETTER REVEAL
        ========================================================= */

    .yb-split-word {
        display: inline-block;

        overflow: hidden;

        vertical-align: top;

        padding-bottom: .12em;
        padding-right: .15em;

        margin-bottom: -.12em;
    }

    .yb-split-char {
        display: inline-block;

        opacity: 0;

        transform:
            translate3d(0, 115%, 0) rotate(5deg);

        transition:
            transform .85s var(--yb-ease),
            opacity .55s ease;

        transition-delay:
            var(--yb-char-delay, 0ms);

        will-change:
            transform,
            opacity;
    }

    .yb-split-ready.is-visible .yb-split-char {
        opacity: 1;

        transform:
            translate3d(0, 0, 0) rotate(0deg);
    }


    /* =========================================================
        SCROLL INDICATOR
        ========================================================= */

    .yb-scroll-pulse {
        animation:
            ybScrollPulse 2.8s ease-in-out infinite;
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


    /* =========================================================
        NAVBAR
        ========================================================= */

    .yb-nav {
        background: transparent !important;

        box-shadow: none !important;

        transition:
            background-color .45s ease,
            backdrop-filter .45s ease,
            -webkit-backdrop-filter .45s ease,
            box-shadow .45s ease,
            padding .45s var(--yb-ease),
            height .45s var(--yb-ease);
    }

    .yb-nav.is-scrolled {
        background:
            rgba(7, 26, 51, .88) !important;

        -webkit-backdrop-filter:
            blur(14px);

        backdrop-filter:
            blur(14px);

        box-shadow:
            0 8px 30px rgba(0, 0, 0, .12) !important;

        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }


    /* =========================================================
        HERO LOAD ANIMATIONS
        ========================================================= */

    .yearbook-wrapper.page-animations .yb-cover-image img {
        animation:
            ybHeroImageIn 1.8s var(--yb-ease) forwards;
    }

    .yearbook-wrapper.page-animations .yb-cover-kicker {
        animation:
            ybRevealUp .9s .18s var(--yb-ease) both;
    }

    .yearbook-wrapper.page-animations .yb-cover-description {
        animation:
            ybRevealUp .9s .58s var(--yb-ease) both;
    }

    .yearbook-wrapper.page-animations .yb-cover-actions {
        animation:
            ybRevealUp .9s .72s var(--yb-ease) both;
    }

    .yearbook-wrapper.page-animations .yb-cover-bottom {
        animation:
            ybRevealUp .9s .9s var(--yb-ease) both;
    }

    @keyframes ybHeroImageIn {

        from {
            opacity: 0;

            transform:
                scale(1.10);

            filter:
                saturate(.5) brightness(.7);
        }

        to {
            opacity: 1;

            transform:
                scale(1);

            filter:
                saturate(.82) brightness(1);
        }

    }

    @keyframes ybRevealUp {

        from {
            opacity: 0;

            transform:
                translate3d(0, 38px, 0);
        }

        to {
            opacity: 1;

            transform:
                translate3d(0, 0, 0);
        }

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

            border-top:
                1px solid var(--yb-dark-line);

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

            grid-template-rows:
                260px 200px;
        }

        .yb-mosaic-item:nth-child(1),
        .yb-mosaic-item:nth-child(3) {
            grid-row: span 2;
        }

        .public-footer-top {
            grid-template-columns: 1fr;

            gap: 40px;
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
            width:
                min(calc(100% - 30px),
                    1240px);
        }

        .yb-cover {
            min-height: 760px;

            height: 100svh;
        }

        .yb-cover-inner {
            min-height: 100svh;

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
            font-size:
                clamp(3.7rem,
                    19vw,
                    6rem);
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

        /*
         * Keep the marquee smooth on mobile while
         * preventing it from becoming distracting.
         */
        .yb-campus-track {
            animation-duration: 55s;
        }

        .yb-campus-name {
            padding: 17px 22px;

            margin-right: 10px;

            font-size: .62rem;
        }

        .public-footer-inner {
            padding:
                46px 20px 22px;
        }

        .public-footer-contact {
            grid-template-columns: 1fr;

            gap: 22px;
        }

        .public-footer-bottom {
            flex-direction: column;

            align-items: flex-start;

            justify-content: flex-start;

            gap: 10px;
        }
    }


    /* =========================================================
        ACCESSIBILITY — REDUCED MOTION
        ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        html {
            scroll-behavior: auto;
        }

        *,
        *::before,
        *::after {
            animation-duration: .01ms !important;

            animation-iteration-count: 1 !important;

            transition-duration: .01ms !important;

            scroll-behavior: auto !important;
        }

        .yearbook-wrapper.page-animations .yb-cover-image img,
        .yearbook-wrapper.page-animations .yb-cover-kicker,
        .yearbook-wrapper.page-animations .yb-cover-description,
        .yearbook-wrapper.page-animations .yb-cover-actions,
        .yearbook-wrapper.page-animations .yb-cover-bottom {
            animation: none !important;
        }

        /*
         * Stop the campus marquee for users who
         * prefer reduced motion.
         */
        .yb-campus-track {
            animation: none !important;

            transform: translateX(0) !important;
        }

        .scroll-animations-ready .yb-reveal,
        .scroll-animations-ready .yb-reveal-img,
        .scroll-animations-ready .yb-graduation-card,
        .scroll-animations-ready .yb-mosaic-item {
            opacity: 1 !important;

            transform: none !important;

            clip-path: none !important;
        }

        .yb-split-char {
            opacity: 1 !important;

            transform: none !important;
        }

        .yb-scroll-pulse {
            animation: none !important;
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

                <a
                    href="{{ url('/') }}"
                    class="yb-brand">

                    <div class="yb-brand-mark">
                        LIU
                    </div>

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

                <h1
                    class="yb-cover-title"
                    data-split-load>

                    A year<br>

                    <span>worth</span><br>

                    remembering

                </h1>

                @else

                <h1
                    class="yb-cover-title"
                    data-split-load>

                    The<br>

                    <span>yearbook</span><br>

                    archive.

                </h1>

                @endif


                <p class="yb-cover-description">

                    A living collection of the people, places,
                    celebrations, and moments that shaped our
                    university year.

                </p>


                <div class="yb-cover-actions">

                    <a
                        href="{{ route('public.events') }}"
                        class="yb-cover-link">

                        Moments

                        <span class="yb-cover-link-arrow">
                            ↘
                        </span>

                    </a>


                    <a
                        href="{{ route('public.graduates') }}"
                        style="
                            color: rgba(255,255,255,.75);
                            text-decoration:none;
                            font-size:.68rem;
                            text-transform:uppercase;
                            letter-spacing:1.5px;
                            font-weight:800;
                        ">

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

                        {{ $currentYear ? substr($currentYear->title, -2) : '00' }}

                    </div>


                    <p>

                        Every academic year leaves behind more
                        than dates and ceremonies. It leaves stories.

                    </p>

                </div>


                <div>

                    <h2
                        class="yb-intro-heading"
                        data-split-scroll>

                        This is more than an archive.

                        <em>
                            This is what the year looked like.
                        </em>

                    </h2>


                    <p class="yb-intro-copy">

                        Explore the people who graduated, the events
                        that brought the community together, and the
                        moments that became part of our shared history.

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

    <section
        class="yb-timeline-section"
        id="timeline">

        <div class="yb-container">

            <div class="yb-section-header">

                <div>

                    <div class="yb-overline">
                        Chapter one
                    </div>


                    <h2 data-split-scroll>

                        The year<br>

                        <em>unfolds.</em>

                    </h2>

                </div>


                <p class="yb-section-header-note">

                    A selection of moments, celebrations and
                    events that defined the academic year.

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

                                <span>
                                    →
                                </span>

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
                                class="yb-event-image yb-reveal-img"
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

    <section
        class="yb-people"
        id="people">

        <div class="yb-container">

            <div class="yb-people-heading">

                <div>

                    <div class="yb-overline">
                        Chapter two
                    </div>


                    <h2 data-split-scroll>

                        The<br>

                        <span>people.</span>

                    </h2>

                </div>


                <p>

                    Behind every ceremony is a collection of
                    people, ambitions, friendships and stories.
                    Explore the graduation moments that marked
                    the end of one chapter and the beginning
                    of another.

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
        06 — CAMPUSES
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


                    <h2 data-split-scroll>

                        One university.<br>

                        <em>Many places.</em>

                    </h2>


                    <p>

                        From Lebanon to the wider world, the year
                        was experienced across campuses, communities
                        and classrooms. Explore the places that make
                        up the LIU story.

                    </p>

                </div>

            </div>


            {{-- =================================================
                CONTINUOUS CAMPUS MARQUEE
                ================================================= --}}

            <div class="yb-campus-strip">

                <div class="yb-campus-track">

                    {{-- First set --}}

                    <span class="yb-campus-name">
                        Beirut
                    </span>

                    <span class="yb-campus-name">
                        Bekaa
                    </span>

                    <span class="yb-campus-name">
                        Saida
                    </span>

                    <span class="yb-campus-name">
                        Nabatieh
                    </span>

                    <span class="yb-campus-name">
                        Tripoli
                    </span>

                    <span class="yb-campus-name">
                        Mount Lebanon
                    </span>

                    <span class="yb-campus-name">
                        Tyre
                    </span>

                    <span class="yb-campus-name">
                        Rayak
                    </span>

                    <span class="yb-campus-name">
                        Akkar
                    </span>

                    <span class="yb-campus-name">
                        Yemen
                    </span>

                    <span class="yb-campus-name">
                        Senegal
                    </span>

                    <span class="yb-campus-name">
                        Mauritania
                    </span>


                    {{-- Identical second set for seamless looping --}}

                    <span class="yb-campus-name">
                        Beirut
                    </span>

                    <span class="yb-campus-name">
                        Bekaa
                    </span>

                    <span class="yb-campus-name">
                        Saida
                    </span>

                    <span class="yb-campus-name">
                        Nabatieh
                    </span>

                    <span class="yb-campus-name">
                        Tripoli
                    </span>

                    <span class="yb-campus-name">
                        Mount Lebanon
                    </span>

                    <span class="yb-campus-name">
                        Tyre
                    </span>

                    <span class="yb-campus-name">
                        Rayak
                    </span>

                    <span class="yb-campus-name">
                        Akkar
                    </span>

                    <span class="yb-campus-name">
                        Yemen
                    </span>

                    <span class="yb-campus-name">
                        Senegal
                    </span>

                    <span class="yb-campus-name">
                        Mauritania
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        07 — ARCHIVE
        ===================================================== --}}

    <section class="yb-archive">

        <div class="yb-container">

            <div class="yb-archive-inner">

                <div>

                    <div class="yb-overline">
                        The collection
                    </div>


                    <h2
                        class="yb-archive-heading"
                        data-split-scroll>

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

                        <span>
                            ↗
                        </span>

                    </a>

                </div>


                <div class="yb-archive-years">

                    @if($currentYear)

                    <a
                        href="{{ route('public.timeline') }}"
                        class="yb-archive-year">

                        <span>
                            {{ $currentYear->title }}
                        </span>

                        <span>
                            Current edition →
                        </span>

                    </a>

                    @endif


                    <a
                        href="{{ route('public.archive') }}"
                        class="yb-archive-year">

                        <span>
                            Previous editions
                        </span>

                        <span>
                            Explore →
                        </span>

                    </a>


                    <a
                        href="{{ route('public.graduates') }}"
                        class="yb-archive-year">

                        <span>
                            Graduate directory
                        </span>

                        <span>
                            People →
                        </span>

                    </a>


                    <a
                        href="{{ route('public.events') }}"
                        class="yb-archive-year">

                        <span>
                            Event collection
                        </span>

                        <span>
                            Moments →
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        08 — FOOTER
        ===================================================== --}}

    <footer class="public-footer">

        <div class="public-footer-inner">

            <div class="public-footer-top">

                <div class="public-footer-brand">

                    <p class="footer-eyebrow">
                        Lebanese International University
                    </p>


                    <h2>
                        Lebanese International University
                    </h2>


                    <p>
                        Excellence in Education
                    </p>


                    <div
                        class="footer-gold-line"
                        aria-hidden="true">
                    </div>

                </div>


                <div class="public-footer-contact">

                    {{-- Email --}}

                    <div class="footer-contact-item">

                        <span
                            class="footer-contact-icon"
                            aria-hidden="true">

                            <svg viewBox="0 0 24 24">

                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="14"
                                    rx="1.5">
                                </rect>

                                <path d="m3 7 9 6 9-6"></path>

                            </svg>

                        </span>


                        <div class="footer-contact-copy">

                            <strong>
                                Email
                            </strong>

                            <a href="mailto:info@liu.edu.lb">
                                info@liu.edu.lb
                            </a>

                        </div>

                    </div>


                    {{-- Phone --}}

                    <div class="footer-contact-item">

                        <span
                            class="footer-contact-icon"
                            aria-hidden="true">

                            <svg viewBox="0 0 24 24">

                                <path d="
                                    M7.2 3.5
                                    5 4.4
                                    c-.8.3-1.3 1.1-1.1 2
                                    1.2 6.8 6.9 12.5 13.7 13.7
                                    .9.2 1.7-.3 2-1.1
                                    l.9-2.2
                                    c.3-.7 0-1.5-.7-1.9
                                    l-2.8-1.4
                                    c-.6-.3-1.4-.2-1.8.4
                                    l-1.1 1.3
                                    c-2.3-1.1-4.1-2.9-5.2-5.2
                                    l1.3-1.1
                                    c.5-.4.7-1.2.4-1.8
                                    L9.1 4.2
                                    c-.4-.7-1.2-1-1.9-.7Z">
                                </path>

                            </svg>

                        </span>


                        <div class="footer-contact-copy">

                            <strong>
                                Phone
                            </strong>

                            <a href="tel:+9611705080">
                                +961-1-705080
                            </a>

                        </div>

                    </div>


                    {{-- Fax --}}

                    <div class="footer-contact-item">

                        <span
                            class="footer-contact-icon"
                            aria-hidden="true">

                            <svg viewBox="0 0 24 24">

                                <path d="M6 4h12v16H6z"></path>

                                <path d="M9 8h6M9 12h6M9 16h4"></path>

                            </svg>

                        </span>


                        <div class="footer-contact-copy">

                            <strong>
                                Fax
                            </strong>

                            <span>
                                +961-1-306044
                            </span>

                        </div>

                    </div>


                    {{-- Address --}}

                    <div class="footer-contact-item">

                        <span
                            class="footer-contact-icon"
                            aria-hidden="true">

                            <svg viewBox="0 0 24 24">

                                <path d="
                                    M12 21
                                    s7-6.1 7-12
                                    a7 7 0 1 0-14 0
                                    c0 5.9 7 12 7 12Z">
                                </path>

                                <circle
                                    cx="12"
                                    cy="9"
                                    r="2.2">
                                </circle>

                            </svg>

                        </span>


                        <div class="footer-contact-copy">

                            <strong>
                                Address
                            </strong>

                            <span>
                                Mousaitbeh, P.O. Box 14-6404,
                                Beirut, Lebanon
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="public-footer-bottom">

                <span>

                    © {{ now()->year }}
                    Lebanese International University —
                    Digital Yearbook

                </span>


                <a href="{{ route('search.index') }}">
                    Search the archive
                </a>

            </div>

        </div>

    </footer>

</div>

@endsection


@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const wrapper =
            document.querySelector('.yearbook-wrapper');


        if (!wrapper) {
            return;
        }


        /* =========================================================
           REDUCED MOTION
           ========================================================= */

        const prefersReducedMotion =
            window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;


        /* =========================================================
           LETTER SPLITTING
           ========================================================= */

        function splitNode(node, counter) {

            if (node.nodeType === Node.TEXT_NODE) {

                const frag =
                    document.createDocumentFragment();

                const parts =
                    node.textContent.split(/(\s+)/);


                parts.forEach(function(part) {

                    if (part === '') {
                        return;
                    }


                    if (/^\s+$/.test(part)) {

                        frag.appendChild(
                            document.createTextNode(part)
                        );

                        return;
                    }


                    const wordSpan =
                        document.createElement('span');

                    wordSpan.className =
                        'yb-split-word';


                    Array.from(part).forEach(function(ch) {

                        const charSpan =
                            document.createElement('span');

                        charSpan.className =
                            'yb-split-char';

                        charSpan.textContent =
                            ch;


                        charSpan.style.setProperty(
                            '--yb-char-delay',
                            Math.min(
                                counter.i * 16,
                                520
                            ) + 'ms'
                        );


                        counter.i += 1;


                        wordSpan.appendChild(
                            charSpan
                        );

                    });


                    frag.appendChild(
                        wordSpan
                    );

                });


                node.parentNode.replaceChild(
                    frag,
                    node
                );

                return;
            }


            if (
                node.nodeType === Node.ELEMENT_NODE &&
                node.tagName !== 'BR'
            ) {

                Array.from(
                    node.childNodes
                ).forEach(function(child) {

                    splitNode(
                        child,
                        counter
                    );

                });

            }

        }


        function splitHeading(el) {

            if (
                !el ||
                el.dataset.ybSplit === 'done'
            ) {
                return;
            }


            const counter = {
                i: 0
            };


            Array.from(
                el.childNodes
            ).forEach(function(child) {

                splitNode(
                    child,
                    counter
                );

            });


            el.classList.add(
                'yb-split-ready'
            );


            el.dataset.ybSplit =
                'done';

        }


        /* =========================================================
           HERO SETUP
           ========================================================= */

        const loadHeading =
            wrapper.querySelector(
                '[data-split-load]'
            );


        if (loadHeading) {

            splitHeading(
                loadHeading
            );

        }


        /* =========================================================
           REDUCED MOTION FALLBACK
           ========================================================= */

        if (prefersReducedMotion) {

            document
                .querySelectorAll(
                    '[data-split-scroll]'
                )
                .forEach(function(heading) {

                    splitHeading(
                        heading
                    );


                    heading.classList.add(
                        'is-visible'
                    );

                });


            return;
        }


        /* =========================================================
           PAGE LOAD ANIMATION
           ========================================================= */

        requestAnimationFrame(function() {

            wrapper.classList.add(
                'page-animations'
            );


            if (loadHeading) {

                requestAnimationFrame(
                    function() {

                        loadHeading.classList.add(
                            'is-visible'
                        );

                    }
                );

            }

        });


        /* =========================================================
           NAVBAR
           ========================================================= */

        const nav =
            document.querySelector(
                '.yb-nav'
            );


        function updateNavbar() {

            if (!nav) {
                return;
            }


            nav.classList.toggle(
                'is-scrolled',
                window.scrollY > 24
            );

        }


        updateNavbar();


        window.addEventListener(
            'scroll',
            updateNavbar, {
                passive: true
            }
        );


        /* =========================================================
           SCROLL HEADINGS
           ========================================================= */

        const scrollHeadings =
            Array.from(
                wrapper.querySelectorAll(
                    '[data-split-scroll]'
                )
            );


        scrollHeadings.forEach(
            splitHeading
        );


        /* =========================================================
           REVEAL ELEMENTS
           
           IMPORTANT:
           Campus names are intentionally NOT included here.
           They have their own continuous marquee animation.
           ========================================================= */

        const revealItems =
            Array.from(
                wrapper.querySelectorAll(
                    [
                        '.yb-intro-side',
                        '.yb-intro-copy',
                        '.yb-number-item',
                        '.yb-section-header-note',
                        '.yb-event',
                        '.yb-people-heading > p',
                        '.yb-graduation-card',
                        '.yb-moments-header',
                        '.yb-mosaic-item',
                        '.yb-campus-header > p',
                        '.yb-archive-inner',
                        '.public-footer-top',
                        '.yb-event-image',
                        '.yb-reveal-img'
                    ].join(', ')
                )
            );


        revealItems.forEach(
            function(item, index) {

                item.classList.add(
                    'yb-reveal'
                );


                item.style.setProperty(
                    '--yb-delay',
                    Math.min(
                        (index % 8) * 65,
                        420
                    ) + 'ms'
                );

            }
        );


        wrapper.classList.add(
            'scroll-animations-ready'
        );


        /* =========================================================
           INTERSECTION OBSERVER
           ========================================================= */

        const allObserved =
            revealItems.concat(
                scrollHeadings
            );


        if (
            !('IntersectionObserver' in window)
        ) {

            allObserved.forEach(
                function(item) {

                    item.classList.add(
                        'is-visible'
                    );

                }
            );

            return;
        }


        const observer =
            new IntersectionObserver(
                function(entries) {

                    entries.forEach(
                        function(entry) {

                            if (
                                entry.isIntersecting
                            ) {

                                entry.target.classList.add(
                                    'is-visible'
                                );


                                observer.unobserve(
                                    entry.target
                                );

                            }

                        }
                    );

                }, {
                    threshold: 0.12,

                    rootMargin: '0px 0px -8% 0px'
                }
            );


        allObserved.forEach(
            function(item) {

                observer.observe(
                    item
                );

            }
        );


        /* =========================================================
           SCROLL HINT
           ========================================================= */

        const scrollHint =
            wrapper.querySelector(
                '.yb-cover-scroll'
            );


        if (scrollHint) {

            scrollHint.classList.add(
                'yb-scroll-pulse'
            );

        }


        /* =========================================================
           HERO PARALLAX
           ========================================================= */

        const heroImage =
            wrapper.querySelector(
                '.yb-cover-image img'
            );


        if (heroImage) {

            let ticking = false;


            function updateHeroParallax() {

                if (
                    window.scrollY <=
                    window.innerHeight
                ) {

                    const offset =
                        Math.min(
                            window.scrollY * 0.12,
                            70
                        );


                    heroImage.style.transform =
                        'scale(1.03) translate3d(0, ' +
                        offset +
                        'px, 0)';

                }


                ticking = false;

            }


            window.addEventListener(
                'scroll',
                function() {

                    if (!ticking) {

                        window.requestAnimationFrame(
                            updateHeroParallax
                        );

                        ticking = true;

                    }

                }, {
                    passive: true
                }
            );

        }

    });
</script>

@endpush