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
     * @Route("/admin/complaint", name="admin_complaint")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoComplaint = $em->getRepository("AppBundle:Complaint");
        $complaints = $repoComplaint->findAll();

        if ($request->getMethod() === 'POST') {
            foreach ($request->request->all() as $text => $answerList) {
                $complaint = $answerList['_complaint'];
                unset($answerList['_complaint']);

                $text = str_replace('_', ' ', $text);
                $repoExercise = $em->getRepository("AppBundle:Exercise");
                $exercise = $repoExercise->findOneByText($text);
                $exercise->setAnswerList($answerList);

                $complaintToDelete = $repoComplaint->findOneById($complaint);
                $em->remove($complaintToDelete);

                $em->flush();

                return $this->redirectToRoute('admin_complaint');
            }
        }

        return $this->render(
            'admin/complaint/list.html.twig',
            [
                'complaints' => $complaints,
            ]
        );
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
