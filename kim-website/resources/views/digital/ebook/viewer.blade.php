<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - E-Book Viewer</title>

    <!-- Prevent right-click, print, download -->
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #1a202c;
        color: #fff;
        overflow: hidden;
    }

    /* Prevent print */
    @media print {
        body {
            display: none !important;
        }
    }

    /* Header */
    .viewer-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1000;
    }

    .ebook-title {
        font-size: 18px;
        font-weight: 700;
        color: white;
    }

    .access-info {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 14px;
    }

    .access-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .expire-warning {
        background: rgba(255, 193, 7, 0.3);
        border: 1px solid #ffc107;
    }

    /* PDF Container */
    .pdf-container {
        width: 100%;
        height: calc(100vh - 60px);
        position: relative;
        overflow: hidden;
    }

    iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Watermark */
    .watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 80px;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.03);
        pointer-events: none;
        z-index: 999;
        white-space: nowrap;
        text-align: center;
        line-height: 1.5;
    }

    /* Overlay Protection */
    .protection-overlay {
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 998;
    }

    /* Loading */
    .loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 1001;
    }

    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.2);
        border-top: 4px solid #667eea;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Expired View */
    .expired-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: calc(100vh - 60px);
        text-align: center;
        padding: 40px;
    }

    .expired-card {
        background: white;
        color: #2d3748;
        padding: 50px;
        border-radius: 20px;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .expired-icon {
        font-size: 80px;
        margin-bottom: 20px;
    }

    .expired-card h1 {
        font-size: 28px;
        margin-bottom: 15px;
        color: #e53e3e;
    }

    .expired-card p {
        font-size: 16px;
        line-height: 1.6;
        color: #4a5568;
        margin-bottom: 30px;
    }

    .btn-back {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 15px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        display: inline-block;
        transition: transform 0.3s;
    }

    .btn-back:hover {
        transform: translateY(-3px);
    }

    .drive-popout-blocker {
        position: absolute;
        top: 0;
        right: 0;
        width: 70px;
        height: 70px;
        z-index: 1002;
        background: #764ba2;
        border-bottom-left-radius: 30px;
    }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="viewer-header">
        <div class="ebook-title">
            📖 {{ $product->name }}
        </div>
        <div class="access-info">
            <div class="access-badge {{ $access->days_remaining <= 7 ? 'expire-warning' : '' }}">
                <span>⏱️</span>
                <span>Berlaku {{ $access->days_remaining }} hari lagi</span>
            </div>
            <div class="access-badge">
                <span>👀</span>
                <span>{{ $access->view_count }} kali dibuka</span>
            </div>
        </div>
    </div>

    <!-- Watermark -->
    <div class="watermark">
        {{ $access->user->name ?? 'KIM DIGITAL' }}<br>
        ONLY FOR {{ strtoupper($access->order->customer_email) }}
    </div>

    <!-- Protection Overlay -->
    <div class="protection-overlay"></div>

    <!-- Loading -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <p>Memuat e-book...</p>
    </div>

    <!-- PDF Container -->
    <div class="pdf-container" style="position: relative;">
        <!-- Menggunakan Google Drive Preview -->
        <iframe id="pdfFrame" src="https://drive.google.com/file/d/{{ $fileId }}/preview" allow="autoplay"
            onload="document.getElementById('loading').style.display='none'">
        </iframe>

        <div class="drive-popout-blocker"></div>
    </div>

    <script>
    // Disable right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+P
        if (
            e.key === 'F12' ||
            (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
            (e.ctrlKey && (e.key === 'u' || e.key === 'U' || e.key === 's' || e.key === 'S' || e.key === 'p' ||
                e.key === 'P'))
        ) {
            e.preventDefault();
            return false;
        }
    });

    // Disable drag and drop
    document.addEventListener('dragstart', function(e) {
        e.preventDefault();
        return false;
    });

    // Prevent screenshots with browser methods
    document.addEventListener('copy', function(e) {
        e.preventDefault();
        return false;
    });

    // Clear console periodically
    setInterval(function() {
        console.clear();
    }, 1000);

    // Warn if user tries to open dev tools
    const devtools = {
        isOpen: false,
        orientation: null
    };

    const threshold = 160;

    setInterval(function() {
        if (window.outerWidth - window.innerWidth > threshold ||
            window.outerHeight - window.innerHeight > threshold) {
            if (!devtools.isOpen) {
                devtools.isOpen = true;
                console.log('⚠️ Developer tools terdeteksi! Akses Anda akan dicatat.');
            }
        } else {
            devtools.isOpen = false;
        }
    }, 500);

    // Prevent window.print
    window.print = function() {
        alert('Fungsi print dinonaktifkan untuk melindungi hak cipta.');
        return false;
    };

    // Blur detection (tab switching)
    let blurCount = 0;
    window.addEventListener('blur', function() {
        blurCount++;
        console.log('Tab switching detected:', blurCount);
    });

    // Visibility change detection
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            console.log('User left the page');
        }
    });
    </script>
</body>

</html>