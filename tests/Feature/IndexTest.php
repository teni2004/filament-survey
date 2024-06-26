<?php

it('can render page', function () {
    $this->get('/')->assertSuccessful();
});