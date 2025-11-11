<?php
function isMenuActive(array $routes): bool
{
    foreach ($routes as $route) {
        if (request()->routeIs($route)) return true;
    }
    return false;
}
