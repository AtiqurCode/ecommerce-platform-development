<?php

test('home redirects to the product catalogue', function () {
    $response = $this->get(route('home'));

    $response->assertRedirect(route('products.index'));
});
