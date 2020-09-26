<?php

declare(strict_types=1);

namespace paroxity\tesseract\event\player;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerBlockedChatEvent extends PlayerEvent implements Cancellable
{
    use CancellableTrait;

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