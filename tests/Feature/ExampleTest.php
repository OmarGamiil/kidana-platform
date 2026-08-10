<?php

use Inertia\Testing\AssertableInertia as Assert;

test('renders the marketing homepage', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page->component('marketing/Home'));
});
