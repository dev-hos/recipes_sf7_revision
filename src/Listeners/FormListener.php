<?php

namespace App\Listeners;

use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListener
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function autoslug ($field): callable
    {
        return function (PreSubmitEvent $event) use ($field) 
        {
            $data = $event->getData();

            if(empty($data['slug'])) {
                $data['slug'] = strtolower($this->slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }

    public function timestamp (): callable
    {
        return function (PostSubmitEvent $event)
        {
            $data = $event->getData();

            $data->setUpdatedAt(new \DateTimeImmutable());

            if(!$data->getId()) {
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
    }
}