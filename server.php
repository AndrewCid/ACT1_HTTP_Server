<?php
// ================================================
// Optimized PHP Socket Server with Modern UI
// ================================================

// Configuration
const HOST = '127.0.0.1';
const PORT = 8080;

// MIME type mapping
const MIME_TYPES = [
    'html' => 'text/html',
    'css'  => 'text/css',
    'js'   => 'application/javascript',
    'gif'  => 'image/gif',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
];

// Create and configure socket
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($server, HOST, PORT);
socket_listen($server);
echo "Server listening on http://" . HOST . ":" . PORT . "...\n";

// Main server loop
while (true) {
    $client = socket_accept($server);
    $request = socket_read($client, 1024);

    // Extract requested path
    preg_match('#GET (.*?) HTTP#', $request, $matches);
    $path = $matches[1] ?? '/';

    // Serve static files
    $file = ltrim($path, '/') ?: 'index.html';
    
    if (file_exists($file)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mime = MIME_TYPES[$ext] ?? 'application/octet-stream';
        $body = file_get_contents($file);
        
        sendResponse($client, "HTTP/1.1 200 OK", $mime, $body);
        continue;
    }

    // Serve dynamic HTML pages
    if ($path === '/' || $path === '/index.html') {
        $body = getHtmlPage(
            'Running Successfully!',
            'server is online and working beautifully.',
            '<a href="/404" class="btn">Go to 404 Page</a>',
            'success'
        );
        sendResponse($client, "HTTP/1.1 200 OK", 'text/html; charset=UTF-8', $body);
    } else {
        $body = getHtmlPage(
            '404 - Page Not Found',
            "The requested page <b>$path</b> doesn't exist on this server.",
            '<a href="/" class="btn">Return Home</a>',
            'error'
        );
        sendResponse($client, "HTTP/1.1 404 Not Found", 'text/html; charset=UTF-8', $body);
    }
}

// Note: socket_close($server) is unreachable due to infinite loop above
// Server will run until manually terminated (Ctrl+C)

// ================================================
// Helper Functions
// ================================================

function sendResponse($client, $status, $contentType, $body) {
    $response = "$status\r\n";
    $response .= "Content-Type: $contentType\r\n";
    $response .= "Content-Length: " . strlen($body) . "\r\n";
    $response .= "Connection: close\r\n\r\n";
    
    // Send headers first
    socket_write($client, $response);
    
    // Send body in chunks for larger files
    $chunkSize = 8192;
    $offset = 0;
    $length = strlen($body);
    
    while ($offset < $length) {
        $chunk = substr($body, $offset, $chunkSize);
        $written = @socket_write($client, $chunk, strlen($chunk));
        
        if ($written === false) {
            break; // Client disconnected
        }
        
        $offset += $written;
    }
    
    // Ensure all data is sent before closing
    @socket_shutdown($client, 1); // Stop sending, allow client to finish reading
    usleep(10000); // Wait 10ms for client to finish reading
    @socket_close($client);
}

function getHtmlPage($title, $message, $buttonHtml, $cardClass) {
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Socket Server</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Background Image - No Blur for Testing */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("/phainon.gif") no-repeat center center;
            background-size: cover;
            background-color: #333; /* Fallback color to see if pseudo-element renders */
            z-index: -1;
        }

        .card {
            position: relative;
            background: rgba(217, 178, 178, 0.54);
            backdrop-filter: blur(6px);
            padding: 2rem 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
            z-index: 1;
            width: 90%;
            max-width: 420px;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        p {
            color: #e0e0e0;
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-block;
            background: #fff;
            color: #333;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #950000ff;
            color: #fff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .success h1 {
            color: #00ff51ff;
        }

        .error h1 {
            color: #ff5c5c;
        }

        footer {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="card $cardClass">
        <h1>$title</h1>
        <p>$message</p>
        $buttonHtml
        <footer>I swear the gif loads just keep refreshing</footer>
    </div>
</body>
</html>
HTML;
}
?>