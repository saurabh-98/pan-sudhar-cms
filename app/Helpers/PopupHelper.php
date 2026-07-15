<?php

use App\Models\PopupAnnouncement;

if (!function_exists('getActivePopup')) {

    function getActivePopup(string $page)
    {
        return PopupAnnouncement::where('status', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where(function ($query) use ($page) {

                if ($page == 'login') {
                    $query->where('show_on_login', 1);
                }

                if ($page == 'dashboard') {
                    $query->where('show_on_dashboard', 1);
                }

                if ($page == 'home') {
                    $query->where('show_on_home', 1);
                }

            })
            ->orderBy('priority')
            ->first();
    }

}