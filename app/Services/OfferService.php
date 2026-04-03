<?php
namespace App\Services;

use App\Repositories\OfferRepository;
use Illuminate\Support\Facades\Cache; 


class OfferService
{
    protected $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function getAll()
    {
        $data =  $this->offerRepository->all();
         Cache::forget('home_page_data');

         return $data;
    }

        public function find($id)
        {
            $data = $this->offerRepository->findOffer($id);
            Cache::forget('home_page_data');

            return $data;
        }

    public function store($data)
    {
        $data = $this->offerRepository->create($data);
         Cache::forget('home_page_data');

        return $data;
    }

    public function update($id, $data)
    {
        $data = $this->offerRepository->update($id, $data);

         Cache::forget('home_page_data');

        return $data;
    }

    public function delete($id)
    {
        $data = $this->offerRepository->delete($id);
         Cache::forget('home_page_data');

        return $data;
    }

    public function applyOffer($offer, $total)
    {
        if (!$offer->is_active) {
            throw new \Exception('Coupon is inactive');
        }

        if ($offer->expires_at && now()->gt($offer->expires_at)) {
            throw new \Exception('Coupon expired');
        }

        if ($offer->min_order && $total < $offer->min_order) {
            throw new \Exception('Minimum order should be ₹' . $offer->min_order);
        }

        $discount = 0;

        // ✅ FIXED HERE
        if (in_array($offer->type, ['percent', 'percentage'])) {
            $discount = ($total * $offer->value) / 100;
        } elseif ($offer->type === 'fixed') {
            $discount = $offer->value;
        }

        if ($offer->max_discount) {
            $discount = min($discount, $offer->max_discount);
        }

        $discount = min($discount, $total);

        return [
            'discount' => round($discount, 2),
            'final_total' => round($total - $discount, 2)
        ];
    }

    public function getActiveOffers()
    {
        $data = $this->offerRepository->getActive();
         Cache::forget('home_page_data');

        return $data;
    }
}