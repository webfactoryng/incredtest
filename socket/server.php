<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__ . '/vendor/autoload.php';

class iSocketServer implements MessageComponentInterface
{
    protected $connections = [];

    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections[] = $conn;
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Save the CSV content to a file on the server
        $filename = 'saved/products.csv';
        file_put_contents($filename, $msg);
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Remove the connection when closed
        $index = array_search($conn, $this->connections);
        if ($index !== false) {
            unset($this->connections[$index]);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Run the socket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new iSocketServer()
        )
    ),
    8181
);
// Start notice
echo "Socket server started on port 8181\n";
$server->run();
