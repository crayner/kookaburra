<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 26/08/2019
 * Time: 12:42
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DepartmentController
 * @package App\Controller\Modules
 * @Route("/resource", name="resource__")
 */
class ResourceController extends AbstractController
{
    /**
     * download
     * @param string $file
     * @param string $route
     * @return BinaryFileResponse
     * @Route("/{file}/{route}/download/", name="download")
     */
    public function download(string $file, string $route)
    {
        $this->denyAccessUnlessGranted('ROLE_ROUTE', [$route]);

        return new BinaryFileResponse(base64_decode($file));
    }
}