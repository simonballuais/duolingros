<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage", options={"expose" = true})
     */
    public function indexAction(Request $request)
    {
        $blm = $this->get('app.book_lesson_manager');
        $bookLessons = $blm->getAllWithCurrentLearning($this->getUser());
        $lastLessonId = $this->get('session')->getFlashBag()->get('lastLesson');

        if ($lastLessonId) {
            $lastLessonId = array_pop($lastLessonId);
        }

        return $this->render('front/lobby.html.twig',
        [
            "bookLessons" => $bookLessons,
            "lastLesson" => $lastLessonId,
        ]);
    }
}
