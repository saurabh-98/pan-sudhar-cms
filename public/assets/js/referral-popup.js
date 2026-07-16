/* =========================================================
   Referral Popup — interactive behaviors
   Pairs with referral-popup.css
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    const modalEl = document.getElementById('popupModal');

    if (!modalEl) return;

    const confettiLayer = document.getElementById('confettiLayer');

    /* ---------------------------------------------------------
       Escape any ancestor with transform/overflow/position that
       would trap position:fixed and hide the popup underneath
       the page's own header. Must run before modal.show().
    --------------------------------------------------------- */
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }

    if (confettiLayer && confettiLayer.parentElement !== document.body) {
        document.body.appendChild(confettiLayer);
    }

    /* ---------------------------------------------------------
       Confetti
    --------------------------------------------------------- */
    function launchConfetti(){

        if (!confettiLayer) return;

        const colors = ['#0d6efd', '#6610f2', '#ffc107', '#dc3545', '#28a745'];

        const contentEl = modalEl.querySelector('.modal-content');
        const rect = contentEl ? contentEl.getBoundingClientRect() : { left: window.innerWidth / 2, top: 80, width: 0 };
        const originX = rect.left + rect.width / 2;

        for (let i = 0; i < 40; i++) {

            const piece = document.createElement('div');
            piece.className = 'confetti-piece';

            const size = 6 + Math.random() * 6;

            piece.style.width = size + 'px';
            piece.style.height = (size * 0.4) + 'px';
            piece.style.left = (originX + (Math.random() * 260 - 130)) + 'px';
            piece.style.top = (rect.top + 20) + 'px';
            piece.style.background = colors[Math.floor(Math.random() * colors.length)];
            piece.style.animationDuration = (1.2 + Math.random() * 0.9) + 's';
            piece.style.animationDelay = (Math.random() * 0.25) + 's';

            confettiLayer.appendChild(piece);

            piece.addEventListener('animationend', function () {
                piece.remove();
            });

        }

    }

    /* ---------------------------------------------------------
       Countdown timer (resets to 24h each time the popup opens;
       swap endsAt for a real expiry timestamp if available)
    --------------------------------------------------------- */
    let countdownTimer = null;

    function startCountdown(){

        if (countdownTimer) clearInterval(countdownTimer);

        const endsAt = Date.now() + (24 * 60 * 60 * 1000);

        const hoursEl = document.getElementById('cdHours');
        const minutesEl = document.getElementById('cdMinutes');
        const secondsEl = document.getElementById('cdSeconds');
        const strip = document.getElementById('countdownStrip');

        function tick(){

            const remaining = Math.max(0, endsAt - Date.now());

            const h = Math.floor(remaining / 3600000);
            const m = Math.floor((remaining % 3600000) / 60000);
            const s = Math.floor((remaining % 60000) / 1000);

            if (hoursEl) hoursEl.textContent = String(h).padStart(2, '0');
            if (minutesEl) minutesEl.textContent = String(m).padStart(2, '0');
            if (secondsEl) secondsEl.textContent = String(s).padStart(2, '0');

            if (remaining <= 0) {
                clearInterval(countdownTimer);
                if (strip) strip.textContent = '⏳ This offer has expired';
            }

        }

        tick();
        countdownTimer = setInterval(tick, 1000);

    }

    /* ---------------------------------------------------------
       Close with a quick zoom-out instead of an abrupt cut
    --------------------------------------------------------- */
    function closeWithAnimation(){

        const instance = bootstrap.Modal.getOrCreateInstance(modalEl);

        modalEl.classList.add('closing');

        setTimeout(function () {
            instance.hide();
            modalEl.classList.remove('closing');
        }, 200);

    }

    /* ---------------------------------------------------------
       Generic "copy text, flash the button" helper
    --------------------------------------------------------- */
    function copyText(text, onDone){

        const finish = function () {
            if (onDone) onDone();
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(finish).catch(function () {
                fallbackCopy(text);
                finish();
            });
        } else {
            fallbackCopy(text);
            finish();
        }

    }

    function fallbackCopy(text){

        const tmp = document.createElement('textarea');
        tmp.value = text;
        tmp.style.position = 'fixed';
        tmp.style.opacity = '0';
        document.body.appendChild(tmp);
        tmp.select();
        try { document.execCommand('copy'); } catch (e) { /* no-op */ }
        document.body.removeChild(tmp);

    }

    function flashButton(btn, activeLabel, defaultLabel, activeClass){

        if (!btn) return;

        btn.classList.add(activeClass || 'copied');
        const original = btn.innerHTML;
        btn.innerHTML = activeLabel;

        setTimeout(function () {
            btn.classList.remove(activeClass || 'copied');
            btn.innerHTML = defaultLabel || original;
        }, 1800);

    }

    /* ---------------------------------------------------------
       Wire up interactive elements
    --------------------------------------------------------- */
    const giftIcon = document.getElementById('giftIcon');
    const closeBtn = document.getElementById('popupCloseBtn');
    const maybeLaterBtn = document.getElementById('popupMaybeLaterBtn');
    const linkInput = document.getElementById('referralLinkInput');
    const copyLinkBtn = document.getElementById('copyLinkBtn');
    const copyReferralBtn = document.getElementById('copyReferralBtn');
    const downloadQrBtn = document.getElementById('downloadQrBtn');
    const referralCodeCard = document.querySelector('.referral-code-card');
    const qrImage = document.querySelector('.popup-body img.rounded');

    if (giftIcon) {
        giftIcon.addEventListener('click', function () {
            giftIcon.classList.remove('opened');
            void giftIcon.offsetWidth; // restart animation
            giftIcon.classList.add('opened');
            launchConfetti();
        });
    }

    if (closeBtn) closeBtn.addEventListener('click', closeWithAnimation);
    if (maybeLaterBtn) maybeLaterBtn.addEventListener('click', closeWithAnimation);

    if (copyLinkBtn && linkInput) {
        copyLinkBtn.addEventListener('click', function () {
            copyText(linkInput.value, function () {
                copyLinkBtn.dataset.originalHtml = copyLinkBtn.dataset.originalHtml || copyLinkBtn.innerHTML;
                flashButton(copyLinkBtn, '✅ Copied!', copyLinkBtn.dataset.originalHtml);
            });
        });
    }

    if (copyReferralBtn && linkInput) {
        copyReferralBtn.addEventListener('click', function () {
            copyText(linkInput.value, function () {
                copyReferralBtn.dataset.originalHtml = copyReferralBtn.dataset.originalHtml || copyReferralBtn.innerHTML;
                flashButton(copyReferralBtn, '<i class="fas fa-check"></i> Copied', copyReferralBtn.dataset.originalHtml);
            });
        });
    }

    if (referralCodeCard) {

        referralCodeCard.addEventListener('click', function () {

            const codeEl = referralCodeCard.querySelector('h2');
            const code = codeEl ? codeEl.textContent.trim() : '';

            if (!code || code === 'Not Available') return;

            copyText(code, function () {
                referralCodeCard.classList.add('copied');
                setTimeout(function () {
                    referralCodeCard.classList.remove('copied');
                }, 1800);
            });

        });

    }

    if (downloadQrBtn && qrImage) {

        downloadQrBtn.addEventListener('click', function () {

            fetch(qrImage.src)
                .then(function (res) { return res.blob(); })
                .then(function (blob) {

                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'referral-qr-code.png';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(url);

                    downloadQrBtn.dataset.originalHtml = downloadQrBtn.dataset.originalHtml || downloadQrBtn.innerHTML;
                    flashButton(downloadQrBtn, '<i class="fas fa-check"></i> Saved', downloadQrBtn.dataset.originalHtml, 'copied');

                })
                .catch(function () {
                    // Cross-origin fetch blocked — fall back to opening the QR in a new tab
                    window.open(qrImage.src, '_blank');
                });

        });

    }

    modalEl.addEventListener('shown.bs.modal', function () {
        startCountdown();
        launchConfetti();
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        if (countdownTimer) clearInterval(countdownTimer);
    });

});
