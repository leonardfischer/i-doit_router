<?php

// Check if the add-on is active.
if (isys_module_manager::instance()->is_active('lfischer_router')) {
    // Register the add-on namespace.
    \idoit\Psr4AutoloaderClass::factory()->addNamespace('idoit\Module\Lfischerrouter', __DIR__ . '/src/');

    // Register a route.
    isys_request_controller::instance()
        ->addModuleRoute('GET', '/open-object/[**:name]', 'lfischer_router', 'Route', 'openObject')
        ->addModuleRoute('GET', '/open-object-by-ip/[**:ip]', 'lfischer_router', 'Route', 'openObjectByIp')
        ->addModuleRoute('GET', '/open-inventory/[**:inventory]', 'lfischer_router', 'Route', 'openObjectByInventory')
        ->addModuleRoute('GET', '/change-tenant/[i:id]', 'lfischer_router', 'Route', 'changeTenant');
}
