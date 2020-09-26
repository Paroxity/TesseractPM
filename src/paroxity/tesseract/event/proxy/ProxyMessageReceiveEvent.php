<?php
declare(strict_types=1);

namespace paroxity\tesseract\event\proxy;

use pocketmine\event\Event;

class ProxyMessageReceiveEvent extends Event
{
    /** @var string */
    private $message;
    /** @var string */
    private $sender;

    public function __construct(string $message, string $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getSender(): string
    {
        return $this->sender;
    }
}