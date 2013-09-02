<?php

namespace letskickin\BackBundle\EventListener;

use letskickin\BackBundle\Event\PotEvent;
use letskickin\BackBundle\PotEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PotListener implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

	public function onCreatedPot(PotEvent $event)
	{
		// ...
	}

    public function onSavedPot(PotEvent $event)
    {
        $pot = $event->getPot();

		$message = \Swift_Message::newInstance()
			->setSubject($pot->getOccasion())
			->setFrom('send@example.com')
			->setTo($pot->getAdminEmail())
//			->setBody(
//				$this->renderView(
//					'HelloBundle:Hello:email.txt.twig',
//					array('name' => $name)
//				)
//			)
			->setBody("Hi " . $pot->getAdminName() . ", you have created your pot: " . $pot->getOccasion())
		;
		$this->mailer->send($message);

        /*foreach ($pot->getGuests() as $subscriber) {
            $message = Swift_Message::newInstance()
                ->setSubject($pot->getOccasion())
                ->setFrom('send@example.com')
                ->setTo($subscriber->getEmail())
                ->setBody("Hey, somebody invited you to chip in a pot! It says: " . $pot->getOccasion())
            ;
            $this->mailer->send($message);
        }*/
    }

	public function onAddParticipant(PotEvent $event)
	{
		$pot = $event->getPot();

		$message = \Swift_Message::newInstance()
			->setSubject($pot->getOccasion())
			->setFrom('send@example.com')
			->setTo($pot->getAdminEmail())
//			->setBody(
//				$this->renderView(
//					'HelloBundle:Hello:email.txt.twig',
//					array('name' => $name)
//				)
//			)
			->setBody("Hi " . $pot->getAdminName() . ", a new participant has added info to: " . $pot->getOccasion())
		;
		$this->mailer->send($message);
	}

    public static function getSubscribedEvents()
    {
        return array(
			PotEvents::POT_SAVED => 'onSavedPot',
			PotEvents::POT_CREATED => 'onCreatedPot',
			PotEvents::PARTICIPANT_ADDED => 'onAddParticipant',
		);
    }
}