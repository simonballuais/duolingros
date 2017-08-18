<?php
namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LessonController extends Controller
{
    public function lobbyAction()
    {
        return $this->render('lesson/lobby');
    }
    /**
     * @Route("/api/lesson/{lesson}/start", name="api_lesson_start")
     * @Method({"GET"})
     */
    public function startLessonAction(Request $request)
    {
        return new JsonResponse();
    }
}
?>
