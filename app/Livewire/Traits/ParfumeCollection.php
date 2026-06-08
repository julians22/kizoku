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
                'price' => 398000,
                'product_features' => [
                    [
                        'title' => 'Persona',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Good to know',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Notes & Accords',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Ingredients',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ]
                ]
            ],
            [
                'name' => 'KAKAN',
                'slug' => 'kakan',
                'image' => asset('img/KAKAN.png'),
                'price' => 398000,
                'product_features' => [
                    [
                        'title' => 'Persona',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Good to know',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Notes & Accords',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Ingredients',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ]
                ]
            ],
            [
                'name' => 'KOHAKU',
                'slug' => 'kohaku',
                'image' => asset('img/KOHAKU.png'),
                'price' => 398000,
                'product_features' => [
                    [
                        'title' => 'Persona',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Good to know',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Notes & Accords',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Ingredients',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ]
                ]
            ],
            [
                'name' => 'KI',
                'slug' => 'ki',
                'image' => asset('img/KI.png'),
                'price' => 398000,
                'product_features' => [
                    [
                        'title' => 'Persona',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Good to know',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Notes & Accords',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ],
                    [
                        'title' => 'Ingredients',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec suscipit auctor dui, sed efficitur enim.'
                    ]
                ]
            ],
        ];
    }
}
