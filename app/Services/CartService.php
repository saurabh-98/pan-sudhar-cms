<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\DTO\CartDTO;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    
    public function addToCart(CartDTO $dto)
    {
        $cart = $this->cartRepository->findByUserAndMenu(
            $dto->user_id,
            $dto->menu_id
        );

        if ($cart) {
            return $this->cartRepository->updateQty(
                $cart,
                $cart->qty + ($dto->qty ?? 1)
            );
        }

        return $this->cartRepository->create([
            'user_id' => $dto->user_id,
            'menu_id' => $dto->menu_id,
            'qty' => $dto->qty ?? 1
        ]);
    }

    public function getCartCount($userId)
    {

        return $this->cartRepository->count($userId);
    }

    
    public function getCart($userId)
    {
        return $this->cartRepository->getUserCart($userId);
    }

    public function updateQuantity($cartId, $qty)
    {
        $cart = $this->cartRepository->findById($cartId);

        if (!$cart) {
            throw new \Exception("Cart item not found");
        }


        return $this->cartRepository->updateQty($cart, $qty);
    }

    
    public function getCartItems($userId)
    {
        return $this->cartRepository
            ->getUserCart($userId)
            ->load('menu'); 
    }

    
    public function removeItem($id)
    {
        return $this->cartRepository->delete($id);
    }

   
    public function clearCart($userId)
    {
        return $this->cartRepository->clearByUser($userId);
    }
}