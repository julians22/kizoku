<?php

namespace App\Livewire\Traits;

trait ParfumeCollection
{
    private function getParfumeCollections()
    {
        // KAHEN Collection
        // KAKAN Collection
        // KOHAKU Collection
        // KI Collection
        return [
            [
                'name' => 'KAHEN',
                'slug' => 'kahen',
                'image' => asset('img/KAHEN.png'),
                'price' => 1500000
            ],
            [
                'name' => 'KAKAN',
                'slug' => 'kakan',
                'image' => asset('img/KAKAN.png'),
                'price' => 1500000
            ],
            [
                'name' => 'KOHAKU',
                'slug' => 'kohaku',
                'image' => asset('img/KOHAKU.png'),
                'price' => 1500000
            ],
            [
                'name' => 'KI',
                'slug' => 'ki',
                'image' => asset('img/KI.png'),
                'price' => 1500000
            ],
        ];
    }
}
