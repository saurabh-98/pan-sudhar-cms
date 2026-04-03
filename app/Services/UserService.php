<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\DTO\UserDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /* =========================
       GET USERS
    ========================= */
    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    public function getAllUsersWithOrders()
    {
        return $this->userRepository->getAllUsersWithOrders();
    }

    /* =========================
       CREATE USER (ADMIN)
    ========================= */
    public function createUser(UserDTO $dto)
    {
        return $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
            'first_login' => true
        ]);
    }

    /* =========================
       REGISTER USER (FRONTEND)
    ========================= */
    public function register(UserDTO $dto)
    {
        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return $user;
    }

    /* =========================
       LOGIN USER
    ========================= */
    public function login(UserDTO $dto, $remember = false)
    {
        if (!Auth::attempt([
            'email' => $dto->email,
            'password' => $dto->password
        ], $remember)) {
            return false;
        }

        request()->session()->regenerate();

        return Auth::user();
    }

    /* =========================
       UPDATE USER
    ========================= */
    public function updateUser($id, UserDTO $dto)
    {
        $user = $this->userRepository->findById($id);

        $data = [
            'name' => $dto->name,
            'email' => $dto->email,
            'role' => $dto->role
        ];

        if ($dto->password) {
            $data['password'] = Hash::make($dto->password);
        }

        return $this->userRepository->update($user, $data);
    }

    /* =========================
       DELETE USER
    ========================= */
    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }

    /* =========================
       🔥 CUSTOMER LIST (DATATABLE)
    ========================= */
    public function getCustomerList()
    {
        $users = $this->userRepository->getAllUsersWithOrders();

        return $users->map(function ($user) {

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,

                'orders' => $user->orders->count(),

                'spent' => '₹' . number_format(
                    $user->orders->sum('final_total'), 2
                ),

                'status' => $user->status ?? 'active',

                'actions' => '
                    <a href="'.route('admin.users.customer.show',$user->id).'" class="btn btn-sm btn-primary">👁</a>
                    <button class="btn btn-sm btn-danger deleteUser" data-id="'.$user->id.'">🗑</button>
                '
            ];
        });
    }

    /* =========================
       🔥 CUSTOMER PROFILE
    ========================= */
    public function getCustomerProfile($id)
    {
        $user = $this->userRepository->findById($id);

        $orders = $this->userRepository->getOrdersByUser($id);

        return [
            'user' => $user,
            'totalOrders' => $orders->count(),
            'totalSpent' => $orders->sum('final_total')
        ];
    }

    /* =========================
    GET USER BY ID
    ========================= */
    public function getUserById($id)
    {
        return $this->userRepository->findById($id);
    }

    public function forcePasswordReset($id)
    {
        $user = $this->userRepository->findById($id);

        return $this->userRepository->update($user, [
            'password' => null,
            'first_login' => true
        ]);
    }

    /* =========================
       🔥 CUSTOMER ORDERS (AJAX)
    ========================= */
    public function getCustomerOrders($id)
    {
        $orders = $this->userRepository->getOrdersByUser($id);

        return $orders->map(function ($order) {

            return [
                'id' => $order->id,

                'total' => '₹' . number_format($order->final_total, 2),

                'status' => $order->status,

                'payment' => $order->payment_status,

                'date' => $order->created_at->format('d M Y, h:i A'),

                'actions' => '
                    <a href="'.route('admin.orders.invoice',$order->id).'" target="_blank" class="btn btn-sm btn-primary">👁</a>
                    <a href="'.route('admin.orders.invoice.download',$order->id).'" class="btn btn-sm btn-success">⬇</a>
                '
            ];
        });
    }
}