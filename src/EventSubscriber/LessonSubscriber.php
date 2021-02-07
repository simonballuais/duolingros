<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Lesson;
use App\Entity\Proposition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class LessonSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setOrder', EventPriorities::POST_WRITE],
        ];
    }

    public function setOrder(ViewEvent $event): void
    {
        $lesson = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$lesson instanceof Lesson || Request::METHOD_POST !== $method) {
            return;
        }

        $order = $this->em->getRepository(Lesson::class)
             ->getNextLessonOrderForBookLesson($lesson->getBookLesson());

        $lesson->setOrder($order);

        $this->em->flush();
    }
}
