<style>
    /* ================= Mission Control — dark cockpit =================
       Scoped to .mc-shell; the rest of the admin chrome stays light.
       Rule: every status visual loops forever while on screen. */

    [x-cloak] { display: none !important; }

    .mc-shell {
        --mc-bg: #070b14;
        --mc-panel: rgba(255, 255, 255, 0.04);
        --mc-line: rgba(255, 255, 255, 0.09);
        --mc-text: #e6edf7;
        --mc-dim: #8b98ad;
        --mc-amber: #f59e0b;
        --mc-sky: #38bdf8;
        --mc-emerald: #34d399;
        --mc-violet: #a78bfa;
        --mc-gold: #fbbf24;
        --mc-red: #f87171;
        position: relative;
        margin: -1rem;
        min-height: calc(100vh - 0px);
        padding: 30px clamp(16px, 3vw, 44px) 60px;
        background:
            radial-gradient(1100px 500px at 80% -10%, rgba(56, 189, 248, 0.08), transparent 60%),
            radial-gradient(900px 500px at 10% 110%, rgba(167, 139, 250, 0.07), transparent 60%),
            var(--mc-bg);
        color: var(--mc-text);
        font-family: var(--font-sans, "Instrument Sans", system-ui, sans-serif);
        overflow: hidden;
    }

    .mc-grid-bg {
        position: absolute;
        inset: -60px;
        background-image:
            linear-gradient(rgba(148, 163, 184, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(148, 163, 184, 0.05) 1px, transparent 1px);
        background-size: 44px 44px;
        mask-image: radial-gradient(75% 60% at 50% 35%, #000 30%, transparent 100%);
        animation: mcPan 60s linear infinite;
        pointer-events: none;
    }
    @keyframes mcPan { from { transform: translate3d(0, 0, 0); } to { transform: translate3d(44px, 44px, 0); } }

    /* ---------- header ---------- */
    .mc-header {
        position: relative;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }
    .mc-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        font-size: clamp(20px, 2.6vw, 30px);
        font-weight: 800;
        letter-spacing: 0.14em;
        color: var(--mc-text);
    }
    .mc-title-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(140deg, rgba(56, 189, 248, 0.22), rgba(167, 139, 250, 0.22));
        border: 1px solid var(--mc-line);
        color: var(--mc-sky);
        font-size: 24px;
        animation: mcGlowSky 2.8s ease-in-out infinite;
    }
    .mc-sub {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 8px 0 0 2px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.22em;
        color: var(--mc-dim);
    }
    .mc-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--mc-emerald);
        animation: mcPulseDot 1.8s ease-in-out infinite;
    }
    @keyframes mcPulseDot {
        0%, 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.5); }
        50% { box-shadow: 0 0 0 7px rgba(52, 211, 153, 0); }
    }

    .mc-launch {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 12px 22px;
        border: 1px solid rgba(56, 189, 248, 0.4);
        border-radius: 12px;
        background: linear-gradient(140deg, rgba(56, 189, 248, 0.18), rgba(167, 139, 250, 0.18));
        color: var(--mc-text);
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.04em;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        animation: mcGlowSky 3.2s ease-in-out infinite;
    }
    .mc-launch:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(56, 189, 248, 0.25); }
    @keyframes mcGlowSky {
        0%, 100% { box-shadow: 0 0 0 0 rgba(56, 189, 248, 0.0), 0 0 18px rgba(56, 189, 248, 0.12); }
        50% { box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.25), 0 0 30px rgba(56, 189, 248, 0.28); }
    }

    /* ---------- AI-console ticker ---------- */
    .mc-ticker {
        display: inline-block;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.06em;
        animation: mcTickerIn 0.5s cubic-bezier(0.2, 0.9, 0.3, 1);
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    @keyframes mcTickerIn {
        from { opacity: 0; transform: translateY(7px); filter: blur(3px); }
        to { opacity: 1; transform: translateY(0); filter: blur(0); }
    }
    .mc-shimmer {
        background: linear-gradient(100deg, #94a3b8 20%, #f8fafc 40%, #94a3b8 60%);
        background-size: 220% 100%;
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: mcTickerIn 0.5s cubic-bezier(0.2, 0.9, 0.3, 1), mcShimmer 2.4s linear infinite;
    }
    @keyframes mcShimmer { from { background-position: 200% 0; } to { background-position: -20% 0; } }

    /* ---------- tabs ---------- */
    .mc-tabs { display: flex; gap: 10px; margin-bottom: 20px; position: relative; }
    .mc-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border: 1px solid var(--mc-line);
        border-radius: 999px;
        background: var(--mc-panel);
        color: var(--mc-dim);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .mc-tab-on { color: var(--mc-text); border-color: rgba(56, 189, 248, 0.5); background: rgba(56, 189, 248, 0.1); }
    .mc-tab-count {
        min-width: 20px;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        font-size: 11px;
        text-align: center;
    }

    /* ---------- board / cards ---------- */
    .mc-board {
        position: relative;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 18px;
    }
    .mc-card {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 18px;
        border: 1px solid var(--mc-line);
        border-radius: 18px;
        background: linear-gradient(160deg, rgba(255, 255, 255, 0.055), rgba(255, 255, 255, 0.02));
        backdrop-filter: blur(8px);
        animation: mcCardIn 0.5s cubic-bezier(0.2, 0.9, 0.3, 1);
        transition: transform 0.25s ease, border-color 0.25s ease;
    }
    .mc-card:hover { transform: translateY(-3px); border-color: rgba(255, 255, 255, 0.18); }
    @keyframes mcCardIn { from { opacity: 0; transform: translateY(14px) scale(0.98); } to { opacity: 1; transform: none; } }

    .mc-card--urgent { border-color: rgba(248, 113, 113, 0.4); }
    .mc-card--urgent::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 18px;
        pointer-events: none;
        animation: mcUrgent 2s ease-in-out infinite;
    }
    @keyframes mcUrgent {
        0%, 100% { box-shadow: inset 0 0 0 1px rgba(248, 113, 113, 0), 0 0 0 0 rgba(248, 113, 113, 0); }
        50% { box-shadow: inset 0 0 0 1px rgba(248, 113, 113, 0.35), 0 0 22px rgba(248, 113, 113, 0.14); }
    }

    .mc-card-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .mc-priority {
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }
    .mc-priority--low { background: rgba(148, 163, 184, 0.14); color: #b6c2d4; }
    .mc-priority--normal { background: rgba(56, 189, 248, 0.14); color: #7dd3fc; }
    .mc-priority--urgent { background: rgba(248, 113, 113, 0.16); color: #fca5a5; animation: mcBlink 1.4s ease-in-out infinite; }
    @keyframes mcBlink { 0%, 100% { opacity: 1; } 50% { opacity: 0.55; } }
    .mc-time { font-size: 11px; color: var(--mc-dim); }

    .mc-card-title { margin: 0; font-size: 16.5px; font-weight: 700; color: var(--mc-text); cursor: pointer; }
    .mc-card-title:hover { color: #7dd3fc; }
    .mc-card-desc {
        margin: 0;
        font-size: 13px;
        line-height: 1.55;
        color: var(--mc-dim);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .mc-thumb {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid var(--mc-line);
        cursor: zoom-in;
    }
    .mc-due { display: flex; align-items: center; gap: 7px; font-size: 12px; color: var(--mc-dim); }
    .mc-overdue {
        padding: 2px 8px;
        border-radius: 999px;
        background: rgba(248, 113, 113, 0.18);
        color: #fca5a5;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.12em;
        animation: mcBlink 1.2s ease-in-out infinite;
    }

    /* ---------- status pill (per-status infinite motion) ---------- */
    .mc-status {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 14px;
        border: 1px solid var(--mc-line);
        background: rgba(0, 0, 0, 0.25);
        min-height: 62px;
    }
    .mc-status-icon {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        font-size: 20px;
    }
    .mc-status-label {
        margin: 0 0 3px;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .mc-status-copy { min-width: 0; }

    /* awaiting: amber radar rings, expanding forever */
    .mc-status--awaiting_review .mc-status-label { color: var(--mc-amber); }
    .mc-status--awaiting_review .mc-status-icon { color: var(--mc-amber); background: rgba(245, 158, 11, 0.12); }
    .mc-status--awaiting_review .mc-status-icon::before,
    .mc-status--awaiting_review .mc-status-icon::after {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 1px solid var(--mc-amber);
        animation: mcRadar 2.2s ease-out infinite;
    }
    .mc-status--awaiting_review .mc-status-icon::after { animation-delay: 1.1s; }
    @keyframes mcRadar {
        from { transform: scale(1); opacity: 0.7; }
        to { transform: scale(2.1); opacity: 0; }
    }

    /* under review: sky, scanning sweep */
    .mc-status--under_review .mc-status-label { color: var(--mc-sky); }
    .mc-status--under_review .mc-status-icon {
        color: var(--mc-sky);
        background: rgba(56, 189, 248, 0.12);
        overflow: hidden;
        animation: mcTilt 2.6s ease-in-out infinite;
    }
    .mc-status--under_review .mc-status-icon::before {
        content: "";
        position: absolute;
        inset: -4px;
        background: linear-gradient(180deg, transparent 45%, rgba(56, 189, 248, 0.45) 50%, transparent 55%);
        animation: mcScan 2s linear infinite;
    }
    @keyframes mcScan { from { transform: translateY(-100%); } to { transform: translateY(100%); } }
    @keyframes mcTilt { 0%, 100% { transform: rotate(-8deg); } 50% { transform: rotate(8deg); } }

    /* approved: emerald pop + breathing shield */
    .mc-status--approved .mc-status-label { color: var(--mc-emerald); }
    .mc-status--approved .mc-status-icon {
        color: var(--mc-emerald);
        background: rgba(52, 211, 153, 0.12);
        animation: mcPop 0.55s cubic-bezier(0.3, 1.6, 0.5, 1), mcBreatheGreen 2.4s ease-in-out 0.55s infinite;
    }
    @keyframes mcPop { 0% { transform: scale(0.4); } 70% { transform: scale(1.18); } 100% { transform: scale(1); } }
    @keyframes mcBreatheGreen {
        0%, 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.45); }
        50% { box-shadow: 0 0 0 9px rgba(52, 211, 153, 0); }
    }

    /* in progress: violet spinning gear */
    .mc-status--in_progress .mc-status-label { color: var(--mc-violet); }
    .mc-status--in_progress .mc-status-icon { color: var(--mc-violet); background: rgba(167, 139, 250, 0.12); }
    .mc-status--in_progress .mc-status-icon i { animation: mcSpin 3s linear infinite; }
    .mc-status--in_progress .mc-status-icon::before {
        content: "";
        position: absolute;
        inset: -3px;
        border-radius: 50%;
        border: 1px dashed rgba(167, 139, 250, 0.5);
        animation: mcSpin 9s linear infinite reverse;
    }
    @keyframes mcSpin { to { transform: rotate(360deg); } }

    /* delivered: gold pop then infinite soft glow + sparkle */
    .mc-status--delivered .mc-status-label { color: var(--mc-gold); }
    .mc-status--delivered .mc-status-icon {
        color: var(--mc-gold);
        background: rgba(251, 191, 36, 0.13);
        animation: mcPop 0.55s cubic-bezier(0.3, 1.6, 0.5, 1), mcGlowGold 2.6s ease-in-out 0.55s infinite;
    }
    .mc-status--delivered .mc-status-icon::after {
        content: "✦";
        position: absolute;
        top: -7px;
        right: -5px;
        font-size: 11px;
        color: var(--mc-gold);
        animation: mcTwinkle 1.8s ease-in-out infinite;
    }
    @keyframes mcGlowGold {
        0%, 100% { box-shadow: 0 0 6px rgba(251, 191, 36, 0.25); }
        50% { box-shadow: 0 0 22px rgba(251, 191, 36, 0.5); }
    }
    @keyframes mcTwinkle { 0%, 100% { opacity: 0.25; transform: scale(0.8) rotate(0deg); } 50% { opacity: 1; transform: scale(1.25) rotate(20deg); } }

    .mc-countdown {
        margin: 6px 0 0;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: var(--mc-emerald);
        animation: mcBlink 1.6s ease-in-out infinite;
    }

    /* ---------- rajin controls ---------- */
    .mc-controls {
        display: flex;
        gap: 8px;
        padding-top: 2px;
    }
    .mc-ctl {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 0;
        border: 1px solid var(--mc-line);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.03);
        color: var(--mc-dim);
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .mc-ctl:hover { color: var(--mc-text); border-color: rgba(255, 255, 255, 0.3); transform: translateY(-1px); }
    .mc-ctl-on { color: var(--mc-sky); border-color: rgba(56, 189, 248, 0.55); background: rgba(56, 189, 248, 0.12); }

    /* ---------- card actions ---------- */
    .mc-actions { display: flex; align-items: center; gap: 8px; }
    .mc-act {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border: 1px solid var(--mc-line);
        border-radius: 10px;
        background: transparent;
        color: var(--mc-dim);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .mc-act:hover:not(:disabled) { color: var(--mc-text); border-color: rgba(255, 255, 255, 0.3); }
    .mc-act:disabled { opacity: 0.45; cursor: not-allowed; }
    .mc-act--danger:hover { color: #fca5a5; border-color: rgba(248, 113, 113, 0.5); }

    .mc-empty {
        grid-column: 1 / -1;
        padding: 60px 0;
        text-align: center;
        color: var(--mc-dim);
        font-size: 14px;
        letter-spacing: 0.08em;
    }

    /* ---------- create panel (modal) ---------- */
    .mc-modal-scrim, .mc-overlay, .mc-lightbox {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(3, 6, 12, 0.78);
        backdrop-filter: blur(8px);
        padding: 20px;
    }
    .mc-modal {
        width: min(560px, 100%);
        max-height: 90vh;
        overflow-y: auto;
        padding: 26px;
        border: 1px solid var(--mc-line);
        border-radius: 20px;
        background: linear-gradient(165deg, #0d1424, #090e1a);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
        animation: mcCardIn 0.35s cubic-bezier(0.2, 0.9, 0.3, 1);
        color: var(--mc-text);
    }
    .mc-modal h2 { margin: 0 0 4px; font-size: 19px; font-weight: 800; letter-spacing: 0.06em; }
    .mc-modal .mc-hint { margin: 0 0 20px; font-size: 12px; color: var(--mc-dim); }
    .mc-field { margin-bottom: 15px; }
    .mc-field label { display: block; margin-bottom: 6px; font-size: 11px; font-weight: 800; letter-spacing: 0.14em; color: var(--mc-dim); text-transform: uppercase; }
    .mc-input, .mc-textarea {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid var(--mc-line);
        border-radius: 11px;
        background: rgba(255, 255, 255, 0.04);
        color: var(--mc-text);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .mc-input:focus, .mc-textarea:focus { border-color: rgba(56, 189, 248, 0.55); box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.14); }
    .mc-textarea { min-height: 96px; resize: vertical; }
    .mc-error { margin: 5px 0 0; font-size: 12px; color: #fca5a5; }

    .mc-seg { display: flex; gap: 8px; }
    .mc-seg button {
        flex: 1;
        padding: 9px 0;
        border: 1px solid var(--mc-line);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.03);
        color: var(--mc-dim);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .mc-seg .mc-seg-on--low { color: #cbd5e1; border-color: rgba(148, 163, 184, 0.6); background: rgba(148, 163, 184, 0.12); }
    .mc-seg .mc-seg-on--normal { color: #7dd3fc; border-color: rgba(56, 189, 248, 0.6); background: rgba(56, 189, 248, 0.12); }
    .mc-seg .mc-seg-on--urgent { color: #fca5a5; border-color: rgba(248, 113, 113, 0.6); background: rgba(248, 113, 113, 0.12); }

    .mc-drop {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 18px;
        border: 1.5px dashed rgba(255, 255, 255, 0.18);
        border-radius: 13px;
        color: var(--mc-dim);
        font-size: 12.5px;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }
    .mc-drop:hover { border-color: rgba(56, 189, 248, 0.5); }
    .mc-drop img { max-height: 110px; border-radius: 9px; }
    .mc-modal-actions { display: flex; gap: 10px; margin-top: 22px; }
    .mc-btn-ghost {
        padding: 12px 18px;
        border: 1px solid var(--mc-line);
        border-radius: 12px;
        background: transparent;
        color: var(--mc-dim);
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
    }
    .mc-btn-primary {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 12px 18px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(120deg, #0ea5e9, #8b5cf6);
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .mc-btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(14, 165, 233, 0.35); }
    .mc-btn-primary:disabled { opacity: 0.6; cursor: wait; }

    /* ---------- dispatch overlay ---------- */
    .mc-overlay { z-index: 80; flex-direction: column; text-align: center; }
    .mc-overlay-title { margin: 22px 0 10px; font-size: clamp(18px, 2.4vw, 26px); font-weight: 800; letter-spacing: 0.06em; color: var(--mc-text); }
    .mc-orbit {
        position: relative;
        width: 130px;
        height: 130px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mc-orbit::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 1px dashed rgba(56, 189, 248, 0.4);
        animation: mcSpin 7s linear infinite;
    }
    .mc-orbit i { font-size: 52px; color: var(--mc-sky); animation: mcFloat 1.6s ease-in-out infinite; }
    @keyframes mcFloat { 0%, 100% { transform: translate(0, 0) rotate(-6deg); } 50% { transform: translate(6px, -10px) rotate(8deg); } }

    .mc-radar-stage {
        position: relative;
        width: 130px;
        height: 130px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mc-radar-stage i { font-size: 52px; color: var(--mc-amber); }
    .mc-radar-ring {
        position: absolute;
        inset: 22px;
        border-radius: 50%;
        border: 1.5px solid var(--mc-amber);
        animation: mcRadar 2s ease-out infinite;
    }
    .mc-radar-ring--late { animation-delay: 1s; }

    /* ---------- drawer ---------- */
    .mc-drawer-scrim { position: fixed; inset: 0; z-index: 65; background: rgba(3, 6, 12, 0.6); backdrop-filter: blur(3px); }
    .mc-drawer {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 70;
        width: min(440px, 94vw);
        overflow-y: auto;
        padding: 24px;
        background: linear-gradient(190deg, #0d1424, #080d18);
        border-left: 1px solid var(--mc-line);
        box-shadow: -30px 0 70px rgba(0, 0, 0, 0.55);
        color: var(--mc-text);
    }
    .mc-drawer-anim { transition: transform 0.3s cubic-bezier(0.2, 0.9, 0.3, 1); }
    .mc-drawer-closed { transform: translateX(100%); }
    .mc-drawer-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 6px; }
    .mc-drawer-head h2 { margin: 0; font-size: 18px; font-weight: 800; }
    .mc-x {
        border: 1px solid var(--mc-line);
        border-radius: 9px;
        background: transparent;
        color: var(--mc-dim);
        width: 32px;
        height: 32px;
        font-size: 16px;
        cursor: pointer;
    }
    .mc-drawer-desc { font-size: 13.5px; line-height: 1.6; color: var(--mc-dim); white-space: pre-line; }
    .mc-drawer-img { width: 100%; border-radius: 12px; border: 1px solid var(--mc-line); margin: 12px 0; cursor: zoom-in; }

    .mc-timeline { margin: 18px 0; padding: 0 0 0 4px; border-left: 1px solid rgba(255, 255, 255, 0.1); }
    .mc-event { position: relative; padding: 0 0 16px 18px; }
    .mc-event::before {
        content: "";
        position: absolute;
        left: -4.5px;
        top: 5px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--mc-sky);
        box-shadow: 0 0 8px rgba(56, 189, 248, 0.6);
    }
    .mc-event--comment::before { background: var(--mc-violet); box-shadow: 0 0 8px rgba(167, 139, 250, 0.6); }
    .mc-event p { margin: 0; font-size: 12.5px; color: var(--mc-dim); }
    .mc-event b { color: var(--mc-text); font-weight: 700; }
    .mc-ev-time { margin-left: 7px; font-size: 11px; color: #64748b; }
    .mc-bubble {
        margin-top: 4px;
        padding: 10px 13px;
        border: 1px solid var(--mc-line);
        border-radius: 12px;
        background: rgba(167, 139, 250, 0.07);
        font-size: 13px;
        color: var(--mc-text);
        white-space: pre-line;
    }
    .mc-comment-form { display: flex; gap: 9px; margin-top: 14px; }
    .mc-comment-form .mc-input { flex: 1; }

    .mc-lightbox { z-index: 90; cursor: zoom-out; }
    .mc-lightbox img { max-width: 92vw; max-height: 88vh; border-radius: 14px; box-shadow: 0 30px 90px rgba(0, 0, 0, 0.7); }

    @media (max-width: 640px) {
        .mc-shell { padding: 20px 14px 50px; }
        .mc-board { grid-template-columns: 1fr; }
    }
</style>
