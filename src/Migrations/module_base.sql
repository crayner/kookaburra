SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `gibbonmodule` (`gibbonModuleID`, `name`, `description`, `entryURL`, `type`, `active`, `category`, `version`, `author`, `url`) VALUES
(0001, 'School Admin', 'Allows administrators to configure school settings.', 'schoolYear_manage.php', 'Core', 'Y', 'Admin', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0002, 'User Admin', 'Allows administrators to manage users.', 'user_manage.php', 'Core', 'Y', 'Admin', '0.0.00', 'Craig Rayner', 'http://www.craigrayner.com'),
(0003, 'System Admin', 'Allows administrators to configure system settings.', 'system_settings', 'Core', 'Y', 'Admin', '0.0.00', 'Craig Rayner', 'http://www.craigrayner.com'),
(0004, 'Departments', 'View details within a department', 'list', 'Core', 'Y', 'Learn', '0.0.00', 'Craig Rayner', 'http://www.craigrayner.com'),
(0005, 'Students', 'Allows users to view student data', 'student_view.php', 'Core', 'Y', 'People', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0006, 'Attendance', 'School attendance taking', 'attendance.php', 'Core', 'Y', 'People', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0007, 'Markbook', 'A system for keeping track of marks', 'markbook_view.php', 'Core', 'Y', 'Assess', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0008, 'Data Updater', 'Allow users to update their family\'s data', 'data_updates.php', 'Core', 'Y', 'People', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0009, 'Planner', 'Supports lesson planning and information sharing for staff, student and parents', 'planner.php', 'Core', 'Y', 'Learn', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0011, 'Individual Needs', 'Individual Needs', 'in_view.php', 'Core', 'Y', 'Learn', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0012, 'Crowd Assessment', 'Allows users to assess each other\'s work', 'crowdAssess.php', 'Core', 'Y', 'Assess', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0013, 'Timetable Admin', 'Timetable administration', 'tt.php', 'Core', 'Y', 'Admin', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0014, 'Timetable', 'Allows users to view timetables', 'tt.php', 'Core', 'Y', 'Learn', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0015, 'Activities', 'Run a school activities program', 'activities_view.php', 'Core', 'Y', 'Learn', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0016, 'Formal Assessment', 'Facilitates tracking of student performance in external examinations.', 'externalAssessment.php', 'Core', 'Y', 'Assess', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0119, 'Behaviour', 'Tracking Student Behaviour', 'behaviour_manage.php', 'Core', 'Y', 'People', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0121, 'Messenger', 'Unified messenger for email, message wall and more.', 'messenger_manage.php', 'Core', 'Y', 'Other', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0126, 'Rubrics', 'Allows users to create rubrics for assessment', 'rubrics.php', 'Core', 'Y', 'Assess', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0130, 'Library', 'Allows the management of a catalog from which items can be borrowed.', 'manage_catalogue', 'Core', 'Y', 'Learn', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0135, 'Finance', 'Allows a school to issue invoices and track payments.', 'invoices_manage.php', 'Core', 'Y', 'Other', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0136, 'Staff', 'Allows users to view staff information', 'staff_view.php', 'Core', 'Y', 'People', 'v18.0.01', 'Ross Parker', 'http://rossparker.org'),
(0137, 'Roll Groups', 'Allows users to view a listing of roll groups', 'list', 'Core', 'Y', 'People', '0.0.00', 'Craig Rayner', 'http://www.craigrayner.com'),
(0141, 'Tracking', 'Provides visual graphing of student progress, as recorded in the Markbook and Internal Assessment.', 'graphing.php', 'Core', 'Y', 'Assess', 'v18.0.01', 'Ross Parker', 'https://rossparker.org');

SET FOREIGN_KEY_CHECKS = 1;
