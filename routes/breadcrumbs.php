<?php

// Breadcrumbs::for('dashboard', function ($trail) {
//     $trail->push('dashboard', route('showDashboard'));
// });
//products 
Breadcrumbs::for('Sản phẩm', function ($trail) {
    $trail->push('Sản phẩm', route('products.show'));
    // $trail->parent('dashboard');
});
// products > add product
Breadcrumbs::for('Thêm sản phẩm', function ($trail) {
    $trail->parent('Sản phẩm');
    $trail->push('Thêm sản phẩm', route('products.addview'));
});
// products > edit product
Breadcrumbs::for('Sửa sản phẩm', function ($trail, $prod) {
    $trail->parent('Sản phẩm');
    $trail->push('Sửa sản phẩm', route('products.editview', $prod));
});

//material
Breadcrumbs::for('Nguyên liệu', function ($trail) {
    $trail->push('Nguyên liệu', route('showMaterial'));
    // $trail->parent('dashboard');
});
// products > add product
Breadcrumbs::for('Thêm nguyên liệu', function ($trail) {
    $trail->parent('Nguyên liệu');
    $trail->push('Thêm nguyên liệu', route('material.addview'));
});
// products > edit product
Breadcrumbs::for('Sửa nguyên liệu', function ($trail, $mal) {
    $trail->parent('Nguyên liệu');
    $trail->push('Sửa nguyên liệu', route('material.editview', $mal));
});