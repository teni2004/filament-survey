<?php

use App\Models\User;

it('user can access survey index page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/'); //it's saying that this page is forbidden but that shouldn't be the case because the user is logged in :/
    $response->assertStatus(200);
});

