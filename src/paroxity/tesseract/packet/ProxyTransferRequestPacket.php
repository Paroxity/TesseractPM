<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use pocketmine\utils\UUID;

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

    public function decodePayload(): void
    {
        $this->uuid = $this->getUUID();
        $this->target = $this->getString();
    }

    public function encodePayload(): void
    {
        $this->putUUID($this->uuid);
        $this->putString($this->target);
    }

    public function proxyHandle(): void
    {
        // NOOP
    }
}