<div class="d-flex gap-2">
    <input type="number"
           class="form-control amount-input"
           placeholder="Enter Amount"
           min="1"
           step="0.01"
           style="max-width:140px;">

    <button type="button"
            class="btn {{ $addClass }} btn-recharge"
            data-id="{{ $user->id }}"
            data-name="{{ $user->name }}"
            data-action="add"
            data-balance="{{ $user->wallet_balance }}"
            data-url="{{ route('admin.wallet.add', $user->id) }}">
        {{ $addBtn }}
    </button>

    <button type="button"
            class="btn btn-outline-danger btn-recharge"
            data-id="{{ $user->id }}"
            data-name="{{ $user->name }}"
            data-action="deduct"
            data-balance="{{ $user->wallet_balance }}"
            data-url="{{ route('admin.wallet.deduct', $user->id) }}">
        Pull Back
    </button>
</div>