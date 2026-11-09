<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AccountDetailsComponent extends Component
{
    #[Computed]
    public function currentUser(): ?Customer
    {
        return Auth::user();
    }

    #[Computed]
    public function primaryAddress(): ?CustomerAddress
    {
        if (! Auth::check()) {
            return null;
        }

        return CustomerAddress::where('customer_id', Auth::id())
            ->where('is_primary', true)
            ->first();
    }

    public function render()
    {
        return view('livewire.account-details-component');
    }
}
