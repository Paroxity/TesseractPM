<?php

declare(strict_types=1);

namespace paroxity\tesseract;

use paroxity\tesseract\packet\ProxyAuthRequestPacket;
use paroxity\tesseract\packet\ProxyAuthResponsePacket;
use paroxity\tesseract\packet\ProxyBlockedChatPacket;
use paroxity\tesseract\packet\ProxyPacket;
use paroxity\tesseract\packet\ProxyTransferRequestPacket;
use paroxity\tesseract\packet\ProxyTransferResponsePacket;
use paroxity\tesseract\thread\SocketThread;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\snooze\SleeperNotifier;
use pocketmine\utils\Internet;
use pocketmine\uuid\UUID;

class Tesseract extends PluginBase
{
    /** @var self */
    private static $instance;

    /** @var SocketThread */
    private $thread;

    /** @var string */
    private $proxyAddress;
    /** @var int */
    private $proxyPort;

    /** @var array[] */
    private $transferRequests;

    protected function onLoad(): void
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

        $pool = PacketPool::getInstance();
        $pool->registerPacket(new ProxyAuthRequestPacket());
        $pool->registerPacket(new ProxyAuthResponsePacket());
        $pool->registerPacket(new ProxyBlockedChatPacket());
        $pool->registerPacket(new ProxyTransferRequestPacket());
        $pool->registerPacket(new ProxyTransferResponsePacket());

        $notifier = new SleeperNotifier();
        $localAddress = $this->proxyAddress === "127.0.0.1" ? "127.0.0.1" : Internet::getIP();
        $this->thread = $thread = new SocketThread($proxyAddress, (int)($socket["port"] ?? 19131), $socket["secret"] ?? "", $server["name"] ?? "TesseractServer", ($localAddress ? $localAddress : "127.0.0.1") . ":" . $this->getServer()->getPort(), $notifier);
        $this->getServer()->getTickSleeper()->addNotifier($notifier, static function () use ($pool, $thread) {
            while (($buffer = $thread->getBuffer()) !== null) {
                $packet = $pool->getPacket($buffer);
                if ($packet instanceof ProxyPacket) {
                    $packet->decode();
                    $packet->proxyHandle();
                }
            }
        });

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    /**
     * @return Tesseract
     */
    public static function getInstance(): Tesseract
    {
        return self::$instance;
    }

    public function getProxyAddress(): string
    {
        return $this->proxyAddress;
    }

    public function getProxyPort(): int
    {
        return $this->proxyPort;
    }

    public function transfer(Player $player, string $target, ?callable $onSuccess = null, ?callable $onFailure = null): void
    {
        $pk = ProxyTransferRequestPacket::create($player->getUniqueId(), $target);
        $this->thread->addPacketToQueue($pk);

        $this->transferRequests[$player->getUniqueId()->toString()] = [$onSuccess, $onFailure];
    }

    public function transferResponse(UUID $uuid, bool $success, string $reason): void
    {
        if (!isset($this->transferRequests[$uuid->toString()])) {
            return;
        }

        [$onSuccess, $onFailure] = $this->transferRequests[$uuid->toString()];
        if ($success) {
            if ($onSuccess !== null) ($onSuccess)();
        } else {
            if ($onFailure !== null) ($onFailure($reason));
        }
        unset($this->transferRequests[$uuid->toString()]);
    }
}
