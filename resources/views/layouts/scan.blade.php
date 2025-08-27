<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scanner - Klinik Medis</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qr-scanner/1.4.2/qr-scanner.umd.min.js"></script>
    <style>
        :root {
            --medical-blue: #2563eb;
            --medical-teal: #0891b2;
            --medical-mint: #10b981;
            --medical-light: #f0f9ff;
            --medical-white: #ffffff;
            --medical-gray: #f8fafc;
            --accent-green: #059669;
            --accent-orange: #ea580c;
            --accent-red: #dc2626;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --text-muted: #94a3b8;
            --shadow-medical: 0 4px 6px -1px rgba(37, 99, 235, 0.1), 0 2px 4px -1px rgba(37, 99, 235, 0.06);
            --glow-medical: 0 0 20px rgba(37, 99, 235, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--medical-blue) 0%, var(--medical-teal) 50%, var(--medical-mint) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255,255,255,0.1) 1px, transparent 1px),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 60px 60px, 80px 80px;
            animation: medicalPattern 30s linear infinite;
        }

        @keyframes medicalPattern {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        .scanner-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            box-shadow: var(--shadow-medical);
            padding: 2rem;
            max-width: 500px;
            width: 95%;
            text-align: center;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: containerSlide 0.8s ease-out;
        }

        @keyframes containerSlide {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .scanner-header {
            margin-bottom: 2rem;
        }

        .scanner-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .scanner-title i {
            color: var(--medical-blue);
            font-size: 1.8rem;
        }

        .scanner-subtitle {
            color: var(--text-light);
            font-size: 1rem;
            font-weight: 400;
        }

        .camera-container {
            position: relative;
            background: var(--medical-gray);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 2rem;
            border: 3px solid var(--medical-blue);
            box-shadow: var(--shadow-medical);
        }

        #qr-video {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
        }

        .scanner-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            border: 3px solid var(--medical-blue);
            border-radius: 20px;
            box-shadow: 
                0 0 0 4px rgba(37, 99, 235, 0.2),
                inset 0 0 0 4px rgba(37, 99, 235, 0.1);
            animation: scannerPulse 2s ease-in-out infinite;
        }

        @keyframes scannerPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.05); opacity: 0.8; }
        }

        .scanner-overlay::before,
        .scanner-overlay::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            border: 4px solid var(--accent-green);
        }

        .scanner-overlay::before {
            top: -4px;
            left: -4px;
            border-right: none;
            border-bottom: none;
            border-top-left-radius: 20px;
        }

        .scanner-overlay::after {
            bottom: -4px;
            right: -4px;
            border-left: none;
            border-top: none;
            border-bottom-right-radius: 20px;
        }

        .scanner-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent-green), transparent);
            animation: scanLine 2s linear infinite;
        }

        @keyframes scanLine {
            0% { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(194px); opacity: 0; }
        }

        .control-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 120px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--medical-blue), var(--medical-teal));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow-medical);
        }

        .btn-secondary {
            background: var(--medical-gray);
            color: var(--text-dark);
            border: 2px solid var(--medical-blue);
        }

        .btn-secondary:hover {
            background: var(--medical-blue);
            color: white;
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .result-container {
            background: var(--medical-light);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 1rem;
            border: 2px solid var(--medical-blue);
            display: none;
            animation: resultShow 0.5s ease-out;
        }

        @keyframes resultShow {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .result-header i {
            color: var(--accent-green);
            font-size: 1.5rem;
        }

        .result-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .result-content {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(37, 99, 235, 0.2);
            word-break: break-all;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: var(--text-dark);
            text-align: left;
        }

        .status-indicator {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-scanning {
            background: rgba(37, 99, 235, 0.1);
            color: var(--medical-blue);
            border: 1px solid var(--medical-blue);
        }

        .status-success {
            background: rgba(5, 150, 105, 0.1);
            color: var(--accent-green);
            border: 1px solid var(--accent-green);
        }

        .status-error {
            background: rgba(234, 88, 12, 0.1);
            color: var(--accent-orange);
            border: 1px solid var(--accent-orange);
        }

        .pulse {
            animation: pulse 1s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .camera-selection {
            margin-bottom: 1rem;
        }

        .camera-select {
            padding: 0.75rem 1rem;
            border: 2px solid var(--medical-blue);
            border-radius: 12px;
            background: white;
            color: var(--text-dark);
            font-size: 1rem;
            width: 100%;
            max-width: 300px;
            cursor: pointer;
        }

        .camera-select:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        @media (max-width: 576px) {
            .scanner-container {
                padding: 1.5rem;
                margin: 10px;
            }

            .scanner-title {
                font-size: 1.6rem;
            }

            #qr-video {
                height: 250px;
            }

            .scanner-overlay {
                width: 150px;
                height: 150px;
            }

            .control-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="scanner-container">
        <div class="scanner-header">
            <h1 class="scanner-title">
                <i class="fas fa-qrcode"></i>
                QR Scanner
            </h1>
            <p class="scanner-subtitle">Arahkan kamera ke QR code untuk memindai</p>
        </div>

        <div class="status-indicator status-scanning">
            <i class="fas fa-circle pulse"></i>
            Siap Memindai
        </div>

        <div class="camera-selection">
            <select class="camera-select" id="camera-select">
                <option value="">Pilih Kamera...</option>
            </select>
        </div>

        <div class="camera-container">
            <video id="qr-video" playsinline></video>
            <div class="scanner-overlay">
                <div class="scanner-line"></div>
            </div>
        </div>

        <div class="control-buttons">
            <button class="btn btn-primary" id="start-btn">
                <i class="fas fa-play"></i>
                Mulai Scan
            </button>
            <button class="btn btn-secondary" id="stop-btn" disabled>
                <i class="fas fa-stop"></i>
                Berhenti
            </button>
        </div>

        <div class="result-container" id="result-container">
            <div class="result-header">
                <i class="fas fa-check-circle"></i>
                <span class="result-title">Hasil Scan QR Code</span>
            </div>
            <div class="result-content" id="result-content">
                <!-- Hasil scan akan muncul disini -->
            </div>
        </div>
    </div>

    <script>
        class MedicalQRScanner {
            constructor() {
                this.scanner = null;
                this.videoElement = document.getElementById('qr-video');
                this.startBtn = document.getElementById('start-btn');
                this.stopBtn = document.getElementById('stop-btn');
                this.resultContainer = document.getElementById('result-container');
                this.resultContent = document.getElementById('result-content');
                this.statusIndicator = document.querySelector('.status-indicator');
                this.cameraSelect = document.getElementById('camera-select');
                
                this.isScanning = false;
                this.availableCameras = [];
                
                this.init();
            }

            async init() {
                this.setupEventListeners();
                await this.loadCameras();
                this.updateStatus('Siap Memindai', 'scanning');
            }

            setupEventListeners() {
                this.startBtn.addEventListener('click', () => this.startScanning());
                this.stopBtn.addEventListener('click', () => this.stopScanning());
                this.cameraSelect.addEventListener('change', () => {
                    if (this.isScanning) {
                        this.stopScanning();
                        setTimeout(() => this.startScanning(), 500);
                    }
                });
            }

            async loadCameras() {
                try {
                    const cameras = await QrScanner.listCameras();
                    this.availableCameras = cameras;
                    
                    this.cameraSelect.innerHTML = '<option value="">Pilih Kamera...</option>';
                    
                    cameras.forEach((camera, index) => {
                        const option = document.createElement('option');
                        option.value = camera.id;
                        option.textContent = camera.label || `Kamera ${index + 1}`;
                        this.cameraSelect.appendChild(option);
                    });

                    // Auto select back camera if available
                    const backCamera = cameras.find(camera => 
                        camera.label.toLowerCase().includes('back') || 
                        camera.label.toLowerCase().includes('rear')
                    );
                    
                    if (backCamera) {
                        this.cameraSelect.value = backCamera.id;
                    } else if (cameras.length > 0) {
                        this.cameraSelect.value = cameras[0].id;
                    }
                    
                } catch (error) {
                    console.error('Error loading cameras:', error);
                    this.updateStatus('Error: Tidak dapat mengakses kamera', 'error');
                }
            }

            async startScanning() {
                try {
                    const selectedCameraId = this.cameraSelect.value;
                    if (!selectedCameraId) {
                        alert('Silakan pilih kamera terlebih dahulu');
                        return;
                    }

                    this.scanner = new QrScanner(
                        this.videoElement,
                        (result) => this.handleScanResult(result),
                        {
                            preferredCamera: selectedCameraId,
                            highlightScanRegion: false,
                            highlightCodeOutline: false,
                            maxScansPerSecond: 5,
                        }
                    );

                    await this.scanner.start();
                    
                    this.isScanning = true;
                    this.startBtn.disabled = true;
                    this.stopBtn.disabled = false;
                    this.cameraSelect.disabled = true;
                    
                    this.updateStatus('Sedang Memindai...', 'scanning');
                    this.resultContainer.style.display = 'none';
                    
                } catch (error) {
                    console.error('Error starting scanner:', error);
                    this.updateStatus('Error: Gagal memulai scanner', 'error');
                    this.resetButtons();
                }
            }

            stopScanning() {
                if (this.scanner) {
                    this.scanner.stop();
                    this.scanner = null;
                }
                
                this.isScanning = false;
                this.resetButtons();
                this.updateStatus('Scanner Dihentikan', 'error');
            }

            resetButtons() {
                this.startBtn.disabled = false;
                this.stopBtn.disabled = true;
                this.cameraSelect.disabled = false;
            }

            handleScanResult(result) {
                console.log('QR Code detected:', result);
                
                // Show result
                this.resultContent.textContent = result;
                this.resultContainer.style.display = 'block';
                
                // Update status
                this.updateStatus('QR Code Terdeteksi!', 'success');
                
                // Optional: Stop scanning after successful scan
                this.stopScanning();
                
                // Optional: Play success sound
                this.playSuccessSound();
                
                // Optional: Send data to server or process further
                this.processQRData(result);
            }

            updateStatus(message, type) {
                const statusClasses = {
                    scanning: 'status-scanning',
                    success: 'status-success', 
                    error: 'status-error'
                };
                
                const icons = {
                    scanning: 'fas fa-circle pulse',
                    success: 'fas fa-check-circle',
                    error: 'fas fa-exclamation-circle'
                };
                
                this.statusIndicator.className = `status-indicator ${statusClasses[type]}`;
                this.statusIndicator.innerHTML = `
                    <a href="${message}" class="${icons[type]}"></a>
                    ${message}
                `;
            }

            playSuccessSound() {
                // Create audio context for success sound
                try {
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                    oscillator.frequency.setValueAtTime(1000, audioContext.currentTime + 0.1);
                    
                    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
                    
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.2);
                } catch (error) {
                    console.log('Audio not supported');
                }
            }

            processQRData(qrData) {
                // Process QR data - customize based on your needs
                console.log('Processing QR data:', qrData);
                
                // Example: Check if it's a URL
                if (this.isValidURL(qrData)) {
                    console.log('QR contains URL:', qrData);
                    // Optional: Ask user if they want to open the URL
                    if (confirm('QR code berisi URL. Buka link?')) {
                        window.open(qrData, '_blank');
                    }
                }
                
                // Example: Send to server
                this.sendToServer(qrData);
            }

            isValidURL(string) {
                try {
                    new URL(string);
                    return true;
                } catch (_) {
                    return false;
                }
            }

            sendToServer(qrData) {
                fetch('/api/process-qr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },body: JSON.stringify({ qr_data: qrData })
                }).then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);
                }).catch(error => {
                    console.error('Error sending to server:', error);
                });
            }
        }

        // Initialize scanner when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new MedicalQRScanner();
        });
    </script>
</body>
</html>