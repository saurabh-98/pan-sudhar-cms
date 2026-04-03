<?php

namespace App\Repositories;

use App\Models\FooterLink;
use App\Models\Setting;
use App\Models\SocialLink;

class FooterRepository
{
    /* =========================
       GET ALL (GROUPED - FRONTEND)
    ========================= */
    public function getAll()
    {
        return FooterLink::orderBy('sort_order')
            ->get()
            ->groupBy('section');
    }

    /* =========================
       GET RAW LIST (ADMIN DATATABLE)
    ========================= */
    public function getAllRaw()
    {
        return FooterLink::orderBy('sort_order')->get();
    }

    /* =========================
       SETTINGS
    ========================= */
    public function getSettings()
    {
        return Setting::pluck('value', 'key');
    }

    /* =========================
       SOCIAL LINKS
    ========================= */
    public function getSocials()
    {
        return SocialLink::all();
    }

    /* =========================
       CREATE
    ========================= */
    public function createLink(array $data)
    {
        return FooterLink::create($data);
    }

    /* =========================
       UPDATE
    ========================= */
    public function updateLink($id, array $data)
    {
        $link = FooterLink::findOrFail($id);
        return $link->update($data);
    }

    /* =========================
       DELETE
    ========================= */
    public function deleteLink($id)
    {
        return FooterLink::findOrFail($id)->delete();
    }

    /* =========================
       SETTINGS UPDATE
    ========================= */
    public function updateSettings(array $data)
    {
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}