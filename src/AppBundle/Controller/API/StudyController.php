<?php
namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\Translation;
use AppBundle\Entity\Question;
use AppBundle\Model\Proposition;
use AppBundle\Manager\LearningManager;


class StudyController extends Controller
{
    /**
     * @Route("/api/study/{lesson}/start",
     *        name="api_study_start",
     *        options={"expose"=true}
     *        )
     * @Method({"GET"})
     */
    public function startStudyAction(Lesson $lesson)
    {
        $serializer = $this->get('jms_serializer');
        $sm = $this->get('app.study_manager');
        $exercise = $sm->startStudy($lesson);
        $exercise = json_decode($serializer->serialize($exercise, 'json'));

        return new JsonResponse([
            'lessonTitle' => $lesson->getTitle(),
            'progress' => 0,
            'exercise' => $exercise,
        ]);
    }

    /**
     * @Route("/api/study/translation/answer",
     *        name="api_study_answer_translation",
     *        options={"expose"=true}
     *        )
     * @Method({"POST"})
     */
    public function answerTranslation(Request $request)
    {
        $sm = $this->get('app.study_manager');
        $requestContent = json_decode($request->getContent());

        $proposition = new Proposition($requestContent->text);
        $correction = $sm->tryProposition($proposition);

        return new JsonResponse([
            'proposition' => $request->get('text'),
            'isOk' => $correction->isOk(),
            'remarks' => $correction->getRemarks(),
            'progress' => $sm->getProgress(),
        ]);
    }

    /**
     * @Route("/api/study/question/answer",
     *        name="api_study_answer_question",
     *        options={"expose"=true}
     *        )
     * @Method({"POST"})
     */
    public function answerQuestion(Request $request)
    {
        $sm = $this->get('app.study_manager');
        $requestContent = json_decode($request->getContent());

        $proposition = new Proposition($requestContent->answer);
        $correction = $sm->tryProposition($proposition);

        return new JsonResponse([
            'proposition' => $request->get('text'),
            'isOk' => $correction->isOk(),
            'remarks' => $correction->getRemarks(),
            'progress' => $sm->getProgress(),
            'rightAnswer' => ['id' => $correction->getRightAnswer()->getId()],
        ]);
    }

    /**
     * @Route("/api/study/get_new_exercise",
     *        name="api_study_get_new_exercise",
     *        options={"expose"=true}
     *        )
     * @Method({"GET"})
     */
    public function getNewExeriseAction()
    {
        $sm = $this->get('app.study_manager');
        $exercise = $sm->getNextExercise();

        if ($exercise) {
            $serializer = $this->get('jms_serializer');
            $exercise = json_decode($serializer->serialize($exercise, 'json'));

            return new JsonResponse([
                'progress' => $sm->getProgress(),
                'exercise' => $exercise,
            ]);
        }

        return $this->endStudy();
    }

    public function endStudy()
    {
        $lm = $this->get('app.learning_manager');
        $em = $this->getDoctrine()->getManager();

        $successPercentage = $sm->getSuccessPercentage();

        $learning = $lm->finishLesson(
            $this->getUser(),
            $sm->getCurrentLesson(),
            $successPercentage
        );

        $mastery = $learning->getMastery();

        $em->flush();

        $this->addFlash('lastLesson', $learning->getLesson()->getId());

        return new JsonResponse([
            'studyOver' => true,
            'successPercentage' => $successPercentage,
            'mastery' => $mastery,
            'goodRun' => $successPercentage >= LearningManager::GOOD_PERCENTAGE,
        ]);
    }

    /** * @Route("/api/study/get_lesson_menu",
     *        name="api_study_get_lesson_menu",
     *        options={"expose"=true}
     *        )
     * @Method({"GET"})
     */
    public function getLessonMenuAction()
    {
        $lm = $this->get('app.lessonManager');
        $serializer = $this->get('jms_serializer');
        $lessons = $lm->getAllWithCurrentLearning($this->getUser());

        $response = new Response($serializer->serialize($lessons, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /** * @Route("/api/study/complain",
     *        name="api_study_complaint",
     *        options={"expose"=true}
     *        )
     * @Method({"PUT"})
     */
    public function complainAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sm = $this->get('app.study_manager');
        $cm = $this->get('app.complaint_manager');
        $repoTranslation = $em->getRepository("AppBundle:Translation");

        $proposition = $sm->getLastSubmittedProposition();
        $translationText = $sm->getCurrentTranslationText();
        $translation = $repoTranslation->findOneBy(["text" => $translationText]);

        $complaint = $cm->addComplaint($translation, $proposition);
        $em->flush();

        $message = "Bien reçu Michel !";

        if ($complaint->isInProgress()) {
            $message = "Bien reçu Michel !";
        }

        if ($complaint->isRefused()) {
            $message = "Beeeh, pourtant c'est ça !";
        }

        return new JsonResponse(['message' => $message], 200);
    }

    /** * @Route("/api/study/test_proposition",
     *        name="api_study_test_proposition",
     *        options={"expose"=true}
     *        )
     * @Method({"POST"})
     */
    public function testProposition(Request $request)
    {
        $translationText = $request->get('translation');
        $propositionText = $request->get('proposition');

        $proposition = new Proposition($propositionText);
        $em = $this->get('app.translation_manager');
        $translation = new Translation();
        $translation->setCorrector($this->get('app.corrector.regex'));
        $translation->setAnswerList([$translationText]);
        $correction = $translation->treatProposition($proposition);

        return new JsonResponse([
            'isOk' => $correction->isOk(),
            'remarks' => $correction->getRemarks(),
        ]);
    }

    public function serializePropositions($propositions)
    {
        $result = [];

        foreach ($propositions as $proposition) {
            $result[] = [
                'id' => $proposition->getId(),
                'text' => $proposition->getText(),
            ];
        }

        return $result;
    }
}

