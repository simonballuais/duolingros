<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Course;

class DefaultController extends Controller
{
    /**
     * @Route("/{course}",
     *        name="homepage",
     *        options={"expose" = true},
     *        defaults={"course" = 1}
     *        )
     */
    public function indexAction(Course $course)
    {
        $blm = $this->get('app.book_lesson_manager');
        $bookLessons = $blm->getAllWithCurrentLearning($this->getUser());
        $lastLessonId = $this->get('session')->getFlashBag()->get('lastLesson');

        if ($lastLessonId) {
            $lastLessonId = array_pop($lastLessonId);
        }

        $quote = $this->get('app.quote_generator')->generateTitleQuote();

        $em = $this->getDoctrine()->getManager();
        $repoCourse = $em->getRepository("AppBundle:Course");
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
