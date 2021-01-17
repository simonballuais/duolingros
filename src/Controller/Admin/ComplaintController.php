<?php
namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Complaint;


class ComplaintController extends Controller
{
    /**
     * @Route("/admin/complaints", name="admin_complaint")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repoComplaint = $em->getRepository("App:Complaint");
        $complaints = $repoComplaint->findAll();

        return $this->render(
            'admin/complaint/index.html.twig',
            [
                'complaints' => $complaints,
                'allCourses' => [],
            ]
        );
    }

    /**
     * @Route("/admin/complaints/{complaint}", name="admin_post_complaint")
     * @Method({"POST"})
     */
    public function postAction(Request $request, Complaint $complaint)
    {
        $translation = $complaint->getTranslation();
        $em = $this->getDoctrine()->getManager();
        $repoTranslation = $em->getRepository("App:Translation");

        $translation->setText($request->get('translation-text'));
        $newAnswers = $request->request->get('answers');
        $newAnswersWithoutEmptyAnswer = [];

        foreach ($newAnswers as $answer) {
            if ($answer) {
                $newAnswersWithoutEmptyAnswer[] = $answer;
            }
        }

        $translation->setAnswers($newAnswersWithoutEmptyAnswer);
        $em->remove($complaint);
        $em->flush();

        return $this->redirectToRoute('admin_complaint');
    }

    /**
     * @Route("/admin/complaint/delete/{complaint}", name="complaint_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Complaint $complaint)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($complaint);
        $em->flush();

        return $this->redirectToRoute('admin_complaint');
    }
}
?>
