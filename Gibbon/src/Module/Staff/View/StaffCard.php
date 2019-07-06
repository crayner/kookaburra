<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

namespace Gibbon\Module\Staff\View;

use Gibbon\Domain\Staff\StaffGateway;
use Gibbon\Domain\RollGroups\RollGroupGateway;
use Gibbon\Contracts\Services\GibbonSession;
use Gibbon\Contracts\Database\Connection;
use Gibbon\View\Page;

/**
 * StaffCard
 * 
 * A view composer class for the staff card template: set a gibbonPersonID and display the staff details and links to their info.
 *
 * @version v18
 * @since   v18
 */
class StaffCard
{
    protected $session;
    protected $db;
    protected $staffGateway;
    protected $rollGroupGateway;
    protected $gibbonPersonID;
    protected $status;
    protected $tag;

    public function __construct(GibbonSession $session, Connection $db, StaffGateway $staffGateway, RollGroupGateway $rollGroupGateway)
    {
        $this->session = $session;
        $this->db = $db;
        $this->staffGateway = $staffGateway;
        $this->rollGroupGateway = $rollGroupGateway;
    }

    public function setPerson($gibbonPersonID)
    {
        $this->gibbonPersonID = $gibbonPersonID;

        return $this;
    }

    public function setStatus($status, $tag = '')
    {
        $this->status = $status;
        $this->tag = $tag;

        return $this;
    }

    public function compose(Page $page)
    {
        $guid = $this->session->get('guid');
        $connection2 = $this->db->getConnection();

        $page->writeFromTemplate('staffCard.html.twig', [
            'staff'             => $this->staffGateway->selectStaffByID($this->gibbonPersonID ?? '')->fetch(),
            'rollGroup'         => $this->rollGroupGateway->selectRollGroupsByTutor($this->gibbonPersonID ?? '')->fetch(),
            'canViewProfile'    => isActionAccessible($guid, $connection2, '/modules/Staff/staff_view_details.php'),
            'canViewAbsences'   => isActionAccessible($guid, $connection2, '/modules/Staff/absences_view_byPerson.php', 'View Absences_any'),
            'canViewTimetable'  => isActionAccessible($guid, $connection2, '/modules/Timetable/tt_view.php'),
            'canViewRollGroups' => isActionAccessible($guid, $connection2, '/modules/Roll Groups/rollGroups.php'),
            'status'            => $this->status,
            'tag'               => $this->tag,
        ]);
    }
}
