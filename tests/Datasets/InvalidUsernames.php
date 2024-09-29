<?php

use App\Models\Profile;

dataset(
    'invalid_usernames.not_having_2_letters', [
        'a12345', 'a.123', '__a__',
    ]
);

dataset(
    'invalid_usernames.containing_disallowed_chars', [
        'username!', '@username', 'user-name', 'abコンサート',
    ]
);

dataset(
    'invalid_usernames.reserved', [
        'about', 'access', 'admin', 'demo', 'dev', 'facebook', 'file', 'www', 'xxx',
    ]
);

dataset(
    'invalid_usernames.already_in_use', [function () {
        Profile::factory()->create(['username' => 'already_in_use']);

        return 'already_in_use';
    }]
);

dataset(
    'invalid_usernames', [
        'not_having_2_letters' => ['a12345', 'a.123', '__a__'],
        'containing_disallowed_chars' => ['username!', '@username', 'user-name', 'abコンサート'],
        'reserved' => ['about', 'access', 'admin', 'demo', 'dev', 'facebook', 'file', 'www', 'xxx'],
        'already_in_use' => [function () {
            Profile::factory()->create(['username' => 'already_in_use']);

            return 'already_in_use';
        }],
    ]
);
