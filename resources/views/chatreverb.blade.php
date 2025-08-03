<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js"></script>
</head>
<body>
<div>
    <h1>Live Chat</h1>

    <div id="messages">
        <!-- Poruke će se ovdje pojavljivati -->
    </div>

    <form id="chatForm">

        <input type="text" id="message" placeholder="Unesite poruku">
        <button type="submit">Pošaljite</button>
    </form>
</div>

<script>
    // Inicijalizacija Laravel Echo
    const echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("PUSHER_APP_KEY") }}',
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        forceTLS: true
    });

    // Slušanje za događaj MessageSent na kanalu 'chat.1'
    echo.channel('chat.1')
        .listen('MessageSent', (event) => {
            const messageDiv = document.createElement('div');
            messageDiv.textContent = event.message;
            document.getElementById('messages').appendChild(messageDiv);
        });

    // Slanje poruke putem AJAX-a
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const message = document.getElementById('message').value;

        fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ message: message })
        }).then(response => response.json())
            .then(data => {
                console.log('Poruka poslana!', data);
                document.getElementById('message').value = '';  // Očisti unos nakon slanja
            });
    });
</script>
</body>
</html>
