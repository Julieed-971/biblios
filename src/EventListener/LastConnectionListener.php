<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class LastConnectionListener
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }

    #[AsEventListener(event: 'security.interactive_login')]
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        // Récupérer le current connected user
        $user = $event->getAuthenticationToken()->getUser();
        
        // Vérifier si instance de User
        if ($user instanceof User) {
            // Mettre à jour propriété lastconnectedat avec la date du jour
            $user->setLastConnectedAt(new \DateTimeImmutable());
            // Mettre à jour la propriété lastConnectedAt du current user dans la base de données
            $this->manager->flush();
        }
    }
}
