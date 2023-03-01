<?php

namespace App\Entity;

use App\Entity\Don;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class Geocoding implements EventSubscriberInterface
{
    private $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public static function getSubscribedEvents()
    {
        return [
            'postPersist' => 'onUserCreated',
        ];
    }

    public function onUserCreated(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // Only send notifications for User entities
        if (!$entity instanceof Don) {
            return;
        }

        $notification = new Notification(
            'A new Don has been created!',
            ['email'],
            'default'
        );

        $this->notifier->send($notification);
    }
}
