<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 26/08/2019
 * Time: 12:42
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        if (strpos($route, 'ROLE_') === 0)
            $this->denyAccessUnlessGranted($route);
        else
            $this->denyAccessUnlessGranted('ROLE_ROUTE', [$route]);

        $file = base64_decode($file);

        $public = realpath(__DIR__ . '/../../public');
        $file = realpath($file) ?: realpath($public.$file) ?: '';

        if (!is_file($file))
            throw new \Exception('The file was not found to download.');

        return new BinaryFileResponse($file);
    }

    /**
     * delete
     * @param string $file
     * @param string $route
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @Route("/{file}/{route}/delete/", name="delete")
     */
    public function delete(string $file, string $route, TranslatorInterface $translator)
    {
        if ((strpos($route, 'ROLE_') === 0 && $this->isGranted($route)) || $this->isGranted('ROLE_ROUTE', [$route])) {
            $file = base64_decode($file);
            $public = realpath(__DIR__ . '/../../public');
            $file = is_file($file) ? $file : (is_file($public . $file) ? $public . $file : '');
            if (is_file($file)) {
                unlink($file);
                $data['errors'][] = ['class' => 'success', 'message' => $translator->trans('Your request was completed successfully.')];
            } else {
                $data['errors'][] = ['class' => 'warning', 'message' => $translator->trans('Your request was successful, but some data was not properly deleted.')];
            }
            $data['errors'][] = ['class' => 'info', 'message' => $translator->trans('You must submit the form to save this empty value.', [], 'messages')];
            $data['status'] = 'success';
        } else {
            $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed because you do not have access to this action.')];
            $data['status'] = 'error';

        }
        return new JsonResponse($data, 200);
    }
}