<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use paroxity\tesseract\event\PlayerBlockedChatEvent;
use pocketmine\Server;
use pocketmine\utils\UUID;

class ProxyBlockedChatPacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_BLOCKED_CHAT_PACKET;

    /** @var UUID */
    private $uuid;
    /** @var string */
    private $message;

    protected function decodePayload(): void
    {
        $this->uuid = $this->getUUID();
        $this->message = $this->getString();
    }

    protected function encodePayload(): void
    {
        $this->putUUID($this->uuid);
        $this->putString($this->message);
    }

    public function proxyHandle(): void
    {
        $player = Server::getInstance()->getPlayerByUUID($this->uuid);
        if ($player !== null) {
            ($event = new PlayerBlockedChatEvent($player, $this->message))->call();
            if ($event->isCancelled()) {
                $player->chat($event->getMessage());
            }
        }
    }
}