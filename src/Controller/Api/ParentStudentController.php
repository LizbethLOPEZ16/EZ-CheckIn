<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ParentStudent;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/student")
 */
class ParentStudentController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/getStudentByBarcode/{barcode}", name="api_student_get_by_barcode", methods={"GET"})
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getStudentByBarcode($barcode)
    {
        // $this->denyAccessUnlessGranted('ROLE_PROFESSOR'); // only access by proffesors
        $studentRepository = $this->em->getRepository(Student::class);
        $psRepository = $this->em->getRepository(ParentStudent::class);
        $student = $studentRepository->findOneBy(["id_aleatorio" => $barcode]);
        $parentStudent = $psRepository->findOneBy(["student" => $student]);

        return new JsonResponse($parentStudent, 200);
    }

    /**
     * @Route("/updateStudent/{id}", name="api_student_update", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateStudent($id, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $datetime = \DateTime::createFromFormat("Y-m-d H:i:s", $data["datetime"]);

        // $this->denyAccessUnlessGranted('ROLE_PROFESSOR');
        $studentRepository = $this->em->getRepository(Student::class);
        $student = $studentRepository->findOneBy(["id" => $id]);
        if ($data["schedule"] === "timeIn") {
            $student->setTimeIn($datetime);
        } else if ($data["schedule"] === "timeOut") {
            $student->setTimeOut($datetime);
        } else {
            return new JsonResponse(['error' => 'error', 'message' => 'Por favor especifique horario!'], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($student);
        $entityManager->flush();

        return new JsonResponse(['success' => 'success', 'message' => 'Student updated successfully!'], 200);
    }
}
