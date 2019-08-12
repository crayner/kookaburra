<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 7/07/2019
 * Time: 09:22
 */

namespace App\Controller;

use App\Entity\CourseClass;
use App\Provider\ProviderFactory;
use App\Twig\FastFinder;
use Gibbon\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FinderController extends AbstractController
{
    /**
     * finderSearch
     * @param Request $request
     * @Route("/finder/{id}/redirect/", name="finder_redirect", methods={"GET"})
     */
    public function finderRedirect(string $id, Request $request)
    {
        $type = substr($id, 0, 3);
        $id = substr($id, 4);

        switch ($type) {
            case 'Stu':
                return $this->redirectToRoute('home', ['q' => '/modules/Students/student_view_details.php', 'gibbonPersonID' => $id]);
                break;
            case 'Act':
                return $this->redirectToRoute('home', ['q' => '/modules/'.$id]);
                break;
            case 'Sta':
                return $this->redirectToRoute('home', ['q' => '/modules/Staff/staff_view_details.php', 'gibbonPersonID' => $id]);
                break;
            case 'Cla':
                $class = ProviderFactory::getRepository(CourseClass::class)->find($id);
                return $this->redirectToRoute('departments__course_class_details', ['class' => $class->getId(), 'course' => $class->getCourse()->getId(), 'department' => $class->getCourse()->getDepartment()->getId()]);
                break;
            default:
                throw new Exception(sprintf('The finder search failed for the unknown type of "%s".', $type));
        }
    }

    /**
     * loadFastFinderOptions
     * @Route("/api/finder/search/legacy/", name="api_finder_options_legacy")
     */
    public function loadFastFinderOptions(FastFinder $fastFinder, Request $request)
    {
        $fastFinder->execute();
        $search = $request->query->get('q');
        $content = $fastFinder->getScriptManager()->getAppProps();
        $results = [];
        foreach($content['fastFinder']['fastFindChoices'] as $choice)
        {
            foreach($choice['suggestions'] as $suggestion)
            {
                if (stripos($suggestion['text'], $search) !== false || stripos($suggestion['search'], $search) !== false)
                {
                    $result = [];
                    $result['id'] = $suggestion['id'];
                    $result['name'] = $suggestion['text'];
                    if (strpos($suggestion['id'], 'Act-') === 0)
                        $result['name'] = $suggestion['search'] . ': ' .$suggestion['text'];
                    $results[] = $result;
                }
            }
        }

        return new JsonResponse($results, 200);
    }

    /**
     * finderLegacy
     * @param Request $request
     * @Route("/finder/legacy/", name="finder_redirect_legacy", methods={"GET"})
     */
    public function finderLegacy(Request $request)
    {
        $id = $request->query->get('fastFinderSearch');
        return $this->forward(FinderController::class.'::finderRedirect', ['id' => $id]);
    }
}