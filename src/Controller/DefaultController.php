<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Course;
use App\Manager\BookLessonManager;
use App\Manager\QuoteGenerator;

class DefaultController extends Controller
{
    /**
     * @Route("/{course}",
     *        name="homepage",
     *        options={"expose" = true},
     *        defaults={"course" = 1},
     *        requirements={"course"="\d+"}
     *        )
     */
    public function indexAction(
        Course $course,
        BookLessonManager $blm,
        QuoteGenerator $quoteGenerator
    ) {
        $bookLessons = $blm->getAllWithCurrentLearning($this->getUser());
        $lastLessonId = $this->get('session')->getFlashBag()->get('lastLesson');

        if ($lastLessonId) {
            $lastLessonId = array_pop($lastLessonId);
        }

        $quote = $quoteGenerator->generateTitleQuote();

        $em = $this->getDoctrine()->getManager();
        $repoCourse = $em->getRepository("App:Course");
        $allCourses = $repoCourse->findAll();

        return $this->render(
            'front/lobby/lobby.html.twig',
            [
                "quote" => $quote,
                "currentCourse" => $course,
                "allCourses" => $allCourses,
                "lastLesson" => $lastLessonId,
            ]
        );
    }
}
