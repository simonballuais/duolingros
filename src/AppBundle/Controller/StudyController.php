<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class StudyController extends Controller
{
    /**
     * @Route("/study", name="front_study_lobby")
     * @Method({"GET"})
     */
    public function lobbyAction()
    {
        $lessonList = $this->get('app.lesson_manager')->getAll();

        $learningList = $this->getUser()->getLearningList();

        foreach ($learningList as $learning) {
            foreach ($lessonList as $lesson) {
                if ($learning->getLesson()->getId() === $lesson->getId()) {
                    $lesson->setCurrentLearning($learning);
                }
            }
        }

        return $this->render('front/study/lobby.html.twig',
            [
                "lessonList" => $lessonList
            ]);
    }

}
?>
