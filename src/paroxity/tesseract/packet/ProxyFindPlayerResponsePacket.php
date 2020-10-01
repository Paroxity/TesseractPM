<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use paroxity\tesseract\Tesseract;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class ProxyFindPlayerResponsePacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_FIND_PLAYER_RESPONSE_PACKET;

    /** @var string */
    private $username;
    /** @var bool */
    private $online;
    /** @var string */
    private $server;

    public static function create(string $username, bool $online, string $server): self
    {
        $result = new self;
        $result->username = $username;
        $result->online = $online;
        $result->server = $server;
        return $result;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function isOnline(): bool
    {
        return $this->online;
    }

    public function getServer(): string
    {
        return $this->server;
    }

    protected function decodePayload(PacketSerializer $in): void
    {
        $this->username = $in->getString();
        $this->online = $in->getBool();
        $this->server = $in->getString();
    }

    protected function encodePayload(PacketSerializer $out): void
    {
        $out->putString($this->username);
        $out->putBool($this->online);
        $out->putString($this->server);
    }

    public function proxyHandle(): void
    {
        Tesseract::getInstance()->findPlayerResponse($this->username, $this->online, $this->server);
    }
}