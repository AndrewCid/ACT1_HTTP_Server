<?php
// Create TCP/IP socket
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

// Bind to IP and port
socket_bind($server, '127.0.0.1', 8080);

// Start listening for connections
socket_listen($server);
echo "Server listening on http://127.0.0.1:8080...\n";

while (true) {
    $client = socket_accept($server); // Wait for connection
    $request = socket_read($client, 1024); // Read incoming request

    // Parse the request
    preg_match('#GET (.*?) HTTP#', $request, $matches);
    $path = $matches[1] ?? '/';

    // Determine response
    if ($path === '/' || $path === '/index.html') {
        $body = "<h1>Server Running Successfully!</h1>";
        $status = "HTTP/1.1 200 OK";
    } else {
        $body = "<h1>404 Resource Not Found</h1>";
        $status = "HTTP/1.1 404 Not Found";
    }

    // Build full HTTP response
    $response = "$status\r\n";
    $response .= "Content-Type: text/html\r\n";
    $response .= "Content-Length: " . strlen($body) . "\r\n";
    $response .= "Connection: close\r\n\r\n";
    $response .= $body;

    // Send response and close socket
    socket_write($client, $response);
    socket_close($client);
}

socket_close($server);
?>
