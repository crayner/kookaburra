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

use Gibbon\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FinderController extends AbstractController
{
    /**
     * finderSearch
     * @param Request $request
     * @Route("/finder/redirect/", name="finder_request", methods={"GET"})
     */
    public function finderRedirect(Request $request)
    {
        $type = substr($request->query->get('fastFinderSearch'), 0, 3);
        $id = substr($request->query->get('fastFinderSearch'), 4);

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
                return $this->redirectToRoute('home', ['q' => '/modules/Departments/department_course_class.php', 'gibbonCourseClassID' => $id]);
                break;
            default:
                throw new Exception(sprintf('The finder search failed for the unknown type of "%s".', $type));
        }
    }
}