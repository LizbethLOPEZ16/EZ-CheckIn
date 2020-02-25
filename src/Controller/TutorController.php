<?php

namespace App\Controller;

use App\Entity\ParentStudent;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TutorController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/tutor", name="tutor")
     */
    public function index()
    {
        $user = $this->getUser();
        $students = [];

        foreach ($user->getParentStudents() as $ps) {
            array_push($students, $ps->getStudent());
        };

        return $this->render('tutor/index.html.twig', [
            'action_name' => 'Home',
            'students' => $students
        ]);
    }

    /**
     * @Route("/tutor/add", name="tutor_add")
     */
    public function add(Request $request)
    {
        if ($request->get('studentId') !== null) {
            $this->denyAccessUnlessGranted('ROLE_TUTOR');
            $user = $this->getUser();
            $parentStudent = new ParentStudent();
            $studentRepository = $this->em->getRepository(Student::class);
            $student = $studentRepository->findOneBy(["id_aleatorio" => $request->get('studentId')]);

            $parentStudent->setStudent($student);
            $parentStudent->setParent($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parentStudent);
            $entityManager->flush();
        }


        return $this->render('tutor/add.html.twig', [
            'action_name' => 'Add Student'
        ]);
    }
}
