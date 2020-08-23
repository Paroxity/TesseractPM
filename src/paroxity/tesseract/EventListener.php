<?php

namespace paroxity\tesseract;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;

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
        if ($player->getAddress() !== $this->plugin->getProxyAddress()) {
            $player->transfer($this->plugin->getProxyAddress(), $this->plugin->getProxyPort());
            $event->setCancelled();
        }
    }
}