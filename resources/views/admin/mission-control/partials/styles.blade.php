<style>
    /* ================= Mission Control — dark cockpit v2 =================
       Scoped to .mc-shell; the rest of the admin chrome stays light.
       Rule: every "alive" visual loops forever while on screen. */

    [x-cloak] { display: none !important; }

    .mc-shell {
        --mc-bg0: #04060c;
        --mc-bg1: #0a1020;
        --mc-panel: rgba(255, 255, 255, 0.045);
        --mc-panel2: rgba(255, 255, 255, 0.02);
        --mc-line: rgba(148, 163, 184, 0.16);
        --mc-line-soft: rgba(148, 163, 184, 0.09);
        --mc-text: #eef3fb;
        --mc-dim: #93a1b8;
        --mc-faint: #64748b;
        --mc-cyan: #22d3ee;
        --mc-violet: #8b5cf6;
        --mc-amber: #f6b73c;
        --mc-emerald: #34d399;
        --mc-rose: #fb7185;
        --mc-gold: #f5c542;
        position: relative;
        margin: -1rem;
        min-height: 100vh;
        padding: 26px clamp(16px, 3vw, 44px) 70px;
        background:
            radial-gradient(1200px 520px at 82% -12%, rgba(34, 211, 238, 0.07), transparent 60%),
            radial-gradient(1000px 560px at 8% 112%, rgba(139, 92, 246, 0.08), transparent 60%),
            linear-gradient(180deg, var(--mc-bg1), var(--mc-bg0) 55%);
        color: var(--mc-text);
        font-family: var(--font-sans, "Instrument Sans", system-ui, sans-serif);
        overflow: hidden;
    }

    .mc-grid-bg {
        position: absolute;
        inset: -60px;
        background-image:
            linear-gradient(rgba(148, 163, 184, 0.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(148, 163, 184, 0.045) 1px, transparent 1px);
        background-size: 46px 46px;
        mask-image: radial-gradient(80% 65% at 50% 30%, #000 25%, transparent 100%);
        animation: mcPan 70s linear infinite;
        pointer-events: none;
    }
    @keyframes mcPan { from { transform: translate3d(0, 0, 0); } to { transform: translate3d(46px, 46px, 0); } }

    .mc-aurora {
        position: absolute;
        top: -180px;
        left: 30%;
        width: 560px;
        height: 420px;
        background: radial-gradient(closest-side, rgba(34, 211, 238, 0.14), rgba(139, 92, 246, 0.1), transparent 75%);
        filter: blur(70px);
        animation: mcDrift 22s ease-in-out infinite;
        pointer-events: none;
    }
    @keyframes mcDrift {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(-140px, 60px) scale(1.15); }
        66% { transform: translate(120px, 20px) scale(0.92); }
    }

    /* ---------- top command bar ---------- */
    .mc-topbar {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        padding: 18px 22px;
        margin-bottom: 24px;
        border: 1px solid var(--mc-line);
        border-radius: 20px;
        background: linear-gradient(160deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02));
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
    }
    .mc-topbar-left { display: flex; align-items: center; gap: 16px; }
    .mc-emblem {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: linear-gradient(140deg, rgba(34, 211, 238, 0.16), rgba(139, 92, 246, 0.2));
        border: 1px solid rgba(34, 211, 238, 0.35);
        color: var(--mc-cyan);
        font-size: 28px;
        overflow: hidden;
    }
    .mc-emblem::before {
        content: "";
        position: absolute;
        inset: -40%;
        background: conic-gradient(from 0deg, transparent 78%, rgba(34, 211, 238, 0.5), transparent);
        animation: mcSpin 3.6s linear infinite;
    }
    .mc-emblem i { position: relative; }
    .mc-title {
        margin: 0;
        font-size: clamp(19px, 2.3vw, 26px);
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        background: linear-gradient(95deg, #e8f6ff 10%, #7dd3fc 45%, #c4b5fd 85%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .mc-sub {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 5px 0 0;
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--mc-dim);
    }
    .mc-sub-cap { color: var(--mc-emerald); }
    .mc-live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--mc-emerald);
        animation: mcPulseDot 1.8s ease-in-out infinite;
    }
    @keyframes mcPulseDot {
        0%, 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.5); }
        50% { box-shadow: 0 0 0 7px rgba(52, 211, 153, 0); }
    }

    .mc-topbar-right { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .mc-stats { display: flex; gap: 8px; }
    .mc-stat {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 13px;
        border: 1px solid var(--mc-line-soft);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.03);
        font-size: 12px;
        color: var(--mc-dim);
        white-space: nowrap;
    }
    .mc-stat b { color: var(--mc-text); font-weight: 800; }
    .mc-stat-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
    .mc-stat-dot--cyan { background: var(--mc-cyan); box-shadow: 0 0 8px rgba(34, 211, 238, 0.7); }
    .mc-stat-dot--violet { background: var(--mc-violet); box-shadow: 0 0 8px rgba(139, 92, 246, 0.7); }
    .mc-stat-dot--gold { background: var(--mc-gold); box-shadow: 0 0 8px rgba(245, 197, 66, 0.7); }
    .mc-clock {
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.12em;
        color: var(--mc-cyan);
        text-shadow: 0 0 12px rgba(34, 211, 238, 0.35);
    }

    .mc-launch {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 12px 22px;
        border: none;
        border-radius: 13px;
        background: linear-gradient(120deg, #0ea5e9, #8b5cf6);
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.04em;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        animation: mcLaunchGlow 3s ease-in-out infinite;
    }
    .mc-launch:hover { transform: translateY(-2px); box-shadow: 0 14px 34px rgba(14, 165, 233, 0.45); }
    @keyframes mcLaunchGlow {
        0%, 100% { box-shadow: 0 6px 22px rgba(14, 165, 233, 0.25); }
        50% { box-shadow: 0 10px 34px rgba(139, 92, 246, 0.45); }
    }

    /* ---------- AI-console ticker ----------
       The element is never re-created: swapping between the two parity
       classes restarts the entry animation, the shimmer loops forever. */
    .mc-ticker {
        display: inline-block;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.05em;
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: bottom;
    }
    .mc-shimmer {
        background: linear-gradient(100deg, #8fa2bd 25%, #ffffff 42%, #8fa2bd 60%);
        background-size: 220% 100%;
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .mc-ticker--a { animation: mcTickerInA 0.5s cubic-bezier(0.2, 0.9, 0.3, 1), mcShimmer 2.4s linear infinite; }
    .mc-ticker--b { animation: mcTickerInB 0.5s cubic-bezier(0.2, 0.9, 0.3, 1), mcShimmer 2.4s linear infinite; }
    @keyframes mcTickerInA {
        from { opacity: 0; transform: translateY(7px); filter: blur(3px); }
        to { opacity: 1; transform: translateY(0); filter: blur(0); }
    }
    @keyframes mcTickerInB {
        from { opacity: 0; transform: translateY(7px); filter: blur(3px); }
        to { opacity: 1; transform: translateY(0); filter: blur(0); }
    }
    @keyframes mcShimmer { from { background-position: 200% 0; } to { background-position: -20% 0; } }

    /* ---------- tabs ---------- */
    .mc-tabs { display: flex; gap: 10px; margin-bottom: 20px; }
    .mc-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: 1px solid var(--mc-line);
        border-radius: 999px;
        background: var(--mc-panel2);
        color: var(--mc-dim);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.22s ease;
    }
    .mc-tab:hover { color: var(--mc-text); }
    .mc-tab-on {
        color: var(--mc-text);
        border-color: rgba(34, 211, 238, 0.5);
        background: linear-gradient(120deg, rgba(34, 211, 238, 0.14), rgba(139, 92, 246, 0.14));
        box-shadow: 0 0 22px rgba(34, 211, 238, 0.15);
    }
    .mc-tab-count {
        min-width: 21px;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.09);
        font-size: 11px;
        text-align: center;
    }

    /* ---------- board / cards ---------- */
    .mc-board {
        position: relative;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
        gap: 20px;
        align-items: start;
    }
    .mc-card {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 13px;
        padding: 20px;
        border: 1px solid var(--mc-line);
        border-radius: 22px;
        background: linear-gradient(165deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.018) 60%);
        backdrop-filter: blur(9px);
        box-shadow: 0 18px 44px rgba(0, 0, 0, 0.35);
        animation: mcCardIn 0.5s cubic-bezier(0.2, 0.9, 0.3, 1);
        transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
    }
    .mc-card:hover {
        transform: translateY(-4px);
        border-color: rgba(148, 163, 184, 0.32);
        box-shadow: 0 26px 60px rgba(0, 0, 0, 0.45);
    }
    @keyframes mcCardIn { from { opacity: 0; transform: translateY(16px) scale(0.98); } to { opacity: 1; transform: none; } }

    /* status-tinted hairline across the top of each card */
    .mc-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 8%;
        right: 8%;
        height: 2px;
        border-radius: 2px;
        background: linear-gradient(90deg, transparent, var(--mc-st, var(--mc-cyan)), transparent);
        opacity: 0.9;
    }
    .mc-card--st-awaiting_review { --mc-st: var(--mc-amber); }
    .mc-card--st-under_review { --mc-st: var(--mc-cyan); }
    .mc-card--st-approved { --mc-st: var(--mc-emerald); }
    .mc-card--st-in_progress { --mc-st: var(--mc-violet); }
    .mc-card--st-delivered { --mc-st: var(--mc-gold); }

    .mc-card--urgent::after {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 22px;
        pointer-events: none;
        animation: mcUrgent 2.2s ease-in-out infinite;
    }
    @keyframes mcUrgent {
        0%, 100% { box-shadow: inset 0 0 0 1px rgba(251, 113, 133, 0); }
        50% { box-shadow: inset 0 0 0 1px rgba(251, 113, 133, 0.45), inset 0 0 34px rgba(251, 113, 133, 0.07); }
    }

    .mc-card-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .mc-priority {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        border: 1px solid transparent;
    }
    .mc-priority .mc-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .mc-priority--low { background: rgba(148, 163, 184, 0.1); color: #b6c2d4; border-color: rgba(148, 163, 184, 0.25); }
    .mc-priority--normal { background: rgba(34, 211, 238, 0.1); color: #67e8f9; border-color: rgba(34, 211, 238, 0.3); }
    .mc-priority--urgent {
        background: rgba(251, 113, 133, 0.12);
        color: #fda4af;
        border-color: rgba(251, 113, 133, 0.4);
        animation: mcBlink 1.4s ease-in-out infinite;
    }
    @keyframes mcBlink { 0%, 100% { opacity: 1; } 50% { opacity: 0.55; } }
    .mc-time { font-size: 11px; color: var(--mc-faint); }

    .mc-card-title { margin: 0; font-size: 17px; font-weight: 750; color: var(--mc-text); cursor: pointer; line-height: 1.35; }
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

    .mc-thumb-wrap {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--mc-line-soft);
        cursor: zoom-in;
    }
    .mc-thumb { display: block; width: 100%; height: 128px; object-fit: cover; transition: transform 0.35s ease; }
    .mc-thumb-wrap:hover .mc-thumb { transform: scale(1.05); }
    .mc-thumb-zoom {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        background: rgba(4, 6, 12, 0.55);
        color: var(--mc-text);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.06em;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .mc-thumb-wrap:hover .mc-thumb-zoom { opacity: 1; }

    .mc-meta { display: flex; flex-wrap: wrap; gap: 7px; }
    .mc-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border: 1px solid var(--mc-line-soft);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.03);
        color: var(--mc-dim);
        font-size: 11.5px;
        font-weight: 600;
        white-space: nowrap;
    }
    .mc-chip--overdue {
        background: rgba(251, 113, 133, 0.12);
        border-color: rgba(251, 113, 133, 0.4);
        color: #fda4af;
        animation: mcBlink 1.2s ease-in-out infinite;
    }
    .mc-chip--countdown {
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        background: rgba(52, 211, 153, 0.1);
        border-color: rgba(52, 211, 153, 0.4);
        color: #6ee7b7;
        animation: mcBlink 1.6s ease-in-out infinite;
        flex: 0 0 auto;
    }

    /* ---------- status module (per-status infinite motion) ---------- */
    .mc-status {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 15px;
        border: 1px solid var(--mc-line-soft);
        background: rgba(2, 4, 10, 0.45);
        min-height: 64px;
    }
    .mc-status-icon {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 21px;
    }
    .mc-status-label {
        margin: 0 0 3px;
        font-size: 12.5px;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }
    .mc-status-copy { min-width: 0; flex: 1; }

    /* awaiting: amber radar rings, expanding forever */
    .mc-status--awaiting_review .mc-status-label { color: var(--mc-amber); }
    .mc-status--awaiting_review .mc-status-icon { color: var(--mc-amber); background: rgba(246, 183, 60, 0.12); }
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

    /* under review: cyan scanning sweep + tilt */
    .mc-status--under_review .mc-status-label { color: var(--mc-cyan); }
    .mc-status--under_review .mc-status-icon {
        color: var(--mc-cyan);
        background: rgba(34, 211, 238, 0.12);
        overflow: hidden;
        animation: mcTilt 2.6s ease-in-out infinite;
    }
    .mc-status--under_review .mc-status-icon::before {
        content: "";
        position: absolute;
        inset: -4px;
        background: linear-gradient(180deg, transparent 45%, rgba(34, 211, 238, 0.5) 50%, transparent 55%);
        animation: mcScan 2s linear infinite;
    }
    @keyframes mcScan { from { transform: translateY(-100%); } to { transform: translateY(100%); } }
    @keyframes mcTilt { 0%, 100% { transform: rotate(-8deg); } 50% { transform: rotate(8deg); } }

    /* approved: emerald pop + breathing halo */
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

    /* in progress: violet spinning gear + dashed orbit */
    .mc-status--in_progress .mc-status-label { color: #c4b5fd; }
    .mc-status--in_progress .mc-status-icon { color: #c4b5fd; background: rgba(139, 92, 246, 0.14); }
    .mc-status--in_progress .mc-status-icon i { animation: mcSpin 3s linear infinite; }
    .mc-status--in_progress .mc-status-icon::before {
        content: "";
        position: absolute;
        inset: -3px;
        border-radius: 50%;
        border: 1px dashed rgba(139, 92, 246, 0.55);
        animation: mcSpin 9s linear infinite reverse;
    }
    @keyframes mcSpin { to { transform: rotate(360deg); } }

    /* delivered: gold pop, then infinite soft glow + sparkle */
    .mc-status--delivered .mc-status-label { color: var(--mc-gold); }
    .mc-status--delivered .mc-status-icon {
        color: var(--mc-gold);
        background: rgba(245, 197, 66, 0.13);
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
        0%, 100% { box-shadow: 0 0 6px rgba(245, 197, 66, 0.25); }
        50% { box-shadow: 0 0 22px rgba(245, 197, 66, 0.5); }
    }
    @keyframes mcTwinkle { 0%, 100% { opacity: 0.25; transform: scale(0.8) rotate(0deg); } 50% { opacity: 1; transform: scale(1.25) rotate(20deg); } }

    /* ---------- pipeline stepper ---------- */
    .mc-pipe { position: relative; padding: 6px 6px 0; }
    .mc-pipe-rail {
        position: absolute;
        top: 21px;
        left: 26px;
        right: 26px;
        height: 2px;
        border-radius: 2px;
        background: rgba(148, 163, 184, 0.18);
        overflow: hidden;
    }
    .mc-pipe-fill {
        height: 100%;
        border-radius: 2px;
        background: linear-gradient(90deg, var(--mc-cyan), var(--mc-violet));
        box-shadow: 0 0 12px rgba(34, 211, 238, 0.5);
        transition: width 0.6s cubic-bezier(0.2, 0.9, 0.3, 1);
    }
    .mc-pipe-steps { position: relative; display: flex; justify-content: space-between; }
    .mc-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        border: none;
        background: transparent;
        padding: 0;
        cursor: default;
        color: var(--mc-faint);
    }
    .mc-pipe--live .mc-step { cursor: pointer; }
    .mc-step-node {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid var(--mc-line);
        background: #0b1120;
        font-size: 14px;
        transition: all 0.25s ease;
    }
    .mc-step-lbl {
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }
    .mc-step--done { color: var(--mc-dim); }
    .mc-step--done .mc-step-node {
        border-color: rgba(34, 211, 238, 0.45);
        background: linear-gradient(140deg, rgba(34, 211, 238, 0.2), rgba(139, 92, 246, 0.2));
        color: #a5f3fc;
    }
    .mc-step--now { color: var(--mc-text); }
    .mc-step--now .mc-step-node {
        border-color: var(--mc-st, var(--mc-cyan));
        color: var(--mc-st, var(--mc-cyan));
        background: #0b1120;
        animation: mcStepPulse 1.8s ease-in-out infinite;
    }
    @keyframes mcStepPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(148, 163, 184, 0.35); }
        50% { box-shadow: 0 0 0 7px rgba(148, 163, 184, 0); }
    }
    .mc-pipe--live .mc-step:hover .mc-step-node { transform: scale(1.14); border-color: rgba(255, 255, 255, 0.45); color: var(--mc-text); }

    /* ---------- card actions ---------- */
    .mc-actions { display: flex; align-items: center; gap: 8px; }
    .mc-act-spacer { flex: 1; }
    .mc-act {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 13px;
        border: 1px solid var(--mc-line-soft);
        border-radius: 11px;
        background: rgba(255, 255, 255, 0.03);
        color: var(--mc-dim);
        font-size: 12.5px;
        font-weight: 650;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .mc-act:hover:not(:disabled) { color: var(--mc-text); border-color: rgba(255, 255, 255, 0.3); }
    .mc-act:disabled { opacity: 0.45; cursor: not-allowed; }
    .mc-act--icon { padding: 8px 10px; }
    .mc-act--danger:hover { color: #fda4af; border-color: rgba(251, 113, 133, 0.5); }
    .mc-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border: none;
        border-radius: 11px;
        background: linear-gradient(120deg, #0ea5e9, #8b5cf6);
        color: #fff;
        font-size: 12.5px;
        font-weight: 800;
        letter-spacing: 0.04em;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .mc-cta:hover { transform: translateY(-1px); box-shadow: 0 10px 26px rgba(14, 165, 233, 0.4); }

    .mc-empty {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        padding: 70px 0;
        border: 1.5px dashed var(--mc-line);
        border-radius: 22px;
        color: var(--mc-dim);
        font-size: 14px;
        letter-spacing: 0.06em;
    }
    .mc-empty-radar {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        color: var(--mc-cyan);
        font-size: 30px;
        background: rgba(34, 211, 238, 0.08);
    }
    .mc-empty-radar::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 1px solid var(--mc-cyan);
        animation: mcRadar 2.4s ease-out infinite;
    }

    /* ---------- overlays / modals ---------- */
    .mc-modal-scrim, .mc-overlay, .mc-lightbox {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(2, 4, 10, 0.8);
        backdrop-filter: blur(9px);
        padding: 20px;
    }
    .mc-modal {
        width: min(560px, 100%);
        max-height: 90vh;
        overflow-y: auto;
        padding: 28px;
        border: 1px solid var(--mc-line);
        border-radius: 22px;
        background: linear-gradient(165deg, #0d1526, #080d19);
        box-shadow: 0 34px 90px rgba(0, 0, 0, 0.65);
        animation: mcCardIn 0.35s cubic-bezier(0.2, 0.9, 0.3, 1);
        color: var(--mc-text);
    }
    .mc-modal h2 { margin: 0 0 4px; font-size: 19px; font-weight: 800; letter-spacing: 0.05em; }
    .mc-modal .mc-hint { margin: 0 0 20px; font-size: 12px; color: var(--mc-dim); }
    .mc-optional { opacity: 0.5; text-transform: none; letter-spacing: 0; }
    .mc-field { margin-bottom: 15px; }
    .mc-field label {
        display: block;
        margin-bottom: 6px;
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: 0.15em;
        color: var(--mc-dim);
        text-transform: uppercase;
    }
    .mc-input, .mc-textarea {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid var(--mc-line);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.04);
        color: var(--mc-text);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .mc-input:focus, .mc-textarea:focus { border-color: rgba(34, 211, 238, 0.55); box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.13); }
    .mc-textarea { min-height: 96px; resize: vertical; }
    .mc-error { margin: 5px 0 0; font-size: 12px; color: #fda4af; }
    .mc-file-hidden { display: none; }

    .mc-seg { display: flex; gap: 8px; }
    .mc-seg button {
        flex: 1;
        padding: 9px 0;
        border: 1px solid var(--mc-line);
        border-radius: 11px;
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
    .mc-seg .mc-seg-on--normal { color: #67e8f9; border-color: rgba(34, 211, 238, 0.6); background: rgba(34, 211, 238, 0.12); }
    .mc-seg .mc-seg-on--urgent { color: #fda4af; border-color: rgba(251, 113, 133, 0.6); background: rgba(251, 113, 133, 0.12); }

    .mc-drop {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 18px;
        border: 1.5px dashed rgba(148, 163, 184, 0.3);
        border-radius: 14px;
        color: var(--mc-dim);
        font-size: 12.5px;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }
    .mc-drop:hover { border-color: rgba(34, 211, 238, 0.5); }
    .mc-drop img { max-height: 110px; border-radius: 10px; }
    .mc-modal-actions { display: flex; gap: 10px; margin-top: 22px; }
    .mc-btn-ghost {
        padding: 12px 18px;
        border: 1px solid var(--mc-line);
        border-radius: 13px;
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
        border-radius: 13px;
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
    .mc-btn-primary--sq { flex: 0 0 auto; }

    /* ---------- dispatch overlay ---------- */
    .mc-overlay { z-index: 80; text-align: center; }
    .mc-overlay-title { margin: 22px 0 10px; font-size: clamp(18px, 2.4vw, 26px); font-weight: 800; letter-spacing: 0.05em; color: var(--mc-text); }
    .mc-orbit {
        position: relative;
        width: 130px;
        height: 130px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mc-orbit::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 1px dashed rgba(34, 211, 238, 0.45);
        animation: mcSpin 7s linear infinite;
    }
    .mc-orbit i { font-size: 52px; color: var(--mc-cyan); animation: mcFloat 1.6s ease-in-out infinite; }
    @keyframes mcFloat { 0%, 100% { transform: translate(0, 0) rotate(-6deg); } 50% { transform: translate(6px, -10px) rotate(8deg); } }

    .mc-radar-stage {
        position: relative;
        width: 130px;
        height: 130px;
        margin: 0 auto;
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

    /* ---------- task console ---------- */
    .mc-console {
        width: min(880px, 100%);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--mc-line);
        border-radius: 24px;
        background: linear-gradient(170deg, #0d1526, #070c17);
        box-shadow: 0 40px 110px rgba(0, 0, 0, 0.7);
        animation: mcCardIn 0.35s cubic-bezier(0.2, 0.9, 0.3, 1);
        color: var(--mc-text);
        overflow: hidden;
    }
    .mc-console-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 22px;
        border-bottom: 1px solid var(--mc-line-soft);
        background: rgba(255, 255, 255, 0.025);
    }
    .mc-console-head-copy { flex: 1; min-width: 0; }
    .mc-console-head h2 { margin: 0 0 4px; font-size: 18px; font-weight: 800; }
    .mc-console-head p { display: flex; align-items: center; gap: 10px; margin: 0; }
    .mc-x {
        flex: 0 0 auto;
        border: 1px solid var(--mc-line);
        border-radius: 10px;
        background: transparent;
        color: var(--mc-dim);
        width: 34px;
        height: 34px;
        font-size: 17px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .mc-x:hover { color: var(--mc-text); border-color: rgba(255, 255, 255, 0.35); }

    .mc-console-body {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        gap: 0;
        overflow: hidden;
        min-height: 0;
    }
    .mc-console-brief {
        padding: 20px 22px;
        border-right: 1px solid var(--mc-line-soft);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .mc-console-kicker {
        margin: 0;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--mc-faint);
    }
    .mc-console-desc { margin: 0; font-size: 13.5px; line-height: 1.65; color: var(--mc-dim); white-space: pre-line; }
    .mc-console-feed {
        display: flex;
        flex-direction: column;
        padding: 20px 22px;
        overflow: hidden;
        min-height: 0;
    }
    .mc-timeline {
        flex: 1;
        overflow-y: auto;
        margin: 12px 0 14px;
        padding-left: 4px;
        border-left: 1px solid rgba(148, 163, 184, 0.14);
        min-height: 120px;
        max-height: 46vh;
    }
    .mc-event { position: relative; padding: 0 0 15px 18px; }
    .mc-event::before {
        content: "";
        position: absolute;
        left: -4.5px;
        top: 5px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--mc-cyan);
        box-shadow: 0 0 8px rgba(34, 211, 238, 0.6);
    }
    .mc-event--comment::before { background: var(--mc-violet); box-shadow: 0 0 8px rgba(139, 92, 246, 0.6); }
    .mc-event p { margin: 0; font-size: 12.5px; color: var(--mc-dim); }
    .mc-event b { color: var(--mc-text); font-weight: 700; }
    .mc-ev-time { margin-left: 7px; font-size: 11px; color: var(--mc-faint); }
    .mc-bubble {
        margin-top: 5px;
        padding: 10px 13px;
        border: 1px solid var(--mc-line-soft);
        border-radius: 13px;
        background: rgba(139, 92, 246, 0.08);
        font-size: 13px;
        color: var(--mc-text);
        white-space: pre-line;
    }
    .mc-comment-form { display: flex; gap: 9px; }
    .mc-comment-form .mc-input { flex: 1; }

    .mc-lightbox { z-index: 90; cursor: zoom-out; }
    .mc-lightbox img { max-width: 92vw; max-height: 88vh; border-radius: 16px; box-shadow: 0 30px 90px rgba(0, 0, 0, 0.75); }

    @media (max-width: 860px) {
        .mc-console-body { grid-template-columns: 1fr; overflow-y: auto; }
        .mc-console-brief { border-right: none; border-bottom: 1px solid var(--mc-line-soft); }
        .mc-timeline { max-height: 30vh; }
    }
    @media (max-width: 640px) {
        .mc-shell { padding: 18px 14px 56px; }
        .mc-board { grid-template-columns: 1fr; }
        .mc-topbar { padding: 16px; }
        .mc-stats { display: none; }
    }
</style>
