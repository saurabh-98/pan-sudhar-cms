<?php

namespace App\Services;

use App\DTO\CampaignDTO;
use App\Models\Campaign;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; // ✅ ADDED


class CampaignService
{
    protected $repo;

    public function __construct(CampaignRepository $repo)
    {
        $this->repo = $repo;
    }

    /* ================= STORE ================= */
    public function store(CampaignDTO $dto, $file = null)
    {
        if ($file && $file->isValid()) {

            $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

            // ✅ FORCE PUBLIC DISK
            Storage::disk('public')->putFileAs(
                'campaigns',
                $file,
                $filename
            );

            $dto->image = 'campaigns/'.$filename;
        }

        $data = $this->repo->create($dto->toArray());

         Cache::forget('home_page_data');

         return $data;
    }

    /* ================= UPDATE ================= */
    public function update(Campaign $campaign, CampaignDTO $dto, $file = null)
    {
        if ($file && $file->isValid()) {

            // ✅ delete old image
            if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
                Storage::disk('public')->delete($campaign->image);
            }

            $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

            // ✅ FORCE PUBLIC DISK
            Storage::disk('public')->putFileAs(
                'campaigns',
                $file,
                $filename
            );

            $dto->image = 'campaigns/'.$filename;
        }

        $data = $this->repo->update($campaign, $dto->toArray());

         Cache::forget('home_page_data');
         return $data;
    }

    /* ================= DELETE ================= */
    public function delete(Campaign $campaign)
    {
        if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
            Storage::disk('public')->delete($campaign->image);
        }

        $data = $this->repo->delete($campaign);
         Cache::forget('home_page_data');
         return $data;
    }
}