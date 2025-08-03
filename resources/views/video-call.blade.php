<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Poziv</title>
    <script src="https://unpkg.com/@daily-co/daily-js"></script>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; width: 100%; overflow: hidden; }
        #video-frame { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>
<div id="video-frame"></div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Dohvatite token i URL iz URL parametara
        const urlParams = new URLSearchParams(window.location.search);
        const roomUrl = urlParams.get('roomUrl');
        const token = urlParams.get('token');

        if (roomUrl && token) {
            // Kreirajte i pridružite se pozivu pomoću tokena
            const dailyCall = DailyIframe.createFrame({
                showLeaveButton: true,
                iframeStyle: {
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    width: '100%',
                    height: '100%',
                }
            });
            dailyCall.join({ url: roomUrl, token: token });
        } else {
            document.body.innerHTML = '<h1>Greška: Nije moguće pronaći sobu za poziv.</h1>';
        }
    });
</script>
</body>
</html>
