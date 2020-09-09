<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use paroxity\tesseract\Tesseract;
use pocketmine\utils\UUID;

class ProxyTransferResponsePacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_TRANSFER_RESPONSE_PACKET;

    /** @var UUID */
    public $uuid;
    /** @var bool */
    public $success;
    /** @var string */
    public $reason;

    public function decodePayload(): void
    {
        $this->uuid = $this->getUUID();
        $this->success = $this->getBool();
        $this->reason = $this->getString();
    }

    public function encodePayload(): void
    {
        $this->putUUID($this->uuid);
        $this->putBool($this->success);
        $this->putString($this->reason);
    }

    public function proxyHandle(): void
    {
        Tesseract::getInstance()->transferResponse($this->uuid, $this->success, $this->reason);
    }
}