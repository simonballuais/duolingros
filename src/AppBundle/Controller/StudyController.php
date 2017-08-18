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
     * @Route("/lessons", name="front_lesson_lobby")
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

}
?>
