<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logout" wire:click="logout" />
