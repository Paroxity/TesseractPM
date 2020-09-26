<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class ProxySendMessagePacket extends ProxyPacket
{
    public const NETWORK_ID = ProtocolInfo::PROXY_SEND_MESSAGE_PACKET;

    /** @var string[] */
    private $targets = [];
    /** @var string */
    private $message;

    /**
     * @param string[] $targets
     */
    public static function create(array $targets, string $message): self
    {
        $result = new self;
        $result->targets = $targets;
        $result->message = $message;
        return $result;
    }

    /**
     * @return string[]
     */
    public function getTargets(): array
    {
        return $this->targets;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function decodePayload(PacketSerializer $out): void
    {
        for($i = 0, $count = $out->getUnsignedVarInt(); $i < $count; ++$i){
            $this->targets[] = $out->getString();
        }
        $this->message = $out->getString();
    }

    public function encodePayload(PacketSerializer $in): void
    {
        $in->putUnsignedVarInt(count($this->targets));
        foreach ($this->targets as $target){
            $in->putString($target);
        }
        $in->putString($this->message);
    }

    public function proxyHandle(): void
    {
        // NOOP
    }
}