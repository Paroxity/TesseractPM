<?php

declare(strict_types=1);

namespace paroxity\tesseract\packet;

use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\DataPacket;

abstract class ProxyPacket extends DataPacket
{
    public function handle(NetworkSession $session): bool
    {
        return false;
    }

    abstract public function proxyHandle(): void;
}