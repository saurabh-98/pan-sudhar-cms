<?php

namespace App\Services;

use App\Repositories\FooterRepository;
use App\DTO\FooterLinkDTO;

class FooterService
{
    protected $footerRepository;

    public function __construct(FooterRepository $footerRepository)
    {
        $this->footerRepository = $footerRepository;
    }

    /* =========================
       GET FOOTER DATA (FRONTEND)
    ========================= */
    public function getFooterData()
    {
        return [
            'links'    => $this->footerRepository->getAll(),      // grouped
            'settings' => $this->footerRepository->getSettings(),
            'socials'  => $this->footerRepository->getSocials(),
        ];
    }

    /* =========================
       GET ALL LINKS (ADMIN TABLE)
    ========================= */
    public function getAllLinks()
    {
        return $this->footerRepository->getAllRaw(); // flat list for DataTable
    }

    /* =========================
       CREATE LINK
    ========================= */
    public function createLink($request)
    {
        $dto = new FooterLinkDTO($request);

        return $this->footerRepository->createLink(
            $dto->toArray()
        );
    }

    /* =========================
       UPDATE LINK ✅ FIXED
    ========================= */
    public function updateLink($id, $request)
    {
        $dto = new FooterLinkDTO($request);

        return $this->footerRepository->updateLink(
            $id,
            $dto->toArray()
        );
    }

    /* =========================
       DELETE LINK ✅ FIXED
    ========================= */
    public function deleteLink($id)
    {
        return $this->footerRepository->deleteLink($id);
    }

    /* =========================
       UPDATE SETTINGS
    ========================= */
    public function updateSettings($request)
    {
        return $this->footerRepository->updateSettings(
            $request->settings ?? []
        );
    }
}