<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $screens = array();

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $parts = explode('?', $msg);
        $id = $parts[1];
        $msg = $parts[0];
        
        if(strpos($msg, 'new screen') === 0){
            $this->screens[$id] = array(
                'conn' => $from,
                'controllers' => array()
            );
            echo "new screen registered, id=$id\n";
        } else if(strpos($msg, 'new controller') === 0) {
            if(is_array($this->screens[$id])){
                $this->screens[$id]['controllers'][] = array(
                    'conn' => $from
                );
                echo "new controller registered, screen=$id\n";
                $msg .= ':'.$from->resourceId;
            }
        } else {
            //player command
            $msg .= ':'.$from->resourceId;
        }
        
        foreach($this->screens as $sid => $screen){
            if($id == $sid && $from !== $screen['conn']){
                $screen['conn']->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
