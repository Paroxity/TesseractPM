<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use paroxity\tesseract\Tesseract;
use pocketmine\utils\UUID;

class ProxyTransferResponsePacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_TRANSFER_RESPONSE_PACKET;

    /** @var UUID */
    private $uuid;
    /** @var bool */
    private $success;
    /** @var string */
    private $reason;

    public static function create(UUID $uuid, bool $success, string $reason): self
    {
        $result = new self;
        $result->uuid = $uuid;
        $result->success = $success;
        $result->reason = $reason;
        return $result;
    }

    public function getUUID(): UUID
    {
        return $this->uuid;
    }

    public function wasSuccessful(): bool
    {
        return $this->success;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

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