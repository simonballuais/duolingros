<?php
namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class StudyController extends Controller
{
    /**
     * @Route("/api/lesson/{lesson}/start",
     *        name="api_lesson_start",
     *        options={"expose"=true}
     *        )
     * @Method({"GET"})
     */
    public function startLessonAction(Request $request)
    {
        return new JsonResponse(['coincoin' => 'tralala']);
    }
}
?>
