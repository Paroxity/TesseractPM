<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use pocketmine\utils\UUID;

class ProxyTransferRequestPacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_TRANSFER_REQUEST_PACKET;

    /** @var UUID */
    public $uuid;
    /** @var string */
    public $target;

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