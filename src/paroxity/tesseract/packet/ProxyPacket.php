<?php

namespace paroxity\tesseract\packet;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PacketHandlerInterface;

abstract class ProxyPacket extends DataPacket
{

    public function handle(PacketHandlerInterface $handler): bool
    {
        return true;
    }

    abstract public function proxyHandle(): void;
}