<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Lesson;
use AppBundle\Form\LessonType;

class LessonController extends Controller
{
    /**
     * @Route("/admin/lessons/{lesson}", name="admin_questions")
     * @Method({"POST", "GET"})
     */
    public function listAction(
        Request $request,
        Lesson $lesson
    ) {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.lesson_manager')->update($lesson);

            return $this->redirectToRoute(
                'admin_questions',
                [
                    'lesson' => $lesson->getId()
                ]
            );
        }

        return $this->render(
            'admin/lesson/list.html.twig',
            [
                'lesson' => $lesson,
                'form' => $form->createView(),
                'allCourses' => [],
            ]
        );
    }
}

