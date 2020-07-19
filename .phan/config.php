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
    'file_list' => [
        'packages/css/init.php',
    ],
    'directory_list' => [
        'src',
    ],
    'exclude_file_regex' => '@^vendor/.*/(tests?|Tests?)/@',
    'exclude_analysis_directory_list' => [
        'vendor/',
    ],
];
