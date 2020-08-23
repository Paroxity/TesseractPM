<?php

namespace paroxity\tesseract\event;

use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

class PlayerBlockedChatEvent extends PlayerEvent implements Cancellable
{
    /** @var string */
    private $message;

    public function __construct(Player $player, string $message)
    {
        $this->player = $player;
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}