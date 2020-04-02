SET FOREIGN_KEY_CHECKS = 0;
INSERT INTO `gibbonLanguage` (`gibbonLanguageID`, `name`) VALUES
(1, 'Afrikaans'),
(2, 'Albanian'),
(3, 'Arabic'),
(4, 'Armenian'),
(5, 'Basque'),
(6, 'Bengali'),
(7, 'Bulgarian'),
(8, 'Catalan'),
(9, 'Cambodian'),
(10, 'Chinese (Mandarin)'),
(11, 'Chinese (Cantonese)'),
(12, 'Croatian'),
(13, 'Czech'),
(14, 'Danish'),
(15, 'Dutch'),
(16, 'English'),
(17, 'Estonian'),
(18, 'Fijian'),
(19, 'Finnish'),
(20, 'French'),
(21, 'Georgian'),
(22, 'German'),
(23, 'Greek'),
(24, 'Gujarati'),
(25, 'Hebrew'),
(26, 'Hindi'),
(27, 'Hungarian'),
(28, 'Icelandic'),
(29, 'Indonesian'),
(30, 'Irish'),
(31, 'Italian'),
(32, 'Japanese'),
(33, 'Javanese'),
(34, 'Korean'),
(35, 'Latin'),
(36, 'Latvian'),
(37, 'Lithuanian'),
(38, 'Macedonian'),
(39, 'Malay'),
(40, 'Malayalam'),
(41, 'Maltese'),
(42, 'Maori'),
(43, 'Marathi'),
(44, 'Mongolian'),
(45, 'Nepali'),
(46, 'Norwegian'),
(47, 'Persian'),
(48, 'Polish'),
(49, 'Portuguese'),
(50, 'Punjabi'),
(51, 'Quechua'),
(52, 'Romanian'),
(53, 'Russian'),
(54, 'Samoan'),
(55, 'Serbian'),
(56, 'Slovak'),
(57, 'Slovenian'),
(58, 'Spanish'),
(59, 'Swahili'),
(60, 'Swedish'),
(61, 'Tamil'),
(62, 'Tatar'),
(63, 'Telugu'),
(64, 'Thai'),
(65, 'Tibetan'),
(66, 'Tongan'),
(67, 'Turkish'),
(68, 'Ukrainian'),
(69, 'Urdu'),
(70, 'Uzbek'),
(71, 'Vietnamese'),
(72, 'Welsh'),
(73, 'Xhosa'),
(74, 'Odia'),
(75, 'Myanmar'),
(76, 'Burmese'),
(77, 'Filipino'),
(78, 'Sinhala');

INSERT INTO `gibbonMedicalCondition` (`gibbonMedicalConditionID`, `name`) VALUES
(4, 'Allergy - Animal'),
(3, 'Allergy - Drug'),
(1, 'Allergy - Food'),
(5, 'Allergy - Grass/Pollen'),
(2, 'Allergy - Insect'),
(6, 'Allergy - Other'),
(7, 'Asthma'),
(12, 'Convulsions/Epilepsy'),
(10, 'Diabetes'),
(18, 'Dizziness or Fainting spells'),
(20, 'Frequent Nose Bleeds'),
(8, 'G6PD Deficiency'),
(22, 'Hearing Impairment'),
(15, 'Heart Condition'),
(11, 'Hypertension'),
(9, 'Joint Problems'),
(13, 'Kidney Disease'),
(27, 'Other'),
(16, 'Previous Concussion or Head Injury'),
(17, 'Previous Serious Injury'),
(21, 'Psychological Condition'),
(14, 'Rare Blood Type'),
(19, 'Rheumatic Fever'),
(26, 'Travel Sickness'),
(23, 'Visual Impairment'),
(25, 'Visual Impairment - Colour Blindness'),
(24, 'Visual Impairment - Requiring Contact Lenses or Glasses');


INSERT INTO `gibbonStaffAbsenceType` (`name`, `nameShort`, `active`, `requiresApproval`, `reasons`, `sequenceNumber`) VALUES
('Sick Leave', 'S', 'Y', 'N', '', 1),
('Personal Leave', 'P', 'Y', 'N', '', 2),
('Non-paid Leave', 'NP', 'Y', 'N', '', 3),
('School Related', 'D', 'Y', 'N', 'PD,Sports Trip,Offsite Event,Other', 4);

INSERT INTO `gibbonStudentNoteCategory` (`name`, `template`, `active`) VALUES
('Academic', '', 'Y'),
('Pastoral', '', 'Y'),
('Behaviour', '', 'Y'),
('Other', '', 'Y');

INSERT INTO `gibbonTheme` (`gibbonThemeID`, `name`, `description`, `active`, `version`, `author`, `url`) VALUES
(13, 'Default', 'Gibbon\'s 2015 look and feel.', 'Y', '1.0.00', 'Ross Parker', 'http://rossparker.org');

INSERT INTO `gibbonUsernameFormat` (`id`, `gibbonRoleIDList`, `format`, `isDefault`, `isNumeric`, `numericValue`, `numericIncrement`, `numericSize`) VALUES
(1, '003', '[preferredName:1][surname]', 'Y', 'N', 0, 1, 4),
(2, '001,002,006', '[preferredName:1].[surname]', 'N', 'N', 0, 1, 4);

SET FOREIGN_KEY_CHECKS = 1;
