<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\uuid\UUID;

class ProxyFindPlayerPacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_FIND_PLAYER_PACKET;

    /** @var string */
    private $username;

    public static function create(string $username): self
    {
        $result = new self;
        $result->username = $username;
        return $result;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    protected function decodePayload(PacketSerializer $in): void
    {
        $this->username = $in->getString();
    }

    protected function encodePayload(PacketSerializer $out): void
    {
        $out->putString($this->username);
    }

    public function proxyHandle(): void
    {
        // NOOP
    }
}