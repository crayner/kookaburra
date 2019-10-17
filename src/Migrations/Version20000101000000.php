<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20000101000000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }


    /**
     * @param mixed[] $params
     * @param mixed[] $types
     */
    protected function addSql(
        string $sql,
        array $params = [],
        array $types = []
    ) : void {

        if (strpos($sql, 'CREATE TABLE') === 0 && strpos($sql, ' AUTO_INCREMENT,') !== false) {
            $first = strpos($sql, ' AUTO_INCREMENT,') + 16;
            $start = substr($sql, 0, $first);
            $end = substr($sql, $first);
            $sql = $start . str_replace(' AUTO_INCREMENT,', ',', $end);
        }

        $this->version->addSql($sql, $params, $types);
    }


    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("CREATE TABLE gibbonAction (gibbonActionID INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(50) NOT NULL COMMENT 'The action name should be unqiue to the module that it is related to', precedence INT(2), category VARCHAR(20) NOT NULL, description VARCHAR(255) NOT NULL, URLList LONGTEXT NOT NULL COMMENT 'Comma seperated list of all URLs that make up this action', entryURL VARCHAR(255) NOT NULL, entrySidebar VARCHAR(1) DEFAULT 'Y' NOT NULL, menuShow VARCHAR(1) DEFAULT 'Y' NOT NULL, defaultPermissionAdmin VARCHAR(1) DEFAULT 'N' NOT NULL, defaultPermissionTeacher VARCHAR(1) DEFAULT 'N' NOT NULL, defaultPermissionStudent VARCHAR(1) DEFAULT 'N' NOT NULL, defaultPermissionParent VARCHAR(1) DEFAULT 'N' NOT NULL, defaultPermissionSupport VARCHAR(1) DEFAULT 'N' NOT NULL, categoryPermissionStaff VARCHAR(1) DEFAULT 'Y' NOT NULL, categoryPermissionStudent VARCHAR(1) DEFAULT 'Y' NOT NULL, categoryPermissionParent VARCHAR(1) DEFAULT 'Y' NOT NULL, categoryPermissionOther VARCHAR(1) DEFAULT 'Y' NOT NULL, gibbonModuleID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonModuleID (gibbonModuleID), UNIQUE INDEX moduleActionName (name, gibbonModuleID), PRIMARY KEY(gibbonActionID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1");
        $this->addSql('CREATE TABLE gibbonActivity (gibbonActivityID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, registration VARCHAR(1) DEFAULT \'Y\' NOT NULL COMMENT \'Can a parent/student select this for registration?\', name VARCHAR(40) NOT NULL, provider VARCHAR(8) DEFAULT \'School\' NOT NULL, type VARCHAR(255) NOT NULL, gibbonSchoolYearTermIDList LONGTEXT NOT NULL, listingStart DATE DEFAULT NULL, listingEnd DATE DEFAULT NULL, programStart DATE DEFAULT NULL, programEnd DATE DEFAULT NULL, description LONGTEXT DEFAULT NULL, payment NUMERIC(8, 2) DEFAULT NULL, paymentFirmness VARCHAR(9) DEFAULT \'Finalised\', paymentType VARCHAR(24) DEFAULT \'Entire Programme\', gibbonYearGroupIDList VARCHAR(255) NOT NULL, maxParticipants INT(3), gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_F971E05871FA7520 (gibbonSchoolYearID), PRIMARY KEY(gibbonActivityID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonActivityAttendance (gibbonActivityAttendanceID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, attendance LONGTEXT NOT NULL, date DATE DEFAULT NULL, timestampTaken DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonActivityID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDTaker INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_2357A712C3BBAF6F (gibbonActivityID), INDEX IDX_2357A71211A14ED (gibbonPersonIDTaker), PRIMARY KEY(gibbonActivityAttendanceID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonActivitySlot (gibbonActivitySlotID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, locationExternal VARCHAR(50) NOT NULL, timeStart TIME NOT NULL, timeEnd TIME NOT NULL, gibbonActivityID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonSpaceID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonDaysOfWeekID INT(2) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_59227ABBC3BBAF6F (gibbonActivityID), INDEX IDX_59227ABBD8D64BA0 (gibbonSpaceID), INDEX IDX_59227ABB2817C0E1 (gibbonDaysOfWeekID), PRIMARY KEY(gibbonActivitySlotID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonActivityStaff (gibbonActivityStaffID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, role VARCHAR(9) DEFAULT \'Organiser\' NOT NULL, gibbonActivityID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_CDFE4137C3BBAF6F (gibbonActivityID), INDEX IDX_CDFE4137CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonActivityStaffID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonActivityStudent (gibbonActivityStudentID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, status VARCHAR(12) DEFAULT \'Pending\' NOT NULL, timestamp DATETIME NOT NULL, invoiceGenerated VARCHAR(1) DEFAULT \'N\' NOT NULL, gibbonActivityID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonActivityIDBackup INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFinanceInvoiceID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_413CBAAFC3BBAF6F (gibbonActivityID), INDEX IDX_413CBAAFCC6782D6 (gibbonPersonID), INDEX IDX_413CBAAF6A1380D0 (gibbonActivityIDBackup), INDEX IDX_413CBAAFF51FBF6 (gibbonFinanceInvoiceID), INDEX gibbonActivityID (gibbonActivityID, status), PRIMARY KEY(gibbonActivityStudentID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonAlarm (gibbonAlarmID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, type VARCHAR(8) DEFAULT NULL, status VARCHAR(7) DEFAULT \'Past\' NOT NULL, timestampStart DATETIME DEFAULT NULL, timestampEnd DATETIME DEFAULT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_E3BDDFEBCC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonAlarmID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonAlarmConfirm (gibbonAlarmConfirmID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonAlarmID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_8E3CE4BCBA3565E5 (gibbonAlarmID), INDEX IDX_8E3CE4BCCC6782D6 (gibbonPersonID), UNIQUE INDEX gibbonAlarmID (gibbonAlarmID, gibbonPersonID), PRIMARY KEY(gibbonAlarmConfirmID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonAlertLevel (gibbonAlertLevelID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, nameShort VARCHAR(4) NOT NULL, color VARCHAR(6) NOT NULL COMMENT \'RGB Hex, no leading #\', colorBG VARCHAR(6) NOT NULL COMMENT \'RGB Hex, no leading #\', description LONGTEXT NOT NULL, sequenceNumber INT(3), PRIMARY KEY(gibbonAlertLevelID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonApplicationForm (gibbonApplicationFormID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonApplicationFormHash VARCHAR(40) DEFAULT NULL, surname VARCHAR(60) NOT NULL, firstName VARCHAR(60) NOT NULL, preferredName VARCHAR(60) NOT NULL, officialName VARCHAR(150) NOT NULL, nameInCharacters VARCHAR(20) NOT NULL, gender VARCHAR(12) DEFAULT \'Unspecified\', username VARCHAR(20) DEFAULT NULL, status VARCHAR(12) DEFAULT \'Pending\' NOT NULL, dob DATE DEFAULT NULL, email VARCHAR(75) DEFAULT NULL, homeAddress LONGTEXT DEFAULT NULL, homeAddressDistrict VARCHAR(255) DEFAULT NULL, homeAddressCountry VARCHAR(255) DEFAULT NULL, phone1Type VARCHAR(6) NOT NULL, phone1CountryCode VARCHAR(7) NOT NULL, phone1 VARCHAR(20) NOT NULL, phone2Type VARCHAR(6) NOT NULL, phone2CountryCode VARCHAR(7) NOT NULL, phone2 VARCHAR(20) NOT NULL, countryOfBirth VARCHAR(30) NOT NULL, citizenship1 VARCHAR(255) NOT NULL, citizenship1Passport VARCHAR(30) NOT NULL, nationalIDCardNumber VARCHAR(30) NOT NULL, residencyStatus VARCHAR(255) NOT NULL, visaExpiryDate DATE DEFAULT NULL, dayType VARCHAR(255) DEFAULT NULL, referenceEmail VARCHAR(100) DEFAULT NULL, schoolName1 VARCHAR(50) NOT NULL, schoolAddress1 VARCHAR(255) NOT NULL, schoolGrades1 VARCHAR(20) NOT NULL, schoolLanguage1 VARCHAR(50) NOT NULL, schoolDate1 DATE DEFAULT NULL, schoolName2 VARCHAR(50) NOT NULL, schoolAddress2 VARCHAR(255) NOT NULL, schoolGrades2 VARCHAR(20) NOT NULL, schoolLanguage2 VARCHAR(50) NOT NULL, schoolDate2 DATE DEFAULT NULL, siblingName1 VARCHAR(50) NOT NULL, siblingDOB1 DATE DEFAULT NULL, siblingSchool1 VARCHAR(50) NOT NULL, siblingSchoolJoiningDate1 DATE DEFAULT NULL, siblingName2 VARCHAR(50) NOT NULL, siblingDOB2 DATE DEFAULT NULL, siblingSchool2 VARCHAR(50) NOT NULL, siblingSchoolJoiningDate2 DATE DEFAULT NULL, siblingName3 VARCHAR(50) NOT NULL, siblingDOB3 DATE DEFAULT NULL, siblingSchool3 VARCHAR(50) NOT NULL, siblingSchoolJoiningDate3 DATE DEFAULT NULL, languageHomePrimary VARCHAR(30) NOT NULL, languageHomeSecondary VARCHAR(30) NOT NULL, languageFirst VARCHAR(30) NOT NULL, languageSecond VARCHAR(30) NOT NULL, languageThird VARCHAR(30) NOT NULL, medicalInformation LONGTEXT NOT NULL, sen VARCHAR(1) DEFAULT NULL, senDetails LONGTEXT NOT NULL, languageChoice VARCHAR(100) DEFAULT NULL, languageChoiceExperience LONGTEXT DEFAULT NULL, scholarshipInterest VARCHAR(1) DEFAULT \'N\' NOT NULL, scholarshipRequired VARCHAR(1) DEFAULT \'N\' NOT NULL, payment VARCHAR(7) DEFAULT \'Family\' NOT NULL, companyName VARCHAR(100) DEFAULT NULL, companyContact VARCHAR(100) DEFAULT NULL, companyAddress VARCHAR(255) DEFAULT NULL, companyEmail LONGTEXT DEFAULT NULL, companyCCFamily VARCHAR(1) DEFAULT NULL COMMENT \'When company is billed, should family receive a copy?\', companyPhone VARCHAR(20) DEFAULT NULL, companyAll VARCHAR(1) DEFAULT NULL, gibbonFinanceFeeCategoryIDList LONGTEXT DEFAULT NULL, agreement VARCHAR(1) DEFAULT NULL, parent1title VARCHAR(5) DEFAULT NULL, parent1surname VARCHAR(60) DEFAULT NULL, parent1firstName VARCHAR(60) DEFAULT NULL, parent1preferredName VARCHAR(60) DEFAULT NULL, parent1officialName VARCHAR(150) DEFAULT NULL, parent1nameInCharacters VARCHAR(20) DEFAULT NULL, parent1gender VARCHAR(12) DEFAULT \'Unspecified\', parent1relationship VARCHAR(50) DEFAULT NULL, parent1languageFirst VARCHAR(30) DEFAULT NULL, parent1languageSecond VARCHAR(30) DEFAULT NULL, parent1citizenship1 VARCHAR(255) DEFAULT NULL, parent1nationalIDCardNumber VARCHAR(30) DEFAULT NULL, parent1residencyStatus VARCHAR(255) DEFAULT NULL, parent1visaExpiryDate DATE DEFAULT NULL, parent1email VARCHAR(75) DEFAULT NULL, parent1phone1Type VARCHAR(6) DEFAULT NULL, parent1phone1CountryCode VARCHAR(7) DEFAULT NULL, parent1phone1 VARCHAR(20) DEFAULT NULL, parent1phone2Type VARCHAR(6) DEFAULT NULL, parent1phone2CountryCode VARCHAR(7) DEFAULT NULL, parent1phone2 VARCHAR(20) DEFAULT NULL, parent1profession VARCHAR(30) DEFAULT NULL, parent1employer VARCHAR(30) DEFAULT NULL, parent2title VARCHAR(5) DEFAULT NULL, parent2surname VARCHAR(60) DEFAULT NULL, parent2firstName VARCHAR(60) DEFAULT NULL, parent2preferredName VARCHAR(60) DEFAULT NULL, parent2officialName VARCHAR(150) DEFAULT NULL, parent2nameInCharacters VARCHAR(20) DEFAULT NULL, parent2gender VARCHAR(12) DEFAULT \'Unspecified\', parent2relationship VARCHAR(50) DEFAULT NULL, parent2languageFirst VARCHAR(30) DEFAULT NULL, parent2languageSecond VARCHAR(30) DEFAULT NULL, parent2citizenship1 VARCHAR(255) DEFAULT NULL, parent2nationalIDCardNumber VARCHAR(30) DEFAULT NULL, parent2residencyStatus VARCHAR(255) DEFAULT NULL, parent2visaExpiryDate DATE DEFAULT NULL, parent2email VARCHAR(75) DEFAULT NULL, parent2phone1Type VARCHAR(6) DEFAULT NULL, parent2phone1CountryCode VARCHAR(7) DEFAULT NULL, parent2phone1 VARCHAR(20) DEFAULT NULL, parent2phone2Type VARCHAR(6) DEFAULT NULL, parent2phone2CountryCode VARCHAR(7) DEFAULT NULL, parent2phone2 VARCHAR(20) DEFAULT NULL, parent2profession VARCHAR(30) DEFAULT NULL, parent2employer VARCHAR(30) DEFAULT NULL, timestamp DATETIME DEFAULT NULL, priority INT(1), milestones LONGTEXT NOT NULL, notes LONGTEXT NOT NULL, dateStart DATE DEFAULT NULL, howDidYouHear VARCHAR(255) DEFAULT NULL, howDidYouHearMore VARCHAR(255) DEFAULT NULL, paymentMade VARCHAR(10) DEFAULT \'N\' NOT NULL, studentID VARCHAR(10) DEFAULT NULL, privacy LONGTEXT DEFAULT NULL, fields LONGTEXT NOT NULL COMMENT \'Serialised array of custom field values\', parent1fields LONGTEXT NOT NULL COMMENT \'Serialised array of custom field values\', parent2fields LONGTEXT NOT NULL COMMENT \'Serialised array of custom field values\', gibbonSchoolYearIDEntry INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonYearGroupIDEntry INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, parent1gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonRollGroupID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFamilyID INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPaymentID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_A309B59CF9B7736F (gibbonSchoolYearIDEntry), INDEX IDX_A309B59C9DE35FD8 (gibbonYearGroupIDEntry), INDEX IDX_A309B59C7DF7AB4B (parent1gibbonPersonID), INDEX IDX_A309B59CA85AE4EC (gibbonRollGroupID), INDEX IDX_A309B59C51F0BB1F (gibbonFamilyID), INDEX IDX_A309B59CA0F353A3 (gibbonPaymentID), PRIMARY KEY(gibbonApplicationFormID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonApplicationFormFile (gibbonApplicationFormFileID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, gibbonApplicationFormID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_86B3B2D2772C4226 (gibbonApplicationFormID), PRIMARY KEY(gibbonApplicationFormFileID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonApplicationFormLink (gibbonApplicationFormLinkID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonApplicationFormID1 INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonApplicationFormID2 INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_3C801D3351A64608 (gibbonApplicationFormID1), INDEX IDX_3C801D33C8AF17B2 (gibbonApplicationFormID2), UNIQUE INDEX link (gibbonApplicationFormID1, gibbonApplicationFormID2), PRIMARY KEY(gibbonApplicationFormLinkID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonApplicationFormRelationship (gibbonApplicationFormRelationshipID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, relationship VARCHAR(50) NOT NULL, gibbonApplicationFormID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_5E0017E7772C4226 (gibbonApplicationFormID), INDEX IDX_5E0017E7CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonApplicationFormRelationshipID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonAttendanceCode (gibbonAttendanceCodeID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, nameShort VARCHAR(4) NOT NULL, type VARCHAR(12) NOT NULL, direction VARCHAR(3) NOT NULL, scope VARCHAR(14) NOT NULL, active VARCHAR(1) NOT NULL, reportable VARCHAR(1) NOT NULL, future VARCHAR(1) NOT NULL, gibbonRoleIDAll VARCHAR(90) NOT NULL, sequenceNumber INT(3), UNIQUE INDEX name (name), UNIQUE INDEX nameShort (nameShort), PRIMARY KEY(gibbonAttendanceCodeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonAttendanceLogCourseClass (gibbonAttendanceLogCourseClassID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE DEFAULT NULL, timestampTaken DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDTaker INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_6D6C05B0B67991E (gibbonCourseClassID), INDEX IDX_6D6C05B011A14ED (gibbonPersonIDTaker), PRIMARY KEY(gibbonAttendanceLogCourseClassID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonAttendanceLogPerson (gibbonAttendanceLogPersonID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, direction VARCHAR(3) NOT NULL, type VARCHAR(30) NOT NULL, reason VARCHAR(30) NOT NULL, context VARCHAR(20) DEFAULT NULL, comment VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, timestampTaken DATETIME DEFAULT NULL, gibbonAttendanceCodeID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDTaker INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_1FC495175772A3E4 (gibbonAttendanceCodeID), INDEX IDX_1FC4951711A14ED (gibbonPersonIDTaker), INDEX IDX_1FC49517B67991E (gibbonCourseClassID), INDEX date (date), INDEX dateAndPerson (date, gibbonPersonID), INDEX gibbonPersonID (gibbonPersonID), UNIQUE INDEX dateContextPersonClass (date, context, gibbonPersonID, gibbonCourseClassID), PRIMARY KEY(gibbonAttendanceLogPersonID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonAttendanceLogRollGroup (gibbonAttendanceLogRollGroupID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE DEFAULT NULL, timestampTaken DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonRollGroupID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDTaker INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_A6F88BEDA85AE4EC (gibbonRollGroupID), INDEX IDX_A6F88BED11A14ED (gibbonPersonIDTaker), PRIMARY KEY(gibbonAttendanceLogRollGroupID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonBehaviour (gibbonBehaviourID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE NOT NULL, type VARCHAR(8) NOT NULL, descriptor VARCHAR(100) DEFAULT NULL, level VARCHAR(100) DEFAULT NULL, comment LONGTEXT NOT NULL, followup LONGTEXT NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_64915B371FA7520 (gibbonSchoolYearID), INDEX IDX_64915B3FE417281 (gibbonPlannerEntryID), INDEX IDX_64915B3FF59AAB0 (gibbonPersonIDCreator), INDEX gibbonPersonID (gibbonPersonID), PRIMARY KEY(gibbonBehaviourID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonBehaviourLetter (gibbonBehaviourLetterID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, letterLevel VARCHAR(1) NOT NULL, status VARCHAR(7) NOT NULL, recordCountAtCreation INT(3), body LONGTEXT NOT NULL, recipientList LONGTEXT NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_5F61F91071FA7520 (gibbonSchoolYearID), INDEX IDX_5F61F910CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonBehaviourLetterID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonCountry (printable_name VARCHAR(80) NOT NULL, iddCountryCode VARCHAR(7) NOT NULL, PRIMARY KEY(printable_name)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonCourse` (
  `gibbonCourseID` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `nameShort` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `map` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\' COMMENT \'Should this course be included in curriculum maps and other summaries?\',
  `gibbonYearGroupIDList` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `orderBy` int(3) DEFAULT NULL,
  `gibbonSchoolYearID` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonDepartmentID` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonCourseID`),
  UNIQUE KEY `nameYear` (`gibbonSchoolYearID`,`name`),
  UNIQUE KEY `nameShortYear` (`gibbonSchoolYearID`,`nameShort`),
  KEY `IDX_D9B3D8B96DFE7E92` (`gibbonDepartmentID`),
  KEY `gibbonSchoolYearID` (`gibbonSchoolYearID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonCourseClass` (
    `gibbonCourseClassID` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nameShort` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `reportable` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `attendance` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `gibbonCourseID` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonScaleIDTarget` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonCourseClassID`),
  UNIQUE KEY `nameCourse` (`name`,`gibbonCourseID`),
  UNIQUE KEY `nameShortCourse` (`nameShort`,`gibbonCourseID`),
  KEY `IDX_455FF3977DD4B430` (`gibbonScaleIDTarget`),
  KEY `gibbonCourseID` (`gibbonCourseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonCourseClassMap (gibbonCourseClassMapID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonRollGroupID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonYearGroupID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_97F9BC70A85AE4EC (gibbonRollGroupID), INDEX IDX_97F9BC70427372F (gibbonYearGroupID), UNIQUE INDEX gibbonCourseClassID (gibbonCourseClassID), PRIMARY KEY(gibbonCourseClassMapID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonCourseClassPerson` (
  `gibbonCourseClassPersonID` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `role` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `reportable` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `gibbonCourseClassID` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonCourseClassPersonID`),
  UNIQUE KEY `courseClassPerson` (`gibbonCourseClassID`,`gibbonPersonID`),
  KEY `IDX_D9B888E9CC6782D6` (`gibbonPersonID`),
  KEY `gibbonCourseClassID` (`gibbonCourseClassID`),
  KEY `gibbonPersonID` (`gibbonPersonID`,`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
        $this->addSql('CREATE TABLE gibbonCrowdAssessDiscuss (gibbonCrowdAssessDiscussID INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, comment LONGTEXT NOT NULL, gibbonPlannerEntryHomeworkID INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonCrowdAssessDiscussIDReplyTo INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_D17E708617B9ED44 (gibbonPlannerEntryHomeworkID), INDEX IDX_D17E7086CC6782D6 (gibbonPersonID), INDEX IDX_D17E7086D96E9809 (gibbonCrowdAssessDiscussIDReplyTo), PRIMARY KEY(gibbonCrowdAssessDiscussID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonDaysOfWeek (gibbonDaysOfWeekID INT(2) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(10) NOT NULL, nameShort VARCHAR(4) NOT NULL, sequenceNumber INT(2), schoolDay VARCHAR(1) DEFAULT \'Y\' NOT NULL, schoolOpen TIME DEFAULT NULL, schoolStart TIME DEFAULT NULL, schoolEnd TIME DEFAULT NULL, schoolClose TIME DEFAULT NULL, UNIQUE INDEX name (name, nameShort), UNIQUE INDEX nameShort (nameShort), UNIQUE INDEX sequenceNumber (sequenceNumber), PRIMARY KEY(gibbonDaysOfWeekID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonDepartment` (
  `gibbonDepartmentID` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `type` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Learning Area\',
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `nameShort` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `subjectListing` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `blurb` longtext COLLATE utf8_unicode_ci,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`gibbonDepartmentID`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `nameShort` (`nameShort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonDepartmentResource (gibbonDepartmentResourceID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, type VARCHAR(16) NOT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, gibbonDepartmentID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_276BB0276DFE7E92 (gibbonDepartmentID), PRIMARY KEY(gibbonDepartmentResourceID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonDepartmentStaff` (
  `gibbonDepartmentStaffID` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `role` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `gibbonDepartmentID` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonDepartmentStaffID`),
  UNIQUE KEY `departmentPerson` (`gibbonDepartmentID`,`gibbonPersonID`),
  KEY `IDX_EE77E05B6DFE7E92` (`gibbonDepartmentID`),
  KEY `IDX_EE77E05BCC6782D6` (`gibbonPersonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonDistrict (gibbonDistrictID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, PRIMARY KEY(gibbonDistrictID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonExternalAssessment (gibbonExternalAssessmentID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, nameShort VARCHAR(10) NOT NULL, description VARCHAR(255) NOT NULL, website LONGTEXT NOT NULL, active VARCHAR(1) NOT NULL, allowFileUpload VARCHAR(1) DEFAULT \'N\' NOT NULL, PRIMARY KEY(gibbonExternalAssessmentID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonExternalAssessmentField (gibbonExternalAssessmentFieldID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, category VARCHAR(50) NOT NULL, `order` INT(4), gibbonYearGroupIDList VARCHAR(255) DEFAULT NULL, gibbonExternalAssessmentID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonScaleID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_A03EECA95F72BC3 (gibbonScaleID), INDEX gibbonExternalAssessmentID (gibbonExternalAssessmentID), PRIMARY KEY(gibbonExternalAssessmentFieldID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonExternalAssessmentStudent (gibbonExternalAssessmentStudentID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE NOT NULL, attachment VARCHAR(255) NOT NULL, gibbonExternalAssessmentID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonExternalAssessmentID (gibbonExternalAssessmentID), INDEX gibbonPersonID (gibbonPersonID), PRIMARY KEY(gibbonExternalAssessmentStudentID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonExternalAssessmentStudentEntry (gibbonExternalAssessmentStudentEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonExternalAssessmentStudentID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonExternalAssessmentFieldID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonScaleGradeID INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonExternalAssessmentStudentID (gibbonExternalAssessmentStudentID), INDEX gibbonExternalAssessmentFieldID (gibbonExternalAssessmentFieldID), INDEX gibbonScaleGradeID (gibbonScaleGradeID), PRIMARY KEY(gibbonExternalAssessmentStudentEntryID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql("CREATE TABLE `gibbonFamily` (
  `gibbonFamilyID` int(7) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nameAddress` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT \'The formal name to be used for addressing the family (e.g. Mr. & Mrs. Smith)\',
  `homeAddress` longtext COLLATE utf8_unicode_ci NOT NULL,
  `homeAddressDistrict` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `homeAddressCountry` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `languageHomePrimary` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `languageHomeSecondary` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `familySync` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`gibbonFamilyID`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `familySync` (`familySync`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1");
        $this->addSql('CREATE TABLE `gibbonFamilyAdult` (
  `gibbonFamilyAdultID` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `comment` longtext COLLATE utf8_unicode_ci NOT NULL,
  `childDataAccess` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `contactPriority` int(2) DEFAULT NULL,
  `contactCall` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `contactSMS` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `contactEmail` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `contactMail` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `gibbonFamilyID` int(7) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonFamilyAdultID`),
  UNIQUE KEY `familyMember` (`gibbonFamilyID`,`gibbonPersonID`),
  KEY `IDX_EEF67AB51F0BB1F` (`gibbonFamilyID`),
  KEY `gibbonPersonIndex` (`gibbonPersonID`),
  KEY `familyContactPriority` (`gibbonFamilyID`,`contactPriority`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonFamilyChild` (
  `gibbonFamilyChildID` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `comment` longtext COLLATE utf8_unicode_ci NOT NULL,
  `gibbonFamilyID` int(7) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonFamilyChildID`),
  UNIQUE KEY `familyMember` (`gibbonFamilyID`,`gibbonPersonID`),
  KEY `gibbonFamilyIndex` (`gibbonFamilyID`),
  KEY `gibbonPersonIndex` (`gibbonPersonID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonFamilyRelationship` (
  `gibbonFamilyRelationshipID` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `relationship` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gibbonFamilyID` int(7) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonID1` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonID2` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonFamilyRelationshipID`),
  KEY `IDX_D6CA42151F0BB1F` (`gibbonFamilyID`),
  KEY `IDX_D6CA421ECA0FFD4` (`gibbonPersonID1`),
  KEY `IDX_D6CA42175A9AE6E` (`gibbonPersonID2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFamilyUpdate (gibbonFamilyUpdateID INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT, status VARCHAR(8) DEFAULT \'Pending\' NOT NULL, nameAddress VARCHAR(100) NOT NULL, homeAddress LONGTEXT NOT NULL, homeAddressDistrict VARCHAR(255) NOT NULL, homeAddressCountry VARCHAR(255) NOT NULL, languageHomePrimary VARCHAR(30) NOT NULL, languageHomeSecondary VARCHAR(30) NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFamilyID INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdater INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_438A3D3B71FA7520 (gibbonSchoolYearID), INDEX IDX_438A3D3B51F0BB1F (gibbonFamilyID), INDEX IDX_438A3D3B71106375 (gibbonPersonIDUpdater), INDEX gibbonFamilyIndex (gibbonFamilyID, gibbonSchoolYearID), PRIMARY KEY(gibbonFamilyUpdateID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFileExtension (gibbonFileExtensionID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, type VARCHAR(16) DEFAULT \'Other\' NOT NULL, extension VARCHAR(7) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(gibbonFileExtensionID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceBillingSchedule (gibbonFinanceBillingScheduleID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, invoiceIssueDate DATE DEFAULT NULL, invoiceDueDate DATE DEFAULT NULL, timestampCreator DATETIME DEFAULT NULL, timestampUpdate DATETIME DEFAULT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdate INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_EC0D8C7D71FA7520 (gibbonSchoolYearID), INDEX IDX_EC0D8C7DFF59AAB0 (gibbonPersonIDCreator), INDEX IDX_EC0D8C7DAE8C8C10 (gibbonPersonIDUpdate), PRIMARY KEY(gibbonFinanceBillingScheduleID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceBudget (gibbonFinanceBudgetID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, nameShort VARCHAR(8) NOT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, category VARCHAR(255) NOT NULL, timestampCreator DATETIME DEFAULT NULL, timestampUpdate DATETIME DEFAULT NULL, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdate INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_EE793C02FF59AAB0 (gibbonPersonIDCreator), INDEX IDX_EE793C02AE8C8C10 (gibbonPersonIDUpdate), UNIQUE INDEX name (name), UNIQUE INDEX nameShort (nameShort), PRIMARY KEY(gibbonFinanceBudgetID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceBudgetCycle (gibbonFinanceBudgetCycleID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(7) NOT NULL, status VARCHAR(8) DEFAULT \'Upcoming\' NOT NULL, dateStart DATE NOT NULL, dateEnd DATE NOT NULL, sequenceNumber INT(6), timestampCreator DATETIME DEFAULT NULL, timestampUpdate DATETIME DEFAULT NULL, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdate INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_2AA76753FF59AAB0 (gibbonPersonIDCreator), INDEX IDX_2AA76753AE8C8C10 (gibbonPersonIDUpdate), UNIQUE INDEX name (name), PRIMARY KEY(gibbonFinanceBudgetCycleID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceBudgetCycleAllocation (gibbonFinanceBudgetCycleAllocationID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, value NUMERIC(14, 2) DEFAULT \'0.00\' NOT NULL, gibbonFinanceBudgetID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFinanceBudgetCycleID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_B27D799BC8A9346 (gibbonFinanceBudgetID), INDEX IDX_B27D799B5393B3F1 (gibbonFinanceBudgetCycleID), PRIMARY KEY(gibbonFinanceBudgetCycleAllocationID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceBudgetPerson (gibbonFinanceBudgetPersonID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, access VARCHAR(6) NOT NULL, gibbonFinanceBudgetID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_AF223270C8A9346 (gibbonFinanceBudgetID), INDEX IDX_AF223270CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonFinanceBudgetPersonID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceExpense (gibbonFinanceExpenseID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, title VARCHAR(60) NOT NULL, body LONGTEXT NOT NULL, status VARCHAR(10) NOT NULL, cost NUMERIC(12, 2) NOT NULL, countAgainstBudget VARCHAR(1) DEFAULT \'Y\' NOT NULL, purchaseBy VARCHAR(6) DEFAULT \'School\' NOT NULL, purchaseDetails LONGTEXT NOT NULL, paymentMethod VARCHAR(16) DEFAULT NULL, paymentDate DATE DEFAULT NULL, paymentAmount NUMERIC(12, 2) DEFAULT NULL, paymentID VARCHAR(100) DEFAULT NULL, paymentReimbursementReceipt VARCHAR(255) NOT NULL, paymentReimbursementStatus VARCHAR(10) DEFAULT NULL, timestampCreator DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, statusApprovalBudgetCleared VARCHAR(1) DEFAULT \'N\' NOT NULL, gibbonFinanceBudgetID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFinanceBudgetCycleID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDPayment INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_47ECFF5C8A9346 (gibbonFinanceBudgetID), INDEX IDX_47ECFF55393B3F1 (gibbonFinanceBudgetCycleID), INDEX IDX_47ECFF52E77C4DE (gibbonPersonIDPayment), INDEX IDX_47ECFF5FF59AAB0 (gibbonPersonIDCreator), PRIMARY KEY(gibbonFinanceExpenseID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceExpenseApprover (gibbonFinanceExpenseApproverID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, sequenceNumber INT(4), timestampCreator DATETIME DEFAULT NULL, timestampUpdate DATETIME DEFAULT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdate INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_38833027CC6782D6 (gibbonPersonID), INDEX IDX_38833027FF59AAB0 (gibbonPersonIDCreator), INDEX IDX_38833027AE8C8C10 (gibbonPersonIDUpdate), PRIMARY KEY(gibbonFinanceExpenseApproverID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceExpenseLog (gibbonFinanceExpenseLogID INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, action VARCHAR(24) NOT NULL, comment LONGTEXT NOT NULL, gibbonFinanceExpenseID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_FFA208A073C3AD9D (gibbonFinanceExpenseID), INDEX IDX_FFA208A0CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonFinanceExpenseLogID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceFee (gibbonFinanceFeeID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(100) NOT NULL, nameShort VARCHAR(6) NOT NULL, description LONGTEXT NOT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, fee NUMERIC(12, 2) NOT NULL, timestampCreator DATETIME DEFAULT NULL, timestampUpdate DATETIME DEFAULT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFinanceFeeCategoryID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdate INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_7D222CFC71FA7520 (gibbonSchoolYearID), INDEX IDX_7D222CFCB05DE109 (gibbonFinanceFeeCategoryID), INDEX IDX_7D222CFCFF59AAB0 (gibbonPersonIDCreator), INDEX IDX_7D222CFCAE8C8C10 (gibbonPersonIDUpdate), PRIMARY KEY(gibbonFinanceFeeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceFeeCategory (gibbonFinanceFeeCategoryID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(100) NOT NULL, nameShort VARCHAR(6) NOT NULL, description LONGTEXT NOT NULL, active VARCHAR(1) NOT NULL, timestampCreator DATETIME DEFAULT NULL, timestampUpdate DATETIME DEFAULT NULL, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdate INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_14C5A939FF59AAB0 (gibbonPersonIDCreator), INDEX IDX_14C5A939AE8C8C10 (gibbonPersonIDUpdate), PRIMARY KEY(gibbonFinanceFeeCategoryID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceInvoice (gibbonFinanceInvoiceID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, invoiceTo VARCHAR(8) DEFAULT \'Family\' NOT NULL, billingScheduleType VARCHAR(12) DEFAULT \'Ad Hoc\' NOT NULL, separated VARCHAR(1) DEFAULT NULL COMMENT \'Has this invoice been separated from its schedule in gibbonFinanceBillingSchedule? Only applies to scheduled invoices. Separation takes place during invoice issueing.\', status VARCHAR(16) DEFAULT \'Pending\' NOT NULL, gibbonFinanceFeeCategoryIDList LONGTEXT DEFAULT NULL, invoiceIssueDate DATE DEFAULT NULL, invoiceDueDate DATE DEFAULT NULL, paidDate DATE DEFAULT NULL, paidAmount NUMERIC(13, 2) DEFAULT NULL COMMENT \'The current running total amount paid to this invoice\', reminderCount INT(3), notes LONGTEXT NOT NULL, `key` VARCHAR(40) NOT NULL, timestampCreator DATETIME DEFAULT NULL, timestampUpdate DATETIME DEFAULT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFinanceInvoiceeID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFinanceBillingScheduleID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPaymentID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdate INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_B921551771FA7520 (gibbonSchoolYearID), INDEX IDX_B9215517739BAD1F (gibbonFinanceInvoiceeID), INDEX IDX_B92155176F4C4787 (gibbonFinanceBillingScheduleID), INDEX IDX_B9215517A0F353A3 (gibbonPaymentID), INDEX IDX_B9215517FF59AAB0 (gibbonPersonIDCreator), INDEX IDX_B9215517AE8C8C10 (gibbonPersonIDUpdate), PRIMARY KEY(gibbonFinanceInvoiceID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceInvoicee (gibbonFinanceInvoiceeID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, invoiceTo VARCHAR(8) NOT NULL, companyName VARCHAR(100) DEFAULT NULL, companyContact VARCHAR(100) DEFAULT NULL, companyAddress VARCHAR(255) DEFAULT NULL, companyEmail LONGTEXT DEFAULT NULL, companyCCFamily VARCHAR(1) DEFAULT NULL COMMENT \'When company is billed, should family receive a copy?\', companyPhone VARCHAR(20) DEFAULT NULL, companyAll VARCHAR(1) DEFAULT NULL COMMENT \'Should company pay all invoices?.\', gibbonFinanceFeeCategoryIDList LONGTEXT DEFAULT NULL COMMENT \'If companyAll is N, list category IDs for campany to pay here.\', gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_6CB0DEC8CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonFinanceInvoiceeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceInvoiceeUpdate (gibbonFinanceInvoiceeUpdateID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, status VARCHAR(8) DEFAULT \'Pending\' NOT NULL, invoiceTo VARCHAR(8) NOT NULL, companyName VARCHAR(100) DEFAULT NULL, companyContact VARCHAR(100) DEFAULT NULL, companyAddress VARCHAR(255) DEFAULT NULL, companyEmail LONGTEXT DEFAULT NULL, companyCCFamily VARCHAR(1) DEFAULT NULL COMMENT \'When company is billed, should family receive a copy?\', companyPhone VARCHAR(20) DEFAULT NULL, companyAll VARCHAR(1) DEFAULT NULL COMMENT \'Should company pay all invoices?.\', gibbonFinanceFeeCategoryIDList LONGTEXT DEFAULT NULL COMMENT \'If companyAll is N, list category IDs for campany to pay here.\', timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFinanceInvoiceeID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdater INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_848117171FA7520 (gibbonSchoolYearID), INDEX IDX_8481171739BAD1F (gibbonFinanceInvoiceeID), INDEX IDX_848117171106375 (gibbonPersonIDUpdater), INDEX gibbonInvoiceeIndex (gibbonFinanceInvoiceeID, gibbonSchoolYearID), PRIMARY KEY(gibbonFinanceInvoiceeUpdateID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFinanceInvoiceFee (gibbonFinanceInvoiceFeeID INT(15) UNSIGNED ZEROFILL AUTO_INCREMENT, feeType VARCHAR(12) DEFAULT \'Ad Hoc\' NOT NULL, separated VARCHAR(1) DEFAULT NULL COMMENT \'Has this fee been separated from its parent in gibbonFinanceFee? Only applies to Standard fees. Separation takes place during invoice issueing.\', name VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, fee NUMERIC(12, 2) DEFAULT NULL, sequenceNumber INT(10), gibbonFinanceInvoiceID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonFinanceFeeID INT(6) UNSIGNED ZEROFILL, gibbonFinanceFeeCategoryID INT(6) UNSIGNED ZEROFILL, INDEX IDX_3CC82E56F51FBF6 (gibbonFinanceInvoiceID), INDEX IDX_3CC82E569B02DC4A (gibbonFinanceFeeID), INDEX IDX_3CC82E56B05DE109 (gibbonFinanceFeeCategoryID), PRIMARY KEY(gibbonFinanceInvoiceFeeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonFirstAid (gibbonFirstAidID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, description LONGTEXT NOT NULL, actionTaken LONGTEXT NOT NULL, followUp LONGTEXT NOT NULL, date DATE NOT NULL, timeIn TIME NOT NULL, timeOut TIME DEFAULT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonPersonIDPatient INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDFirstAider INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_ABF0052759859738 (gibbonPersonIDPatient), INDEX IDX_ABF00527B67991E (gibbonCourseClassID), INDEX IDX_ABF0052722759506 (gibbonPersonIDFirstAider), INDEX IDX_ABF0052771FA7520 (gibbonSchoolYearID), PRIMARY KEY(gibbonFirstAidID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonGroup (gibbonGroupID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, timestampCreated DATETIME DEFAULT NULL, timestampUpdated DATETIME DEFAULT CURRENT_TIMESTAMP, gibbonPersonIDOwner INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_FAE2DDF3659378D6 (gibbonPersonIDOwner), INDEX IDX_FAE2DDF371FA7520 (gibbonSchoolYearID), PRIMARY KEY(gibbonGroupID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonGroupPerson (gibbonGroupPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonGroupID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_15367BAAD62085CF (gibbonGroupID), INDEX IDX_15367BAACC6782D6 (gibbonPersonID), UNIQUE INDEX gibbonGroupID (gibbonGroupID, gibbonPersonID), PRIMARY KEY(gibbonGroupPersonID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonHook (gibbonHookID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, type VARCHAR(20) DEFAULT NULL, options LONGTEXT NOT NULL, gibbonModuleID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_5418FD5ECB86AD4B (gibbonModuleID), UNIQUE INDEX name (name, type), PRIMARY KEY(gibbonHookID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonHouse` (
  `gibbonHouseID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nameShort` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`gibbonHouseID`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  UNIQUE KEY `nameShort` (`nameShort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibboni18n (gibboni18nID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, code VARCHAR(5) NOT NULL, name VARCHAR(100) NOT NULL, version VARCHAR(10) DEFAULT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, installed VARCHAR(1) DEFAULT \'N\' NOT NULL, systemDefault VARCHAR(1) DEFAULT \'N\' NOT NULL, dateFormat VARCHAR(20) NOT NULL, dateFormatRegEx LONGTEXT NOT NULL, dateFormatPHP VARCHAR(20) NOT NULL, rtl VARCHAR(1) DEFAULT \'N\' NOT NULL, PRIMARY KEY(gibboni18nID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonINArchive (gibbonINArchiveID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, strategies LONGTEXT NOT NULL, targets LONGTEXT NOT NULL, notes LONGTEXT NOT NULL, descriptors LONGTEXT NOT NULL COMMENT \'Serialised array of descriptors.\', archiveTitle VARCHAR(50) NOT NULL, archiveTimestamp DATETIME DEFAULT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_43A82C35CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonINArchiveID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonINAssistant (gibbonINAssistantID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, comment LONGTEXT NOT NULL, gibbonPersonIDStudent INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDAssistant INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_9A5EAC8FF47CEFE0 (gibbonPersonIDStudent), INDEX IDX_9A5EAC8F1E50E8C2 (gibbonPersonIDAssistant), PRIMARY KEY(gibbonINAssistantID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonINDescriptor (gibbonINDescriptorID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, nameShort VARCHAR(5) NOT NULL, description LONGTEXT NOT NULL, sequenceNumber INT(3), PRIMARY KEY(gibbonINDescriptorID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonIN (gibbonINID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, strategies LONGTEXT NOT NULL, targets LONGTEXT NOT NULL, notes LONGTEXT NOT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, UNIQUE INDEX gibbonPersonID (gibbonPersonID), PRIMARY KEY(gibbonINID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonINPersonDescriptor (gibbonINPersonDescriptorID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonINDescriptorID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonAlertLevelID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_8D22AA26CC6782D6 (gibbonPersonID), INDEX IDX_8D22AA26789947CD (gibbonINDescriptorID), INDEX IDX_8D22AA26891EFB5B (gibbonAlertLevelID), PRIMARY KEY(gibbonINPersonDescriptorID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonInternalAssessmentColumn (gibbonInternalAssessmentColumnID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, groupingID INT(8) UNSIGNED ZEROFILL, name VARCHAR(20) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, attachment VARCHAR(255) NOT NULL, attainment VARCHAR(1) DEFAULT \'Y\' NOT NULL, effort VARCHAR(1) DEFAULT \'Y\' NOT NULL, comment VARCHAR(1) DEFAULT \'Y\' NOT NULL, uploadedResponse VARCHAR(1) DEFAULT \'N\' NOT NULL, complete VARCHAR(1) NOT NULL, completeDate DATE DEFAULT NULL, viewableStudents VARCHAR(1) NOT NULL, viewableParents VARCHAR(1) NOT NULL, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonScaleIDAttainment INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonScaleIDEffort INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDLastEdit INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_E0A1D88AB67991E (gibbonCourseClassID), INDEX IDX_E0A1D88A2C639785 (gibbonScaleIDAttainment), INDEX IDX_E0A1D88AD395ACF8 (gibbonScaleIDEffort), INDEX IDX_E0A1D88AFF59AAB0 (gibbonPersonIDCreator), INDEX IDX_E0A1D88A519966BA (gibbonPersonIDLastEdit), PRIMARY KEY(gibbonInternalAssessmentColumnID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonInternalAssessmentEntry (gibbonInternalAssessmentEntryID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, attainmentValue VARCHAR(10) DEFAULT NULL, attainmentDescriptor VARCHAR(100) DEFAULT NULL, effortValue VARCHAR(10) DEFAULT NULL, effortDescriptor VARCHAR(100) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, response LONGTEXT DEFAULT NULL, gibbonInternalAssessmentColumnID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDStudent INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDLastEdit INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_B09C6F558B7A9BC (gibbonInternalAssessmentColumnID), INDEX IDX_B09C6F5F47CEFE0 (gibbonPersonIDStudent), INDEX IDX_B09C6F5519966BA (gibbonPersonIDLastEdit), PRIMARY KEY(gibbonInternalAssessmentEntryID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonLanguage (gibbonLanguageID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, PRIMARY KEY(gibbonLanguageID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        
        $this->addSql('CREATE TABLE `gibbonMarkbookColumn` (
  `gibbonMarkbookColumnID` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `groupingID` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `sequenceNumber` int(3) UNSIGNED DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `attainment` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `attainmentWeighting` decimal(5,2) DEFAULT NULL,
  `attainmentRaw` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'N\',
  `attainmentRawMax` decimal(8,2) DEFAULT NULL,
  `effort` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `comment` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `uploadedResponse` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `complete` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `completeDate` date DEFAULT NULL,
  `viewableStudents` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `viewableParents` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `gibbonCourseClassID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `gibbonHookID` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonUnitID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPlannerEntryID` int(14) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonSchoolYearTermID` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonScaleIDAttainment` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonScaleIDEffort` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonRubricIDAttainment` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonRubricIDEffort` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDCreator` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDLastEdit` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonMarkbookColumnID`),
  UNIQUE KEY `nameCourseClass` (`name`,`gibbonCourseClassID`),
  KEY `IDX_AA57806EF6E7C959` (`gibbonHookID`),
  KEY `IDX_AA57806E46DE4A3D` (`gibbonUnitID`),
  KEY `IDX_AA57806EFE417281` (`gibbonPlannerEntryID`),
  KEY `IDX_AA57806E88C7C454` (`gibbonSchoolYearTermID`),
  KEY `IDX_AA57806E2C639785` (`gibbonScaleIDAttainment`),
  KEY `IDX_AA57806ED395ACF8` (`gibbonScaleIDEffort`),
  KEY `IDX_AA57806E2151BB77` (`gibbonRubricIDAttainment`),
  KEY `IDX_AA57806EBA294907` (`gibbonRubricIDEffort`),
  KEY `IDX_AA57806EFF59AAB0` (`gibbonPersonIDCreator`),
  KEY `IDX_AA57806E519966BA` (`gibbonPersonIDLastEdit`),
  KEY `gibbonCourseClassID` (`gibbonCourseClassID`),
  KEY `completeDate` (`completeDate`),
  KEY `complete` (`complete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE IF NOT EXISTS `gibbonMarkbookEntry` (
  `gibbonMarkbookEntryID` int(12) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `modifiedAssessment` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attainmentValue` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attainmentValueRaw` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attainmentDescriptor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attainmentConcern` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT \'`P` denotes that student has exceed their personal target\',
  `effortValue` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `effortDescriptor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `effortConcern` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` longtext COLLATE utf8_unicode_ci,
  `response` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gibbonMarkbookColumnID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDStudent` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDLastEdit` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonMarkbookEntryID`),
  UNIQUE KEY `columnStudent` (`gibbonMarkbookColumnID`,`gibbonPersonIDStudent`),
  KEY `IDX_22F46391519966BA` (`gibbonPersonIDLastEdit`),
  KEY `gibbonPersonIDStudent` (`gibbonPersonIDStudent`),
  KEY `gibbonMarkbookColumnID` (`gibbonMarkbookColumnID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonMarkbookTarget (gibbonMarkbookTargetID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDStudent INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonScaleGradeID INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_916B28ECB67991E (gibbonCourseClassID), INDEX IDX_916B28ECF47CEFE0 (gibbonPersonIDStudent), INDEX IDX_916B28EC5E440573 (gibbonScaleGradeID), UNIQUE INDEX coursePerson (gibbonCourseClassID, gibbonPersonIDStudent), PRIMARY KEY(gibbonMarkbookTargetID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonMarkbookWeight (gibbonMarkbookWeightID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, type VARCHAR(50) NOT NULL, description VARCHAR(50) NOT NULL, reportable VARCHAR(1) DEFAULT \'Y\' NOT NULL, calculate VARCHAR(4) DEFAULT \'year\' NOT NULL, weighting NUMERIC(5, 2) NOT NULL, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_D0C95251B67991E (gibbonCourseClassID), PRIMARY KEY(gibbonMarkbookWeightID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonMedicalCondition (gibbonMedicalConditionID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(80) NOT NULL, UNIQUE INDEX name (name), PRIMARY KEY(gibbonMedicalConditionID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonMessenger (gibbonMessengerID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, email VARCHAR(1) DEFAULT \'N\' NOT NULL, messageWall VARCHAR(1) DEFAULT \'N\' NOT NULL, messageWall_date1 DATE DEFAULT NULL, messageWall_date2 DATE DEFAULT NULL, messageWall_date3 DATE DEFAULT NULL, sms VARCHAR(1) DEFAULT \'N\' NOT NULL, subject VARCHAR(60) NOT NULL, body LONGTEXT NOT NULL, timestamp DATETIME DEFAULT NULL, emailReport LONGTEXT NOT NULL, emailReceipt VARCHAR(1) DEFAULT NULL, emailReceiptText LONGTEXT DEFAULT NULL, smsReport LONGTEXT NOT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_C7127C4CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonMessengerID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonMessengerCannedResponse (gibbonMessengerCannedResponseID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, subject VARCHAR(30) NOT NULL, body LONGTEXT NOT NULL, timestampCreator DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_C83786BFFF59AAB0 (gibbonPersonIDCreator), PRIMARY KEY(gibbonMessengerCannedResponseID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonMessengerReceipt (gibbonMessengerReceiptID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, targetType VARCHAR(16) NOT NULL, targetID VARCHAR(30) NOT NULL, contactType VARCHAR(5) DEFAULT NULL, contactDetail VARCHAR(255) DEFAULT NULL, `key` VARCHAR(40) DEFAULT NULL, confirmed VARCHAR(1) DEFAULT NULL, confirmedTimestamp DATETIME DEFAULT NULL, gibbonMessengerID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_30BB77081B4FC86A (gibbonMessengerID), INDEX IDX_30BB7708CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonMessengerReceiptID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonMessengerTarget (gibbonMessengerTargetID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, type VARCHAR(16) DEFAULT NULL, id VARCHAR(30) NOT NULL, parents VARCHAR(1) DEFAULT \'N\' NOT NULL, students VARCHAR(1) DEFAULT \'N\' NOT NULL, staff VARCHAR(1) DEFAULT \'N\' NOT NULL, gibbonMessengerID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_62C2BBE11B4FC86A (gibbonMessengerID), PRIMARY KEY(gibbonMessengerTargetID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonModule (gibbonModuleID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL COMMENT \'This name should be globally unique preferably, but certainly locally unique\', description LONGTEXT NOT NULL, entryURL VARCHAR(255) DEFAULT \'index.php\' NOT NULL, type VARCHAR(12) DEFAULT \'Core\' NOT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, category VARCHAR(10) NOT NULL, version VARCHAR(6) NOT NULL, author VARCHAR(40) NOT NULL, url VARCHAR(255) NOT NULL, UNIQUE INDEX gibbonModuleName (name), PRIMARY KEY(gibbonModuleID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonNotification (gibbonNotificationID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, status VARCHAR(8) DEFAULT \'New\' NOT NULL, count INT(4), text LONGTEXT NOT NULL,actionLink VARCHAR(255) NOT NULL COMMENT \'Relative to absoluteURL, start with a forward slash\', timestamp DATETIME NOT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonModuleID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_D5180450CC6782D6 (gibbonPersonID), INDEX IDX_D5180450CB86AD4B (gibbonModuleID), PRIMARY KEY(gibbonNotificationID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibboNnotificationEvent` (
  `gibbonNotificationEventID` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `event` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `moduleName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `actionName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(12) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Core\',
  `scopes` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'All\',
  `active` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `moduleID` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `actionID` int(7) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonNotificationEventID`),
  UNIQUE KEY `event` (`event`,`moduleName`),
  KEY `FK_A364BEAD9E834449` (`moduleID`),
  KEY `FK_A364BEADB6AA0365` (`actionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonNotificationListener (gibbonNotificationListenerID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, scopeType VARCHAR(30) DEFAULT NULL, scopeID INT(20) UNSIGNED, gibbonNotificationEventID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_6313F17E26A39C71 (gibbonNotificationEventID), INDEX IDX_6313F17ECC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonNotificationListenerID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonOutcome` (
  `gibbonOutcomeID` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nameShort` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `active` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `scope` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `gibbonYearGroupIDList` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT \'(DC2Type:simple_array)\',
  `gibbonDepartmentID` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDCreator` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonOutcomeID`),
  UNIQUE KEY `nameDepartment` (`name`,`gibbonDepartmentID`),
  UNIQUE KEY `nameShortDepartment` (`nameShort`,`gibbonDepartmentID`),
  KEY `IDX_307340756DFE7E92` (`gibbonDepartmentID`),
  KEY `IDX_30734075FF59AAB0` (`gibbonPersonIDCreator`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPayment (gibbonPaymentID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, foreignTable VARCHAR(50) NOT NULL, foreignTableID INT(14) UNSIGNED ZEROFILL, type VARCHAR(16) DEFAULT \'Online\' NOT NULL, status VARCHAR(8) DEFAULT \'Complete\' NOT NULL COMMENT \'Complete means paid in one go, partial is part of a set of payments, and final is last in a set of payments.\', amount NUMERIC(13, 2) NOT NULL, gateway VARCHAR(6) DEFAULT NULL, onlineTransactionStatus VARCHAR(12) DEFAULT NULL, paymentToken VARCHAR(50) DEFAULT NULL, paymentPayerID VARCHAR(50) DEFAULT NULL, paymentTransactionID VARCHAR(50) DEFAULT NULL, paymentReceiptID VARCHAR(50) DEFAULT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_6DE7A9BACC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonPaymentID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPermission (permissionID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonRoleID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonActionID INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonRoleID (gibbonRoleID), INDEX gibbonActionID (gibbonActionID), PRIMARY KEY(permissionID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPerson (gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, title VARCHAR(5) NOT NULL, surname VARCHAR(60) NOT NULL, firstName VARCHAR(60) NOT NULL, preferredName VARCHAR(60) NOT NULL, officialName VARCHAR(150) NOT NULL, nameInCharacters VARCHAR(60) NOT NULL, gender VARCHAR(16) DEFAULT \'Unspecified\' NOT NULL, username VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, passwordStrong VARCHAR(255) NOT NULL, passwordStrongSalt VARCHAR(255) NOT NULL, passwordForceReset VARCHAR(1) DEFAULT \'N\' NOT NULL COMMENT \'Force user to reset password on next login.\', status VARCHAR(16) DEFAULT \'Full\' NOT NULL, canLogin VARCHAR(1) DEFAULT \'Y\' NOT NULL, gibbonRoleIDAll VARCHAR(255) NOT NULL, dob DATE DEFAULT NULL, email VARCHAR(75) DEFAULT NULL, emailAlternate VARCHAR(75) DEFAULT NULL, image_240 VARCHAR(255) DEFAULT NULL, lastIPAddress VARCHAR(15) NOT NULL, lastTimestamp DATETIME DEFAULT NULL, lastFailIPAddress VARCHAR(15) DEFAULT NULL, lastFailTimestamp DATETIME DEFAULT NULL, failCount INT(1), address1 LONGTEXT NOT NULL, address1District VARCHAR(255) NOT NULL, address1Country VARCHAR(255) NOT NULL, address2 LONGTEXT NOT NULL, address2district VARCHAR(255) NOT NULL, address2country VARCHAR(255) NOT NULL, phone1type VARCHAR(6) NOT NULL, phone1CountryCode VARCHAR(7) NOT NULL, phone1 VARCHAR(20) NOT NULL, phone2type VARCHAR(6) NOT NULL, phone2CountryCode VARCHAR(7) NOT NULL, phone2 VARCHAR(20) NOT NULL, phone3type VARCHAR(6) NOT NULL, phone3CountryCode VARCHAR(7) NOT NULL, phone3 VARCHAR(20) NOT NULL, phone4type VARCHAR(6) NOT NULL, phone4CountryCode VARCHAR(7) NOT NULL, phone4 VARCHAR(20) NOT NULL, website VARCHAR(255) NOT NULL, languageFirst VARCHAR(30) NOT NULL, languageSecond VARCHAR(30) NOT NULL, languageThird VARCHAR(30) NOT NULL, countryOfBirth VARCHAR(30) NOT NULL, birthCertificateScan VARCHAR(255) NOT NULL, ethnicity VARCHAR(255) NOT NULL, citizenship1 VARCHAR(255) NOT NULL, citizenship1passport VARCHAR(30) NOT NULL, citizenship1PassportScan VARCHAR(255) NOT NULL, citizenship2 VARCHAR(255) NOT NULL, citizenship2passport VARCHAR(30) NOT NULL, religion VARCHAR(30) NOT NULL, nationalIDCardNumber VARCHAR(30) NOT NULL, nationalIDCardScan VARCHAR(255) NOT NULL, residencyStatus VARCHAR(255) NOT NULL, visaExpiryDate DATE DEFAULT NULL, profession VARCHAR(90) NOT NULL, employer VARCHAR(90) NOT NULL, jobTitle VARCHAR(90) NOT NULL, emergency1name VARCHAR(90) NOT NULL, emergency1number1 VARCHAR(30) NOT NULL, emergency1number2 VARCHAR(30) NOT NULL, emergency1relationship VARCHAR(30) NOT NULL, emergency2name VARCHAR(90) NOT NULL, emergency2number1 VARCHAR(30) NOT NULL, emergency2number2 VARCHAR(30) NOT NULL, emergency2relationship VARCHAR(30) NOT NULL, studentID VARCHAR(10) NOT NULL, dateStart DATE DEFAULT NULL, dateEnd DATE DEFAULT NULL, lastSchool VARCHAR(100) NOT NULL, nextSchool VARCHAR(100) NOT NULL, departureReason VARCHAR(50) NOT NULL, transport VARCHAR(255) NOT NULL, transportNotes LONGTEXT NOT NULL, `calendarFeedPersonal` VARCHAR(192) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, viewCalendarSchool VARCHAR(1) DEFAULT \'Y\' NOT NULL, viewCalendarPersonal VARCHAR(1) DEFAULT \'Y\' NOT NULL, viewCalendarSpaceBooking VARCHAR(1) DEFAULT \'N\' NOT NULL, lockerNumber VARCHAR(20) NOT NULL, vehicleRegistration VARCHAR(20) NOT NULL, personalBackground VARCHAR(255) NOT NULL, messengerLastBubble DATE DEFAULT NULL, privacy LONGTEXT DEFAULT NULL, dayType VARCHAR(255) DEFAULT NULL COMMENT \'Student day type, as specified in the application form.\', studentAgreements LONGTEXT DEFAULT NULL, googleAPIRefreshToken VARCHAR(255) NOT NULL, receiveNotificationEmails VARCHAR(1) DEFAULT \'Y\' NOT NULL, fields LONGTEXT DEFAULT NULL COMMENT \'Serialised array of custom field values(DC2Type:array)\', gibbonRoleIDPrimary INT(3) UNSIGNED ZEROFILL, gibbonHouseID INT(3) UNSIGNED ZEROFILL, gibbonSchoolYearIDClassOf INT(3) UNSIGNED ZEROFILL, gibbonApplicationFormID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonThemeIDPersonal INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, gibboni18nIDPersonal INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_FBF1667668D8F4F8 (gibbonRoleIDPrimary), INDEX IDX_FBF166768AF65507 (gibbonHouseID), INDEX IDX_FBF166768AB34571 (gibbonSchoolYearIDClassOf), INDEX IDX_FBF16676772C4226 (gibbonApplicationFormID), INDEX IDX_FBF166767E3D96BF (gibbonThemeIDPersonal), INDEX IDX_FBF166764D960E0E (gibboni18nIDPersonal), INDEX username_2 (username, email), UNIQUE INDEX username (username), PRIMARY KEY(gibbonPersonID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPersonField (gibbonPersonFieldID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(10) NOT NULL, options LONGTEXT NOT NULL COMMENT \'Field length for varchar, rows for text, comma-separate list for select/checkbox.\', required VARCHAR(1) DEFAULT \'N\' NOT NULL, activePersonStudent TINYINT(1) DEFAULT \'0\' NOT NULL, activePersonStaff TINYINT(1) DEFAULT \'0\' NOT NULL, activePersonParent TINYINT(1) DEFAULT \'0\' NOT NULL, activePersonOther TINYINT(1) DEFAULT \'0\' NOT NULL, activeApplicationForm TINYINT(1) DEFAULT \'0\' NOT NULL, activeDataUpdater TINYINT(1) DEFAULT \'0\' NOT NULL, activePublicRegistration TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(gibbonPersonFieldID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPersonMedical (gibbonPersonMedicalID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, bloodType VARCHAR(3) NOT NULL, longTermMedication VARCHAR(1) NOT NULL, longTermMedicationDetails LONGTEXT NOT NULL, tetanusWithin10Years VARCHAR(1) NOT NULL, comment LONGTEXT NOT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonPersonID (gibbonPersonID), PRIMARY KEY(gibbonPersonMedicalID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPersonMedicalCondition (gibbonPersonMedicalConditionID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(100) NOT NULL, triggers VARCHAR(255) NOT NULL, reaction VARCHAR(255) NOT NULL, response VARCHAR(255) NOT NULL, medication VARCHAR(255) NOT NULL, lastEpisode DATE DEFAULT NULL, lastEpisodeTreatment VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, gibbonPersonMedicalID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonAlertLevelID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_9F35C9A7891EFB5B (gibbonAlertLevelID), INDEX gibbonPersonMedicalID (gibbonPersonMedicalID), PRIMARY KEY(gibbonPersonMedicalConditionID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPersonMedicalConditionUpdate (gibbonPersonMedicalConditionUpdateID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(80) NOT NULL, triggers VARCHAR(255) NOT NULL, reaction VARCHAR(255) NOT NULL, response VARCHAR(255) NOT NULL, medication VARCHAR(255) NOT NULL, lastEpisode DATE DEFAULT NULL, lastEpisodeTreatment VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonPersonMedicalUpdateID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonMedicalConditionID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonMedicalID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonAlertLevelID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdater INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_4E2F6CEC41D19174 (gibbonPersonMedicalUpdateID), INDEX IDX_4E2F6CEC122DAC35 (gibbonPersonMedicalConditionID), INDEX IDX_4E2F6CEC65737DEB (gibbonPersonMedicalID), INDEX IDX_4E2F6CEC891EFB5B (gibbonAlertLevelID), INDEX IDX_4E2F6CEC71106375 (gibbonPersonIDUpdater), PRIMARY KEY(gibbonPersonMedicalConditionUpdateID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPersonMedicalSymptoms (gibbonPersonMedicalSymptomsID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, symptoms LONGTEXT NOT NULL, date DATE NOT NULL, timestampTaken DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDTaker INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_2C6BF3A5CC6782D6 (gibbonPersonID), INDEX IDX_2C6BF3A511A14ED (gibbonPersonIDTaker), PRIMARY KEY(gibbonPersonMedicalSymptomsID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPersonMedicalUpdate (gibbonPersonMedicalUpdateID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, status VARCHAR(8) DEFAULT \'Pending\' NOT NULL, bloodType VARCHAR(3) NOT NULL, longTermMedication VARCHAR(1) NOT NULL, longTermMedicationDetails LONGTEXT NOT NULL, tetanusWithin10Years VARCHAR(1) NOT NULL, comment LONGTEXT NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonMedicalID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdater INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_EEB6690471FA7520 (gibbonSchoolYearID), INDEX IDX_EEB6690465737DEB (gibbonPersonMedicalID), INDEX IDX_EEB66904CC6782D6 (gibbonPersonID), INDEX IDX_EEB6690471106375 (gibbonPersonIDUpdater), INDEX gibbonMedicalIndex (gibbonPersonID, gibbonPersonMedicalID, gibbonSchoolYearID), PRIMARY KEY(gibbonPersonMedicalUpdateID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPersonReset (gibbonPersonResetID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, `key` VARCHAR(40) NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_BACD0C68CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonPersonResetID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPersonUpdate (gibbonPersonUpdateID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, status VARCHAR(8) DEFAULT \'Pending\' NOT NULL, title VARCHAR(5) NOT NULL, surname VARCHAR(60) NOT NULL, firstName VARCHAR(60) NOT NULL, preferredName VARCHAR(60) NOT NULL, officialName VARCHAR(150) NOT NULL, nameInCharacters VARCHAR(60) NOT NULL, dob DATE DEFAULT NULL, email VARCHAR(75) DEFAULT NULL, emailAlternate VARCHAR(75) DEFAULT NULL, address1 LONGTEXT NOT NULL, address1District VARCHAR(255) NOT NULL, address1Country VARCHAR(255) NOT NULL, address2 LONGTEXT NOT NULL, address2district VARCHAR(255) NOT NULL, address2country VARCHAR(255) NOT NULL, phone1type VARCHAR(6) NOT NULL, phone1CountryCode VARCHAR(7) NOT NULL, phone1 VARCHAR(20) NOT NULL, phone2type VARCHAR(6) NOT NULL, phone2CountryCode VARCHAR(7) NOT NULL, phone2 VARCHAR(20) NOT NULL, phone3type VARCHAR(6) NOT NULL, phone3CountryCode VARCHAR(7) NOT NULL, phone3 VARCHAR(20) NOT NULL, phone4type VARCHAR(6) NOT NULL, phone4CountryCode VARCHAR(7) NOT NULL, phone4 VARCHAR(20) NOT NULL, languageFirst VARCHAR(30) NOT NULL, languageSecond VARCHAR(30) NOT NULL, languageThird VARCHAR(30) NOT NULL, countryOfBirth VARCHAR(30) NOT NULL, ethnicity VARCHAR(255) NOT NULL, citizenship1 VARCHAR(255) NOT NULL, citizenship1passport VARCHAR(30) NOT NULL, citizenship2 VARCHAR(255) NOT NULL, citizenship2passport VARCHAR(30) NOT NULL, religion VARCHAR(30) NOT NULL, nationalIDCardCountry VARCHAR(30) NOT NULL, nationalIDCardNumber VARCHAR(30) NOT NULL, residencyStatus VARCHAR(255) NOT NULL, visaExpiryDate DATE DEFAULT NULL, profession VARCHAR(90) DEFAULT NULL, employer VARCHAR(90) DEFAULT NULL, jobTitle VARCHAR(90) DEFAULT NULL, emergency1name VARCHAR(90) DEFAULT NULL, emergency1number1 VARCHAR(30) DEFAULT NULL, emergency1number2 VARCHAR(30) DEFAULT NULL, emergency1relationship VARCHAR(30) DEFAULT NULL, emergency2name VARCHAR(90) DEFAULT NULL, emergency2number1 VARCHAR(30) DEFAULT NULL, emergency2number2 VARCHAR(30) DEFAULT NULL, emergency2relationship VARCHAR(30) DEFAULT NULL, vehicleRegistration VARCHAR(20) NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, privacy LONGTEXT DEFAULT NULL, fields LONGTEXT NOT NULL COMMENT \'Serialised array of custom field values\', gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUpdater INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_D3CBB18C71FA7520 (gibbonSchoolYearID), INDEX IDX_D3CBB18CCC6782D6 (gibbonPersonID), INDEX IDX_D3CBB18C71106375 (gibbonPersonIDUpdater), INDEX gibbonPersonIndex (gibbonPersonID, gibbonSchoolYearID), PRIMARY KEY(gibbonPersonUpdateID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPlannerEntry (gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE DEFAULT NULL, timeStart TIME DEFAULT NULL, timeEnd TIME DEFAULT NULL, name VARCHAR(50) NOT NULL, summary VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, teachersNotes LONGTEXT NOT NULL, homework VARCHAR(1) DEFAULT \'N\' NOT NULL, homeworkDueDateTime DATETIME DEFAULT NULL, homeworkDetails LONGTEXT NOT NULL, homeworkSubmission VARCHAR(1) NOT NULL, homeworkSubmissionDateOpen DATE DEFAULT NULL, homeworkSubmissionDrafts VARCHAR(1) DEFAULT NULL, homeworkSubmissionType VARCHAR(10) NOT NULL, homeworkSubmissionRequired VARCHAR(10) DEFAULT \'Optional\', homeworkCrowdAssess VARCHAR(1) NOT NULL, homeworkCrowdAssessOtherTeachersRead VARCHAR(1) NOT NULL, homeworkCrowdAssessOtherParentsRead VARCHAR(1) NOT NULL, homeworkCrowdAssessClassmatesParentsRead VARCHAR(1) NOT NULL, homeworkCrowdAssessSubmitterParentsRead VARCHAR(1) NOT NULL, homeworkCrowdAssessOtherStudentsRead VARCHAR(1) NOT NULL, homeworkCrowdAssessClassmatesRead VARCHAR(1) NOT NULL, viewableStudents VARCHAR(1) DEFAULT \'Y\' NOT NULL, viewableParents VARCHAR(1) DEFAULT \'N\' NOT NULL, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonUnitID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDLastEdit INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_B35E3CEE46DE4A3D (gibbonUnitID), INDEX IDX_B35E3CEEFF59AAB0 (gibbonPersonIDCreator), INDEX IDX_B35E3CEE519966BA (gibbonPersonIDLastEdit), INDEX gibbonCourseClassID (gibbonCourseClassID), PRIMARY KEY(gibbonPlannerEntryID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPlannerEntryDiscuss (gibbonPlannerEntryDiscussID INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, comment LONGTEXT NOT NULL, gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPlannerEntryDiscussIDReplyTo INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_A2D5383EFE417281 (gibbonPlannerEntryID), INDEX IDX_A2D5383ECC6782D6 (gibbonPersonID), INDEX IDX_A2D5383E18B0DB2F (gibbonPlannerEntryDiscussIDReplyTo), PRIMARY KEY(gibbonPlannerEntryDiscussID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPlannerEntryGuest (gibbonPlannerEntryGuestID INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, role VARCHAR(16) NOT NULL, gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_E9A57557FE417281 (gibbonPlannerEntryID), INDEX IDX_E9A57557CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonPlannerEntryGuestID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPlannerEntryHomework (gibbonPlannerEntryHomeworkID INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, type VARCHAR(4) NOT NULL, version VARCHAR(5) NOT NULL, status VARCHAR(9) NOT NULL, location VARCHAR(255) DEFAULT NULL, count INT(1), timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_ED6C8A2EFE417281 (gibbonPlannerEntryID), INDEX IDX_ED6C8A2ECC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonPlannerEntryHomeworkID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPlannerEntryOutcome (gibbonPlannerEntryOutcomeID INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, sequenceNumber INT(4), content LONGTEXT NOT NULL, gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonOutcomeID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_57C2DE1CFE417281 (gibbonPlannerEntryID), INDEX IDX_57C2DE1C35479F6A (gibbonOutcomeID), PRIMARY KEY(gibbonPlannerEntryOutcomeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPlannerEntryStudentHomework (gibbonPlannerEntryStudentHomeworkID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, homeworkDueDateTime DATETIME NOT NULL, homeworkDetails LONGTEXT NOT NULL, homeworkComplete VARCHAR(1) DEFAULT \'N\' NOT NULL, gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_F458CB62FE417281 (gibbonPlannerEntryID), INDEX IDX_F458CB62CC6782D6 (gibbonPersonID), INDEX gibbonPlannerEntryID (gibbonPlannerEntryID, gibbonPersonID), PRIMARY KEY(gibbonPlannerEntryStudentHomeworkID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPlannerEntryStudentTracker (gibbonPlannerEntryStudentTrackerID INT(16) UNSIGNED ZEROFILL AUTO_INCREMENT, homeworkComplete DATETIME NOT NULL, gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_936AA4E7FE417281 (gibbonPlannerEntryID), INDEX IDX_936AA4E7CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonPlannerEntryStudentTrackerID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonPlannerParentWeeklyEmailSummary (gibbonPlannerParentWeeklyEmailSummaryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, weekOfYear INT(2), `key` VARCHAR(40) NOT NULL, confirmed VARCHAR(1) DEFAULT \'N\' NOT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDParent INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDStudent INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_2E7C18B771FA7520 (gibbonSchoolYearID), INDEX IDX_2E7C18B7B27D927 (gibbonPersonIDParent), INDEX IDX_2E7C18B7F47CEFE0 (gibbonPersonIDStudent), UNIQUE INDEX `key` (`key`), PRIMARY KEY(gibbonPlannerParentWeeklyEmailSummaryID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonResource (gibbonResourceID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(60) NOT NULL, description LONGTEXT NOT NULL, gibbonYearGroupIDList VARCHAR(255) NOT NULL, type VARCHAR(4) NOT NULL, category VARCHAR(255) NOT NULL, purpose VARCHAR(255) NOT NULL, tags LONGTEXT NOT NULL, content LONGTEXT NOT NULL, timestamp DATETIME DEFAULT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_E9941D14CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonResourceID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonResourceTag (gibbonResourceTagID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, tag VARCHAR(100) NOT NULL, count INT(6), INDEX tag_2 (tag), UNIQUE INDEX tag (tag), PRIMARY KEY(gibbonResourceTagID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonRole (gibbonRoleID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, category VARCHAR(8) DEFAULT \'Staff\' NOT NULL, name VARCHAR(20) NOT NULL, nameShort VARCHAR(4) NOT NULL, description VARCHAR(60) NOT NULL, type VARCHAR(4) DEFAULT \'Core\' NOT NULL, canLoginRole VARCHAR(1) DEFAULT \'Y\' NOT NULL, futureYearsLogin VARCHAR(1) DEFAULT \'Y\' NOT NULL, pastYearsLogin VARCHAR(1) DEFAULT \'Y\' NOT NULL, restriction VARCHAR(10) DEFAULT \'None\' NOT NULL, UNIQUE INDEX name (name), UNIQUE INDEX nameShort (nameShort), PRIMARY KEY(gibbonRoleID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE IF NOT EXISTS `gibbonRollGroup` (
  `gibbonRollGroupID` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `nameShort` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `attendance` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gibbonSchoolYearID` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDTutor` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDTutor2` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDTutor3` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDEA` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDEA2` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonPersonIDEA3` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonSpaceID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonRollGroupIDNext` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonRollGroupID`),
  UNIQUE KEY `nameSchoolYear` (`name`,`gibbonSchoolYearID`),
  UNIQUE KEY `nameShortSchoolYear` (`nameShort`,`gibbonSchoolYearID`),
  KEY `IDX_86CF5C4371FA7520` (`gibbonSchoolYearID`),
  KEY `IDX_86CF5C4333F4D8E2` (`gibbonPersonIDTutor`),
  KEY `IDX_86CF5C4354E2C981` (`gibbonPersonIDTutor2`),
  KEY `IDX_86CF5C4323E5F917` (`gibbonPersonIDTutor3`),
  KEY `IDX_86CF5C4319D8944B` (`gibbonPersonIDEA`),
  KEY `IDX_86CF5C43FBC2FE81` (`gibbonPersonIDEA2`),
  KEY `IDX_86CF5C438CC5CE17` (`gibbonPersonIDEA3`),
  KEY `IDX_86CF5C43D8D64BA0` (`gibbonSpaceID`),
  KEY `IDX_86CF5C43E06A7FA` (`gibbonRollGroupIDNext`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonRubric (gibbonRubricID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, category VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, active VARCHAR(1) NOT NULL, scope VARCHAR(10) NOT NULL, gibbonYearGroupIDList VARCHAR(255) NOT NULL, gibbonDepartmentID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonScaleID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_AFE9B66C6DFE7E92 (gibbonDepartmentID), INDEX IDX_AFE9B66C5F72BC3 (gibbonScaleID), INDEX IDX_AFE9B66CFF59AAB0 (gibbonPersonIDCreator), PRIMARY KEY(gibbonRubricID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonRubricCell (gibbonRubricCellID INT(11) UNSIGNED ZEROFILL AUTO_INCREMENT, contents LONGTEXT NOT NULL, gibbonRubricID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonRubricColumnID INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonRubricRowID INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonRubricID (gibbonRubricID), INDEX gibbonRubricColumnID (gibbonRubricColumnID), INDEX gibbonRubricRowID (gibbonRubricRowID), PRIMARY KEY(gibbonRubricCellID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonRubricColumn (gibbonRubricColumnID INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT, title VARCHAR(20) NOT NULL, sequenceNumber INT(2), visualise VARCHAR(1) DEFAULT \'Y\' NOT NULL, gibbonRubricID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonScaleGradeID INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_E31DA4325E440573 (gibbonScaleGradeID), INDEX gibbonRubricID (gibbonRubricID), PRIMARY KEY(gibbonRubricColumnID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonRubricEntry (gibbonRubricEntry INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, contextDBTable VARCHAR(255) NOT NULL COMMENT \'Which database table is this entry related to?\', contextDBTableID INT(20) UNSIGNED ZEROFILL, gibbonRubricID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonRubricCellID INT(11) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonRubricID (gibbonRubricID), INDEX gibbonPersonID (gibbonPersonID), INDEX gibbonRubricCellID (gibbonRubricCellID), INDEX contextDBTable (contextDBTable), INDEX contextDBTableID (contextDBTableID), PRIMARY KEY(gibbonRubricEntry)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonRubricRow (gibbonRubricRowID INT(9) UNSIGNED ZEROFILL AUTO_INCREMENT, title VARCHAR(40) NOT NULL, sequenceNumber INT(2), gibbonRubricID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonOutcomeID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_96F9D13235479F6A (gibbonOutcomeID), INDEX gibbonRubricID (gibbonRubricID), PRIMARY KEY(gibbonRubricRowID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonScale (gibbonScaleID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(40) NOT NULL, nameShort VARCHAR(5) NOT NULL, `usage` VARCHAR(50) NOT NULL, lowestAcceptable VARCHAR(5) DEFAULT NULL COMMENT \'This is the sequence number of the lowest grade a student can get without being unsatisfactory\', active VARCHAR(1) DEFAULT \'Y\' NOT NULL, `numeric` VARCHAR(1) DEFAULT \'N\' NOT NULL, PRIMARY KEY(gibbonScaleID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonScaleGrade` (
  `gibbonScaleGradeID` int(7) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `value` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `descriptor` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sequenceNumber` int(5) DEFAULT NULL,
  `isDefault` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'N\',
  `gibbonScaleID` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonScaleGradeID`),
  UNIQUE KEY `scaleValue` (`gibbonScaleID`,`value`) USING BTREE,
  UNIQUE KEY `scaleSequence` (`gibbonScaleID`,`sequenceNumber`),
  KEY `IDX_262DE8A75F72BC3` (`gibbonScaleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonSchoolYear` (
  `gibbonSchoolYearID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `name` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Upcoming\',
  `firstDay` date DEFAULT NULL,
  `lastDay` date DEFAULT NULL,
  `sequenceNumber` int(3) DEFAULT NULL,
  PRIMARY KEY (`gibbonSchoolYearID`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  UNIQUE KEY `sequence` (`sequenceNumber`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonSchoolYearSpecialDay (gibbonSchoolYearSpecialDayID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, type VARCHAR(14) NOT NULL, name VARCHAR(20) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, schoolOpen TIME DEFAULT NULL, schoolStart TIME DEFAULT NULL, schoolEnd TIME DEFAULT NULL, schoolClose TIME DEFAULT NULL, gibbonSchoolYearTermID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_EB4E375D88C7C454 (gibbonSchoolYearTermID), UNIQUE INDEX date (date), PRIMARY KEY(gibbonSchoolYearSpecialDayID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonSchoolYearTerm (gibbonSchoolYearTermID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, sequenceNumber INT(5), name VARCHAR(20) NOT NULL, nameShort VARCHAR(4) NOT NULL, firstDay DATE NOT NULL, lastDay DATE NOT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_41C4671071FA7520 (gibbonSchoolYearID), UNIQUE INDEX sequenceNumber (sequenceNumber, gibbonSchoolYearID), PRIMARY KEY(gibbonSchoolYearTermID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonSetting (gibbonSettingID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, scope VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, nameDisplay VARCHAR(60) NOT NULL, description VARCHAR(255) NOT NULL, value LONGTEXT NULL, UNIQUE INDEX scope (scope, nameDisplay), UNIQUE INDEX scope_2 (scope, name), PRIMARY KEY(gibbonSettingID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonSpace (gibbonSpaceID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, type VARCHAR(50) NOT NULL, capacity INT(5), computer VARCHAR(1) NOT NULL, computerStudent INT(3), projector VARCHAR(1) NOT NULL, tv VARCHAR(1) NOT NULL, dvd VARCHAR(1) NOT NULL, hifi VARCHAR(1) NOT NULL, speakers VARCHAR(1) NOT NULL, iwb VARCHAR(1) NOT NULL, phoneInternal VARCHAR(5) NOT NULL, phoneExternal VARCHAR(20) NOT NULL, comment LONGTEXT NOT NULL, UNIQUE INDEX name (name), PRIMARY KEY(gibbonSpaceID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonSpacePerson (gibbonSpacePersonID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, usageType VARCHAR(8) DEFAULT NULL, gibbonSpaceID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_481C4C89D8D64BA0 (gibbonSpaceID), INDEX IDX_481C4C89CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonSpacePersonID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonStaff` (
  `gibbonStaffID` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `initials` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jobTitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `smartWorkflowHelp` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'Y\',
  `firstAidQualified` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'N\',
  `firstAidExpiry` date DEFAULT NULL,
  `countryOfOrigin` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `qualifications` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `biography` longtext COLLATE utf8_unicode_ci NOT NULL,
  `biographicalGrouping` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT \'Used for group staff when creating a staff directory.\',
  `biographicalGroupingPriority` int(3) DEFAULT NULL,
  `gibbonPersonID` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonStaffID`),
  UNIQUE KEY `staff` (`gibbonPersonID`) USING BTREE,
  UNIQUE KEY `initials` (`initials`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffAbsence (gibbonStaffAbsenceID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, reason VARCHAR(60) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, commentConfidential LONGTEXT DEFAULT NULL, status VARCHAR(16) DEFAULT \'Approved\', coverageRequired VARCHAR(1) DEFAULT \'N\' NOT NULL, timestampApproval DATETIME DEFAULT NULL, notesApproval LONGTEXT DEFAULT NULL, timestampCreator DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, notificationSent VARCHAR(1) DEFAULT \'N\' NOT NULL, notificationList LONGTEXT DEFAULT NULL, gibbonCalendarEventID LONGTEXT DEFAULT NULL, gibbonStaffAbsenceTypeID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDApproval INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonGroupID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, UNIQUE INDEX UNIQ_2FE5FEF78A15C624 (gibbonStaffAbsenceTypeID), UNIQUE INDEX UNIQ_2FE5FEF771FA7520 (gibbonSchoolYearID), UNIQUE INDEX UNIQ_2FE5FEF7CC6782D6 (gibbonPersonID), UNIQUE INDEX UNIQ_2FE5FEF79794905 (gibbonPersonIDApproval), UNIQUE INDEX UNIQ_2FE5FEF7FF59AAB0 (gibbonPersonIDCreator), UNIQUE INDEX UNIQ_2FE5FEF7D62085CF (gibbonGroupID), PRIMARY KEY(gibbonStaffAbsenceID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffAbsenceDate (gibbonStaffAbsenceDateID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE DEFAULT NULL, allDay VARCHAR(1) DEFAULT \'Y\', timeStart TIME DEFAULT NULL, timeEnd TIME DEFAULT NULL, value NUMERIC(2, 1) DEFAULT \'1\' NOT NULL, gibbonStaffAbsenceID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, UNIQUE INDEX UNIQ_269FD270102BE4BE (gibbonStaffAbsenceID), PRIMARY KEY(gibbonStaffAbsenceDateID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffAbsenceType (gibbonStaffAbsenceTypeID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(60) DEFAULT NULL, nameShort VARCHAR(10) DEFAULT NULL, active VARCHAR(1) DEFAULT \'Y\', requiresApproval VARCHAR(1) DEFAULT \'N\', reasons LONGTEXT DEFAULT NULL, sequenceNumber INT(3), PRIMARY KEY(gibbonStaffAbsenceTypeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffApplicationForm (gibbonStaffApplicationFormID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, surname VARCHAR(60) DEFAULT NULL, firstName VARCHAR(60) DEFAULT NULL, preferredName VARCHAR(60) DEFAULT NULL, officialName VARCHAR(150) DEFAULT NULL, nameInCharacters VARCHAR(60) DEFAULT NULL, gender VARCHAR(12) DEFAULT NULL, status VARCHAR(12) DEFAULT \'Pending\' NOT NULL, dob DATE DEFAULT NULL, email VARCHAR(75) DEFAULT NULL, homeAddress LONGTEXT DEFAULT NULL, homeAddressDistrict VARCHAR(255) DEFAULT NULL, homeAddressCountry VARCHAR(255) DEFAULT NULL, phone1Type VARCHAR(6) DEFAULT NULL, phone1CountryCode VARCHAR(7) DEFAULT NULL, phone1 VARCHAR(20) DEFAULT NULL, countryOfBirth VARCHAR(30) DEFAULT NULL, citizenship1 VARCHAR(255) DEFAULT NULL, citizenship1Passport VARCHAR(30) DEFAULT NULL, nationalIDCardNumber VARCHAR(30) DEFAULT NULL, residencyStatus VARCHAR(255) DEFAULT NULL, visaExpiryDate DATE DEFAULT NULL, languageFirst VARCHAR(30) DEFAULT NULL, languageSecond VARCHAR(30) DEFAULT NULL, languageThird VARCHAR(30) DEFAULT NULL, agreement VARCHAR(1) DEFAULT NULL, timestamp DATETIME DEFAULT NULL, priority INT(1), milestones LONGTEXT NOT NULL, notes LONGTEXT NOT NULL, dateStart DATE DEFAULT NULL, questions LONGTEXT NOT NULL, fields LONGTEXT NOT NULL COMMENT \'Serialised array of custom field values\', referenceEmail1 VARCHAR(100) NOT NULL, referenceEmail2 VARCHAR(100) NOT NULL, gibbonStaffJobOpeningID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_48D734D9B060E48C (gibbonStaffJobOpeningID), INDEX IDX_48D734D9CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonStaffApplicationFormID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffApplicationFormFile (gibbonStaffApplicationFormFileID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, gibbonStaffApplicationFormID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_E2EDB80B609DA10E (gibbonStaffApplicationFormID), PRIMARY KEY(gibbonStaffApplicationFormFileID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffContract (gibbonStaffContractID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, title VARCHAR(100) NOT NULL, status VARCHAR(8) NOT NULL, dateStart DATE NOT NULL, dateEnd DATE DEFAULT NULL, salaryScale VARCHAR(255) DEFAULT NULL, salaryAmount NUMERIC(12, 2) DEFAULT NULL, salaryPeriod VARCHAR(255) DEFAULT NULL, responsibility VARCHAR(255) DEFAULT NULL, responsibilityAmount NUMERIC(12, 2) DEFAULT NULL, responsibilityPeriod VARCHAR(255) DEFAULT NULL, housingAmount NUMERIC(12, 2) DEFAULT NULL, housingPeriod VARCHAR(255) DEFAULT NULL, travelAmount NUMERIC(12, 2) DEFAULT NULL, travelPeriod VARCHAR(255) DEFAULT NULL, retirementAmount NUMERIC(12, 2) DEFAULT NULL, retirementPeriod VARCHAR(255) DEFAULT NULL, bonusAmount NUMERIC(12, 2) DEFAULT NULL, bonusPeriod VARCHAR(255) DEFAULT NULL, education LONGTEXT NOT NULL, notes LONGTEXT NOT NULL, contractUpload VARCHAR(255) DEFAULT NULL, timestampCreator DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonStaffID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_28B78AEC76DF47DD (gibbonStaffID), INDEX IDX_28B78AECFF59AAB0 (gibbonPersonIDCreator), PRIMARY KEY(gibbonStaffContractID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffCoverage (gibbonStaffCoverageID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, status VARCHAR(12) DEFAULT \'Requested\' NOT NULL, requestType VARCHAR(12) DEFAULT \'Broadcast\' NOT NULL, substituteTypes VARCHAR(255) DEFAULT NULL, timestampStatus DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, notesStatus LONGTEXT DEFAULT NULL, timestampCoverage DATETIME DEFAULT NULL, notesCoverage LONGTEXT DEFAULT NULL, attachmentType VARCHAR(4) DEFAULT NULL, attachmentContent LONGTEXT DEFAULT NULL, notificationSent VARCHAR(1) DEFAULT \'N\' NOT NULL, notificationList LONGTEXT DEFAULT NULL, gibbonStaffAbsenceID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDStatus INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCoverage INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, UNIQUE INDEX UNIQ_946E51DE102BE4BE (gibbonStaffAbsenceID), UNIQUE INDEX UNIQ_946E51DE71FA7520 (gibbonSchoolYearID), UNIQUE INDEX UNIQ_946E51DECC6782D6 (gibbonPersonID), UNIQUE INDEX UNIQ_946E51DE4DA9DC74 (gibbonPersonIDStatus), UNIQUE INDEX UNIQ_946E51DE4ACF2F45 (gibbonPersonIDCoverage), PRIMARY KEY(gibbonStaffCoverageID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffCoverageDate (gibbonStaffCoverageDateID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE DEFAULT NULL, allDay VARCHAR(1) DEFAULT \'Y\' NOT NULL, timeStart TIME DEFAULT NULL, timeEnd TIME DEFAULT NULL, value NUMERIC(2, 1) DEFAULT NULL, reason VARCHAR(255) DEFAULT NULL, gibbonStaffCoverageID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonStaffAbsenceDateID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDUnavailable INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, UNIQUE INDEX UNIQ_AD45031D12047EA7 (gibbonStaffCoverageID), UNIQUE INDEX UNIQ_AD45031D56318FAB (gibbonStaffAbsenceDateID), UNIQUE INDEX UNIQ_AD45031DFED701B3 (gibbonPersonIDUnavailable), PRIMARY KEY(gibbonStaffCoverageDateID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStaffJobOpening (gibbonStaffJobOpeningID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, type VARCHAR(20) NOT NULL, jobTitle VARCHAR(100) NOT NULL, jdateOpen DATE NOT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, description LONGTEXT NOT NULL, timestampCreator DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_C9D57E5CFF59AAB0 (gibbonPersonIDCreator), PRIMARY KEY(gibbonStaffJobOpeningID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonString (gibbonStringID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, original VARCHAR(100) NOT NULL, replacement VARCHAR(100) NOT NULL, mode VARCHAR(8) NOT NULL, caseSensitive VARCHAR(1) NOT NULL, priority INT(2), PRIMARY KEY(gibbonStringID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStudentEnrolment (gibbonStudentEnrolmentID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, rollOrder INT(2), gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonYearGroupID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonRollGroupID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_ABD4EFFDCC6782D6 (gibbonPersonID), INDEX gibbonSchoolYearID (gibbonSchoolYearID), INDEX gibbonYearGroupID (gibbonYearGroupID), INDEX gibbonRollGroupID (gibbonRollGroupID), INDEX gibbonPersonIndex (gibbonPersonID, gibbonSchoolYearID), PRIMARY KEY(gibbonStudentEnrolmentID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStudentNote (gibbonStudentNoteID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, title VARCHAR(50) NOT NULL, note LONGTEXT NOT NULL, timestamp DATETIME DEFAULT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonStudentNoteCategoryID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_48CB2167CC6782D6 (gibbonPersonID), INDEX IDX_48CB21671E9DC1FF (gibbonStudentNoteCategoryID), INDEX IDX_48CB2167FF59AAB0 (gibbonPersonIDCreator), PRIMARY KEY(gibbonStudentNoteID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonStudentNoteCategory (gibbonStudentNoteCategoryID INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, template LONGTEXT NOT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, PRIMARY KEY(gibbonStudentNoteCategoryID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonSubstitute (gibbonSubstituteID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, active VARCHAR(1) DEFAULT \'Y\', type VARCHAR(60) DEFAULT NULL, details VARCHAR(255) DEFAULT NULL, priority INT(2), gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, UNIQUE INDEX gibbonPersonID (gibbonPersonID), PRIMARY KEY(gibbonSubstituteID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTheme (gibbonThemeID INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, description VARCHAR(100) NOT NULL, active VARCHAR(1) DEFAULT \'N\' NOT NULL, version VARCHAR(6) NOT NULL, author VARCHAR(40) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(gibbonThemeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTT (gibbonTTID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, nameShort VARCHAR(12) NOT NULL, nameShortDisplay VARCHAR(24) DEFAULT \'Day Of The Week\' NOT NULL, gibbonYearGroupIDList VARCHAR(255) NOT NULL, active VARCHAR(1) NOT NULL, gibbonSchoolYearID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_9431F94371FA7520 (gibbonSchoolYearID), PRIMARY KEY(gibbonTTID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTTColumn (gibbonTTColumnID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(30) NOT NULL, nameShort VARCHAR(12) NOT NULL, PRIMARY KEY(gibbonTTColumnID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTTColumnRow (gibbonTTColumnRowID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(12) NOT NULL, nameShort VARCHAR(4) NOT NULL, timeStart TIME NOT NULL, timeEnd TIME NOT NULL, type VARCHAR(8) NOT NULL, gibbonTTColumnID INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonTTColumnID (gibbonTTColumnID), PRIMARY KEY(gibbonTTColumnRowID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE `gibbonTTDay` (
  `gibbonTTDayID` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `name` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `nameShort` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `fontColor` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `gibbonTTID` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `gibbonTTColumnID` int(6) UNSIGNED ZEROFILL DEFAULT NULL,
  PRIMARY KEY (`gibbonTTDayID`),
  UNIQUE KEY `nameShortTT` (`gibbonTTID`,`nameShort`),
  UNIQUE KEY `nameTT` (`gibbonTTID`,`name`) USING BTREE,
  KEY `IDX_3B9106B3EE6A175` (`gibbonTTID`),
  KEY `gibbonTTColumnID` (`gibbonTTColumnID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTTDayDate (gibbonTTDayDateID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE NOT NULL, gibbonTTDayID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX gibbonTTDayID (gibbonTTDayID), PRIMARY KEY(gibbonTTDayDateID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTTDayRowClass (gibbonTTDayRowClassID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonTTColumnRowID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonTTDayID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonSpaceID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_C832432E375800E5 (gibbonTTDayID), INDEX gibbonCourseClassID (gibbonCourseClassID), INDEX gibbonSpaceID (gibbonSpaceID), INDEX gibbonTTColumnRowID (gibbonTTColumnRowID), PRIMARY KEY(gibbonTTDayRowClassID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTTDayRowClassException (gibbonTTDayRowClassExceptionID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonTTDayRowClassID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_D25EB853F501B20E (gibbonTTDayRowClassID), INDEX IDX_D25EB853CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonTTDayRowClassExceptionID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTTImport (gibbonTTImportID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, courseNameShort VARCHAR(6) NOT NULL, classNameShort VARCHAR(5) NOT NULL, dayName VARCHAR(12) NOT NULL, rowName VARCHAR(12) NOT NULL, teacherUsernameList LONGTEXT NOT NULL, spaceName VARCHAR(30) NOT NULL, PRIMARY KEY(gibbonTTImportID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTTSpaceBooking (gibbonTTSpaceBookingID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, foreignKey VARCHAR(30) DEFAULT \'gibbonSpaceID\' NOT NULL, foreignKeyID INT(10) UNSIGNED ZEROFILL, date DATE NOT NULL, timeStart TIME NOT NULL, timeEnd TIME NOT NULL, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_1A34AD71CC6782D6 (gibbonPersonID), PRIMARY KEY(gibbonTTSpaceBookingID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonTTSpaceChange (gibbonTTSpaceChangeID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, date DATE NOT NULL, gibbonTTDayRowClassID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonSpaceID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_772A323ED8D64BA0 (gibbonSpaceID), INDEX IDX_772A323ECC6782D6 (gibbonPersonID), INDEX gibbonTTDayRowClassID (gibbonTTDayRowClassID), INDEX date (date), PRIMARY KEY(gibbonTTSpaceChangeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonUnit (gibbonUnitID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(40) NOT NULL, active VARCHAR(1) DEFAULT \'Y\' NOT NULL, description LONGTEXT NOT NULL, tags LONGTEXT NOT NULL, map VARCHAR(1) DEFAULT \'Y\' NOT NULL COMMENT \'Should this unit be included in curriculum maps and other summaries?\', ordering INT(2), attachment VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, license VARCHAR(50) DEFAULT NULL, sharedPublic VARCHAR(1) DEFAULT NULL, gibbonCourseID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDCreator INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPersonIDLastEdit INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_2CFBB258ACDCF59E (gibbonCourseID), INDEX IDX_2CFBB258FF59AAB0 (gibbonPersonIDCreator), INDEX IDX_2CFBB258519966BA (gibbonPersonIDLastEdit), PRIMARY KEY(gibbonUnitID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonUnitBlock (gibbonUnitBlockID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, title VARCHAR(100) NOT NULL, type VARCHAR(50) NOT NULL, length VARCHAR(3) NOT NULL, contents LONGTEXT NOT NULL, teachersNotes LONGTEXT NOT NULL, sequenceNumber INT(4), gibbonUnitID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_7D624DA246DE4A3D (gibbonUnitID), PRIMARY KEY(gibbonUnitBlockID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonUnitClass (gibbonUnitClassID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, running VARCHAR(1) DEFAULT \'N\' NOT NULL, gibbonUnitID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonCourseClassID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_1332C31F46DE4A3D (gibbonUnitID), INDEX IDX_1332C31FB67991E (gibbonCourseClassID), PRIMARY KEY(gibbonUnitClassID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonUnitClassBlock (gibbonUnitClassBlockID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, title VARCHAR(100) NOT NULL, type VARCHAR(50) NOT NULL, length VARCHAR(3) NOT NULL, contents LONGTEXT NOT NULL, teachersNotes LONGTEXT NOT NULL, sequenceNumber INT(4), complete VARCHAR(1) DEFAULT \'N\' NOT NULL, gibbonUnitClassID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonPlannerEntryID INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonUnitBlockID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_829289F1DEE4ED9C (gibbonUnitClassID), INDEX IDX_829289F1FE417281 (gibbonPlannerEntryID), INDEX IDX_829289F1858FFD1E (gibbonUnitBlockID), PRIMARY KEY(gibbonUnitClassBlockID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonUnitOutcome (gibbonUnitOutcomeID INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT, sequenceNumber INT(4), content LONGTEXT NOT NULL, gibbonUnitID INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonOutcomeID INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_6D39303A46DE4A3D (gibbonUnitID), INDEX IDX_6D39303A35479F6A (gibbonOutcomeID), PRIMARY KEY(gibbonUnitOutcomeID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonUsernameFormat (gibbonUsernameFormatID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, gibbonRoleIDList VARCHAR(255) DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, isDefault VARCHAR(1) DEFAULT \'N\' NOT NULL, isNumeric VARCHAR(1) DEFAULT \'N\' NOT NULL, numericValue INT(12) UNSIGNED, numericIncrement INT(3) UNSIGNED, numericSize INT(3) UNSIGNED, PRIMARY KEY(gibbonUsernameFormatID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('CREATE TABLE gibbonYearGroup (gibbonYearGroupID INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT, name VARCHAR(15) NOT NULL, nameShort VARCHAR(4) NOT NULL, sequenceNumber INT(3) UNSIGNED NOT NULL, gibbonPersonIDHOY INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT, INDEX IDX_CD51DC7DB7F9F88C (gibbonPersonIDHOY), UNIQUE INDEX name (name), UNIQUE INDEX nameShort (nameShort), UNIQUE INDEX sequenceNumber (sequenceNumber), PRIMARY KEY(gibbonYearGroupID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB AUTO_INCREMENT = 1');
        $this->addSql('ALTER TABLE gibbonAction ADD CONSTRAINT FK_88E13B92CB86AD4B FOREIGN KEY (gibbonModuleID) REFERENCES gibbonModule (gibbonModuleID)');
        $this->addSql('ALTER TABLE gibbonActivity ADD CONSTRAINT FK_F971E05871FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonActivityAttendance ADD CONSTRAINT FK_2357A712C3BBAF6F FOREIGN KEY (gibbonActivityID) REFERENCES gibbonActivity (gibbonActivityID)');
        $this->addSql('ALTER TABLE gibbonActivityAttendance ADD CONSTRAINT FK_2357A71211A14ED FOREIGN KEY (gibbonPersonIDTaker) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonActivitySlot ADD CONSTRAINT FK_59227ABBC3BBAF6F FOREIGN KEY (gibbonActivityID) REFERENCES gibbonActivity (gibbonActivityID)');
        $this->addSql('ALTER TABLE gibbonActivitySlot ADD CONSTRAINT FK_59227ABBD8D64BA0 FOREIGN KEY (gibbonSpaceID) REFERENCES gibbonSpace (gibbonSpaceID)');
        $this->addSql('ALTER TABLE gibbonActivitySlot ADD CONSTRAINT FK_59227ABB2817C0E1 FOREIGN KEY (gibbonDaysOfWeekID) REFERENCES gibbonDaysOfWeek (gibbonDaysOfWeekID)');
        $this->addSql('ALTER TABLE gibbonActivityStaff ADD CONSTRAINT FK_CDFE4137C3BBAF6F FOREIGN KEY (gibbonActivityID) REFERENCES gibbonActivity (gibbonActivityID)');
        $this->addSql('ALTER TABLE gibbonActivityStaff ADD CONSTRAINT FK_CDFE4137CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonActivityStudent ADD CONSTRAINT FK_413CBAAFC3BBAF6F FOREIGN KEY (gibbonActivityID) REFERENCES gibbonActivity (gibbonActivityID)');
        $this->addSql('ALTER TABLE gibbonActivityStudent ADD CONSTRAINT FK_413CBAAFCC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonActivityStudent ADD CONSTRAINT FK_413CBAAF6A1380D0 FOREIGN KEY (gibbonActivityIDBackup) REFERENCES gibbonActivity (gibbonActivityID)');
        $this->addSql('ALTER TABLE gibbonActivityStudent ADD CONSTRAINT FK_413CBAAFF51FBF6 FOREIGN KEY (gibbonFinanceInvoiceID) REFERENCES gibbonFinanceInvoice (gibbonFinanceInvoiceID)');
        $this->addSql('ALTER TABLE gibbonAlarm ADD CONSTRAINT FK_E3BDDFEBCC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonAlarmConfirm ADD CONSTRAINT FK_8E3CE4BCBA3565E5 FOREIGN KEY (gibbonAlarmID) REFERENCES gibbonAlarm (gibbonAlarmID)');
        $this->addSql('ALTER TABLE gibbonAlarmConfirm ADD CONSTRAINT FK_8E3CE4BCCC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonApplicationForm ADD CONSTRAINT FK_A309B59CF9B7736F FOREIGN KEY (gibbonSchoolYearIDEntry) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonApplicationForm ADD CONSTRAINT FK_A309B59C9DE35FD8 FOREIGN KEY (gibbonYearGroupIDEntry) REFERENCES gibbonYearGroup (gibbonYearGroupID)');
        $this->addSql('ALTER TABLE gibbonApplicationForm ADD CONSTRAINT FK_A309B59C7DF7AB4B FOREIGN KEY (parent1gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonApplicationForm ADD CONSTRAINT FK_A309B59CA85AE4EC FOREIGN KEY (gibbonRollGroupID) REFERENCES gibbonRollGroup (gibbonRollGroupID)');
        $this->addSql('ALTER TABLE gibbonApplicationForm ADD CONSTRAINT FK_A309B59C51F0BB1F FOREIGN KEY (gibbonFamilyID) REFERENCES gibbonFamily (gibbonFamilyID)');
        $this->addSql('ALTER TABLE gibbonApplicationForm ADD CONSTRAINT FK_A309B59CA0F353A3 FOREIGN KEY (gibbonPaymentID) REFERENCES gibbonPayment (gibbonPaymentID)');
        $this->addSql('ALTER TABLE gibbonApplicationFormFile ADD CONSTRAINT FK_86B3B2D2772C4226 FOREIGN KEY (gibbonApplicationFormID) REFERENCES gibbonApplicationForm (gibbonApplicationFormID)');
        $this->addSql('ALTER TABLE gibbonApplicationFormLink ADD CONSTRAINT FK_3C801D3351A64608 FOREIGN KEY (gibbonApplicationFormID1) REFERENCES gibbonApplicationForm (gibbonApplicationFormID)');
        $this->addSql('ALTER TABLE gibbonApplicationFormLink ADD CONSTRAINT FK_3C801D33C8AF17B2 FOREIGN KEY (gibbonApplicationFormID2) REFERENCES gibbonApplicationForm (gibbonApplicationFormID)');
        $this->addSql('ALTER TABLE gibbonApplicationFormRelationship ADD CONSTRAINT FK_5E0017E7772C4226 FOREIGN KEY (gibbonApplicationFormID) REFERENCES gibbonApplicationForm (gibbonApplicationFormID)');
        $this->addSql('ALTER TABLE gibbonApplicationFormRelationship ADD CONSTRAINT FK_5E0017E7CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonAttendanceLogCourseClass ADD CONSTRAINT FK_6D6C05B0B67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonAttendanceLogCourseClass ADD CONSTRAINT FK_6D6C05B011A14ED FOREIGN KEY (gibbonPersonIDTaker) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonAttendanceLogPerson ADD CONSTRAINT FK_1FC495175772A3E4 FOREIGN KEY (gibbonAttendanceCodeID) REFERENCES gibbonAttendanceCode (gibbonAttendanceCodeID)');
        $this->addSql('ALTER TABLE gibbonAttendanceLogPerson ADD CONSTRAINT FK_1FC49517CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonAttendanceLogPerson ADD CONSTRAINT FK_1FC4951711A14ED FOREIGN KEY (gibbonPersonIDTaker) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonAttendanceLogPerson ADD CONSTRAINT FK_1FC49517B67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonAttendanceLogRollGroup ADD CONSTRAINT FK_A6F88BEDA85AE4EC FOREIGN KEY (gibbonRollGroupID) REFERENCES gibbonRollGroup (gibbonRollGroupID)');
        $this->addSql('ALTER TABLE gibbonAttendanceLogRollGroup ADD CONSTRAINT FK_A6F88BED11A14ED FOREIGN KEY (gibbonPersonIDTaker) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonBehaviour ADD CONSTRAINT FK_64915B371FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonBehaviour ADD CONSTRAINT FK_64915B3CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonBehaviour ADD CONSTRAINT FK_64915B3FE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonBehaviour ADD CONSTRAINT FK_64915B3FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonBehaviourLetter ADD CONSTRAINT FK_5F61F91071FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonBehaviourLetter ADD CONSTRAINT FK_5F61F910CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonCourse ADD CONSTRAINT FK_D9B3D8B971FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonCourse ADD CONSTRAINT FK_D9B3D8B96DFE7E92 FOREIGN KEY (gibbonDepartmentID) REFERENCES gibbonDepartment (gibbonDepartmentID)');
        $this->addSql('ALTER TABLE gibbonCourseClass ADD CONSTRAINT FK_455FF397ACDCF59E FOREIGN KEY (gibbonCourseID) REFERENCES gibbonCourse (gibbonCourseID)');
        $this->addSql('ALTER TABLE gibbonCourseClass ADD CONSTRAINT FK_455FF3977DD4B430 FOREIGN KEY (gibbonScaleIDTarget) REFERENCES gibbonScale (gibbonScaleID)');
        $this->addSql('ALTER TABLE gibbonCourseClassMap ADD CONSTRAINT FK_97F9BC70B67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonCourseClassMap ADD CONSTRAINT FK_97F9BC70A85AE4EC FOREIGN KEY (gibbonRollGroupID) REFERENCES gibbonRollGroup (gibbonRollGroupID)');
        $this->addSql('ALTER TABLE gibbonCourseClassMap ADD CONSTRAINT FK_97F9BC70427372F FOREIGN KEY (gibbonYearGroupID) REFERENCES gibbonYearGroup (gibbonYearGroupID)');
        $this->addSql('ALTER TABLE gibbonCourseClassPerson ADD CONSTRAINT FK_D9B888E9B67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonCourseClassPerson ADD CONSTRAINT FK_D9B888E9CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonCrowdAssessDiscuss ADD CONSTRAINT FK_D17E708617B9ED44 FOREIGN KEY (gibbonPlannerEntryHomeworkID) REFERENCES gibbonPlannerEntryHomework (gibbonPlannerEntryHomeworkID)');
        $this->addSql('ALTER TABLE gibbonCrowdAssessDiscuss ADD CONSTRAINT FK_D17E7086CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonCrowdAssessDiscuss ADD CONSTRAINT FK_D17E7086D96E9809 FOREIGN KEY (gibbonCrowdAssessDiscussIDReplyTo) REFERENCES gibbonCrowdAssessDiscuss (gibbonCrowdAssessDiscussID)');
        $this->addSql('ALTER TABLE gibbonDepartmentResource ADD CONSTRAINT FK_276BB0276DFE7E92 FOREIGN KEY (gibbonDepartmentID) REFERENCES gibbonDepartment (gibbonDepartmentID)');
        $this->addSql('ALTER TABLE gibbonDepartmentStaff ADD CONSTRAINT FK_EE77E05B6DFE7E92 FOREIGN KEY (gibbonDepartmentID) REFERENCES gibbonDepartment (gibbonDepartmentID)');
        $this->addSql('ALTER TABLE gibbonDepartmentStaff ADD CONSTRAINT FK_EE77E05BCC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentField ADD CONSTRAINT FK_A03EECA92660493E FOREIGN KEY (gibbonExternalAssessmentID) REFERENCES gibbonExternalAssessment (gibbonExternalAssessmentID)');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentField ADD CONSTRAINT FK_A03EECA95F72BC3 FOREIGN KEY (gibbonScaleID) REFERENCES gibbonScale (gibbonScaleID)');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudent ADD CONSTRAINT FK_158E9F482660493E FOREIGN KEY (gibbonExternalAssessmentID) REFERENCES gibbonExternalAssessment (gibbonExternalAssessmentID)');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudent ADD CONSTRAINT FK_158E9F48CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudentEntry ADD CONSTRAINT FK_BB5E809049C33D6C FOREIGN KEY (gibbonExternalAssessmentStudentID) REFERENCES gibbonExternalAssessmentStudent (gibbonExternalAssessmentStudentID)');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudentEntry ADD CONSTRAINT FK_BB5E8090C7AA6AF7 FOREIGN KEY (gibbonExternalAssessmentFieldID) REFERENCES gibbonExternalAssessmentField (gibbonExternalAssessmentFieldID)');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudentEntry ADD CONSTRAINT FK_BB5E80905E440573 FOREIGN KEY (gibbonScaleGradeID) REFERENCES gibbonScaleGrade (gibbonScaleGradeID)');
        $this->addSql('ALTER TABLE gibbonFamilyAdult ADD CONSTRAINT FK_EEF67AB51F0BB1F FOREIGN KEY (gibbonFamilyID) REFERENCES gibbonFamily (gibbonFamilyID)');
        $this->addSql('ALTER TABLE gibbonFamilyAdult ADD CONSTRAINT FK_EEF67ABCC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFamilyChild ADD CONSTRAINT FK_3672C10351F0BB1F FOREIGN KEY (gibbonFamilyID) REFERENCES gibbonFamily (gibbonFamilyID)');
        $this->addSql('ALTER TABLE gibbonFamilyChild ADD CONSTRAINT FK_3672C103CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFamilyRelationship ADD CONSTRAINT FK_D6CA42151F0BB1F FOREIGN KEY (gibbonFamilyID) REFERENCES gibbonFamily (gibbonFamilyID)');
        $this->addSql('ALTER TABLE gibbonFamilyRelationship ADD CONSTRAINT FK_D6CA421ECA0FFD4 FOREIGN KEY (gibbonPersonID1) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFamilyRelationship ADD CONSTRAINT FK_D6CA42175A9AE6E FOREIGN KEY (gibbonPersonID2) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFamilyUpdate ADD CONSTRAINT FK_438A3D3B71FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonFamilyUpdate ADD CONSTRAINT FK_438A3D3B51F0BB1F FOREIGN KEY (gibbonFamilyID) REFERENCES gibbonFamily (gibbonFamilyID)');
        $this->addSql('ALTER TABLE gibbonFamilyUpdate ADD CONSTRAINT FK_438A3D3B71106375 FOREIGN KEY (gibbonPersonIDUpdater) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceBillingSchedule ADD CONSTRAINT FK_EC0D8C7D71FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonFinanceBillingSchedule ADD CONSTRAINT FK_EC0D8C7DFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceBillingSchedule ADD CONSTRAINT FK_EC0D8C7DAE8C8C10 FOREIGN KEY (gibbonPersonIDUpdate) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceBudget ADD CONSTRAINT FK_EE793C02FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceBudget ADD CONSTRAINT FK_EE793C02AE8C8C10 FOREIGN KEY (gibbonPersonIDUpdate) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetCycle ADD CONSTRAINT FK_2AA76753FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetCycle ADD CONSTRAINT FK_2AA76753AE8C8C10 FOREIGN KEY (gibbonPersonIDUpdate) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetCycleAllocation ADD CONSTRAINT FK_B27D799BC8A9346 FOREIGN KEY (gibbonFinanceBudgetID) REFERENCES gibbonFinanceBudget (gibbonFinanceBudgetID)');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetCycleAllocation ADD CONSTRAINT FK_B27D799B5393B3F1 FOREIGN KEY (gibbonFinanceBudgetCycleID) REFERENCES gibbonFinanceBudgetCycle (gibbonFinanceBudgetCycleID)');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetPerson ADD CONSTRAINT FK_AF223270C8A9346 FOREIGN KEY (gibbonFinanceBudgetID) REFERENCES gibbonFinanceBudget (gibbonFinanceBudgetID)');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetPerson ADD CONSTRAINT FK_AF223270CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpense ADD CONSTRAINT FK_47ECFF5C8A9346 FOREIGN KEY (gibbonFinanceBudgetID) REFERENCES gibbonFinanceBudget (gibbonFinanceBudgetID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpense ADD CONSTRAINT FK_47ECFF55393B3F1 FOREIGN KEY (gibbonFinanceBudgetCycleID) REFERENCES gibbonFinanceBudgetCycle (gibbonFinanceBudgetCycleID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpense ADD CONSTRAINT FK_47ECFF52E77C4DE FOREIGN KEY (gibbonPersonIDPayment) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpense ADD CONSTRAINT FK_47ECFF5FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseApprover ADD CONSTRAINT FK_38833027CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseApprover ADD CONSTRAINT FK_38833027FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseApprover ADD CONSTRAINT FK_38833027AE8C8C10 FOREIGN KEY (gibbonPersonIDUpdate) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseLog ADD CONSTRAINT FK_FFA208A073C3AD9D FOREIGN KEY (gibbonFinanceExpenseID) REFERENCES gibbonFinanceExpense (gibbonFinanceExpenseID)');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseLog ADD CONSTRAINT FK_FFA208A0CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceFee ADD CONSTRAINT FK_7D222CFC71FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonFinanceFee ADD CONSTRAINT FK_7D222CFCB05DE109 FOREIGN KEY (gibbonFinanceFeeCategoryID) REFERENCES gibbonFinanceFeeCategory (gibbonFinanceFeeCategoryID)');
        $this->addSql('ALTER TABLE gibbonFinanceFee ADD CONSTRAINT FK_7D222CFCFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceFee ADD CONSTRAINT FK_7D222CFCAE8C8C10 FOREIGN KEY (gibbonPersonIDUpdate) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceFeeCategory ADD CONSTRAINT FK_14C5A939FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceFeeCategory ADD CONSTRAINT FK_14C5A939AE8C8C10 FOREIGN KEY (gibbonPersonIDUpdate) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice ADD CONSTRAINT FK_B921551771FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice ADD CONSTRAINT FK_B9215517739BAD1F FOREIGN KEY (gibbonFinanceInvoiceeID) REFERENCES gibbonFinanceInvoicee (gibbonFinanceInvoiceeID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice ADD CONSTRAINT FK_B92155176F4C4787 FOREIGN KEY (gibbonFinanceBillingScheduleID) REFERENCES gibbonFinanceBillingSchedule (gibbonFinanceBillingScheduleID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice ADD CONSTRAINT FK_B9215517A0F353A3 FOREIGN KEY (gibbonPaymentID) REFERENCES gibbonPayment (gibbonPaymentID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice ADD CONSTRAINT FK_B9215517FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice ADD CONSTRAINT FK_B9215517AE8C8C10 FOREIGN KEY (gibbonPersonIDUpdate) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoicee ADD CONSTRAINT FK_6CB0DEC8CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceeUpdate ADD CONSTRAINT FK_848117171FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceeUpdate ADD CONSTRAINT FK_8481171739BAD1F FOREIGN KEY (gibbonFinanceInvoiceeID) REFERENCES gibbonFinanceInvoicee (gibbonFinanceInvoiceeID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceeUpdate ADD CONSTRAINT FK_848117171106375 FOREIGN KEY (gibbonPersonIDUpdater) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceFee ADD CONSTRAINT FK_3CC82E56F51FBF6 FOREIGN KEY (gibbonFinanceInvoiceID) REFERENCES gibbonFinanceInvoice (gibbonFinanceInvoiceID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceFee ADD CONSTRAINT FK_3CC82E569B02DC4A FOREIGN KEY (gibbonFinanceFeeID) REFERENCES gibbonFinanceFee (gibbonFinanceFeeID)');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceFee ADD CONSTRAINT FK_3CC82E56B05DE109 FOREIGN KEY (gibbonFinanceFeeCategoryID) REFERENCES gibbonFinanceFeeCategory (gibbonFinanceFeeCategoryID)');
        $this->addSql('ALTER TABLE gibbonFirstAid ADD CONSTRAINT FK_ABF0052759859738 FOREIGN KEY (gibbonPersonIDPatient) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFirstAid ADD CONSTRAINT FK_ABF00527B67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonFirstAid ADD CONSTRAINT FK_ABF0052722759506 FOREIGN KEY (gibbonPersonIDFirstAider) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonFirstAid ADD CONSTRAINT FK_ABF0052771FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonGroup ADD CONSTRAINT FK_FAE2DDF3659378D6 FOREIGN KEY (gibbonPersonIDOwner) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonGroup ADD CONSTRAINT FK_FAE2DDF371FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonGroupPerson ADD CONSTRAINT FK_15367BAAD62085CF FOREIGN KEY (gibbonGroupID) REFERENCES gibbonGroup (gibbonGroupID)');
        $this->addSql('ALTER TABLE gibbonGroupPerson ADD CONSTRAINT FK_15367BAACC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonHook ADD CONSTRAINT FK_5418FD5ECB86AD4B FOREIGN KEY (gibbonModuleID) REFERENCES gibbonModule (gibbonModuleID)');
        $this->addSql('ALTER TABLE gibbonINArchive ADD CONSTRAINT FK_43A82C35CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonINAssistant ADD CONSTRAINT FK_9A5EAC8FF47CEFE0 FOREIGN KEY (gibbonPersonIDStudent) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonINAssistant ADD CONSTRAINT FK_9A5EAC8F1E50E8C2 FOREIGN KEY (gibbonPersonIDAssistant) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonIN ADD CONSTRAINT FK_963F6C25CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonINPersonDescriptor ADD CONSTRAINT FK_8D22AA26CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonINPersonDescriptor ADD CONSTRAINT FK_8D22AA26789947CD FOREIGN KEY (gibbonINDescriptorID) REFERENCES gibbonINDescriptor (gibbonINDescriptorID)');
        $this->addSql('ALTER TABLE gibbonINPersonDescriptor ADD CONSTRAINT FK_8D22AA26891EFB5B FOREIGN KEY (gibbonAlertLevelID) REFERENCES gibbonAlertLevel (gibbonAlertLevelID)');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn ADD CONSTRAINT FK_E0A1D88AB67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn ADD CONSTRAINT FK_E0A1D88A2C639785 FOREIGN KEY (gibbonScaleIDAttainment) REFERENCES gibbonScale (gibbonScaleID)');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn ADD CONSTRAINT FK_E0A1D88AD395ACF8 FOREIGN KEY (gibbonScaleIDEffort) REFERENCES gibbonScale (gibbonScaleID)');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn ADD CONSTRAINT FK_E0A1D88AFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn ADD CONSTRAINT FK_E0A1D88A519966BA FOREIGN KEY (gibbonPersonIDLastEdit) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentEntry ADD CONSTRAINT FK_B09C6F558B7A9BC FOREIGN KEY (gibbonInternalAssessmentColumnID) REFERENCES gibbonInternalAssessmentColumn (gibbonInternalAssessmentColumnID)');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentEntry ADD CONSTRAINT FK_B09C6F5F47CEFE0 FOREIGN KEY (gibbonPersonIDStudent) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentEntry ADD CONSTRAINT FK_B09C6F5519966BA FOREIGN KEY (gibbonPersonIDLastEdit) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonLog ADD CONSTRAINT FK_C0122755CB86AD4B FOREIGN KEY (gibbonModuleID) REFERENCES gibbonModule (gibbonModuleID)');
        $this->addSql('ALTER TABLE gibbonLog ADD CONSTRAINT FK_C0122755CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonLog ADD CONSTRAINT FK_C012275571FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806EB67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806EF6E7C959 FOREIGN KEY (gibbonHookID) REFERENCES gibbonHook (gibbonHookID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806E46DE4A3D FOREIGN KEY (gibbonUnitID) REFERENCES gibbonUnit (gibbonUnitID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806EFE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806E88C7C454 FOREIGN KEY (gibbonSchoolYearTermID) REFERENCES gibbonSchoolYearTerm (gibbonSchoolYearTermID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806E2C639785 FOREIGN KEY (gibbonScaleIDAttainment) REFERENCES gibbonScale (gibbonScaleID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806ED395ACF8 FOREIGN KEY (gibbonScaleIDEffort) REFERENCES gibbonScale (gibbonScaleID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806E2151BB77 FOREIGN KEY (gibbonRubricIDAttainment) REFERENCES gibbonRubric (gibbonRubricID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806EBA294907 FOREIGN KEY (gibbonRubricIDEffort) REFERENCES gibbonRubric (gibbonRubricID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806EFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn ADD CONSTRAINT FK_AA57806E519966BA FOREIGN KEY (gibbonPersonIDLastEdit) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonMarkbookEntry ADD CONSTRAINT FK_22F463917150C64 FOREIGN KEY (gibbonMarkbookColumnID) REFERENCES gibbonMarkbookColumn (gibbonMarkbookColumnID)');
        $this->addSql('ALTER TABLE gibbonMarkbookEntry ADD CONSTRAINT FK_22F46391F47CEFE0 FOREIGN KEY (gibbonPersonIDStudent) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonMarkbookEntry ADD CONSTRAINT FK_22F46391519966BA FOREIGN KEY (gibbonPersonIDLastEdit) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonMarkbookTarget ADD CONSTRAINT FK_916B28ECB67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonMarkbookTarget ADD CONSTRAINT FK_916B28ECF47CEFE0 FOREIGN KEY (gibbonPersonIDStudent) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonMarkbookTarget ADD CONSTRAINT FK_916B28EC5E440573 FOREIGN KEY (gibbonScaleGradeID) REFERENCES gibbonScaleGrade (gibbonScaleGradeID)');
        $this->addSql('ALTER TABLE gibbonMarkbookWeight ADD CONSTRAINT FK_D0C95251B67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonMessenger ADD CONSTRAINT FK_C7127C4CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonMessengerCannedResponse ADD CONSTRAINT FK_C83786BFFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonMessengerReceipt ADD CONSTRAINT FK_30BB77081B4FC86A FOREIGN KEY (gibbonMessengerID) REFERENCES gibbonMessenger (gibbonMessengerID)');
        $this->addSql('ALTER TABLE gibbonMessengerReceipt ADD CONSTRAINT FK_30BB7708CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonMessengerTarget ADD CONSTRAINT FK_62C2BBE11B4FC86A FOREIGN KEY (gibbonMessengerID) REFERENCES gibbonMessenger (gibbonMessengerID)');
        $this->addSql('ALTER TABLE gibbonNotification ADD CONSTRAINT FK_D5180450CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonNotification ADD CONSTRAINT FK_D5180450CB86AD4B FOREIGN KEY (gibbonModuleID) REFERENCES gibbonModule (gibbonModuleID)');
        $this->addSql('ALTER TABLE gibbonNotificationListener ADD CONSTRAINT FK_6313F17E26A39C71 FOREIGN KEY (gibbonNotificationEventID) REFERENCES gibbonNotificationEvent (gibbonNotificationEventID)');
        $this->addSql('ALTER TABLE gibbonNotificationListener ADD CONSTRAINT FK_6313F17ECC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonOutcome ADD CONSTRAINT FK_307340756DFE7E92 FOREIGN KEY (gibbonDepartmentID) REFERENCES gibbonDepartment (gibbonDepartmentID)');
        $this->addSql('ALTER TABLE gibbonOutcome ADD CONSTRAINT FK_30734075FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPayment ADD CONSTRAINT FK_6DE7A9BACC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPermission ADD CONSTRAINT FK_BA9FFF14C816A40 FOREIGN KEY (gibbonRoleID) REFERENCES gibbonRole (gibbonRoleID)');
        $this->addSql('ALTER TABLE gibbonPermission ADD CONSTRAINT FK_BA9FFF1E3AFEA67 FOREIGN KEY (gibbonActionID) REFERENCES gibbonAction (gibbonActionID)');
        $this->addSql('ALTER TABLE gibbonPerson ADD CONSTRAINT FK_FBF1667668D8F4F8 FOREIGN KEY (gibbonRoleIDPrimary) REFERENCES gibbonRole (gibbonRoleID)');
        $this->addSql('ALTER TABLE gibbonPerson ADD CONSTRAINT FK_FBF166768AF65507 FOREIGN KEY (gibbonHouseID) REFERENCES gibbonHouse (gibbonHouseID)');
        $this->addSql('ALTER TABLE gibbonPerson ADD CONSTRAINT FK_FBF166768AB34571 FOREIGN KEY (gibbonSchoolYearIDClassOf) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonPerson ADD CONSTRAINT FK_FBF16676772C4226 FOREIGN KEY (gibbonApplicationFormID) REFERENCES gibbonApplicationForm (gibbonApplicationFormID)');
        $this->addSql('ALTER TABLE gibbonPerson ADD CONSTRAINT FK_FBF166767E3D96BF FOREIGN KEY (gibbonThemeIDPersonal) REFERENCES gibbonTheme (gibbonThemeID)');
        $this->addSql('ALTER TABLE gibbonPerson ADD CONSTRAINT FK_FBF166764D960E0E FOREIGN KEY (gibboni18nIDPersonal) REFERENCES gibboni18n (gibboni18nID)');
        $this->addSql('ALTER TABLE gibbonPersonMedical ADD CONSTRAINT FK_CD40DFDBCC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalCondition ADD CONSTRAINT FK_9F35C9A765737DEB FOREIGN KEY (gibbonPersonMedicalID) REFERENCES gibbonPersonMedical (gibbonPersonMedicalID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalCondition ADD CONSTRAINT FK_9F35C9A7891EFB5B FOREIGN KEY (gibbonAlertLevelID) REFERENCES gibbonAlertLevel (gibbonAlertLevelID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate ADD CONSTRAINT FK_4E2F6CEC41D19174 FOREIGN KEY (gibbonPersonMedicalUpdateID) REFERENCES gibbonPersonMedicalUpdate (gibbonPersonMedicalUpdateID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate ADD CONSTRAINT FK_4E2F6CEC122DAC35 FOREIGN KEY (gibbonPersonMedicalConditionID) REFERENCES gibbonPersonMedicalCondition (gibbonPersonMedicalConditionID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate ADD CONSTRAINT FK_4E2F6CEC65737DEB FOREIGN KEY (gibbonPersonMedicalID) REFERENCES gibbonPersonMedical (gibbonPersonMedicalID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate ADD CONSTRAINT FK_4E2F6CEC891EFB5B FOREIGN KEY (gibbonAlertLevelID) REFERENCES gibbonAlertLevel (gibbonAlertLevelID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate ADD CONSTRAINT FK_4E2F6CEC71106375 FOREIGN KEY (gibbonPersonIDUpdater) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalSymptoms ADD CONSTRAINT FK_2C6BF3A5CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalSymptoms ADD CONSTRAINT FK_2C6BF3A511A14ED FOREIGN KEY (gibbonPersonIDTaker) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalUpdate ADD CONSTRAINT FK_EEB6690471FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalUpdate ADD CONSTRAINT FK_EEB6690465737DEB FOREIGN KEY (gibbonPersonMedicalID) REFERENCES gibbonPersonMedical (gibbonPersonMedicalID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalUpdate ADD CONSTRAINT FK_EEB66904CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPersonMedicalUpdate ADD CONSTRAINT FK_EEB6690471106375 FOREIGN KEY (gibbonPersonIDUpdater) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPersonReset ADD CONSTRAINT FK_BACD0C68CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPersonUpdate ADD CONSTRAINT FK_D3CBB18C71FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonPersonUpdate ADD CONSTRAINT FK_D3CBB18CCC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPersonUpdate ADD CONSTRAINT FK_D3CBB18C71106375 FOREIGN KEY (gibbonPersonIDUpdater) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntry ADD CONSTRAINT FK_B35E3CEEB67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntry ADD CONSTRAINT FK_B35E3CEE46DE4A3D FOREIGN KEY (gibbonUnitID) REFERENCES gibbonUnit (gibbonUnitID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntry ADD CONSTRAINT FK_B35E3CEEFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntry ADD CONSTRAINT FK_B35E3CEE519966BA FOREIGN KEY (gibbonPersonIDLastEdit) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryDiscuss ADD CONSTRAINT FK_A2D5383EFE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryDiscuss ADD CONSTRAINT FK_A2D5383ECC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryDiscuss ADD CONSTRAINT FK_A2D5383E18B0DB2F FOREIGN KEY (gibbonPlannerEntryDiscussIDReplyTo) REFERENCES gibbonPlannerEntryDiscuss (gibbonPlannerEntryDiscussID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryGuest ADD CONSTRAINT FK_E9A57557FE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryGuest ADD CONSTRAINT FK_E9A57557CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryHomework ADD CONSTRAINT FK_ED6C8A2EFE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryHomework ADD CONSTRAINT FK_ED6C8A2ECC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryOutcome ADD CONSTRAINT FK_57C2DE1CFE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryOutcome ADD CONSTRAINT FK_57C2DE1C35479F6A FOREIGN KEY (gibbonOutcomeID) REFERENCES gibbonOutcome (gibbonOutcomeID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryStudentHomework ADD CONSTRAINT FK_F458CB62FE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryStudentHomework ADD CONSTRAINT FK_F458CB62CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryStudentTracker ADD CONSTRAINT FK_936AA4E7FE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonPlannerEntryStudentTracker ADD CONSTRAINT FK_936AA4E7CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerParentWeeklyEmailSummary ADD CONSTRAINT FK_2E7C18B771FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonPlannerParentWeeklyEmailSummary ADD CONSTRAINT FK_2E7C18B7B27D927 FOREIGN KEY (gibbonPersonIDParent) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonPlannerParentWeeklyEmailSummary ADD CONSTRAINT FK_2E7C18B7F47CEFE0 FOREIGN KEY (gibbonPersonIDStudent) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonResource ADD CONSTRAINT FK_E9941D14CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C4371FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C4333F4D8E2 FOREIGN KEY (gibbonPersonIDTutor) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C4354E2C981 FOREIGN KEY (gibbonPersonIDTutor2) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C4323E5F917 FOREIGN KEY (gibbonPersonIDTutor3) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C4319D8944B FOREIGN KEY (gibbonPersonIDEA) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C43FBC2FE81 FOREIGN KEY (gibbonPersonIDEA2) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C438CC5CE17 FOREIGN KEY (gibbonPersonIDEA3) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C43D8D64BA0 FOREIGN KEY (gibbonSpaceID) REFERENCES gibbonSpace (gibbonSpaceID)');
        $this->addSql('ALTER TABLE gibbonRollGroup ADD CONSTRAINT FK_86CF5C43E06A7FA FOREIGN KEY (gibbonRollGroupIDNext) REFERENCES gibbonRollGroup (gibbonRollGroupID)');
        $this->addSql('ALTER TABLE gibbonRubric ADD CONSTRAINT FK_AFE9B66C6DFE7E92 FOREIGN KEY (gibbonDepartmentID) REFERENCES gibbonDepartment (gibbonDepartmentID)');
        $this->addSql('ALTER TABLE gibbonRubric ADD CONSTRAINT FK_AFE9B66C5F72BC3 FOREIGN KEY (gibbonScaleID) REFERENCES gibbonScale (gibbonScaleID)');
        $this->addSql('ALTER TABLE gibbonRubric ADD CONSTRAINT FK_AFE9B66CFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRubricCell ADD CONSTRAINT FK_12431419FA99FEC1 FOREIGN KEY (gibbonRubricID) REFERENCES gibbonRubric (gibbonRubricID)');
        $this->addSql('ALTER TABLE gibbonRubricCell ADD CONSTRAINT FK_124314192D18B3A7 FOREIGN KEY (gibbonRubricColumnID) REFERENCES gibbonRubricColumn (gibbonRubricColumnID)');
        $this->addSql('ALTER TABLE gibbonRubricCell ADD CONSTRAINT FK_12431419D7743F0 FOREIGN KEY (gibbonRubricRowID) REFERENCES gibbonRubricRow (gibbonRubricRowID)');
        $this->addSql('ALTER TABLE gibbonRubricColumn ADD CONSTRAINT FK_E31DA432FA99FEC1 FOREIGN KEY (gibbonRubricID) REFERENCES gibbonRubric (gibbonRubricID)');
        $this->addSql('ALTER TABLE gibbonRubricColumn ADD CONSTRAINT FK_E31DA4325E440573 FOREIGN KEY (gibbonScaleGradeID) REFERENCES gibbonScaleGrade (gibbonScaleGradeID)');
        $this->addSql('ALTER TABLE gibbonRubricEntry ADD CONSTRAINT FK_1977277FA99FEC1 FOREIGN KEY (gibbonRubricID) REFERENCES gibbonRubric (gibbonRubricID)');
        $this->addSql('ALTER TABLE gibbonRubricEntry ADD CONSTRAINT FK_1977277CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonRubricEntry ADD CONSTRAINT FK_197727790090C1C FOREIGN KEY (gibbonRubricCellID) REFERENCES gibbonRubricCell (gibbonRubricCellID)');
        $this->addSql('ALTER TABLE gibbonRubricRow ADD CONSTRAINT FK_96F9D132FA99FEC1 FOREIGN KEY (gibbonRubricID) REFERENCES gibbonRubric (gibbonRubricID)');
        $this->addSql('ALTER TABLE gibbonRubricRow ADD CONSTRAINT FK_96F9D13235479F6A FOREIGN KEY (gibbonOutcomeID) REFERENCES gibbonOutcome (gibbonOutcomeID)');
        $this->addSql('ALTER TABLE gibbonScaleGrade ADD CONSTRAINT FK_262DE8A75F72BC3 FOREIGN KEY (gibbonScaleID) REFERENCES gibbonScale (gibbonScaleID)');
        $this->addSql('ALTER TABLE gibbonSchoolYearSpecialDay ADD CONSTRAINT FK_EB4E375D88C7C454 FOREIGN KEY (gibbonSchoolYearTermID) REFERENCES gibbonSchoolYearTerm (gibbonSchoolYearTermID)');
        $this->addSql('ALTER TABLE gibbonSchoolYearTerm ADD CONSTRAINT FK_41C4671071FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonSpacePerson ADD CONSTRAINT FK_481C4C89D8D64BA0 FOREIGN KEY (gibbonSpaceID) REFERENCES gibbonSpace (gibbonSpaceID)');
        $this->addSql('ALTER TABLE gibbonSpacePerson ADD CONSTRAINT FK_481C4C89CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaff ADD CONSTRAINT FK_D54C6AA4CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffAbsence ADD CONSTRAINT FK_2FE5FEF78A15C624 FOREIGN KEY (gibbonStaffAbsenceTypeID) REFERENCES gibbonStaffAbsenceType (gibbonStaffAbsenceTypeID)');
        $this->addSql('ALTER TABLE gibbonStaffAbsence ADD CONSTRAINT FK_2FE5FEF771FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonStaffAbsence ADD CONSTRAINT FK_2FE5FEF7CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffAbsence ADD CONSTRAINT FK_2FE5FEF79794905 FOREIGN KEY (gibbonPersonIDApproval) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffAbsence ADD CONSTRAINT FK_2FE5FEF7FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffAbsence ADD CONSTRAINT FK_2FE5FEF7D62085CF FOREIGN KEY (gibbonGroupID) REFERENCES gibbonGroup (gibbonGroupID)');
        $this->addSql('ALTER TABLE gibbonStaffAbsenceDate ADD CONSTRAINT FK_269FD270102BE4BE FOREIGN KEY (gibbonStaffAbsenceID) REFERENCES gibbonStaffAbsence (gibbonStaffAbsenceID)');
        $this->addSql('ALTER TABLE gibbonStaffApplicationForm ADD CONSTRAINT FK_48D734D9B060E48C FOREIGN KEY (gibbonStaffJobOpeningID) REFERENCES gibbonStaffJobOpening (gibbonStaffJobOpeningID)');
        $this->addSql('ALTER TABLE gibbonStaffApplicationForm ADD CONSTRAINT FK_48D734D9CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffApplicationFormFile ADD CONSTRAINT FK_E2EDB80B609DA10E FOREIGN KEY (gibbonStaffApplicationFormID) REFERENCES gibbonStaffApplicationForm (gibbonStaffApplicationFormID)');
        $this->addSql('ALTER TABLE gibbonStaffContract ADD CONSTRAINT FK_28B78AEC76DF47DD FOREIGN KEY (gibbonStaffID) REFERENCES gibbonStaff (gibbonStaffID)');
        $this->addSql('ALTER TABLE gibbonStaffContract ADD CONSTRAINT FK_28B78AECFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffCoverage ADD CONSTRAINT FK_946E51DE102BE4BE FOREIGN KEY (gibbonStaffAbsenceID) REFERENCES gibbonStaffAbsence (gibbonStaffAbsenceID)');
        $this->addSql('ALTER TABLE gibbonStaffCoverage ADD CONSTRAINT FK_946E51DE71FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonStaffCoverage ADD CONSTRAINT FK_946E51DECC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffCoverage ADD CONSTRAINT FK_946E51DE4DA9DC74 FOREIGN KEY (gibbonPersonIDStatus) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffCoverage ADD CONSTRAINT FK_946E51DE4ACF2F45 FOREIGN KEY (gibbonPersonIDCoverage) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffCoverageDate ADD CONSTRAINT FK_AD45031D12047EA7 FOREIGN KEY (gibbonStaffCoverageID) REFERENCES gibbonStaffCoverage (gibbonStaffCoverageID)');
        $this->addSql('ALTER TABLE gibbonStaffCoverageDate ADD CONSTRAINT FK_AD45031D56318FAB FOREIGN KEY (gibbonStaffAbsenceDateID) REFERENCES gibbonStaffAbsenceDate (gibbonStaffAbsenceDateID)');
        $this->addSql('ALTER TABLE gibbonStaffCoverageDate ADD CONSTRAINT FK_AD45031DFED701B3 FOREIGN KEY (gibbonPersonIDUnavailable) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStaffJobOpening ADD CONSTRAINT FK_C9D57E5CFF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment ADD CONSTRAINT FK_ABD4EFFDCC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment ADD CONSTRAINT FK_ABD4EFFD71FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment ADD CONSTRAINT FK_ABD4EFFD427372F FOREIGN KEY (gibbonYearGroupID) REFERENCES gibbonYearGroup (gibbonYearGroupID)');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment ADD CONSTRAINT FK_ABD4EFFDA85AE4EC FOREIGN KEY (gibbonRollGroupID) REFERENCES gibbonRollGroup (gibbonRollGroupID)');
        $this->addSql('ALTER TABLE gibbonStudentNote ADD CONSTRAINT FK_48CB2167CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonStudentNote ADD CONSTRAINT FK_48CB21671E9DC1FF FOREIGN KEY (gibbonStudentNoteCategoryID) REFERENCES gibbonStudentNoteCategory (gibbonStudentNoteCategoryID)');
        $this->addSql('ALTER TABLE gibbonStudentNote ADD CONSTRAINT FK_48CB2167FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonSubstitute ADD CONSTRAINT FK_24133EE2CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonTT ADD CONSTRAINT FK_9431F94371FA7520 FOREIGN KEY (gibbonSchoolYearID) REFERENCES gibbonSchoolYear (gibbonSchoolYearID)');
        $this->addSql('ALTER TABLE gibbonTTColumnRow ADD CONSTRAINT FK_699BB36D9791D118 FOREIGN KEY (gibbonTTColumnID) REFERENCES gibbonTTColumn (gibbonTTColumnID)');
        $this->addSql('ALTER TABLE gibbonTTDay ADD CONSTRAINT FK_3B9106B3EE6A175 FOREIGN KEY (gibbonTTID) REFERENCES gibbonTT (gibbonTTID)');
        $this->addSql('ALTER TABLE gibbonTTDay ADD CONSTRAINT FK_3B9106B39791D118 FOREIGN KEY (gibbonTTColumnID) REFERENCES gibbonTTColumn (gibbonTTColumnID)');
        $this->addSql('ALTER TABLE gibbonTTDayDate ADD CONSTRAINT FK_D8B757FD375800E5 FOREIGN KEY (gibbonTTDayID) REFERENCES gibbonTTDay (gibbonTTDayID)');
        $this->addSql('ALTER TABLE gibbonTTDayRowClass ADD CONSTRAINT FK_C832432E93E8FD7D FOREIGN KEY (gibbonTTColumnRowID) REFERENCES gibbonTTColumnRow (gibbonTTColumnRowID)');
        $this->addSql('ALTER TABLE gibbonTTDayRowClass ADD CONSTRAINT FK_C832432E375800E5 FOREIGN KEY (gibbonTTDayID) REFERENCES gibbonTTDay (gibbonTTDayID)');
        $this->addSql('ALTER TABLE gibbonTTDayRowClass ADD CONSTRAINT FK_C832432EB67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonTTDayRowClass ADD CONSTRAINT FK_C832432ED8D64BA0 FOREIGN KEY (gibbonSpaceID) REFERENCES gibbonSpace (gibbonSpaceID)');
        $this->addSql('ALTER TABLE gibbonTTDayRowClassException ADD CONSTRAINT FK_D25EB853F501B20E FOREIGN KEY (gibbonTTDayRowClassID) REFERENCES gibbonTTDayRowClass (gibbonTTDayRowClassID)');
        $this->addSql('ALTER TABLE gibbonTTDayRowClassException ADD CONSTRAINT FK_D25EB853CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonTTSpaceBooking ADD CONSTRAINT FK_1A34AD71CC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonTTSpaceChange ADD CONSTRAINT FK_772A323EF501B20E FOREIGN KEY (gibbonTTDayRowClassID) REFERENCES gibbonTTDayRowClass (gibbonTTDayRowClassID)');
        $this->addSql('ALTER TABLE gibbonTTSpaceChange ADD CONSTRAINT FK_772A323ED8D64BA0 FOREIGN KEY (gibbonSpaceID) REFERENCES gibbonSpace (gibbonSpaceID)');
        $this->addSql('ALTER TABLE gibbonTTSpaceChange ADD CONSTRAINT FK_772A323ECC6782D6 FOREIGN KEY (gibbonPersonID) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonUnit ADD CONSTRAINT FK_2CFBB258ACDCF59E FOREIGN KEY (gibbonCourseID) REFERENCES gibbonCourse (gibbonCourseID)');
        $this->addSql('ALTER TABLE gibbonUnit ADD CONSTRAINT FK_2CFBB258FF59AAB0 FOREIGN KEY (gibbonPersonIDCreator) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonUnit ADD CONSTRAINT FK_2CFBB258519966BA FOREIGN KEY (gibbonPersonIDLastEdit) REFERENCES gibbonPerson (gibbonPersonID)');
        $this->addSql('ALTER TABLE gibbonUnitBlock ADD CONSTRAINT FK_7D624DA246DE4A3D FOREIGN KEY (gibbonUnitID) REFERENCES gibbonUnit (gibbonUnitID)');
        $this->addSql('ALTER TABLE gibbonUnitClass ADD CONSTRAINT FK_1332C31F46DE4A3D FOREIGN KEY (gibbonUnitID) REFERENCES gibbonUnit (gibbonUnitID)');
        $this->addSql('ALTER TABLE gibbonUnitClass ADD CONSTRAINT FK_1332C31FB67991E FOREIGN KEY (gibbonCourseClassID) REFERENCES gibbonCourseClass (gibbonCourseClassID)');
        $this->addSql('ALTER TABLE gibbonUnitClassBlock ADD CONSTRAINT FK_829289F1DEE4ED9C FOREIGN KEY (gibbonUnitClassID) REFERENCES gibbonUnitClass (gibbonUnitClassID)');
        $this->addSql('ALTER TABLE gibbonUnitClassBlock ADD CONSTRAINT FK_829289F1FE417281 FOREIGN KEY (gibbonPlannerEntryID) REFERENCES gibbonPlannerEntry (gibbonPlannerEntryID)');
        $this->addSql('ALTER TABLE gibbonUnitClassBlock ADD CONSTRAINT FK_829289F1858FFD1E FOREIGN KEY (gibbonUnitBlockID) REFERENCES gibbonUnitBlock (gibbonUnitBlockID)');
        $this->addSql('ALTER TABLE gibbonUnitOutcome ADD CONSTRAINT FK_6D39303A46DE4A3D FOREIGN KEY (gibbonUnitID) REFERENCES gibbonUnit (gibbonUnitID)');
        $this->addSql('ALTER TABLE gibbonUnitOutcome ADD CONSTRAINT FK_6D39303A35479F6A FOREIGN KEY (gibbonOutcomeID) REFERENCES gibbonOutcome (gibbonOutcomeID)');
        $this->addSql('ALTER TABLE gibbonYearGroup ADD CONSTRAINT FK_CD51DC7DB7F9F88C FOREIGN KEY (gibbonPersonIDHOY) REFERENCES gibbonPerson (gibbonPersonID)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gibbonPermission DROP FOREIGN KEY FK_BA9FFF1E3AFEA67');
        $this->addSql('ALTER TABLE gibbonActivityAttendance DROP FOREIGN KEY FK_2357A712C3BBAF6F');
        $this->addSql('ALTER TABLE gibbonActivitySlot DROP FOREIGN KEY FK_59227ABBC3BBAF6F');
        $this->addSql('ALTER TABLE gibbonActivityStaff DROP FOREIGN KEY FK_CDFE4137C3BBAF6F');
        $this->addSql('ALTER TABLE gibbonActivityStudent DROP FOREIGN KEY FK_413CBAAFC3BBAF6F');
        $this->addSql('ALTER TABLE gibbonActivityStudent DROP FOREIGN KEY FK_413CBAAF6A1380D0');
        $this->addSql('ALTER TABLE gibbonAlarmConfirm DROP FOREIGN KEY FK_8E3CE4BCBA3565E5');
        $this->addSql('ALTER TABLE gibbonINPersonDescriptor DROP FOREIGN KEY FK_8D22AA26891EFB5B');
        $this->addSql('ALTER TABLE gibbonPersonMedicalCondition DROP FOREIGN KEY FK_9F35C9A7891EFB5B');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate DROP FOREIGN KEY FK_4E2F6CEC891EFB5B');
        $this->addSql('ALTER TABLE gibbonApplicationFormFile DROP FOREIGN KEY FK_86B3B2D2772C4226');
        $this->addSql('ALTER TABLE gibbonApplicationFormLink DROP FOREIGN KEY FK_3C801D3351A64608');
        $this->addSql('ALTER TABLE gibbonApplicationFormLink DROP FOREIGN KEY FK_3C801D33C8AF17B2');
        $this->addSql('ALTER TABLE gibbonApplicationFormRelationship DROP FOREIGN KEY FK_5E0017E7772C4226');
        $this->addSql('ALTER TABLE gibbonPerson DROP FOREIGN KEY FK_FBF16676772C4226');
        $this->addSql('ALTER TABLE gibbonAttendanceLogPerson DROP FOREIGN KEY FK_1FC495175772A3E4');
        $this->addSql('ALTER TABLE gibbonCourseClass DROP FOREIGN KEY FK_455FF397ACDCF59E');
        $this->addSql('ALTER TABLE gibbonUnit DROP FOREIGN KEY FK_2CFBB258ACDCF59E');
        $this->addSql('ALTER TABLE gibbonAttendanceLogCourseClass DROP FOREIGN KEY FK_6D6C05B0B67991E');
        $this->addSql('ALTER TABLE gibbonAttendanceLogPerson DROP FOREIGN KEY FK_1FC49517B67991E');
        $this->addSql('ALTER TABLE gibbonCourseClassMap DROP FOREIGN KEY FK_97F9BC70B67991E');
        $this->addSql('ALTER TABLE gibbonCourseClassPerson DROP FOREIGN KEY FK_D9B888E9B67991E');
        $this->addSql('ALTER TABLE gibbonFirstAid DROP FOREIGN KEY FK_ABF00527B67991E');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn DROP FOREIGN KEY FK_E0A1D88AB67991E');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806EB67991E');
        $this->addSql('ALTER TABLE gibbonMarkbookTarget DROP FOREIGN KEY FK_916B28ECB67991E');
        $this->addSql('ALTER TABLE gibbonMarkbookWeight DROP FOREIGN KEY FK_D0C95251B67991E');
        $this->addSql('ALTER TABLE gibbonPlannerEntry DROP FOREIGN KEY FK_B35E3CEEB67991E');
        $this->addSql('ALTER TABLE gibbonTTDayRowClass DROP FOREIGN KEY FK_C832432EB67991E');
        $this->addSql('ALTER TABLE gibbonUnitClass DROP FOREIGN KEY FK_1332C31FB67991E');
        $this->addSql('ALTER TABLE gibbonCrowdAssessDiscuss DROP FOREIGN KEY FK_D17E7086D96E9809');
        $this->addSql('ALTER TABLE gibbonActivitySlot DROP FOREIGN KEY FK_59227ABB2817C0E1');
        $this->addSql('ALTER TABLE gibbonCourse DROP FOREIGN KEY FK_D9B3D8B96DFE7E92');
        $this->addSql('ALTER TABLE gibbonDepartmentResource DROP FOREIGN KEY FK_276BB0276DFE7E92');
        $this->addSql('ALTER TABLE gibbonDepartmentStaff DROP FOREIGN KEY FK_EE77E05B6DFE7E92');
        $this->addSql('ALTER TABLE gibbonOutcome DROP FOREIGN KEY FK_307340756DFE7E92');
        $this->addSql('ALTER TABLE gibbonRubric DROP FOREIGN KEY FK_AFE9B66C6DFE7E92');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentField DROP FOREIGN KEY FK_A03EECA92660493E');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudent DROP FOREIGN KEY FK_158E9F482660493E');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudentEntry DROP FOREIGN KEY FK_BB5E8090C7AA6AF7');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudentEntry DROP FOREIGN KEY FK_BB5E809049C33D6C');
        $this->addSql('ALTER TABLE gibbonApplicationForm DROP FOREIGN KEY FK_A309B59C51F0BB1F');
        $this->addSql('ALTER TABLE gibbonFamilyAdult DROP FOREIGN KEY FK_EEF67AB51F0BB1F');
        $this->addSql('ALTER TABLE gibbonFamilyChild DROP FOREIGN KEY FK_3672C10351F0BB1F');
        $this->addSql('ALTER TABLE gibbonFamilyRelationship DROP FOREIGN KEY FK_D6CA42151F0BB1F');
        $this->addSql('ALTER TABLE gibbonFamilyUpdate DROP FOREIGN KEY FK_438A3D3B51F0BB1F');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice DROP FOREIGN KEY FK_B92155176F4C4787');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetCycleAllocation DROP FOREIGN KEY FK_B27D799BC8A9346');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetPerson DROP FOREIGN KEY FK_AF223270C8A9346');
        $this->addSql('ALTER TABLE gibbonFinanceExpense DROP FOREIGN KEY FK_47ECFF5C8A9346');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetCycleAllocation DROP FOREIGN KEY FK_B27D799B5393B3F1');
        $this->addSql('ALTER TABLE gibbonFinanceExpense DROP FOREIGN KEY FK_47ECFF55393B3F1');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseLog DROP FOREIGN KEY FK_FFA208A073C3AD9D');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceFee DROP FOREIGN KEY FK_3CC82E569B02DC4A');
        $this->addSql('ALTER TABLE gibbonFinanceFee DROP FOREIGN KEY FK_7D222CFCB05DE109');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceFee DROP FOREIGN KEY FK_3CC82E56B05DE109');
        $this->addSql('ALTER TABLE gibbonActivityStudent DROP FOREIGN KEY FK_413CBAAFF51FBF6');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceFee DROP FOREIGN KEY FK_3CC82E56F51FBF6');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice DROP FOREIGN KEY FK_B9215517739BAD1F');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceeUpdate DROP FOREIGN KEY FK_8481171739BAD1F');
        $this->addSql('ALTER TABLE gibbonGroupPerson DROP FOREIGN KEY FK_15367BAAD62085CF');
        $this->addSql('ALTER TABLE gibbonStaffAbsence DROP FOREIGN KEY FK_2FE5FEF7D62085CF');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806EF6E7C959');
        $this->addSql('ALTER TABLE gibbonPerson DROP FOREIGN KEY FK_FBF166768AF65507');
        $this->addSql('ALTER TABLE gibbonPerson DROP FOREIGN KEY FK_FBF166764D960E0E');
        $this->addSql('ALTER TABLE gibbonINPersonDescriptor DROP FOREIGN KEY FK_8D22AA26789947CD');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentEntry DROP FOREIGN KEY FK_B09C6F558B7A9BC');
        $this->addSql('ALTER TABLE gibbonMarkbookEntry DROP FOREIGN KEY FK_22F463917150C64');
        $this->addSql('ALTER TABLE gibbonMessengerReceipt DROP FOREIGN KEY FK_30BB77081B4FC86A');
        $this->addSql('ALTER TABLE gibbonMessengerTarget DROP FOREIGN KEY FK_62C2BBE11B4FC86A');
        $this->addSql('ALTER TABLE gibbonAction DROP FOREIGN KEY FK_88E13B92CB86AD4B');
        $this->addSql('ALTER TABLE gibbonHook DROP FOREIGN KEY FK_5418FD5ECB86AD4B');
        $this->addSql('ALTER TABLE gibbonLog DROP FOREIGN KEY FK_C0122755CB86AD4B');
        $this->addSql('ALTER TABLE gibbonNotification DROP FOREIGN KEY FK_D5180450CB86AD4B');
        $this->addSql('ALTER TABLE gibbonNotificationListener DROP FOREIGN KEY FK_6313F17E26A39C71');
        $this->addSql('ALTER TABLE gibbonPlannerEntryOutcome DROP FOREIGN KEY FK_57C2DE1C35479F6A');
        $this->addSql('ALTER TABLE gibbonRubricRow DROP FOREIGN KEY FK_96F9D13235479F6A');
        $this->addSql('ALTER TABLE gibbonUnitOutcome DROP FOREIGN KEY FK_6D39303A35479F6A');
        $this->addSql('ALTER TABLE gibbonApplicationForm DROP FOREIGN KEY FK_A309B59CA0F353A3');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice DROP FOREIGN KEY FK_B9215517A0F353A3');
        $this->addSql('ALTER TABLE gibbonActivityAttendance DROP FOREIGN KEY FK_2357A71211A14ED');
        $this->addSql('ALTER TABLE gibbonActivityStaff DROP FOREIGN KEY FK_CDFE4137CC6782D6');
        $this->addSql('ALTER TABLE gibbonActivityStudent DROP FOREIGN KEY FK_413CBAAFCC6782D6');
        $this->addSql('ALTER TABLE gibbonAlarm DROP FOREIGN KEY FK_E3BDDFEBCC6782D6');
        $this->addSql('ALTER TABLE gibbonAlarmConfirm DROP FOREIGN KEY FK_8E3CE4BCCC6782D6');
        $this->addSql('ALTER TABLE gibbonApplicationForm DROP FOREIGN KEY FK_A309B59C7DF7AB4B');
        $this->addSql('ALTER TABLE gibbonApplicationFormRelationship DROP FOREIGN KEY FK_5E0017E7CC6782D6');
        $this->addSql('ALTER TABLE gibbonAttendanceLogCourseClass DROP FOREIGN KEY FK_6D6C05B011A14ED');
        $this->addSql('ALTER TABLE gibbonAttendanceLogPerson DROP FOREIGN KEY FK_1FC49517CC6782D6');
        $this->addSql('ALTER TABLE gibbonAttendanceLogPerson DROP FOREIGN KEY FK_1FC4951711A14ED');
        $this->addSql('ALTER TABLE gibbonAttendanceLogRollGroup DROP FOREIGN KEY FK_A6F88BED11A14ED');
        $this->addSql('ALTER TABLE gibbonBehaviour DROP FOREIGN KEY FK_64915B3CC6782D6');
        $this->addSql('ALTER TABLE gibbonBehaviour DROP FOREIGN KEY FK_64915B3FF59AAB0');
        $this->addSql('ALTER TABLE gibbonBehaviourLetter DROP FOREIGN KEY FK_5F61F910CC6782D6');
        $this->addSql('ALTER TABLE gibbonCourseClassPerson DROP FOREIGN KEY FK_D9B888E9CC6782D6');
        $this->addSql('ALTER TABLE gibbonCrowdAssessDiscuss DROP FOREIGN KEY FK_D17E7086CC6782D6');
        $this->addSql('ALTER TABLE gibbonDepartmentStaff DROP FOREIGN KEY FK_EE77E05BCC6782D6');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudent DROP FOREIGN KEY FK_158E9F48CC6782D6');
        $this->addSql('ALTER TABLE gibbonFamilyAdult DROP FOREIGN KEY FK_EEF67ABCC6782D6');
        $this->addSql('ALTER TABLE gibbonFamilyChild DROP FOREIGN KEY FK_3672C103CC6782D6');
        $this->addSql('ALTER TABLE gibbonFamilyRelationship DROP FOREIGN KEY FK_D6CA421ECA0FFD4');
        $this->addSql('ALTER TABLE gibbonFamilyRelationship DROP FOREIGN KEY FK_D6CA42175A9AE6E');
        $this->addSql('ALTER TABLE gibbonFamilyUpdate DROP FOREIGN KEY FK_438A3D3B71106375');
        $this->addSql('ALTER TABLE gibbonFinanceBillingSchedule DROP FOREIGN KEY FK_EC0D8C7DFF59AAB0');
        $this->addSql('ALTER TABLE gibbonFinanceBillingSchedule DROP FOREIGN KEY FK_EC0D8C7DAE8C8C10');
        $this->addSql('ALTER TABLE gibbonFinanceBudget DROP FOREIGN KEY FK_EE793C02FF59AAB0');
        $this->addSql('ALTER TABLE gibbonFinanceBudget DROP FOREIGN KEY FK_EE793C02AE8C8C10');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetCycle DROP FOREIGN KEY FK_2AA76753FF59AAB0');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetCycle DROP FOREIGN KEY FK_2AA76753AE8C8C10');
        $this->addSql('ALTER TABLE gibbonFinanceBudgetPerson DROP FOREIGN KEY FK_AF223270CC6782D6');
        $this->addSql('ALTER TABLE gibbonFinanceExpense DROP FOREIGN KEY FK_47ECFF52E77C4DE');
        $this->addSql('ALTER TABLE gibbonFinanceExpense DROP FOREIGN KEY FK_47ECFF5FF59AAB0');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseApprover DROP FOREIGN KEY FK_38833027CC6782D6');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseApprover DROP FOREIGN KEY FK_38833027FF59AAB0');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseApprover DROP FOREIGN KEY FK_38833027AE8C8C10');
        $this->addSql('ALTER TABLE gibbonFinanceExpenseLog DROP FOREIGN KEY FK_FFA208A0CC6782D6');
        $this->addSql('ALTER TABLE gibbonFinanceFee DROP FOREIGN KEY FK_7D222CFCFF59AAB0');
        $this->addSql('ALTER TABLE gibbonFinanceFee DROP FOREIGN KEY FK_7D222CFCAE8C8C10');
        $this->addSql('ALTER TABLE gibbonFinanceFeeCategory DROP FOREIGN KEY FK_14C5A939FF59AAB0');
        $this->addSql('ALTER TABLE gibbonFinanceFeeCategory DROP FOREIGN KEY FK_14C5A939AE8C8C10');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice DROP FOREIGN KEY FK_B9215517FF59AAB0');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice DROP FOREIGN KEY FK_B9215517AE8C8C10');
        $this->addSql('ALTER TABLE gibbonFinanceInvoicee DROP FOREIGN KEY FK_6CB0DEC8CC6782D6');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceeUpdate DROP FOREIGN KEY FK_848117171106375');
        $this->addSql('ALTER TABLE gibbonFirstAid DROP FOREIGN KEY FK_ABF0052759859738');
        $this->addSql('ALTER TABLE gibbonFirstAid DROP FOREIGN KEY FK_ABF0052722759506');
        $this->addSql('ALTER TABLE gibbonGroup DROP FOREIGN KEY FK_FAE2DDF3659378D6');
        $this->addSql('ALTER TABLE gibbonGroupPerson DROP FOREIGN KEY FK_15367BAACC6782D6');
        $this->addSql('ALTER TABLE gibbonINArchive DROP FOREIGN KEY FK_43A82C35CC6782D6');
        $this->addSql('ALTER TABLE gibbonINAssistant DROP FOREIGN KEY FK_9A5EAC8FF47CEFE0');
        $this->addSql('ALTER TABLE gibbonINAssistant DROP FOREIGN KEY FK_9A5EAC8F1E50E8C2');
        $this->addSql('ALTER TABLE gibbonIN DROP FOREIGN KEY FK_963F6C25CC6782D6');
        $this->addSql('ALTER TABLE gibbonINPersonDescriptor DROP FOREIGN KEY FK_8D22AA26CC6782D6');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn DROP FOREIGN KEY FK_E0A1D88AFF59AAB0');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn DROP FOREIGN KEY FK_E0A1D88A519966BA');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentEntry DROP FOREIGN KEY FK_B09C6F5F47CEFE0');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentEntry DROP FOREIGN KEY FK_B09C6F5519966BA');
        $this->addSql('ALTER TABLE gibbonLog DROP FOREIGN KEY FK_C0122755CC6782D6');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806EFF59AAB0');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806E519966BA');
        $this->addSql('ALTER TABLE gibbonMarkbookEntry DROP FOREIGN KEY FK_22F46391F47CEFE0');
        $this->addSql('ALTER TABLE gibbonMarkbookEntry DROP FOREIGN KEY FK_22F46391519966BA');
        $this->addSql('ALTER TABLE gibbonMarkbookTarget DROP FOREIGN KEY FK_916B28ECF47CEFE0');
        $this->addSql('ALTER TABLE gibbonMessenger DROP FOREIGN KEY FK_C7127C4CC6782D6');
        $this->addSql('ALTER TABLE gibbonMessengerCannedResponse DROP FOREIGN KEY FK_C83786BFFF59AAB0');
        $this->addSql('ALTER TABLE gibbonMessengerReceipt DROP FOREIGN KEY FK_30BB7708CC6782D6');
        $this->addSql('ALTER TABLE gibbonNotification DROP FOREIGN KEY FK_D5180450CC6782D6');
        $this->addSql('ALTER TABLE gibbonNotificationListener DROP FOREIGN KEY FK_6313F17ECC6782D6');
        $this->addSql('ALTER TABLE gibbonOutcome DROP FOREIGN KEY FK_30734075FF59AAB0');
        $this->addSql('ALTER TABLE gibbonPayment DROP FOREIGN KEY FK_6DE7A9BACC6782D6');
        $this->addSql('ALTER TABLE gibbonPersonMedical DROP FOREIGN KEY FK_CD40DFDBCC6782D6');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate DROP FOREIGN KEY FK_4E2F6CEC71106375');
        $this->addSql('ALTER TABLE gibbonPersonMedicalSymptoms DROP FOREIGN KEY FK_2C6BF3A5CC6782D6');
        $this->addSql('ALTER TABLE gibbonPersonMedicalSymptoms DROP FOREIGN KEY FK_2C6BF3A511A14ED');
        $this->addSql('ALTER TABLE gibbonPersonMedicalUpdate DROP FOREIGN KEY FK_EEB66904CC6782D6');
        $this->addSql('ALTER TABLE gibbonPersonMedicalUpdate DROP FOREIGN KEY FK_EEB6690471106375');
        $this->addSql('ALTER TABLE gibbonPersonReset DROP FOREIGN KEY FK_BACD0C68CC6782D6');
        $this->addSql('ALTER TABLE gibbonPersonUpdate DROP FOREIGN KEY FK_D3CBB18CCC6782D6');
        $this->addSql('ALTER TABLE gibbonPersonUpdate DROP FOREIGN KEY FK_D3CBB18C71106375');
        $this->addSql('ALTER TABLE gibbonPlannerEntry DROP FOREIGN KEY FK_B35E3CEEFF59AAB0');
        $this->addSql('ALTER TABLE gibbonPlannerEntry DROP FOREIGN KEY FK_B35E3CEE519966BA');
        $this->addSql('ALTER TABLE gibbonPlannerEntryDiscuss DROP FOREIGN KEY FK_A2D5383ECC6782D6');
        $this->addSql('ALTER TABLE gibbonPlannerEntryGuest DROP FOREIGN KEY FK_E9A57557CC6782D6');
        $this->addSql('ALTER TABLE gibbonPlannerEntryHomework DROP FOREIGN KEY FK_ED6C8A2ECC6782D6');
        $this->addSql('ALTER TABLE gibbonPlannerEntryStudentHomework DROP FOREIGN KEY FK_F458CB62CC6782D6');
        $this->addSql('ALTER TABLE gibbonPlannerEntryStudentTracker DROP FOREIGN KEY FK_936AA4E7CC6782D6');
        $this->addSql('ALTER TABLE gibbonPlannerParentWeeklyEmailSummary DROP FOREIGN KEY FK_2E7C18B7B27D927');
        $this->addSql('ALTER TABLE gibbonPlannerParentWeeklyEmailSummary DROP FOREIGN KEY FK_2E7C18B7F47CEFE0');
        $this->addSql('ALTER TABLE gibbonResource DROP FOREIGN KEY FK_E9941D14CC6782D6');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C4333F4D8E2');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C4354E2C981');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C4323E5F917');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C4319D8944B');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C43FBC2FE81');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C438CC5CE17');
        $this->addSql('ALTER TABLE gibbonRubric DROP FOREIGN KEY FK_AFE9B66CFF59AAB0');
        $this->addSql('ALTER TABLE gibbonRubricEntry DROP FOREIGN KEY FK_1977277CC6782D6');
        $this->addSql('ALTER TABLE gibbonSpacePerson DROP FOREIGN KEY FK_481C4C89CC6782D6');
        $this->addSql('ALTER TABLE gibbonStaff DROP FOREIGN KEY FK_D54C6AA4CC6782D6');
        $this->addSql('ALTER TABLE gibbonStaffAbsence DROP FOREIGN KEY FK_2FE5FEF7CC6782D6');
        $this->addSql('ALTER TABLE gibbonStaffAbsence DROP FOREIGN KEY FK_2FE5FEF79794905');
        $this->addSql('ALTER TABLE gibbonStaffAbsence DROP FOREIGN KEY FK_2FE5FEF7FF59AAB0');
        $this->addSql('ALTER TABLE gibbonStaffApplicationForm DROP FOREIGN KEY FK_48D734D9CC6782D6');
        $this->addSql('ALTER TABLE gibbonStaffContract DROP FOREIGN KEY FK_28B78AECFF59AAB0');
        $this->addSql('ALTER TABLE gibbonStaffCoverage DROP FOREIGN KEY FK_946E51DECC6782D6');
        $this->addSql('ALTER TABLE gibbonStaffCoverage DROP FOREIGN KEY FK_946E51DE4DA9DC74');
        $this->addSql('ALTER TABLE gibbonStaffCoverage DROP FOREIGN KEY FK_946E51DE4ACF2F45');
        $this->addSql('ALTER TABLE gibbonStaffCoverageDate DROP FOREIGN KEY FK_AD45031DFED701B3');
        $this->addSql('ALTER TABLE gibbonStaffJobOpening DROP FOREIGN KEY FK_C9D57E5CFF59AAB0');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment DROP FOREIGN KEY FK_ABD4EFFDCC6782D6');
        $this->addSql('ALTER TABLE gibbonStudentNote DROP FOREIGN KEY FK_48CB2167CC6782D6');
        $this->addSql('ALTER TABLE gibbonStudentNote DROP FOREIGN KEY FK_48CB2167FF59AAB0');
        $this->addSql('ALTER TABLE gibbonSubstitute DROP FOREIGN KEY FK_24133EE2CC6782D6');
        $this->addSql('ALTER TABLE gibbonTTDayRowClassException DROP FOREIGN KEY FK_D25EB853CC6782D6');
        $this->addSql('ALTER TABLE gibbonTTSpaceBooking DROP FOREIGN KEY FK_1A34AD71CC6782D6');
        $this->addSql('ALTER TABLE gibbonTTSpaceChange DROP FOREIGN KEY FK_772A323ECC6782D6');
        $this->addSql('ALTER TABLE gibbonUnit DROP FOREIGN KEY FK_2CFBB258FF59AAB0');
        $this->addSql('ALTER TABLE gibbonUnit DROP FOREIGN KEY FK_2CFBB258519966BA');
        $this->addSql('ALTER TABLE gibbonYearGroup DROP FOREIGN KEY FK_CD51DC7DB7F9F88C');
        $this->addSql('ALTER TABLE gibbonPersonMedicalCondition DROP FOREIGN KEY FK_9F35C9A765737DEB');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate DROP FOREIGN KEY FK_4E2F6CEC65737DEB');
        $this->addSql('ALTER TABLE gibbonPersonMedicalUpdate DROP FOREIGN KEY FK_EEB6690465737DEB');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate DROP FOREIGN KEY FK_4E2F6CEC122DAC35');
        $this->addSql('ALTER TABLE gibbonPersonMedicalConditionUpdate DROP FOREIGN KEY FK_4E2F6CEC41D19174');
        $this->addSql('ALTER TABLE gibbonBehaviour DROP FOREIGN KEY FK_64915B3FE417281');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806EFE417281');
        $this->addSql('ALTER TABLE gibbonPlannerEntryDiscuss DROP FOREIGN KEY FK_A2D5383EFE417281');
        $this->addSql('ALTER TABLE gibbonPlannerEntryGuest DROP FOREIGN KEY FK_E9A57557FE417281');
        $this->addSql('ALTER TABLE gibbonPlannerEntryHomework DROP FOREIGN KEY FK_ED6C8A2EFE417281');
        $this->addSql('ALTER TABLE gibbonPlannerEntryOutcome DROP FOREIGN KEY FK_57C2DE1CFE417281');
        $this->addSql('ALTER TABLE gibbonPlannerEntryStudentHomework DROP FOREIGN KEY FK_F458CB62FE417281');
        $this->addSql('ALTER TABLE gibbonPlannerEntryStudentTracker DROP FOREIGN KEY FK_936AA4E7FE417281');
        $this->addSql('ALTER TABLE gibbonUnitClassBlock DROP FOREIGN KEY FK_829289F1FE417281');
        $this->addSql('ALTER TABLE gibbonPlannerEntryDiscuss DROP FOREIGN KEY FK_A2D5383E18B0DB2F');
        $this->addSql('ALTER TABLE gibbonCrowdAssessDiscuss DROP FOREIGN KEY FK_D17E708617B9ED44');
        $this->addSql('ALTER TABLE gibbonPermission DROP FOREIGN KEY FK_BA9FFF14C816A40');
        $this->addSql('ALTER TABLE gibbonPerson DROP FOREIGN KEY FK_FBF1667668D8F4F8');
        $this->addSql('ALTER TABLE gibbonApplicationForm DROP FOREIGN KEY FK_A309B59CA85AE4EC');
        $this->addSql('ALTER TABLE gibbonAttendanceLogRollGroup DROP FOREIGN KEY FK_A6F88BEDA85AE4EC');
        $this->addSql('ALTER TABLE gibbonCourseClassMap DROP FOREIGN KEY FK_97F9BC70A85AE4EC');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C43E06A7FA');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment DROP FOREIGN KEY FK_ABD4EFFDA85AE4EC');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806E2151BB77');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806EBA294907');
        $this->addSql('ALTER TABLE gibbonRubricCell DROP FOREIGN KEY FK_12431419FA99FEC1');
        $this->addSql('ALTER TABLE gibbonRubricColumn DROP FOREIGN KEY FK_E31DA432FA99FEC1');
        $this->addSql('ALTER TABLE gibbonRubricEntry DROP FOREIGN KEY FK_1977277FA99FEC1');
        $this->addSql('ALTER TABLE gibbonRubricRow DROP FOREIGN KEY FK_96F9D132FA99FEC1');
        $this->addSql('ALTER TABLE gibbonRubricEntry DROP FOREIGN KEY FK_197727790090C1C');
        $this->addSql('ALTER TABLE gibbonRubricCell DROP FOREIGN KEY FK_124314192D18B3A7');
        $this->addSql('ALTER TABLE gibbonRubricCell DROP FOREIGN KEY FK_12431419D7743F0');
        $this->addSql('ALTER TABLE gibbonCourseClass DROP FOREIGN KEY FK_455FF3977DD4B430');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentField DROP FOREIGN KEY FK_A03EECA95F72BC3');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn DROP FOREIGN KEY FK_E0A1D88A2C639785');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn DROP FOREIGN KEY FK_E0A1D88AD395ACF8');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806E2C639785');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806ED395ACF8');
        $this->addSql('ALTER TABLE gibbonRubric DROP FOREIGN KEY FK_AFE9B66C5F72BC3');
        $this->addSql('ALTER TABLE gibbonScaleGrade DROP FOREIGN KEY FK_262DE8A75F72BC3');
        $this->addSql('ALTER TABLE gibbonExternalAssessmentStudentEntry DROP FOREIGN KEY FK_BB5E80905E440573');
        $this->addSql('ALTER TABLE gibbonMarkbookTarget DROP FOREIGN KEY FK_916B28EC5E440573');
        $this->addSql('ALTER TABLE gibbonRubricColumn DROP FOREIGN KEY FK_E31DA4325E440573');
        $this->addSql('ALTER TABLE gibbonActivity DROP FOREIGN KEY FK_F971E05871FA7520');
        $this->addSql('ALTER TABLE gibbonApplicationForm DROP FOREIGN KEY FK_A309B59CF9B7736F');
        $this->addSql('ALTER TABLE gibbonBehaviour DROP FOREIGN KEY FK_64915B371FA7520');
        $this->addSql('ALTER TABLE gibbonBehaviourLetter DROP FOREIGN KEY FK_5F61F91071FA7520');
        $this->addSql('ALTER TABLE gibbonCourse DROP FOREIGN KEY FK_D9B3D8B971FA7520');
        $this->addSql('ALTER TABLE gibbonFamilyUpdate DROP FOREIGN KEY FK_438A3D3B71FA7520');
        $this->addSql('ALTER TABLE gibbonFinanceBillingSchedule DROP FOREIGN KEY FK_EC0D8C7D71FA7520');
        $this->addSql('ALTER TABLE gibbonFinanceFee DROP FOREIGN KEY FK_7D222CFC71FA7520');
        $this->addSql('ALTER TABLE gibbonFinanceInvoice DROP FOREIGN KEY FK_B921551771FA7520');
        $this->addSql('ALTER TABLE gibbonFinanceInvoiceeUpdate DROP FOREIGN KEY FK_848117171FA7520');
        $this->addSql('ALTER TABLE gibbonFirstAid DROP FOREIGN KEY FK_ABF0052771FA7520');
        $this->addSql('ALTER TABLE gibbonGroup DROP FOREIGN KEY FK_FAE2DDF371FA7520');
        $this->addSql('ALTER TABLE gibbonLog DROP FOREIGN KEY FK_C012275571FA7520');
        $this->addSql('ALTER TABLE gibbonPerson DROP FOREIGN KEY FK_FBF166768AB34571');
        $this->addSql('ALTER TABLE gibbonPersonMedicalUpdate DROP FOREIGN KEY FK_EEB6690471FA7520');
        $this->addSql('ALTER TABLE gibbonPersonUpdate DROP FOREIGN KEY FK_D3CBB18C71FA7520');
        $this->addSql('ALTER TABLE gibbonPlannerParentWeeklyEmailSummary DROP FOREIGN KEY FK_2E7C18B771FA7520');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C4371FA7520');
        $this->addSql('ALTER TABLE gibbonSchoolYearTerm DROP FOREIGN KEY FK_41C4671071FA7520');
        $this->addSql('ALTER TABLE gibbonStaffAbsence DROP FOREIGN KEY FK_2FE5FEF771FA7520');
        $this->addSql('ALTER TABLE gibbonStaffCoverage DROP FOREIGN KEY FK_946E51DE71FA7520');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment DROP FOREIGN KEY FK_ABD4EFFD71FA7520');
        $this->addSql('ALTER TABLE gibbonTT DROP FOREIGN KEY FK_9431F94371FA7520');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806E88C7C454');
        $this->addSql('ALTER TABLE gibbonSchoolYearSpecialDay DROP FOREIGN KEY FK_EB4E375D88C7C454');
        $this->addSql('ALTER TABLE gibbonActivitySlot DROP FOREIGN KEY FK_59227ABBD8D64BA0');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C43D8D64BA0');
        $this->addSql('ALTER TABLE gibbonSpacePerson DROP FOREIGN KEY FK_481C4C89D8D64BA0');
        $this->addSql('ALTER TABLE gibbonTTDayRowClass DROP FOREIGN KEY FK_C832432ED8D64BA0');
        $this->addSql('ALTER TABLE gibbonTTSpaceChange DROP FOREIGN KEY FK_772A323ED8D64BA0');
        $this->addSql('ALTER TABLE gibbonStaffContract DROP FOREIGN KEY FK_28B78AEC76DF47DD');
        $this->addSql('ALTER TABLE gibbonStaffAbsenceDate DROP FOREIGN KEY FK_269FD270102BE4BE');
        $this->addSql('ALTER TABLE gibbonStaffCoverage DROP FOREIGN KEY FK_946E51DE102BE4BE');
        $this->addSql('ALTER TABLE gibbonStaffCoverageDate DROP FOREIGN KEY FK_AD45031D56318FAB');
        $this->addSql('ALTER TABLE gibbonStaffAbsence DROP FOREIGN KEY FK_2FE5FEF78A15C624');
        $this->addSql('ALTER TABLE gibbonStaffApplicationFormFile DROP FOREIGN KEY FK_E2EDB80B609DA10E');
        $this->addSql('ALTER TABLE gibbonStaffCoverageDate DROP FOREIGN KEY FK_AD45031D12047EA7');
        $this->addSql('ALTER TABLE gibbonStaffApplicationForm DROP FOREIGN KEY FK_48D734D9B060E48C');
        $this->addSql('ALTER TABLE gibbonStudentNote DROP FOREIGN KEY FK_48CB21671E9DC1FF');
        $this->addSql('ALTER TABLE gibbonPerson DROP FOREIGN KEY FK_FBF166767E3D96BF');
        $this->addSql('ALTER TABLE gibbonTTDay DROP FOREIGN KEY FK_3B9106B3EE6A175');
        $this->addSql('ALTER TABLE gibbonTTColumnRow DROP FOREIGN KEY FK_699BB36D9791D118');
        $this->addSql('ALTER TABLE gibbonTTDay DROP FOREIGN KEY FK_3B9106B39791D118');
        $this->addSql('ALTER TABLE gibbonTTDayRowClass DROP FOREIGN KEY FK_C832432E93E8FD7D');
        $this->addSql('ALTER TABLE gibbonTTDayDate DROP FOREIGN KEY FK_D8B757FD375800E5');
        $this->addSql('ALTER TABLE gibbonTTDayRowClass DROP FOREIGN KEY FK_C832432E375800E5');
        $this->addSql('ALTER TABLE gibbonTTDayRowClassException DROP FOREIGN KEY FK_D25EB853F501B20E');
        $this->addSql('ALTER TABLE gibbonTTSpaceChange DROP FOREIGN KEY FK_772A323EF501B20E');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806E46DE4A3D');
        $this->addSql('ALTER TABLE gibbonPlannerEntry DROP FOREIGN KEY FK_B35E3CEE46DE4A3D');
        $this->addSql('ALTER TABLE gibbonUnitBlock DROP FOREIGN KEY FK_7D624DA246DE4A3D');
        $this->addSql('ALTER TABLE gibbonUnitClass DROP FOREIGN KEY FK_1332C31F46DE4A3D');
        $this->addSql('ALTER TABLE gibbonUnitOutcome DROP FOREIGN KEY FK_6D39303A46DE4A3D');
        $this->addSql('ALTER TABLE gibbonUnitClassBlock DROP FOREIGN KEY FK_829289F1858FFD1E');
        $this->addSql('ALTER TABLE gibbonUnitClassBlock DROP FOREIGN KEY FK_829289F1DEE4ED9C');
        $this->addSql('ALTER TABLE gibbonApplicationForm DROP FOREIGN KEY FK_A309B59C9DE35FD8');
        $this->addSql('ALTER TABLE gibbonCourseClassMap DROP FOREIGN KEY FK_97F9BC70427372F');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment DROP FOREIGN KEY FK_ABD4EFFD427372F');
        $this->addSql('DROP TABLE gibbonAction');
        $this->addSql('DROP TABLE gibbonActivity');
        $this->addSql('DROP TABLE gibbonActivityAttendance');
        $this->addSql('DROP TABLE gibbonActivitySlot');
        $this->addSql('DROP TABLE gibbonActivityStaff');
        $this->addSql('DROP TABLE gibbonActivityStudent');
        $this->addSql('DROP TABLE gibbonAlarm');
        $this->addSql('DROP TABLE gibbonAlarmConfirm');
        $this->addSql('DROP TABLE gibbonAlertLevel');
        $this->addSql('DROP TABLE gibbonApplicationForm');
        $this->addSql('DROP TABLE gibbonApplicationFormFile');
        $this->addSql('DROP TABLE gibbonApplicationFormLink');
        $this->addSql('DROP TABLE gibbonApplicationFormRelationship');
        $this->addSql('DROP TABLE gibbonAttendanceCode');
        $this->addSql('DROP TABLE gibbonAttendanceLogCourseClass');
        $this->addSql('DROP TABLE gibbonAttendanceLogPerson');
        $this->addSql('DROP TABLE gibbonAttendanceLogRollGroup');
        $this->addSql('DROP TABLE gibbonBehaviour');
        $this->addSql('DROP TABLE gibbonBehaviourLetter');
        $this->addSql('DROP TABLE gibbonCountry');
        $this->addSql('DROP TABLE gibbonCourse');
        $this->addSql('DROP TABLE gibbonCourseClass');
        $this->addSql('DROP TABLE gibbonCourseClassMap');
        $this->addSql('DROP TABLE gibbonCourseClassPerson');
        $this->addSql('DROP TABLE gibbonCrowdAssessDiscuss');
        $this->addSql('DROP TABLE gibbonDaysOfWeek');
        $this->addSql('DROP TABLE gibbonDepartment');
        $this->addSql('DROP TABLE gibbonDepartmentResource');
        $this->addSql('DROP TABLE gibbonDepartmentStaff');
        $this->addSql('DROP TABLE gibbonDistrict');
        $this->addSql('DROP TABLE gibbonExternalAssessment');
        $this->addSql('DROP TABLE gibbonExternalAssessmentField');
        $this->addSql('DROP TABLE gibbonExternalAssessmentStudent');
        $this->addSql('DROP TABLE gibbonExternalAssessmentStudentEntry');
        $this->addSql('DROP TABLE gibbonFamily');
        $this->addSql('DROP TABLE gibbonFamilyAdult');
        $this->addSql('DROP TABLE gibbonFamilyChild');
        $this->addSql('DROP TABLE gibbonFamilyRelationship');
        $this->addSql('DROP TABLE gibbonFamilyUpdate');
        $this->addSql('DROP TABLE gibbonFileExtension');
        $this->addSql('DROP TABLE gibbonFinanceBillingSchedule');
        $this->addSql('DROP TABLE gibbonFinanceBudget');
        $this->addSql('DROP TABLE gibbonFinanceBudgetCycle');
        $this->addSql('DROP TABLE gibbonFinanceBudgetCycleAllocation');
        $this->addSql('DROP TABLE gibbonFinanceBudgetPerson');
        $this->addSql('DROP TABLE gibbonFinanceExpense');
        $this->addSql('DROP TABLE gibbonFinanceExpenseApprover');
        $this->addSql('DROP TABLE gibbonFinanceExpenseLog');
        $this->addSql('DROP TABLE gibbonFinanceFee');
        $this->addSql('DROP TABLE gibbonFinanceFeeCategory');
        $this->addSql('DROP TABLE gibbonFinanceInvoice');
        $this->addSql('DROP TABLE gibbonFinanceInvoicee');
        $this->addSql('DROP TABLE gibbonFinanceInvoiceeUpdate');
        $this->addSql('DROP TABLE gibbonFinanceInvoiceFee');
        $this->addSql('DROP TABLE gibbonFirstAid');
        $this->addSql('DROP TABLE gibbonGroup');
        $this->addSql('DROP TABLE gibbonGroupPerson');
        $this->addSql('DROP TABLE gibbonHook');
        $this->addSql('DROP TABLE gibbonHouse');
        $this->addSql('DROP TABLE gibboni18n');
        $this->addSql('DROP TABLE gibbonINArchive');
        $this->addSql('DROP TABLE gibbonINAssistant');
        $this->addSql('DROP TABLE gibbonINDescriptor');
        $this->addSql('DROP TABLE gibbonIN');
        $this->addSql('DROP TABLE gibbonINPersonDescriptor');
        $this->addSql('DROP TABLE gibbonInternalAssessmentColumn');
        $this->addSql('DROP TABLE gibbonInternalAssessmentEntry');
        $this->addSql('DROP TABLE gibbonLanguage');
        $this->addSql('DROP TABLE gibbonLog');
        $this->addSql('DROP TABLE gibbonMarkbookColumn');
        $this->addSql('DROP TABLE gibbonMarkbookEntry');
        $this->addSql('DROP TABLE gibbonMarkbookTarget');
        $this->addSql('DROP TABLE gibbonMarkbookWeight');
        $this->addSql('DROP TABLE gibbonMedicalCondition');
        $this->addSql('DROP TABLE gibbonMessenger');
        $this->addSql('DROP TABLE gibbonMessengerCannedResponse');
        $this->addSql('DROP TABLE gibbonMessengerReceipt');
        $this->addSql('DROP TABLE gibbonMessengerTarget');
        $this->addSql('DROP TABLE gibbonModule');
        $this->addSql('DROP TABLE gibbonNotification');
        $this->addSql('DROP TABLE gibbonNotificationEvent');
        $this->addSql('DROP TABLE gibbonNotificationListener');
        $this->addSql('DROP TABLE gibbonOutcome');
        $this->addSql('DROP TABLE gibbonPayment');
        $this->addSql('DROP TABLE gibbonPermission');
        $this->addSql('DROP TABLE gibbonPerson');
        $this->addSql('DROP TABLE gibbonPersonField');
        $this->addSql('DROP TABLE gibbonPersonMedical');
        $this->addSql('DROP TABLE gibbonPersonMedicalCondition');
        $this->addSql('DROP TABLE gibbonPersonMedicalConditionUpdate');
        $this->addSql('DROP TABLE gibbonPersonMedicalSymptoms');
        $this->addSql('DROP TABLE gibbonPersonMedicalUpdate');
        $this->addSql('DROP TABLE gibbonPersonReset');
        $this->addSql('DROP TABLE gibbonPersonUpdate');
        $this->addSql('DROP TABLE gibbonPlannerEntry');
        $this->addSql('DROP TABLE gibbonPlannerEntryDiscuss');
        $this->addSql('DROP TABLE gibbonPlannerEntryGuest');
        $this->addSql('DROP TABLE gibbonPlannerEntryHomework');
        $this->addSql('DROP TABLE gibbonPlannerEntryOutcome');
        $this->addSql('DROP TABLE gibbonPlannerEntryStudentHomework');
        $this->addSql('DROP TABLE gibbonPlannerEntryStudentTracker');
        $this->addSql('DROP TABLE gibbonPlannerParentWeeklyEmailSummary');
        $this->addSql('DROP TABLE gibbonResource');
        $this->addSql('DROP TABLE gibbonResourceTag');
        $this->addSql('DROP TABLE gibbonRole');
        $this->addSql('DROP TABLE gibbonRollGroup');
        $this->addSql('DROP TABLE gibbonRubric');
        $this->addSql('DROP TABLE gibbonRubricCell');
        $this->addSql('DROP TABLE gibbonRubricColumn');
        $this->addSql('DROP TABLE gibbonRubricEntry');
        $this->addSql('DROP TABLE gibbonRubricRow');
        $this->addSql('DROP TABLE gibbonScale');
        $this->addSql('DROP TABLE gibbonScaleGrade');
        $this->addSql('DROP TABLE gibbonSchoolYear');
        $this->addSql('DROP TABLE gibbonSchoolYearSpecialDay');
        $this->addSql('DROP TABLE gibbonSchoolYearTerm');
        $this->addSql('DROP TABLE gibbonSetting');
        $this->addSql('DROP TABLE gibbonSpace');
        $this->addSql('DROP TABLE gibbonSpacePerson');
        $this->addSql('DROP TABLE gibbonStaff');
        $this->addSql('DROP TABLE gibbonStaffAbsence');
        $this->addSql('DROP TABLE gibbonStaffAbsenceDate');
        $this->addSql('DROP TABLE gibbonStaffAbsenceType');
        $this->addSql('DROP TABLE gibbonStaffApplicationForm');
        $this->addSql('DROP TABLE gibbonStaffApplicationFormFile');
        $this->addSql('DROP TABLE gibbonStaffContract');
        $this->addSql('DROP TABLE gibbonStaffCoverage');
        $this->addSql('DROP TABLE gibbonStaffCoverageDate');
        $this->addSql('DROP TABLE gibbonStaffJobOpening');
        $this->addSql('DROP TABLE gibbonString');
        $this->addSql('DROP TABLE gibbonStudentEnrolment');
        $this->addSql('DROP TABLE gibbonStudentNote');
        $this->addSql('DROP TABLE gibbonStudentNoteCategory');
        $this->addSql('DROP TABLE gibbonSubstitute');
        $this->addSql('DROP TABLE gibbonTheme');
        $this->addSql('DROP TABLE gibbonTT');
        $this->addSql('DROP TABLE gibbonTTColumn');
        $this->addSql('DROP TABLE gibbonTTColumnRow');
        $this->addSql('DROP TABLE gibbonTTDay');
        $this->addSql('DROP TABLE gibbonTTDayDate');
        $this->addSql('DROP TABLE gibbonTTDayRowClass');
        $this->addSql('DROP TABLE gibbonTTDayRowClassException');
        $this->addSql('DROP TABLE gibbonTTImport');
        $this->addSql('DROP TABLE gibbonTTSpaceBooking');
        $this->addSql('DROP TABLE gibbonTTSpaceChange');
        $this->addSql('DROP TABLE gibbonUnit');
        $this->addSql('DROP TABLE gibbonUnitBlock');
        $this->addSql('DROP TABLE gibbonUnitClass');
        $this->addSql('DROP TABLE gibbonUnitClassBlock');
        $this->addSql('DROP TABLE gibbonUnitOutcome');
        $this->addSql('DROP TABLE gibbonUsernameFormat');
        $this->addSql('DROP TABLE gibbonYearGroup');
    }
}
