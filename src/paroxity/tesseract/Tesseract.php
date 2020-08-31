<?php

namespace paroxity\tesseract;

use paroxity\tesseract\packet\ProxyAuthRequestPacket;
use paroxity\tesseract\packet\ProxyAuthResponsePacket;
use paroxity\tesseract\packet\ProxyBlockedChatPacket;
use paroxity\tesseract\packet\ProxyPacket;
use paroxity\tesseract\thread\SocketThread;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\snooze\SleeperNotifier;
use pocketmine\utils\Internet;
use pocketmine\utils\UUID;

class Tesseract extends PluginBase
{
    /** @var self */
    private static $instance;

    /** @var SocketThread */
    private $thread;

    /** @var string */
    private $proxyAddress;
    private $proxyPort;

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    public function onEnable(): void
    {
        $config = $this->getConfig();
        $proxy = $config->get("proxy", []);
        $socket = $config->get("socket", []);
        $server = $config->get("server", []);

        $this->proxyAddress = $proxyAddress = $proxy["address"] ?? "127.0.0.1";
        $this->proxyPort = $proxy["port"] ?? 19132;

        PacketPool::registerPacket(new ProxyAuthRequestPacket());
        PacketPool::registerPacket(new ProxyAuthResponsePacket());
        PacketPool::registerPacket(new ProxyBlockedChatPacket());

        $notifier = new SleeperNotifier();
        $localAddress = ($socket["host"] ?? "127.0.0.1") === "127.0.0.1" ? "127.0.0.1" : Internet::getIP();
        $this->thread = $thread = new SocketThread($proxyAddress, (int)($socket["port"] ?? 19131), $socket["secret"] ?? "", $server["name"] ?? "TesseractServer", ($localAddress ? $localAddress : "127.0.0.1") . ":" . $this->getServer()->getPort(), $notifier);
        $this->getServer()->getTickSleeper()->addNotifier($notifier, static function () use ($thread) {
            while (($buffer = $thread->getBuffer()) !== null) {
                $packet = PacketPool::getPacket($buffer);
                if ($packet instanceof ProxyPacket) {
                    $packet->decode();
                    $packet->proxyHandle();
                }
            }
        });

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public static function getInstance(): Tesseract
    {
        return self::$instance;
    }

    public function getProxyAddress()
    {
        return $this->proxyAddress;
    }

    public function getProxyPort()
    {
        return $this->proxyPort;
    }

    public function getPlayerByUUID(UUID $uuid): ?Player
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            if ($player->getUniqueId()->toBinary() === $uuid->toBinary()) {
                return $player;
            }
        }
        return null;
    }
}