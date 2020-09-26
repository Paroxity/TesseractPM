<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use paroxity\tesseract\event\proxy\ProxyMessageReceiveEvent;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class ProxyReceiveMessagePacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_RECEIVE_MESSAGE_PACKET;

    /** @var string */
    private $message;
    /** @var string */
    private $sender;

    public static function create(string $message, string $sender): self
    {
        $result = new self;
        $result->message = $message;
        $result->sender = $sender;
        return $result;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function decodePayload(PacketSerializer $in): void
    {
        $this->message = $in->getString();
        $this->sender = $in->getString();
    }

    public function encodePayload(PacketSerializer $out): void
    {
        $out->putString($this->message);
        $out->putString($this->sender);
    }

    public function proxyHandle(): void
    {
        (new ProxyMessageReceiveEvent($this->message, $this->sender))->call();
    }
}