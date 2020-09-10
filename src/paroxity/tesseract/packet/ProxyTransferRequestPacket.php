<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\uuid\UUID;

class ProxyTransferRequestPacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_TRANSFER_REQUEST_PACKET;

    /** @var UUID */
    private $uuid;
    /** @var string */
    private $target;

    public static function create(UUID $uuid, string $target): self
    {
        $result = new self;
        $result->uuid = $uuid;
        $result->target = $target;
        return $result;
    }

    public function getUUID(): UUID
    {
        return $this->uuid;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    protected function decodePayload(PacketSerializer $in): void
    {
        $this->uuid = $in->getUUID();
        $this->target = $in->getString();
    }

    protected function encodePayload(PacketSerializer $out): void
    {
        $out->putUUID($this->uuid);
        $out->putString($this->target);
    }

    public function proxyHandle(): void
    {
        // NOOP
    }
}