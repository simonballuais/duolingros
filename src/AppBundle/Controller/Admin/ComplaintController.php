<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Complaint;


class ComplaintController extends Controller
{
    /**
     * @Route("/admin/complaints", name="admin_complaint")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repoComplaint = $em->getRepository("AppBundle:Complaint");
        $complaints = $repoComplaint->findAll();

        return $this->render(
            'admin/complaint/index.html.twig',
            [
                'complaints' => $complaints,
            ]
        );
    }

    /**
     * @Route("/admin/complaints/{complaint}", name="admin_post_complaint")
     * @Method({"POST"})
     */
    public function postAction(Request $request, Complaint $complaint)
    {
        $exercise = $complaint->getExercise();
        $em = $this->getDoctrine()->getManager();
        $repoExercise = $em->getRepository("AppBundle:Exercise");

        $exercise->setText($request->get('exercise-text'));
        $newAnswerList = $request->request->get('answers');
        $newAnswerListWithoutEmptyAnswer = [];

        foreach ($newAnswerList as $answer) {
            if ($answer) {
                $newAnswerListWithoutEmptyAnswer[] = $answer;
            }
        }

        $exercise->setAnswerList($newAnswerListWithoutEmptyAnswer);
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
