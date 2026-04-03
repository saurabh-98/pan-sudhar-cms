<?php
namespace App\Repositories;

use App\Models\Campaign;

class CampaignRepository
{
    public function create(array $data)
    {
        return Campaign::create($data);
    }

    public function update(Campaign $campaign, array $data)
    {
        return $campaign->update($data);
    }

    public function delete(Campaign $campaign)
    {
        return $campaign->delete();
    }

    public function getAll()
    {
        return Campaign::latest()->get();
    }
}