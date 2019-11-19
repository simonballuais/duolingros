<?php
namespace App\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Lesson;
use App\Entity\Translation;
use App\Entity\Question;
use App\Model\Proposition;
use App\Model\RegexCorrector;
use App\Manager\LearningManager;
use App\Manager\LessonManager;
use App\Manager\StudyManager;
use App\Manager\ComplaintManager;

class StudyController extends Controller
{
    private $sm;

    public function __construct(
        StudyManager $sm,
        LearningManager $lm,
        EntityManagerInterface $em
    ) {
        $this->sm = $sm;
        $this->lm = $lm;
        $this->em = $em;
    }

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
        $exercise = $this->sm->startStudy($lesson);
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
        $requestContent = json_decode($request->getContent());

        $proposition = new Proposition($requestContent->text);
        $correction = $this->sm->tryProposition($proposition);

        return new JsonResponse([
            'proposition' => $request->get('text'),
            'isOk' => $correction->isOk(),
            'remarks' => $correction->getRemarks(),
            'progress' => $this->sm->getProgress(),
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
        $requestContent = json_decode($request->getContent());

        $proposition = new Proposition($requestContent->answer);
        $correction = $this->sm->tryProposition($proposition);

        return new JsonResponse([
            'proposition' => $request->get('text'),
            'isOk' => $correction->isOk(),
            'remarks' => $correction->getRemarks(),
            'progress' => $this->sm->getProgress(),
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
        $exercise = $this->sm->getNextExercise();

        if ($exercise) {
            $serializer = $this->get('jms_serializer');
            $exercise = json_decode($serializer->serialize($exercise, 'json'));

            return new JsonResponse([
                'progress' => $this->sm->getProgress(),
                'exercise' => $exercise,
            ]);
        }

        return $this->endStudy();
    }

    public function endStudy()
    {
        $successPercentage = $this->sm->getSuccessPercentage();

        $learning = $this->lm->finishLesson(
            $this->getUser(),
            $this->sm->getCurrentLesson(),
            $successPercentage
        );

        $mastery = $learning->getMastery();

        $this->em->flush();

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
        $serializer = $this->get('jms_serializer');
        $lessons = $this->lm->getAllWithCurrentLearning($this->getUser());

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
    public function complainAction(ComplaintManager $cm) {
        $repoTranslation = $this->em->getRepository("App:Translation");

        $proposition = $this->sm->getLastSubmittedProposition();
        $translationId = $this->sm->getCurrentExerciseId();
        $translation = $repoTranslation->findOneById($translationId);

        $complaint = $cm->addComplaint($translation, $proposition);
        $this->em->flush();

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
    public function testProposition(
        Request $request,
        EntityManagerInterface $em,
        RegexCorrector $regexCorrector
    ) {
        $translationText = $request->get('translation');
        $propositionText = $request->get('proposition');

        $proposition = new Proposition($propositionText);
        $translation = new Translation();
        $translation->setAnswerList([$translationText]);

        $correction = $this->sm->tryPropositionForExercise(
            $proposition,
            $translation
        );

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

