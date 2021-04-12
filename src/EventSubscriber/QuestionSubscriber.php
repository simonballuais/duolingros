<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Question;
use App\Entity\Proposition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class QuestionSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addDefaultPropositions', EventPriorities::POST_WRITE],
        ];
    }

    public function addDefaultPropositions(ViewEvent $event): void
    {
        $question = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$question instanceof Question || Request::METHOD_POST !== $method) {
            return;
        }

        //for ($i = 0; $i < 3; $i++)
        //{
            //$defaultProposition = new Proposition();
            //$question->addProposition($defaultProposition);
            //$this->em->persist($defaultProposition);
        //}

        //$this->em->flush($defaultProposition);
    }
}
