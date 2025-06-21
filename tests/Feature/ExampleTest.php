<?php

test('returns a successful response', function () {
    $response = $this->get('/up');

    $response->assertStatus(200);
});
