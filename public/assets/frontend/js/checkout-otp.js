(function () {
    var sendBtn = document.getElementById('checkout_otp_send');
    var verifyBtn = document.getElementById('checkout_otp_verify');
    var otpInput = document.getElementById('checkout_otp');
    var msg = document.getElementById('checkout_otp_msg');
    var submitBtn = document.getElementById('checkout_submit_btn');
    var phoneInput = document.getElementById('phone');
    var tokenInput = document.querySelector('input[name="_token"]');

    if (!sendBtn || !verifyBtn || !otpInput || !phoneInput || !tokenInput) {
        return;
    }

    var csrf = tokenInput.value;
    var sendUrl = sendBtn.getAttribute('data-send-url');
    var verifyUrl = sendBtn.getAttribute('data-verify-url');
    var cooldownTimer = null;
    var verified = false;

    function setMsg(text, ok) {
        if (!msg) return;
        msg.textContent = text;
        msg.style.color = ok ? '#198754' : '#dc3545';
    }

    function postJson(url, payload) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        }).then(function (r) {
            return r.json().then(function (body) {
                return { ok: r.ok, body: body };
            });
        });
    }

    function startCooldown(seconds) {
        var remaining = seconds;
        sendBtn.disabled = true;
        sendBtn.textContent = 'পুনরায় পাঠান (' + remaining + ')';
        cooldownTimer = setInterval(function () {
            remaining--;
            if (remaining <= 0) {
                clearInterval(cooldownTimer);
                sendBtn.disabled = verified;
                sendBtn.textContent = 'OTP পাঠান';
                return;
            }
            sendBtn.textContent = 'পুনরায় পাঠান (' + remaining + ')';
        }, 1000);
    }

    function markUnverified() {
        if (!verified) return;
        verified = false;
        if (submitBtn) submitBtn.disabled = true;
        setMsg('মোবাইল নম্বর পরিবর্তন হয়েছে, আবার OTP পাঠান।', false);
    }

    sendBtn.addEventListener('click', function () {
        if (!phoneInput.value) {
            setMsg('আগে মোবাইল নম্বর লিখুন।', false);
            return;
        }
        sendBtn.disabled = true;
        postJson(sendUrl, { phone: phoneInput.value }).then(function (res) {
            if (res.ok) {
                setMsg(res.body.message || 'OTP পাঠানো হয়েছে।', true);
                startCooldown(60);
            } else {
                setMsg(res.body.message || 'OTP পাঠাতে ব্যর্থ হয়েছে।', false);
                sendBtn.disabled = false;
            }
        }).catch(function () {
            setMsg('OTP পাঠাতে ব্যর্থ হয়েছে।', false);
            sendBtn.disabled = false;
        });
    });

    verifyBtn.addEventListener('click', function () {
        if (!otpInput.value) {
            setMsg('OTP লিখুন।', false);
            return;
        }
        verifyBtn.disabled = true;
        postJson(verifyUrl, { phone: phoneInput.value, otp: otpInput.value }).then(function (res) {
            verifyBtn.disabled = false;
            if (res.ok) {
                verified = true;
                setMsg(res.body.message || 'মোবাইল নম্বর ভেরিফাই হয়েছে।', true);
                if (submitBtn) submitBtn.disabled = false;
                otpInput.disabled = true;
                sendBtn.disabled = true;
                clearInterval(cooldownTimer);
            } else {
                setMsg(res.body.message || 'ভুল OTP।', false);
            }
        }).catch(function () {
            verifyBtn.disabled = false;
            setMsg('ভেরিফাই করতে ব্যর্থ হয়েছে।', false);
        });
    });

    otpInput.addEventListener('input', function () {
        verifyBtn.disabled = !this.value;
    });

    phoneInput.addEventListener('input', markUnverified);
})();
