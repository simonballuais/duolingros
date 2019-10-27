<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\Proposition;
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

        if ($request->getMethod() === 'POST') {
            foreach ($lesson->getQuestionList() as $question) {
                foreach ($question->getPropositionList() as $proposition) {
                    $proposition->setQuestion(null);
                }

                $question->setLesson(null);
            }

            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->get('app.lesson_manager')->update($form);
            }

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

    /**
     * @Route(
     *     "/api/proposition/{proposition}/image",
     *     name="api_post_proposition_image",
     *     options={"expose"=true}
     * )
     * @Method({"POST"})
     */
    public function uploadBase32Image(
        Request $request,
        Proposition $proposition
    ) {
        $content = json_decode($request->getContent());
        $proposition->setImage($content->imageContent);

        $this->getDoctrine()->getManager()->flush();

        return new Response($proposition->getImage());
    }
}

