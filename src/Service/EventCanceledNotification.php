<?php

namespace App\Service;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

class EventCanceledNotification extends Notification
{
    private $eventName;

    public function __construct(string $eventName)
    {
        parent::__construct('Event canceled');
        $this->eventName = $eventName;
    }

    public function getChannels(EmailRecipientInterface $recipient): array
    {
        return ['email'];
    }

    public function asEmailMessage(EmailRecipientInterface $recipient): ?\Symfony\Component\Mime\Email
    {
        return (new \Symfony\Component\Mime\Email())
            ->from('noreply@example.com')
            ->to($recipient->getEmail())
            ->subject('Event canceled')
            ->html(sprintf('The event "%s" has been canceled.', $this->eventName));
    }
}

