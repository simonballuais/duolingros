<?php
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Lesson;
use App\Manager\LessonManager;

class StudyController extends Controller
{
    /**
     * @Route("/study", name="front_study_lobby")
     * @Method({"GET"})
     */
    public function lobbyAction(LessonManager $lessonManager)
    {
        $lessonList = $lessonManager->getAll();
        $em = $this->getDoctrine()->getManager();
        $repoCourse = $em->getRepository("App:Course");
        $courses = $repoCourse->findAll();

        return $this->render('front/study/lobby.html.twig',
            [
                'lessonList' => $lessonList,
                'allCourses' => $courses,
            ]);
    }

    /**
     * @Route("/study/{lesson}", name="front_study_action", options={"expose" = true})
     * @Method({"GET"})
     */
    public function studyAction(Lesson $lesson)
    {
        $em = $this->getDoctrine()->getManager();
        $repoCourse = $em->getRepository("App:Course");
        $courses = $repoCourse->findAll();

        return $this->render(
            'front/study/study.html.twig',
            [
                'lesson' => $lesson,
                'allCourses' => $courses,
                'currentCourse' => $lesson->getBookLesson()->getCourse(),
            ]
        );
    }
}
?>
