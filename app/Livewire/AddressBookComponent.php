<?php

namespace App\Livewire;

use App\Models\CustomerAddress;
use App\Services\Backend\BiteshipService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddressBookComponent extends Component
{
    // View state: 'list' | 'form'
    public string $view = 'list';

    public ?int $editingAddressId = null;

    public string $addressLabel = '';

    public string $addressRecipientName = '';

    public string $addressRecipientPhone = '';

    public string $addressRecipientEmail = '';

    public string $addressFull = '';

    public string $addressNote = '';

    public string $addressAreaId = '';

    public string $addressAreaName = '';

    public string $addressPostalCode = '';

    public string $addressAreaSearch = '';

    public array $addressAreaResults = [];

    public bool $addressIsPrimary = false;

    public function newAddress(): void
    {
        $this->resetAddressForm();
        $this->view = 'form';
    }

    public function editAddress(int $addressId): void
    {
        $address = $this->customerAddressQuery()->findOrFail($addressId);

        $this->editingAddressId = $address->id;
        $this->addressLabel = (string) ($address->label ?? '');
        $this->addressRecipientName = (string) ($address->recipient_name ?? '');
        $this->addressRecipientPhone = (string) ($address->recipient_phone ?? '');
        $this->addressRecipientEmail = (string) ($address->recipient_email ?? '');
        $this->addressFull = $address->full_address;
        $this->addressNote = (string) ($address->note ?? '');
        $this->addressAreaId = (string) ($address->area_id ?? '');
        $this->addressAreaName = (string) ($address->area_name ?? '');
        $this->addressPostalCode = (string) ($address->postal_code ?? '');
        $this->addressAreaSearch = $this->addressAreaName;
        $this->addressIsPrimary = (bool) $address->is_primary;
        $this->addressAreaResults = [];
        $this->view = 'form';
    }

    public function backToList(): void
    {
        $this->resetAddressForm();
        $this->view = 'list';
    }

    public function searchAddressAreas(BiteshipService $biteshipService): void
    {
        try {
            $this->addressAreaResults = $biteshipService->searchAreas($this->addressAreaSearch);
        } catch (Exception $exception) {
            $this->addressAreaResults = [];
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function selectAddressArea(array $area): void
    {
        $this->addressAreaId = (string) ($area['id'] ?? '');
        $this->addressAreaName = (string) ($area['name'] ?? '');
        $this->addressPostalCode = (string) ($area['postal_code'] ?? '');
        $this->addressAreaSearch = $this->addressAreaName;
        $this->addressAreaResults = [];
    }

    public function saveAddress(): void
    {
        $this->validate([
            'addressLabel' => ['nullable', 'string', 'max:100'],
            'addressRecipientName' => ['nullable', 'string', 'max:255'],
            'addressRecipientPhone' => ['nullable', 'string', 'min:10', 'max:20'],
            'addressRecipientEmail' => ['nullable', 'email', 'max:255'],
            'addressFull' => ['required', 'string', 'max:2000'],
            'addressNote' => ['nullable', 'string', 'max:500'],
            'addressAreaId' => ['nullable', 'string', 'max:255'],
            'addressAreaName' => ['nullable', 'string', 'max:255'],
            'addressPostalCode' => ['nullable', 'string', 'max:10'],
        ]);

        $customerId = Auth::id();
        $addressCount = CustomerAddress::where('customer_id', $customerId)->count();
        $existingAddress = $this->editingAddressId
            ? $this->customerAddressQuery()->findOrFail($this->editingAddressId)
            : null;
        $shouldBePrimary = $this->addressIsPrimary || $addressCount === 0 || (bool) $existingAddress?->is_primary;

        if ($shouldBePrimary) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_primary' => false]);
        }

        $address = $existingAddress ?: new CustomerAddress(['customer_id' => $customerId]);

        $address->fill([
            'label' => $this->addressLabel ?: null,
            'recipient_name' => $this->addressRecipientName ?: null,
            'recipient_phone' => $this->addressRecipientPhone ?: null,
            'recipient_email' => $this->addressRecipientEmail ?: null,
            'full_address' => $this->addressFull,
            'note' => $this->addressNote ?: null,
            'area_id' => $this->addressAreaId ?: null,
            'area_name' => $this->addressAreaName ?: null,
            'postal_code' => $this->addressPostalCode ?: null,
            'is_primary' => $shouldBePrimary,
        ]);
        $address->save();

        $this->resetAddressForm();
        $this->view = 'list';
        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Alamat berhasil disimpan.',
        ]);
    }

    public function setPrimaryAddress(int $addressId): void
    {
        $address = $this->customerAddressQuery()->findOrFail($addressId);

        CustomerAddress::where('customer_id', Auth::id())->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Alamat utama berhasil diperbarui.',
        ]);
    }

    public function deleteAddress(int $addressId): void
    {
        $address = $this->customerAddressQuery()->findOrFail($addressId);
        $wasPrimary = $address->is_primary;
        $address->delete();

        if ($wasPrimary) {
            $replacement = $this->customerAddressQuery()->oldest('id')->first();
            $replacement?->update(['is_primary' => true]);
        }

        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Alamat berhasil dihapus.',
        ]);
    }

    private function resetAddressForm(): void
    {
        $this->addressLabel = '';
        $this->addressRecipientName = '';
        $this->addressRecipientPhone = '';
        $this->addressRecipientEmail = '';
        $this->addressFull = '';
        $this->addressNote = '';
        $this->addressAreaId = '';
        $this->addressAreaName = '';
        $this->addressPostalCode = '';
        $this->addressAreaSearch = '';
        $this->addressAreaResults = [];
        $this->addressIsPrimary = false;
        $this->editingAddressId = null;
        $this->resetValidation([
            'addressLabel',
            'addressRecipientName',
            'addressRecipientPhone',
            'addressRecipientEmail',
            'addressFull',
            'addressNote',
            'addressAreaId',
            'addressAreaName',
            'addressPostalCode',
        ]);
    }

    private function customerAddressQuery()
    {
        return CustomerAddress::query()->where('customer_id', Auth::id());
    }

    public function render()
    {
        return view('livewire.address-book-component', [
            'addresses' => Auth::check()
                ? $this->customerAddressQuery()->orderByDesc('is_primary')->latest('id')->get()
                : collect(),
        ]);
    }
}
