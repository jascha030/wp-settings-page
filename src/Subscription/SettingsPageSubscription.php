<?php

namespace Jascha030\WPOL\Subscription;

use Jascha030\WPOL\Subscription\Exception\SubscriptionException;

class SettingsPageSubscription extends Subscription implements Unsubscribable
{
    public function subscribe()
    {
        parent::subscribe();

        add_filter($this->tag, $this->callable, $this->priority, $this->acceptedArguments);
    }

    /**
     * @throws SubscriptionException
     */
    public function unsubscribe()
    {
        if ($this->isActive()) {
            throw new SubscriptionException("Can't unsubscribe before subscribing");
        } else {
            remove_filter($this->tag, $this->callable, $this->priority, $this->acceptedArguments);
        }
    }
}
