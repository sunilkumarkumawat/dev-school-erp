<style>
    @import  url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap');

    .attendance-page {
        --att-ink: #0b1b2b;
        --att-muted: #5b6b7c;
        --att-primary: #0f3d56;
        --att-primary-2: #145c7a;
        --att-accent: #f59e0b;
        --att-accent-2: #16a34a;
        --att-bg: #f4f6fb;
        --att-card: #ffffff;
        --att-border: #e2e8f0;
        --att-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        --att-shadow-soft: 0 6px 18px rgba(15, 23, 42, 0.08);
        font-family: 'Manrope', 'Segoe UI', Tahoma, sans-serif;
        color: var(--att-ink);
    }

    .attendance-page .content-wrapper {
        background: transparent;
    }

    .attendance-page .card.card-outline.card-orange {
        border-radius: 16px;
        border: 1px solid var(--att-border);
        box-shadow: var(--att-shadow-soft);
        overflow: hidden;
    }

    .attendance-page .card.card-outline.card-orange > .card-header {
        background: linear-gradient(120deg, #0f3d56 0%, #145c7a 55%, #1f8a70 100%) !important;
        color: #fff;
        border-bottom: none;
        padding: 18px 20px;
    }

    .attendance-page .card.card-outline.card-orange > .card-header .card-title {
        font-weight: 700;
    }

    .attendance-page .card:not(.card-outline) {
        border-radius: 14px;
        border: 1px solid var(--att-border);
        box-shadow: var(--att-shadow-soft);
    }

    .attendance-page .card:not(.card-outline) > .card-header {
        background: #f8fafc;
        border-bottom: 1px solid var(--att-border);
    }

    .attendance-hero {
        position: relative;
        border-radius: 18px;
        padding: 22px;
        background: linear-gradient(120deg, #0f3d56 0%, #145c7a 45%, #1f8a70 100%);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: var(--att-shadow);
        overflow: hidden;
    }

    .attendance-hero:before,
    .attendance-hero:after {
        content: "";
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .attendance-hero:before {
        width: 260px;
        height: 260px;
        right: -80px;
        top: -140px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.35), rgba(245, 158, 11, 0) 70%);
    }

    .attendance-hero:after {
        width: 220px;
        height: 220px;
        left: -90px;
        bottom: -120px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0) 70%);
    }

    .attendance-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        justify-content: space-between;
    }

    .attendance-hero-kicker {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 700;
    }

    .attendance-hero-title {
        font-family: 'Playfair Display', 'Times New Roman', serif;
        font-size: 28px;
        font-weight: 700;
        margin: 4px 0;
    }

    .attendance-hero-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        margin-bottom: 8px;
    }

    .attendance-hero-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .attendance-chip,
    .att-chip,
    .att-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 6px 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        color: #0b1b2b;
    }

    .att-chip.form-control,
    .att-pill.form-control {
        display: block;
        padding: 4px 10px;
        border-radius: 10px;
        font-weight: 600;
    }

    .attendance-page .card-header .att-chip,
    .attendance-page .card-header .att-pill,
    .attendance-page .card-header .att-date-pill,
    .attendance-page .card-header .self-chip {
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
    }

    .attendance-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        justify-content: flex-end;
    }

    .attendance-card-header {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-bottom: 1px solid var(--att-border);
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
    }

    .attendance-card-title {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .attendance-filter,
    .att-filter-row,
    .att-actions {
        background: #f8fafc;
        border: 1px solid var(--att-border);
        border-radius: 12px;
        padding: 10px;
    }

    .att-filter-row .form-control,
    .att-filter-row .att-chip {
        height: 34px;
    }

    .att-filter-row .select2 {
        min-width: 220px;
    }

    .attendance-table,
    .att-table {
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }

    .attendance-table th,
    .att-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #475569;
        white-space: nowrap;
        background: #f1f5f9;
        border-bottom: 1px solid var(--att-border) !important;
    }

    .attendance-table td,
    .att-table td {
        vertical-align: middle;
        border-color: #eef2f7 !important;
    }

    .att-tabs .nav-link {
        border-radius: 20px;
        padding: 8px 16px;
        font-weight: 600;
        border: 1px solid #d1d9e6;
        color: #0b1b2b;
        background: #ffffff;
    }

    .att-tabs .nav-link.active {
        background: #0b1b2b;
        color: #fff;
        border-color: #0b1b2b;
    }

    .att-status-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(100, 116, 139, 0.12);
        color: #334155;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        display: inline-block;
    }

    .att-header-badge {
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .att-title {
        color: #fff;
        margin: 0;
        font-weight: 700;
    }

    .att-subtext {
        color: var(--att-muted);
        font-size: 13px;
    }

    .att-date-input {
        background: #fff;
        color: #0b1b2b;
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 18px;
        padding: 4px 10px;
        font-size: 13px;
        height: 32px;
        min-width: 140px;
    }

    .att-date-pill {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .att-filter {
        min-width: 200px;
    }

    .att-empty {
        color: var(--att-muted);
        font-size: 13px;
        padding: 10px 0;
    }

    .att-panel {
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        color: #64748b;
        background: #fff;
    }

    .att-field {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .att-field label {
        font-size: 11px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin: 0;
    }

    .att-time {
        min-width: 140px;
    }

    .attendance-pill,
    .attendance-inline,
    .self-chip,
    .self-card,
    .attendance-card {
        border-radius: 14px;
    }

    .attendance-card {
        border: 1px solid var(--att-border);
        background: #fff;
        padding: 16px;
        box-shadow: var(--att-shadow-soft);
    }

    .attendance-card h5 {
        font-weight: 600;
        margin-bottom: 12px;
    }

    .attendance-pill,
    .self-chip {
        border: 1px solid #e2e6ea;
        border-radius: 12px;
        padding: 12px;
        background: #f8f9fa;
    }

    .attendance-pill p {
        margin-bottom: 0;
        font-size: 13px;
        color: var(--att-muted);
    }

    .attendance-inline {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .attendance-page .btn {
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    .attendance-page .btn.btn-primary {
        background: #0b1b2b;
        border-color: #0b1b2b;
    }

    .attendance-page .btn.btn-outline-secondary,
    .attendance-page .btn.btn-outline-light {
        color: #0b1b2b;
        border-color: #cbd5e1;
        background: #fff;
    }

    .attendance-page .btn.btn-outline-secondary:hover,
    .attendance-page .btn.btn-outline-light:hover {
        background: #f1f5f9;
        color: #0b1b2b;
    }

    .attendance-page .card-header .btn.btn-outline-light,
    .attendance-page .card-header .btn.btn-outline-secondary {
        color: #fff;
        border-color: rgba(255, 255, 255, 0.6);
        background: transparent;
    }

    .attendance-page .card-header .btn.btn-outline-light:hover,
    .attendance-page .card-header .btn.btn-outline-secondary:hover {
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
    }

    .attendance-page .btn.btn-success {
        background: #16a34a;
        border-color: #16a34a;
    }

    .attendance-page .btn.btn-outline-danger {
        color: #991b1b;
        border-color: rgba(239, 68, 68, 0.4);
        background: rgba(239, 68, 68, 0.06);
    }

    .attendance-page .btn.btn-outline-danger:hover {
        background: rgba(239, 68, 68, 0.16);
        color: #991b1b;
    }

    @media (max-width: 768px) {
        .attendance-hero {
            padding: 18px;
        }

        .attendance-hero-title {
            font-size: 24px;
        }

        .attendance-hero-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>
<?php /**PATH C:\xampp\htdocs\dev\resources\views/attendance/theme.blade.php ENDPATH**/ ?>