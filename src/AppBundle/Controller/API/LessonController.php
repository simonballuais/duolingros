<?php
namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LessonController extends Controller
{
    /**
     * @Route("/api/lesson/start_lesson", name="api_lesson_start")
     * @Method({"GET"})
     */
    public function startLessonAction(Request $request)
    {

    }
}
?>
