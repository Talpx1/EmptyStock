<?php

use App\Actions\SetActiveProfile;
use App\Models\Profile;
use Mary\Traits\Toast;

use function Livewire\Volt\state;
use function Livewire\Volt\uses;

//TODO: test

uses([Toast::class]);

state(['profile']);

$switchProfile = function () {
    SetActiveProfile::run($this->profile);

    $this->success(__('Switched to @:username', ['username' => $this->profile->username]));

    $this->redirect(route('app.dashboard'), navigate: true);
};

?>
<div>
    <x-button class="btn-ghost" :label="'@' . $profile->username" wire:click="switchProfile" />
</div>
