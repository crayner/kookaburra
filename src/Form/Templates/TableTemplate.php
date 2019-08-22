<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/08/2019
 * Time: 08:01
 */

namespace App\Form\Templates;

/**
 * Class TableTemplate
 * @package App\Form\Templates
 */
class TableTemplate implements ReactTemplateInterface
{
    /**
     * getStandardRow
     * @return array
     */
    public function getStandardRow(): array
    {
        return [
            'class' => 'flex flex-col sm:flex-row justify-between content-center p-0',
            'style' => false,
            'columns' => [
                [
                    'class' => 'flex flex-col flex-grow justify-center -mb-1 sm:mb-0 px-2 border-b-0 sm:border-b border-t-0',
                    'style' => false,
                    'colspan' => 1,
                    'help' => [
                        'class' => 'text-xxs text-gray-600 italic font-normal mt-1 sm:mt-0 help-text',
                    ],
                    'label' => [
                        'class' => 'inline-block mt-4 sm:my-1 sm:max-w-xs font-bold text-sm sm:text-xs',
                    ],
                    'formElements' => [
                        'label_help',
                    ],
                ],
                [
                    'class' => 'w-full max-w-full sm:max-w-xs flex justify-end items-center px-2 border-b-0 sm:border-b border-t-0',
                    'style' => false,
                    'wrapper' => [
                        'class' => 'flex-1 relative',
                    ],
                    'colspan' => 1,
                    'formElements' => [
                        'widget',
                        'errors',
                    ],
                ],
            ],
        ];
    }

    /**
     * getHeaderRow
     * @return array
     */
    public function getHeaderRow(): array
    {
        return [
            'class' => 'break flex flex-col sm:flex-row justify-between content-center p-0',
            'style' => false,
            'columns' => [
                [
                    'class' => 'flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0',
                    'style' => false,
                    'colspan' => 2,
                    'formElements' => [
                        'label_help',
                    ],
                ],
            ],
        ];
    }

    /**
     * getSubmitRow
     * @return array
     */
    public function getSubmitRow(): array
    {
        return [
            'class' => 'flex flex-col sm:flex-row justify-between content-center p-0',
            'style' => false,
            'columns' => [
                [
                    'class' => 'flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0',
                    'style' => false,
                    'help' => [
                        'class' => 'emphasis small',
                    ],
                    'colspan' => 1,
                    'formElements' => [
                        'help',
                    ],
                ],
                [
                    'class' => 'w-full max-w-full sm:max-w-xs flex justify-end items-center px-2 border-b-0 sm:border-b border-t-0',
                    'style' => false,
                    'colspan' => 1,
                    'formElements' => [
                        'widget',
                    ],
                    'wrapper' => [
                        'class' => 'flex-1 relative right',
                    ],
                ],
            ],
        ];
    }

    /**
     * getParentStyle
     * @return array
     */
    public function getParentStyle(): array
    {
        return [
            'form' => [
                'class' => 'smallIntBorder fullWidth standardForm',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ],
            'table' => [
                'class' => 'smallIntBorder fullWidth standardForm relative',
                'cellspacing' => 0,
            ],
        ];
    }
}