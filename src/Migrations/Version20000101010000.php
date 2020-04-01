<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20000101010000 extends AbstractMigration
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
        $this->addSql('ALTER TABLE gibbonFacilityPerson DROP FOREIGN KEY FK_481C4C89CC6782D6');
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
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn DROP FOREIGN KEY FK_E0A1D88A2C639785');
        $this->addSql('ALTER TABLE gibbonInternalAssessmentColumn DROP FOREIGN KEY FK_E0A1D88AD395ACF8');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806E2C639785');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806ED395ACF8');
        $this->addSql('ALTER TABLE gibbonRubric DROP FOREIGN KEY FK_AFE9B66C5F72BC3');
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
        $this->addSql('ALTER TABLE gibbonAcademicYearTerm DROP FOREIGN KEY FK_41C4671071FA7520');
        $this->addSql('ALTER TABLE gibbonStaffAbsence DROP FOREIGN KEY FK_2FE5FEF771FA7520');
        $this->addSql('ALTER TABLE gibbonStaffCoverage DROP FOREIGN KEY FK_946E51DE71FA7520');
        $this->addSql('ALTER TABLE gibbonStudentEnrolment DROP FOREIGN KEY FK_ABD4EFFD71FA7520');
        $this->addSql('ALTER TABLE gibbonTT DROP FOREIGN KEY FK_9431F94371FA7520');
        $this->addSql('ALTER TABLE gibbonMarkbookColumn DROP FOREIGN KEY FK_AA57806E88C7C454');
        $this->addSql('ALTER TABLE gibbonAcademicYearSpecialDay DROP FOREIGN KEY FK_EB4E375D88C7C454');
        $this->addSql('ALTER TABLE gibbonActivitySlot DROP FOREIGN KEY FK_59227ABBD8D64BA0');
        $this->addSql('ALTER TABLE gibbonRollGroup DROP FOREIGN KEY FK_86CF5C43D8D64BA0');
        $this->addSql('ALTER TABLE gibbonFacilityPerson DROP FOREIGN KEY FK_481C4C89D8D64BA0');
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
        $this->addSql('DROP TABLE gibbonApplicationForm');
        $this->addSql('DROP TABLE gibbonApplicationFormFile');
        $this->addSql('DROP TABLE gibbonApplicationFormLink');
        $this->addSql('DROP TABLE gibbonApplicationFormRelationship');
        $this->addSql('DROP TABLE gibbonAttendanceLogCourseClass');
        $this->addSql('DROP TABLE gibbonAttendanceLogPerson');
        $this->addSql('DROP TABLE gibbonAttendanceLogRollGroup');
        $this->addSql('DROP TABLE gibbonBehaviour');
        $this->addSql('DROP TABLE gibbonBehaviourLetter');
        $this->addSql('DROP TABLE gibbonCourse');
        $this->addSql('DROP TABLE gibbonCourseClass');
        $this->addSql('DROP TABLE gibbonCourseClassMap');
        $this->addSql('DROP TABLE gibbonCourseClassPerson');
        $this->addSql('DROP TABLE gibbonCrowdAssessDiscuss');
        $this->addSql('DROP TABLE gibbonDepartment');
        $this->addSql('DROP TABLE gibbonDepartmentResource');
        $this->addSql('DROP TABLE gibbonDepartmentStaff');
        $this->addSql('DROP TABLE gibbonDistrict');
        $this->addSql('DROP TABLE gibbonFamily');
        $this->addSql('DROP TABLE gibbonFamilyAdult');
        $this->addSql('DROP TABLE gibbonFamilyChild');
        $this->addSql('DROP TABLE gibbonFamilyRelationship');
        $this->addSql('DROP TABLE gibbonFamilyUpdate');
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
        $this->addSql('DROP TABLE gibbonSetting');
        $this->addSql('DROP TABLE gibbonFacility');
        $this->addSql('DROP TABLE gibbonFacilityPerson');
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
    }
}
