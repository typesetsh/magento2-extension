<?php

return [
    'target_php_version' => '7.2',
    'suppress_issue_types' => [
       // 'PhanTypeObjectUnsetDeclaredProperty',
       // 'PhanUndeclaredClassMethod',
        'PhanTypeSuspiciousNonTraversableForeach',
        'PhanTypeInvalidDimOffset',
        'PhanTypeInvalidPropertyName',
    ],
    'directory_list' => [
        'src',
        'vendor',
    ],
    'exclude_analysis_directory_list' => [
        'vendor',
    ],
];
