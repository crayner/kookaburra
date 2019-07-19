<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 19/07/2019
 * Time: 10:05
 */

namespace App\Util;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class ErrorHelper
 * @package App\Util
 */
class ErrorHelper
{
    /**
     * @var Environment
     */
    private static $twig;

    /**
     * ErrorHelper constructor.
     */
    public function __construct(Environment $twig)
    {
        self::$twig = $twig;
    }

    /**
     * ErrorResponse
     * @param string $extendedError
     * @param array $extendedParams
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public static function ErrorResponse(string $extendedError = '', array $extendedParams = [], $manager = null)
    {
        $content = self::$twig->render('legacy/error.html.twig',
            [
                'extendedError' => $extendedError,
                'extendedParams' => $extendedParams,
                'manager' => $manager,
            ]
        );

        $response = new Response($content);
        return $response;
    }
}