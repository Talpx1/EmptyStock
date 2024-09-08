<?php

use App\Models\User;
use Spatie\Activitylog\CleanActivitylogCommand;
use Spatie\Activitylog\Models\Activity;

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

it('cleans activity log record older than 90 days', function () {
    $user = User::factory()->createQuietly();

    $activity_to_be_deleted = activity()
        ->causedBy($user)
        ->performedOn($user)
        ->withProperties(['name' => 'TEST1'])
        ->createdAt(now()->subDays(91))
        ->log('updated');

    $activity_not_to_be_deleted = activity()
        ->causedBy($user)
        ->performedOn($user)
        ->withProperties(['name' => 'TEST2'])
        ->createdAt(now()->subDays(89))
        ->log('updated');

    assertDatabaseHas(Activity::class, $activity_to_be_deleted->getAttributes());
    assertDatabaseHas(Activity::class, $activity_not_to_be_deleted->getAttributes());

    assertModelExists($activity_to_be_deleted);
    assertModelExists($activity_not_to_be_deleted);

    artisan(CleanActivitylogCommand::class, ['--days' => 90])
        ->expectsOutputToContain('Cleaning activity log..')
        ->expectsOutputToContain('Deleted 1 record(s) from the activity log.')
        ->assertSuccessful();

    assertDatabaseMissing(Activity::class, $activity_to_be_deleted->getAttributes());
    assertDatabaseHas(Activity::class, $activity_not_to_be_deleted->getAttributes());

    assertModelMissing($activity_to_be_deleted);
    assertModelExists($activity_not_to_be_deleted);
});
