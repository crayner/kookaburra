<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 10/08/2019
 * Time: 10:44
 */

namespace App\Controller\Modules;

use App\Entity\TT;
use App\Manager\GibbonManager;
use App\Util\Format;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TimetableController
 * @package App\Controller\Modules
 */
class TimetableController extends AbstractController
{
    /**
     * render
     * @return \Symfony\Component\HttpFoundation\Response|void
     * @Route("/timetable/render/", name="timetable_render")
     */
    public function renderTT(Request $request, TranslatorInterface $translator, GibbonManager $manager)
    {
        if (!$this->isGranted('ROLE_ACTION', ['/modules/Timetable/tt.php']))
            return new Response('<div class="error">'.$translator->trans('Your request failed because you do not have access to this action.',[],'messages').'</div>');
        $manager->execute();
        include __DIR__ . '/../../../Gibbon/modules/Timetable/moduleFunctions.php';
        $output = '';
        $ttDate = '';
        if ($request->request->get('ttDate') !== '') {
            $ttDate = Format::timestamp(Format::dateConvert($request->request->get('ttDate')));
        }

        $id = $request->request->has('gibbonTTID') && intval($request->request->get('gibbonTTID')) > 0 ? intval($request->request->get('gibbonTTID')) : '';

        if ($request->request->get('fromTT') === 'Y') {
            if ($request->request->get('schoolCalendar') === 'on' || $request->request->get('schoolCalendar') === 'Y') {
                $request->getSession()->set('viewCalendarSchool', 'Y');
            } else {
                $request->getSession()->set('viewCalendarSchool', 'N');
            }

            if ($request->request->get('personalCalendar') === 'on' || $request->request->get('personalCalendar') === 'Y') {
                $request->getSession()->set('viewCalendarPersonal', 'Y');
            } else {
                $request->getSession()->set('viewCalendarPersonal', 'N');
            }

            if ($request->request->get('spaceBookingCalendar') === 'on' || $request->request->get('spaceBookingCalendar') === 'Y') {
                $request->getSession()->set('viewCalendarSpaceBooking', 'Y');
            } else {
                $request->getSession()->set('viewCalendarSpaceBooking', 'N');
            }
        }
        $tt = renderTT($request->getSession()->get('guid'), GibbonManager::getPDO(), $request->getSession()->get('gibbonPersonID'), $id, false, $ttDate, '', '', 'trim');
        if ($tt !== false) {
            $output .= $tt;
        } else {
            $output .= "<div class='error'>";
            $output .= $translator->trans('There is no information for the date specified.', [], 'messages');
            $output .= '</div>';
        }
        return new Response($output);
    }
}