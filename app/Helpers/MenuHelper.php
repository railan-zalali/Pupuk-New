<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MenuHelper
{
    /**
     * Get sidebar menu items based on user permissions
     *
     * @return array
     */
    public static function getSidebarMenu()
    {
        $user = Auth::user();
        $menuConfig = config('menu.sidebar', []);
        $filteredMenu = [];

        foreach ($menuConfig as $menuItem) {
            if (self::userHasPermission($user, $menuItem)) {
                $item = $menuItem;
                
                // Process submenu if exists
                if (isset($menuItem['submenu'])) {
                    $item['submenu'] = array_filter($menuItem['submenu'], function($subItem) use ($user, $menuItem) {
                        // Submenu inherits parent permission
                        return self::userHasPermission($user, $menuItem);
                    });
                }
                
                // Add active state
                $item['is_active'] = self::isMenuActive($menuItem);
                
                $filteredMenu[] = $item;
            }
        }

        return $filteredMenu;
    }

    /**
     * Check if user has permission for menu item
     *
     * @param \App\Models\User $user
     * @param array $menuItem
     * @return bool
     */
    private static function userHasPermission($user, $menuItem)
    {
        if (!$user) {
            return false;
        }

        // If no permission specified, allow access
        if (!isset($menuItem['permission'])) {
            return true;
        }

        return $user->hasPermission($menuItem['permission']);
    }

    /**
     * Check if menu item is currently active
     *
     * @param array $menuItem
     * @return bool
     */
    private static function isMenuActive($menuItem)
    {
        $currentRoute = Route::currentRouteName();
        
        // Return false if no current route
        if (!$currentRoute) {
            return false;
        }
        
        if (!isset($menuItem['active_routes'])) {
            return false;
        }

        foreach ($menuItem['active_routes'] as $pattern) {
            if (fnmatch($pattern, $currentRoute)) {
                return true;
            }
        }

        // Check submenu if exists
        if (isset($menuItem['submenu'])) {
            foreach ($menuItem['submenu'] as $subItem) {
                if (isset($subItem['active_routes'])) {
                    foreach ($subItem['active_routes'] as $pattern) {
                        if (fnmatch($pattern, $currentRoute)) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check if submenu should be open
     *
     * @param array $menuItem
     * @return bool
     */
    public static function isSubmenuOpen($menuItem)
    {
        return self::isMenuActive($menuItem);
    }

    /**
     * Generate menu item HTML
     *
     * @param array $menuItem
     * @return string
     */
    public static function renderMenuItem($menuItem)
    {
        $hasSubmenu = isset($menuItem['submenu']) && !empty($menuItem['submenu']);
        $isActive = $menuItem['is_active'] ?? false;
        $isOpen = $hasSubmenu && self::isSubmenuOpen($menuItem);

        if ($hasSubmenu) {
            return self::renderMenuWithSubmenu($menuItem, $isActive, $isOpen);
        } else {
            return self::renderSimpleMenu($menuItem, $isActive);
        }
    }

    /**
     * Render simple menu item
     *
     * @param array $menuItem
     * @param bool $isActive
     * @return string
     */
    private static function renderSimpleMenu($menuItem, $isActive)
    {
        $activeClass = $isActive ? 'bg-blue-700' : '';
        $route = isset($menuItem['route']) ? route($menuItem['route']) : '#';
        
        return sprintf(
            '<li>
                <a href="%s" class="flex items-center p-2 text-white rounded-lg hover:bg-blue-700 group %s">
                    <i class="%s w-5 h-5 text-white transition duration-75 group-hover:text-white"></i>
                    <span class="ml-3 sidebar-text" :class="{ \'hidden\': sidebarCollapsed && !isMobile }">%s</span>
                </a>
            </li>',
            $route,
            $activeClass,
            $menuItem['icon'] ?? 'fas fa-circle',
            $menuItem['title']
        );
    }

    /**
     * Render menu with submenu
     *
     * @param array $menuItem
     * @param bool $isActive
     * @param bool $isOpen
     * @return string
     */
    private static function renderMenuWithSubmenu($menuItem, $isActive, $isOpen)
    {
        $activeClass = $isActive ? 'bg-blue-700' : '';
        $submenuHtml = '';
        
        foreach ($menuItem['submenu'] as $subItem) {
            $subRoute = isset($subItem['route']) ? route($subItem['route']) : '#';
            $submenuHtml .= sprintf(
                '<li>
                    <a href="%s" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-blue-700">%s</a>
                </li>',
                $subRoute,
                $subItem['title']
            );
        }

        return sprintf(
            '<li>
                <button type="button" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg group hover:bg-blue-700 %s" 
                        x-data="{ open: %s }" 
                        @click="open = !open">
                    <i class="%s w-5 h-5 text-white transition duration-75 group-hover:text-white"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap sidebar-text" :class="{ \'hidden\': sidebarCollapsed && !isMobile }">%s</span>
                    <i class="fas fa-chevron-down w-3 h-3 sidebar-text" :class="{ \'hidden\': sidebarCollapsed && !isMobile, \'rotate-180\': open }"></i>
                </button>
                <ul x-show="open" x-transition class="py-2 space-y-2">
                    %s
                </ul>
            </li>',
            $activeClass,
            $isOpen ? 'true' : 'false',
            $menuItem['icon'] ?? 'fas fa-circle',
            $menuItem['title'],
            $submenuHtml
        );
    }
}