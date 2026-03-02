<?php

namespace App\Livewire;

use App\Livewire\Traits\ParfumeCollection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CollectionComponent extends Component
{
    use ParfumeCollection;

    public $collections;

    public function mount($category)
    {
        $collections = $this->getParfumeCollections();

        $this->collections = collect($collections);
    }

    public function render()
    {
        return view('livewire.collection-component');
    }
}
