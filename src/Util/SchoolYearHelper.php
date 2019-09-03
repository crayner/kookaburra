<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/09/2019
 * Time: 10:01
 */

namespace App\Util;


use App\Entity\SchoolYear;
use App\Provider\ProviderFactory;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SchoolYearHelper
 * @package App\Util
 */
class SchoolYearHelper
{
    /**
     * @var RequestStack
     */
    private static $stack;

    /**
     * GlobalHelper constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        self::$stack = $stack;
    }

    /**
     * getCurrentSchoolYear
     * @return mixed
     */
    public static function getCurrentSchoolYear()
    {
        $session = self::$stack->getCurrentRequest()->getSession();
        if ($session->has('schoolYear'))
            return $session->get('schoolYear');
        return ProviderFactory::getRepository(SchoolYear::class)->findOneByStatus('Current');
    }
}