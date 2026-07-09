<style>
    /* ================= Wedevs AI — charcoal × lime =================
       Scoped to .mc-shell; the admin chrome stays light.
       Ground #252728, primary Volt #9EFB25. Every "alive" visual loops
       forever while on screen. */

    @font-face {
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url('{{ asset('fonts/unbounded-700.woff2') }}') format('woff2');
    }
    @font-face {
        font-family: 'Manrope';
        font-style: normal;
        font-weight: 200 800;
        font-display: swap;
        src: url('{{ asset('fonts/manrope-var.woff2') }}') format('woff2');
    }
    @font-face {
        font-family: 'JetBrains Mono';
        font-style: normal;
        font-weight: 100 800;
        font-display: swap;
        src: url('{{ asset('fonts/jetbrains-mono-var.woff2') }}') format('woff2');
    }

    [x-cloak] { display: none !important; }

    .mc-shell {
        --mc-bg: #252728;
        --mc-raise: #2c2f30;
        --mc-sink: #1c1e1f;
        --mc-bone: #f2f4ee;
        --mc-mist: #a6ada4;
        --mc-ghost: #6e756d;
        --mc-hairline: rgba(255, 255, 255, 0.08);
        --mc-lime: #9efb25;
        --mc-lime2: #c4f04a;
        --mc-olive: #8b9781;
        --mc-mint: #5fe87b;
        --mc-pale: #e9f5d0;
        --mc-coral: #ff6b5e;
        position: relative;
        margin: -1rem;
        min-height: 100vh;
        padding: 26px clamp(16px, 3vw, 44px) 70px;
        background:
            radial-gradient(1000px 420px at 82% -12%, rgba(158, 251, 37, 0.05), transparent 60%),
            radial-gradient(760px 420px at 2% 116%, rgba(158, 251, 37, 0.028), transparent 60%),
            var(--mc-bg);
        color: var(--mc-bone);
        font-family: 'Manrope', var(--font-sans, system-ui), sans-serif;
        overflow: hidden;
    }
    .mc-grid-bg {
        position: absolute;
        inset: -60px;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.026) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.026) 1px, transparent 1px);
        background-size: 46px 46px;
        mask-image: radial-gradient(82% 68% at 50% 28%, #000 22%, transparent 100%);
        animation: mcPan 80s linear infinite;
        pointer-events: none;
    }
    @keyframes mcPan { from { transform: translate3d(0, 0, 0); } to { transform: translate3d(46px, 46px, 0); } }
    .mc-mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }

    /* ---------- worker mascot ---------- */
    .mc-bot { position: relative; display: inline-flex; flex-direction: column; align-items: center; flex: 0 0 auto; }
    .mc-bot svg { display: block; animation: mcBob 3.2s ease-in-out infinite; }
    .mc-bot--busy svg { animation: mcBob 1.5s ease-in-out infinite, mcTiltB 3.2s ease-in-out infinite; }
    @keyframes mcBob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes mcTiltB { 0%, 100% { rotate: -2deg; } 50% { rotate: 2.5deg; } }
    .mc-bot-shadow {
        width: 44%; height: 5px; border-radius: 50%;
        background: rgba(0, 0, 0, 0.5); filter: blur(3px); margin-top: 3px;
        animation: mcShx 3.2s ease-in-out infinite;
    }
    .mc-bot--busy .mc-bot-shadow { animation-duration: 1.5s; }
    @keyframes mcShx { 0%, 100% { transform: scaleX(1); opacity: .55; } 50% { transform: scaleX(.68); opacity: .3; } }
    .mc-bot-eye { transform-box: fill-box; transform-origin: center; animation: mcBlinkEye 4.6s infinite; }
    @keyframes mcBlinkEye { 0%, 91%, 100% { transform: scaleY(1); } 94% { transform: scaleY(.08); } }
    .mc-bot-ant { animation: mcAnt 1.9s ease-in-out infinite; }
    @keyframes mcAnt { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }

    /* ---------- topbar ---------- */
    .mc-topbar {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 16px 22px;
        margin-bottom: 20px;
        border: 1px solid var(--mc-hairline);
        border-radius: 18px;
        background: linear-gradient(160deg, rgba(255, 255, 255, 0.045), rgba(255, 255, 255, 0.012));
        box-shadow: 0 18px 46px rgba(0, 0, 0, 0.35);
    }
    .mc-brand { display: flex; align-items: center; gap: 14px; }
    .mc-word {
        font-family: 'Unbounded', sans-serif;
        font-weight: 700;
        font-size: clamp(14px, 1.8vw, 18px);
        letter-spacing: .05em;
        color: var(--mc-bone);
    }
    .mc-word em { font-style: normal; color: var(--mc-lime); text-shadow: 0 0 20px rgba(158, 251, 37, 0.4); }
    .mc-brand-status {
        display: flex; align-items: center; gap: 7px; margin-top: 3px;
        font-family: 'JetBrains Mono', monospace; font-size: 9.5px;
        color: var(--mc-ghost); letter-spacing: .06em;
    }
    .mc-live-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--mc-lime); flex: 0 0 auto;
        animation: mcPulseDot 2s ease-in-out infinite;
    }
    @keyframes mcPulseDot {
        0%, 100% { box-shadow: 0 0 0 0 rgba(158, 251, 37, 0.5); }
        50% { box-shadow: 0 0 0 7px rgba(158, 251, 37, 0); }
    }
    .mc-topbar-right { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .mc-meta { font-size: 12.5px; color: var(--mc-mist); font-weight: 600; }
    .mc-meta b { color: var(--mc-bone); font-weight: 800; }
    .mc-clock {
        font-family: 'JetBrains Mono', monospace; font-size: 12.5px;
        color: var(--mc-lime); letter-spacing: .1em; opacity: .85;
        font-variant-numeric: tabular-nums;
    }
    .mc-launch {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 20px; border: none; border-radius: 12px;
        background: linear-gradient(165deg, #b7fc55, #86e00e);
        color: #16200a; font-size: 13.5px; font-weight: 800;
        cursor: pointer; transition: transform .2s, box-shadow .2s;
        animation: mcLimeGlow 3.4s ease-in-out infinite;
        font-family: inherit;
    }
    .mc-launch:hover { transform: translateY(-2px); box-shadow: 0 12px 34px rgba(158, 251, 37, 0.35); }
    @keyframes mcLimeGlow {
        0%, 100% { box-shadow: 0 8px 24px rgba(158, 251, 37, 0.15); }
        50% { box-shadow: 0 10px 32px rgba(158, 251, 37, 0.3); }
    }

    /* ---------- ticker / shimmer / typeline ---------- */
    .mc-ticker {
        display: inline-block;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px; font-weight: 500; letter-spacing: .04em;
        white-space: nowrap; max-width: 100%; overflow: hidden; text-overflow: ellipsis;
        vertical-align: bottom;
    }
    .mc-shimmer {
        background: linear-gradient(100deg, #79816f 25%, #e9f5d0 42%, #79816f 60%);
        background-size: 220% 100%;
        -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .mc-ticker--a { animation: mcTickA .5s cubic-bezier(.2,.9,.3,1), mcShimmer 2.6s linear infinite; }
    .mc-ticker--b { animation: mcTickB .5s cubic-bezier(.2,.9,.3,1), mcShimmer 2.6s linear infinite; }
    @keyframes mcTickA { from { opacity: 0; transform: translateY(6px); filter: blur(3px); } to { opacity: 1; transform: none; filter: none; } }
    @keyframes mcTickB { from { opacity: 0; transform: translateY(6px); filter: blur(3px); } to { opacity: 1; transform: none; filter: none; } }
    @keyframes mcShimmer { from { background-position: 200% 0; } to { background-position: -20% 0; } }
    .mc-typeline {
        display: inline-block; overflow: hidden; white-space: nowrap; vertical-align: bottom; max-width: 100%;
        border-right: 2px solid var(--mc-lime);
        animation: mcType 5s steps(40, end) infinite, mcCaret .75s step-end infinite;
        font-family: 'JetBrains Mono', monospace; font-size: 11.5px; color: var(--mc-mist);
    }
    @keyframes mcType { 0% { width: 0; } 55% { width: 40ch; } 82% { width: 40ch; } 100% { width: 40ch; } }
    @keyframes mcCaret { 50% { border-color: transparent; } }

    /* ---------- live hero ---------- */
    .mc-live {
        display: grid;
        grid-template-columns: 1.55fr .85fr;
        border: 1px solid rgba(158, 251, 37, 0.22);
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(150deg, rgba(158, 251, 37, 0.05), rgba(255, 255, 255, 0.012) 55%);
        box-shadow: inset 0 0 70px rgba(158, 251, 37, 0.03), 0 24px 56px rgba(0, 0, 0, 0.35);
        margin-bottom: 24px;
    }
    @media (max-width: 860px) { .mc-live { grid-template-columns: 1fr; } }
    .mc-live-main { padding: 20px 26px; display: flex; flex-direction: column; gap: 11px; min-width: 0; }
    .mc-live-kick { display: flex; align-items: center; gap: 10px; }
    .mc-live-kick .k {
        font-size: 10px; font-weight: 800; letter-spacing: .22em;
        text-transform: uppercase; color: var(--mc-lime);
    }
    .mc-chip-urgent {
        margin-left: auto; padding: 4px 12px; border-radius: 999px;
        border: 1px solid rgba(255, 107, 94, 0.4); background: rgba(255, 107, 94, 0.1);
        color: #ff9d93; font-size: 9.5px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase;
        animation: mcBlink 1.8s ease-in-out infinite;
    }
    @keyframes mcBlink { 0%, 100% { opacity: 1; } 50% { opacity: .55; } }
    .mc-live-title { margin: 0; font-size: clamp(18px, 2.3vw, 23px); font-weight: 800; letter-spacing: -.01em; color: var(--mc-bone); cursor: pointer; }
    .mc-live-title:hover { color: var(--mc-lime2); }
    .mc-live-with { display: flex; align-items: center; gap: 10px; font-size: 12.5px; color: var(--mc-mist); font-weight: 600; }
    .mc-console {
        border: 1px solid var(--mc-hairline); border-radius: 13px;
        background: var(--mc-sink); padding: 13px 16px;
        font-family: 'JetBrains Mono', monospace; font-size: 11.5px; line-height: 2.1;
        color: var(--mc-mist); overflow: hidden;
    }
    .mc-console .ok { color: var(--mc-mint); }
    .mc-console .arrow { color: var(--mc-lime); }
    .mc-live-actions { display: flex; gap: 9px; }
    .mc-live-side {
        border-left: 1px solid rgba(158, 251, 37, 0.14);
        padding: 20px;
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 13px;
        background: rgba(0, 0, 0, 0.18);
    }
    @media (max-width: 860px) { .mc-live-side { border-left: none; border-top: 1px solid rgba(158,251,37,.14); } }
    .mc-ring {
        position: relative; width: 116px; height: 116px; border-radius: 50%;
        background: conic-gradient(var(--mc-lime) calc(var(--pct, 68) * 1%), rgba(255, 255, 255, 0.06) 0);
    }
    .mc-ring::before { content: ""; position: absolute; inset: 9px; border-radius: 50%; background: #1e2021; }
    .mc-ring::after {
        content: ""; position: absolute; inset: -5px; border-radius: 50%;
        border: 1px solid rgba(158, 251, 37, 0.18);
        animation: mcRingB 2.6s ease-in-out infinite;
    }
    @keyframes mcRingB { 0%, 100% { transform: scale(1); opacity: .7; } 50% { transform: scale(1.06); opacity: .2; } }
    .mc-ring b {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        font-family: 'Unbounded', sans-serif; font-weight: 700; font-size: 23px; color: var(--mc-bone);
    }
    .mc-ring b small { font-size: 10px; margin-left: 2px; color: var(--mc-mist); }
    .mc-side-rows { display: flex; flex-direction: column; gap: 5px; width: 100%; max-width: 186px; }
    .mc-side-row {
        display: flex; justify-content: space-between;
        font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mc-ghost);
        font-variant-numeric: tabular-nums;
    }
    .mc-side-row b { color: var(--mc-mist); font-weight: 400; }
    .mc-side-row .sol { color: var(--mc-lime); }

    /* idle hero */
    .mc-idle { display: flex; align-items: center; gap: 18px; padding: 24px 28px; }
    .mc-idle p { margin: 0; }
    .mc-idle .t { font-size: 17px; font-weight: 800; color: var(--mc-bone); }

    /* ---------- metro pipeline map ---------- */
    .mc-metro {
        border: 1px solid var(--mc-hairline);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.018);
        padding: 26px 26px 18px;
        margin-bottom: 24px;
        overflow-x: auto;
    }
    .mc-metro-line { position: relative; display: flex; align-items: flex-start; min-width: 560px; }
    .mc-metro-line::before {
        content: "";
        position: absolute; top: 15px; left: 9%; right: 9%; height: 3px; border-radius: 3px;
        background: linear-gradient(90deg, #8b9781, #c4f04a 30%, #5fe87b 52%, #9efb25 76%, #e9f5d0);
        opacity: .45;
    }
    .mc-station { position: relative; flex: 1; display: flex; flex-direction: column; align-items: center; gap: 7px; min-width: 0; }
    .mc-st-node {
        width: 32px; height: 32px; border-radius: 50%;
        border: 2px solid var(--st, var(--mc-lime));
        background: #1e2021; color: var(--st, var(--mc-lime));
        display: flex; align-items: center; justify-content: center; font-size: 14px; z-index: 1;
    }
    .mc-st-node--busy { animation: mcStPulse 1.9s ease-in-out infinite; }
    @keyframes mcStPulse {
        0%, 100% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--st, #9efb25) 40%, transparent); }
        50% { box-shadow: 0 0 0 8px transparent; }
    }
    .mc-st-lbl { font-size: 8.5px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: var(--st, var(--mc-lime)); }
    .mc-st-chip {
        margin-top: 3px; padding: 5px 12px; border-radius: 999px;
        border: 1px solid color-mix(in srgb, var(--st, #9efb25) 45%, transparent);
        background: color-mix(in srgb, var(--st, #9efb25) 9%, transparent);
        color: var(--mc-bone); font-size: 10.5px; font-weight: 600;
        max-width: 92%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        cursor: pointer; transition: transform .18s;
    }
    .mc-st-chip:hover { transform: translateY(-2px); }

    /* ---------- tabs ---------- */
    .mc-tabs { display: flex; gap: 10px; margin-bottom: 18px; }
    .mc-tab {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 19px; border: 1px solid var(--mc-hairline); border-radius: 999px;
        background: rgba(255, 255, 255, 0.02); color: var(--mc-mist);
        font-size: 12.5px; font-weight: 700; cursor: pointer; transition: all .22s;
        font-family: inherit;
    }
    .mc-tab:hover { color: var(--mc-bone); }
    .mc-tab-on {
        color: var(--mc-bone);
        border-color: rgba(158, 251, 37, 0.45);
        background: rgba(158, 251, 37, 0.08);
        box-shadow: 0 0 20px rgba(158, 251, 37, 0.1);
    }
    .mc-tab-count { font-family: 'JetBrains Mono', monospace; font-size: 10.5px; color: var(--mc-ghost); }
    .mc-tab-on .mc-tab-count { color: var(--mc-lime); }

    /* ---------- board / cards ---------- */
    .mc-board { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; align-items: start; }
    .mc-card {
        --st: var(--mc-mist);
        position: relative;
        display: flex; flex-direction: column; gap: 11px;
        padding: 17px 18px 15px;
        border: 1px solid var(--mc-hairline);
        border-radius: 18px;
        background: var(--mc-raise);
        animation: mcCardIn .5s cubic-bezier(.2,.9,.3,1);
        transition: transform .22s, border-color .22s, box-shadow .22s;
        overflow: hidden;
    }
    .mc-card:hover { transform: translateY(-3px); border-color: rgba(255, 255, 255, 0.18); box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35); }
    @keyframes mcCardIn { from { opacity: 0; transform: translateY(14px) scale(.98); } to { opacity: 1; transform: none; } }
    .mc-card::before {
        content: ""; position: absolute; top: 0; left: 10%; right: 10%; height: 2px; border-radius: 2px;
        background: linear-gradient(90deg, transparent, var(--st), transparent); opacity: .85;
    }
    .mc-card--st-awaiting_review { --st: var(--mc-olive); }
    .mc-card--st-under_review { --st: var(--mc-lime2); }
    .mc-card--st-approved { --st: var(--mc-mint); }
    .mc-card--st-in_progress { --st: var(--mc-lime); border-color: rgba(158, 251, 37, 0.28); }
    .mc-card--st-delivered { --st: var(--mc-pale); }
    .mc-card--urgent::after {
        content: ""; position: absolute; inset: 0; border-radius: 18px; pointer-events: none;
        animation: mcUrg 2.2s ease-in-out infinite;
    }
    @keyframes mcUrg {
        0%, 100% { box-shadow: inset 0 0 0 1px rgba(255, 107, 94, 0); }
        50% { box-shadow: inset 0 0 0 1px rgba(255, 107, 94, 0.4), inset 0 0 30px rgba(255, 107, 94, 0.05); }
    }

    .mc-state-row { display: flex; align-items: center; gap: 8px; }
    .mc-state {
        display: inline-flex; align-items: center; gap: 7px;
        font-size: 9px; font-weight: 800; letter-spacing: .18em; text-transform: uppercase; color: var(--st);
    }
    .mc-state i {
        width: 6px; height: 6px; border-radius: 50%; background: var(--st);
        box-shadow: 0 0 8px var(--st);
        animation: mcBlink 1.9s ease-in-out infinite; flex: 0 0 auto;
    }
    .mc-prio {
        margin-left: auto; font-size: 8.5px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase;
        color: var(--mc-ghost);
    }
    .mc-prio--urgent { color: #ff9d93; animation: mcBlink 1.5s ease-in-out infinite; }
    .mc-prio--normal { color: var(--mc-mist); }
    .mc-time { font-size: 10.5px; color: var(--mc-ghost); margin-left: 10px; }

    .mc-card-title { margin: 0; font-size: 15.5px; font-weight: 750; color: var(--mc-bone); cursor: pointer; line-height: 1.35; }
    .mc-card-title:hover { color: var(--mc-lime2); }
    .mc-card-desc {
        margin: 0; font-size: 12.5px; line-height: 1.55; color: var(--mc-mist);
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .mc-thumb-wrap { position: relative; border-radius: 12px; overflow: hidden; border: 1px solid var(--mc-hairline); cursor: zoom-in; }
    .mc-thumb { display: block; width: 100%; height: 118px; object-fit: cover; transition: transform .35s; }
    .mc-thumb-wrap:hover .mc-thumb { transform: scale(1.05); }
    .mc-thumb-zoom {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: 7px;
        background: rgba(20, 22, 22, 0.6); color: var(--mc-bone);
        font-size: 11.5px; font-weight: 700; opacity: 0; transition: opacity .25s;
    }
    .mc-thumb-wrap:hover .mc-thumb-zoom { opacity: 1; }
    .mc-meta-row { display: flex; flex-wrap: wrap; gap: 7px; }
    .mc-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 11px; border: 1px solid var(--mc-hairline); border-radius: 999px;
        background: rgba(255, 255, 255, 0.02); color: var(--mc-mist);
        font-size: 11px; font-weight: 600; white-space: nowrap;
    }
    .mc-chip--overdue {
        background: rgba(255, 107, 94, 0.1); border-color: rgba(255, 107, 94, 0.4); color: #ff9d93;
        animation: mcBlink 1.3s ease-in-out infinite;
    }
    .mc-chip--countdown {
        font-family: 'JetBrains Mono', monospace;
        background: rgba(95, 232, 123, 0.08); border-color: rgba(95, 232, 123, 0.4); color: #8ff0aa;
        animation: mcBlink 1.7s ease-in-out infinite;
        font-variant-numeric: tabular-nums;
    }
    .mc-card .mc-ticker { color: var(--mc-ghost); }
    .mc-thin-bar { height: 3px; border-radius: 3px; background: rgba(255, 255, 255, 0.06); overflow: hidden; }
    .mc-thin-bar i {
        display: block; height: 100%;
        background: linear-gradient(90deg, #86e00e, #c4f04a);
        box-shadow: 0 0 8px rgba(158, 251, 37, 0.5);
        transition: width .6s cubic-bezier(.2,.9,.3,1);
    }

    /* ---------- per-card stepper ---------- */
    .mc-pipe { position: relative; padding: 5px 4px 0; }
    .mc-pipe-rail {
        position: absolute; top: 19px; left: 24px; right: 24px; height: 2px; border-radius: 2px;
        background: rgba(255, 255, 255, 0.09); overflow: hidden;
    }
    .mc-pipe-fill {
        height: 100%; border-radius: 2px;
        background: linear-gradient(90deg, #86e00e, #c4f04a);
        box-shadow: 0 0 10px rgba(158, 251, 37, 0.5);
        transition: width .6s cubic-bezier(.2,.9,.3,1);
    }
    .mc-pipe-steps { position: relative; display: flex; justify-content: space-between; }
    .mc-step {
        display: flex; flex-direction: column; align-items: center; gap: 5px;
        border: none; background: transparent; padding: 0; cursor: default; color: var(--mc-ghost);
        font-family: inherit;
    }
    .mc-pipe--live .mc-step { cursor: pointer; }
    .mc-step-node {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        border: 1px solid var(--mc-hairline); background: #1e2021;
        font-size: 12px; transition: all .25s;
    }
    .mc-step-lbl { font-size: 8px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
    .mc-step--done { color: var(--mc-mist); }
    .mc-step--done .mc-step-node {
        border-color: rgba(158, 251, 37, 0.4);
        background: rgba(158, 251, 37, 0.1);
        color: var(--mc-lime2);
    }
    .mc-step--now { color: var(--mc-bone); }
    .mc-step--now .mc-step-node {
        border-color: var(--st, var(--mc-lime));
        color: var(--st, var(--mc-lime));
        animation: mcStPulse 1.8s ease-in-out infinite;
    }
    .mc-pipe--live .mc-step:hover .mc-step-node { transform: scale(1.14); border-color: rgba(255, 255, 255, 0.4); color: var(--mc-bone); }

    /* ---------- card actions ---------- */
    .mc-actions { display: flex; align-items: center; gap: 7px; }
    .mc-act-spacer { flex: 1; }
    .mc-act {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 13px; border: 1px solid var(--mc-hairline); border-radius: 10px;
        background: transparent; color: var(--mc-mist);
        font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all .2s;
        font-family: inherit;
    }
    .mc-act:hover:not(:disabled) { color: var(--mc-bone); border-color: rgba(255, 255, 255, 0.26); }
    .mc-act:disabled { opacity: .45; cursor: not-allowed; }
    .mc-act--icon { padding: 7px 9px; }
    .mc-act--danger:hover { color: #ff9d93; border-color: rgba(255, 107, 94, 0.5); }
    .mc-cta {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 15px; border: none; border-radius: 10px;
        background: linear-gradient(165deg, #b7fc55, #86e00e);
        color: #16200a; font-size: 11.5px; font-weight: 800;
        cursor: pointer; transition: transform .2s, box-shadow .2s;
        font-family: inherit;
    }
    .mc-cta:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(158, 251, 37, 0.3); }

    .mc-empty {
        grid-column: 1 / -1;
        display: flex; flex-direction: column; align-items: center; gap: 14px;
        padding: 60px 0; border: 1.5px dashed var(--mc-hairline); border-radius: 20px;
        color: var(--mc-mist); font-size: 13.5px;
    }

    /* ---------- modals / overlays ---------- */
    .mc-modal-scrim, .mc-overlay, .mc-lightbox {
        position: fixed; inset: 0; z-index: 60;
        display: flex; align-items: center; justify-content: center;
        background: rgba(16, 17, 17, 0.82);
        backdrop-filter: blur(9px);
        padding: 20px;
    }
    .mc-modal {
        width: min(560px, 100%);
        max-height: 90vh; overflow-y: auto;
        padding: 28px;
        border: 1px solid var(--mc-hairline);
        border-radius: 20px;
        background: linear-gradient(165deg, #2b2e2f, #212324);
        box-shadow: 0 34px 90px rgba(0, 0, 0, 0.6);
        animation: mcCardIn .35s cubic-bezier(.2,.9,.3,1);
        color: var(--mc-bone);
        font-family: 'Manrope', sans-serif;
    }
    .mc-modal h2 { margin: 0 0 4px; font-size: 19px; font-weight: 800; }
    .mc-modal .mc-hint { margin: 0 0 20px; font-size: 12.5px; color: var(--mc-mist); }
    .mc-optional { opacity: .5; text-transform: none; letter-spacing: 0; }
    .mc-field { margin-bottom: 15px; }
    .mc-field label {
        display: block; margin-bottom: 6px;
        font-size: 10px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: var(--mc-ghost);
    }
    .mc-input, .mc-textarea {
        width: 100%; padding: 11px 13px;
        border: 1px solid var(--mc-hairline); border-radius: 11px;
        background: var(--mc-sink); color: var(--mc-bone);
        font-size: 14px; outline: none; font-family: inherit;
        transition: border-color .2s, box-shadow .2s;
    }
    .mc-input:focus, .mc-textarea:focus { border-color: rgba(158, 251, 37, 0.5); box-shadow: 0 0 0 3px rgba(158, 251, 37, 0.12); }
    .mc-textarea { min-height: 96px; resize: vertical; }
    .mc-error { margin: 5px 0 0; font-size: 12px; color: #ff9d93; }
    .mc-file-hidden { display: none; }
    .mc-seg { display: flex; gap: 8px; }
    .mc-seg button {
        flex: 1; padding: 9px 0;
        border: 1px solid var(--mc-hairline); border-radius: 10px;
        background: rgba(255, 255, 255, 0.02); color: var(--mc-mist);
        font-size: 11.5px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
        cursor: pointer; transition: all .2s; font-family: inherit;
    }
    .mc-seg .mc-seg-on--low { color: #cfd6cc; border-color: rgba(166, 173, 164, 0.6); background: rgba(166, 173, 164, 0.1); }
    .mc-seg .mc-seg-on--normal { color: var(--mc-lime2); border-color: rgba(196, 240, 74, 0.55); background: rgba(196, 240, 74, 0.1); }
    .mc-seg .mc-seg-on--urgent { color: #ff9d93; border-color: rgba(255, 107, 94, 0.55); background: rgba(255, 107, 94, 0.1); }
    .mc-drop {
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;
        padding: 18px; border: 1.5px dashed rgba(255, 255, 255, 0.16); border-radius: 13px;
        color: var(--mc-mist); font-size: 12.5px; cursor: pointer; transition: border-color .2s;
    }
    .mc-drop:hover { border-color: rgba(158, 251, 37, 0.45); }
    .mc-drop img { max-height: 110px; border-radius: 9px; }
    .mc-modal-actions { display: flex; gap: 10px; margin-top: 22px; }
    .mc-btn-ghost {
        padding: 12px 18px; border: 1px solid var(--mc-hairline); border-radius: 12px;
        background: transparent; color: var(--mc-mist);
        font-size: 13px; font-weight: 700; cursor: pointer; font-family: inherit;
    }
    .mc-btn-primary {
        flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 9px;
        padding: 12px 18px; border: none; border-radius: 12px;
        background: linear-gradient(165deg, #b7fc55, #86e00e);
        color: #16200a; font-size: 14px; font-weight: 800;
        cursor: pointer; transition: transform .2s, box-shadow .2s; font-family: inherit;
    }
    .mc-btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(158, 251, 37, 0.3); }
    .mc-btn-primary:disabled { opacity: .6; cursor: wait; }
    .mc-btn-primary--sq { flex: 0 0 auto; }

    /* ---------- dispatch overlay ---------- */
    .mc-overlay { z-index: 80; text-align: center; flex-direction: column; }
    .mc-overlay-title { margin: 20px 0 10px; font-size: clamp(18px, 2.4vw, 25px); font-weight: 800; color: var(--mc-bone); }

    /* ---------- task console modal ---------- */
    .mc-console-modal {
        width: min(880px, 100%);
        max-height: 90vh;
        display: flex; flex-direction: column;
        border: 1px solid var(--mc-hairline);
        border-radius: 22px;
        background: linear-gradient(170deg, #2b2e2f, #1f2122);
        box-shadow: 0 40px 110px rgba(0, 0, 0, 0.65);
        animation: mcCardIn .35s cubic-bezier(.2,.9,.3,1);
        color: var(--mc-bone); overflow: hidden;
        font-family: 'Manrope', sans-serif;
    }
    .mc-cons-head {
        display: flex; align-items: center; gap: 14px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--mc-hairline);
        background: rgba(255, 255, 255, 0.02);
    }
    .mc-cons-copy { flex: 1; min-width: 0; }
    .mc-cons-head h2 { margin: 0 0 4px; font-size: 17px; font-weight: 800; }
    .mc-cons-head p { display: flex; align-items: center; gap: 10px; margin: 0; }
    .mc-cons-state { font-size: 9.5px; font-weight: 800; letter-spacing: .18em; text-transform: uppercase; color: var(--st, var(--mc-lime)); }
    .mc-x {
        flex: 0 0 auto; border: 1px solid var(--mc-hairline); border-radius: 10px;
        background: transparent; color: var(--mc-mist);
        width: 34px; height: 34px; font-size: 17px; cursor: pointer; transition: all .2s;
    }
    .mc-x:hover { color: var(--mc-bone); border-color: rgba(255, 255, 255, 0.3); }
    .mc-cons-body { display: grid; grid-template-columns: 1fr 1.1fr; overflow: hidden; min-height: 0; }
    .mc-cons-brief {
        padding: 18px 22px; border-right: 1px solid var(--mc-hairline);
        overflow-y: auto; display: flex; flex-direction: column; gap: 13px;
    }
    .mc-kicker { margin: 0; font-size: 9.5px; font-weight: 800; letter-spacing: .2em; text-transform: uppercase; color: var(--mc-ghost); }
    .mc-cons-desc { margin: 0; font-size: 13px; line-height: 1.65; color: var(--mc-mist); white-space: pre-line; }
    .mc-cons-feed { display: flex; flex-direction: column; padding: 18px 22px; overflow: hidden; min-height: 0; }
    .mc-timeline {
        flex: 1; overflow-y: auto; margin: 11px 0 13px; padding-left: 4px;
        border-left: 1px solid rgba(255, 255, 255, 0.1);
        min-height: 120px; max-height: 44vh;
    }
    .mc-event { position: relative; padding: 0 0 14px 17px; }
    .mc-event::before {
        content: ""; position: absolute; left: -4.5px; top: 5px;
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--mc-lime); box-shadow: 0 0 8px rgba(158, 251, 37, 0.6);
    }
    .mc-event--comment::before { background: var(--mc-pale); box-shadow: 0 0 8px rgba(233, 245, 208, 0.5); }
    .mc-event p { margin: 0; font-size: 12px; color: var(--mc-mist); }
    .mc-event b { color: var(--mc-bone); font-weight: 700; }
    .mc-ev-time { margin-left: 7px; font-size: 10.5px; color: var(--mc-ghost); }
    .mc-bubble {
        margin-top: 5px; padding: 10px 13px;
        border: 1px solid var(--mc-hairline); border-radius: 12px;
        background: rgba(158, 251, 37, 0.04);
        font-size: 12.5px; color: var(--mc-bone); white-space: pre-line;
    }
    .mc-comment-form { display: flex; gap: 9px; }
    .mc-comment-form .mc-input { flex: 1; }

    .mc-lightbox { z-index: 90; cursor: zoom-out; }
    .mc-lightbox img { max-width: 92vw; max-height: 88vh; border-radius: 16px; box-shadow: 0 30px 90px rgba(0, 0, 0, 0.75); }

    @media (max-width: 860px) {
        .mc-cons-body { grid-template-columns: 1fr; overflow-y: auto; }
        .mc-cons-brief { border-right: none; border-bottom: 1px solid var(--mc-hairline); }
        .mc-timeline { max-height: 30vh; }
    }
    @media (max-width: 640px) {
        .mc-shell { padding: 18px 14px 56px; }
        .mc-board { grid-template-columns: 1fr; }
        .mc-topbar { padding: 14px 16px; }
        .mc-meta { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .mc-shell *, .mc-shell *::before, .mc-shell *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; }
    }
</style>
