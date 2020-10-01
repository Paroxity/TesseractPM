<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use paroxity\tesseract\Tesseract;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\uuid\UUID;

class ProxyPlayerInfoPacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_PLAYER_INFO_PACKET;

    /** @var UUID */
    private $uuid;
    /** @var string */
    private $address;
    /** @var string */
    private $xuid;

    public static function create(UUID $uuid, string $address, string $xuid): self
    {
        $result = new self;
        $result->uuid = $uuid;
        $result->address = $address;
        $result->xuid = $xuid;
        return $result;
    }

    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getXuid(): string
    {
        return $this->xuid;
    }

    public function decodePayload(PacketSerializer $out): void
    {
        $this->uuid = $out->getUUID();
        $this->address = $out->getString();
        $this->xuid = $out->getString();
    }

    public function encodePayload(PacketSerializer $in): void
    {
        $in->putUUID($this->uuid);
        $in->putString($this->address);
        $in->putString($this->xuid);
    }

    public function proxyHandle(): void
    {
        Tesseract::getInstance()->setPlayerInfo($this->uuid, $this->address, $this->xuid);
    }
}