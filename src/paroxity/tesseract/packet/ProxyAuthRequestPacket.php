<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class ProxyAuthRequestPacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_AUTH_REQUEST_PACKET;

    public const CONN_TYPE_SERVER = 0;
    public const CONN_TYPE_OTHER = 1;

    /** @var string */
    private $secret;
    /** @var string */
    private $name;
    /** @var int */
    private $type;
    /** @var string */
    private $address;

    public static function create(string $secret, string $name, int $type, string $address): self
    {
        $result = new self;
        $result->secret = $secret;
        $result->name = $name;
        $result->type = $type;
        $result->address = $address;
        return $result;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getServerName(): string
    {
        return $this->name;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    protected function decodePayload(PacketSerializer $in): void
    {
        $this->secret = $in->getString();
        $this->name = $in->getString();
        $this->type = $in->getByte();
        $this->address = $in->getString();
    }

    protected function encodePayload(PacketSerializer $out): void
    {
        $out->putString($this->secret);
        $out->putString($this->name);
        $out->putByte($this->type);
        $out->putString($this->address);
    }

    public function proxyHandle(): void
    {
        // NOOP
    }
}