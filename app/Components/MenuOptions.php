<?php

namespace App\Components;

class MenuOptions
{
    /**
     * Options when the user is fasting
     */
    static $options = [
        [
            'name' => 'Check the fast status',
            'action' => 'checkFastStatus',
            'status' => ANY
        ],
        [
            'name' => 'Start a fast',
            'action' => 'startFast',
            'status' => FAST_INACTIVE
        ],
        [
            'name' => 'End an active fast',
            'action' => 'endActiveFast',
            'status' => FAST_ACTIVE
        ],
        [
            'name' => 'Update an active fast',
            'action' => 'updateActiveFast',
            'status' => FAST_ACTIVE
        ],
        [
            'name' => 'List all fasts',
            'action' => 'listAllFasts',
            'status' => ANY
        ],
        [
            'name' => 'Exit',
            'action' => 'exit',
            'status' => ANY
        ]
    ];

    /**
     * Retrieves all fasting options
     *
     * @return array
     */
    public static function getFastingOptions(): array
    {
        return static::filterOptions([ANY, FAST_ACTIVE]);
    }

    /**
     * Retrieves all not fasting options
     *
     * @return array
     */
    public static function getNotFastingOptions(): array
    {
        return static::filterOptions([ANY, FAST_INACTIVE]);
    }

    /**
     * Filter options based on some criteria
     *
     * @param array $filter_criteria
     * @return array
     */
    public static function filterOptions(array $filter_criteria): array
    {
        $filtered_options = array_filter(
            static::$options,
            function ($option) use ($filter_criteria) {
                return in_array($option['status'], $filter_criteria);
            }
        );

        return reindexArray($filtered_options);
    }
}
