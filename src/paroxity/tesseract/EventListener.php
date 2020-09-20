<?php

declare(strict_types=1);

namespace paroxity\tesseract;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use function preg_match;

class EventListener implements Listener
{
    /** @var Tesseract */
    private $plugin;

    public function __construct(Tesseract $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerPreLogin(PlayerPreLoginEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player->getAddress() !== $this->plugin->getProxyAddress() && !preg_match("/^(127|172)/", $player->getAddress())) {
            $event->setKickMessage("You must join via the proxy");
            $event->setCancelled();
        }
    }
}