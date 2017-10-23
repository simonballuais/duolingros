<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Lesson;


class StudyController extends Controller
{
    /**
     * @Route("/study", name="front_study_lobby")
     * @Method({"GET"})
     */
    public function lobbyAction()
    {
        $lessonList = $this->get('app.lesson_manager')->getAll();

        return $this->render('front/study/lobby.html.twig',
            [
                "lessonList" => $lessonList
            ]);
    }

    /**
     * @Route("/study/{lesson}", name="front_study_action", options={"expose" = true})
     * @Method({"GET"})
     */
    public function studyAction(Lesson $lesson)
    {
        return $this->render("front/study/study.html.twig",
            [
                "lesson" => $lesson
            ]);
    }
}
?>
