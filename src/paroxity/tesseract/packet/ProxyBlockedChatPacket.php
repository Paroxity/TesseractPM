<?php

namespace paroxity\tesseract\packet;

use paroxity\tesseract\event\PlayerBlockedChatEvent;
use paroxity\tesseract\Tesseract;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\uuid\UUID;

class ProxyBlockedChatPacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_BLOCKED_CHAT_PACKET;

    /** @var UUID */
    private $uuid;
    /** @var string */
    private $message;

    public static function create(UUID $uuid, string $message): self
    {
        $result = new self;
        $result->uuid = $uuid;
        $result->message = $message;
        return $result;
    }

    public function getUUID(): UUID
    {
        return $this->uuid;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    protected function decodePayload(PacketSerializer $in): void
    {
        $this->uuid = $in->getUUID();
        $this->message = $in->getString();
    }

    protected function encodePayload(PacketSerializer $out): void
    {
        $out->putUUID($this->uuid);
        $out->putString($this->message);
    }

    public function proxyHandle(): void
    {
        $player = Tesseract::getInstance()->getPlayerByUUID($this->uuid);
        if ($player !== null) {
            ($event = new PlayerBlockedChatEvent($player, $this->message))->call();
            if ($event->isCancelled()) {
                $player->chat($event->getMessage());
            }
        }
    }
}