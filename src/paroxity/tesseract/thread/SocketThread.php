<?php

namespace paroxity\tesseract\thread;

use Exception;
use paroxity\tesseract\packet\ProxyAuthRequestPacket;
use paroxity\tesseract\packet\ProxyPacket;
use pocketmine\snooze\SleeperNotifier;
use pocketmine\thread\Thread;
use pocketmine\utils\Binary;
use Threaded;
use function sleep;
use function socket_connect;
use function socket_create;
use function socket_last_error;
use function socket_read;
use function socket_set_nonblock;
use function socket_write;
use function strlen;
use const AF_INET;
use const IPPROTO_IP;
use const PTHREADS_INHERIT_NONE;
use const SOCK_STREAM;

class SocketThread extends Thread
{
    /** @var string */
    private $host;
    /** @var int */
    private $port;

    private $secret;
    private $name;
    private $address;

    /** @var Threaded */
    private $sendQueue;
    /** @var Threaded */
    private $receiveBuffer;

    /** @var SleeperNotifier */
    private $notifier;

    /** @var bool */
    private $isRunning;

    public function __construct(string $host, int $port, string $secret, string $name, string $address, SleeperNotifier $notifier)
    {
        $this->host = $host;
        $this->port = $port;

        $this->secret = $secret;
        $this->name = $name;
        $this->address = $address;

        $this->sendQueue = new Threaded();
        $this->receiveBuffer = new Threaded();

        $this->notifier = $notifier;

        $this->isRunning = false;
        $this->start();
    }

    public function onRun(): void
    {
        $socket = $this->connectToSocketServer();
        socket_set_nonblock($socket);
        while ($this->isRunning) {
            while (($send = $this->sendQueue->shift()) !== null) {
                $length = strlen($send);
                socket_write($socket, Binary::writeLInt($length) . $send, 4 + $length);
            }
            do {
                $lengthBuf = @socket_read($socket, 4);
                if (!$lengthBuf && socket_last_error($socket) === 10054) {
                    $socket = $this->connectToSocketServer();
                }
                if ($lengthBuf !== false) {
                    try {
                        $length = Binary::readLInt($lengthBuf);
                        $buffer = socket_read($socket, $length);
                        if ($buffer !== false) {
                            $this->receiveBuffer[] = $buffer;
                            $this->notifier->wakeupSleeper();
                        }
                    } catch (Exception $exception) {
                        echo "Failed to read from socket: " . ($exception->getMessage()) . PHP_EOL;
                    }
                }
            } while ($lengthBuf !== false && $lengthBuf !== "");
            usleep(200);
        }
    }

    /**
     * @return resource
     * @throws Exception
     */
    public function connectToSocketServer()
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, IPPROTO_IP);
        if (!$socket) {
            throw new Exception("Unable to start socket client");
        }

        do {
            $connected = @socket_connect($socket, $this->host, $this->port);
            if (!$connected) {
                sleep(10);
            }
        } while (!$connected);

        $pk = ProxyAuthRequestPacket::create($this->secret, $this->name, ProxyAuthRequestPacket::CONN_TYPE_SERVER, $this->address);
        $this->addPacketToQueue($pk);

        return $socket;
    }

    public function start(int $options = PTHREADS_INHERIT_NONE): bool
    {
        $this->isRunning = true;
        return parent::start($options);
    }

    public function quit(): void
    {
        $this->isRunning = false;
        parent::quit();
    }

    public function addPacketToQueue(ProxyPacket $packet): void
    {
        $packet->encode();
        $this->sendQueue[] = $packet->getSerializer()->getBuffer();
    }

    /**
     * @return string|null
     */
    public function getBuffer(): ?string
    {
        return $this->receiveBuffer->shift();
    }
}