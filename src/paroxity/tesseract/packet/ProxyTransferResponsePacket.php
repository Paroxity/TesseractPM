<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use paroxity\tesseract\Tesseract;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\uuid\UUID;

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

    protected function decodePayload(PacketSerializer $in): void
    {
        $this->uuid = $in->getUUID();
        $this->success = $in->getBool();
        $this->reason = $in->getString();
    }

    protected function encodePayload(PacketSerializer $out): void
    {
        $out->putUUID($this->uuid);
        $out->putBool($this->success);
        $out->putString($this->reason);
    }

    public function proxyHandle(): void
    {
        Tesseract::getInstance()->transferResponse($this->uuid, $this->success, $this->reason);
    }
}