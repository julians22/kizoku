<?php

namespace App\Livewire\Traits;

use Spatie\LaravelMarkdown\MarkdownRenderer;

trait ParfumeCollection
{
    private function getMdContent(string $slug, string $fileName)
    {
        $file_path = resource_path("contents/products/{$slug}/{$fileName}.md");
        if (file_exists($file_path)) {
            $rawContent = file_get_contents($file_path);
            return $rawContent;
        }
        return null;
    }

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
                'image' => asset('img/product/product_Kahen.webp'),
                'image_hover' => asset('img/product/product_Kahen_hover.webp'),
                'image_alt' => 'KAHEN Image',
                'description' => '“Kahen” emits a breeze of spring morning wind on every spray — invigorating and active. Ideal for outdoor settings and casual to smart-casual occasions, the scent blends hyacinth, jasmine, and ylang-ylang with a vigorous green freshness, then settles into the warm, grounding calm of vetiver and cedarwood.',
                'insight_images' => [
                    asset('img/product/kahen/web_image-1.jpg'),
                    asset('img/product/kahen/web_image-2.jpg'),
                    asset('img/product/kahen/web_image-3.jpg')
                ],
                'price' => 398000,
                'product_features' => [
                    [
                        'title' => 'Best for',
                        'description' => $this->getMdContent('kahen', 'best-for')
                    ],
                    [
                        'title' => 'Signature & Moment',
                        'description' => $this->getMdContent('kahen', 'signature-moment')
                    ],
                    [
                        'title' => 'Notes & Accords',
                        'description' => $this->getMdContent('kahen', 'notes')
                    ],
                    [
                        'title' => 'Ingredients',
                        'description' => $this->getMdContent('kahen', 'ingredients')
                    ]
                ]
            ],
            [
                'name' => 'KAKAN',
                'slug' => 'kakan',
                'image' => asset('img/product/product_Kakan.webp'),
                'image_hover' => asset('img/product/product_Kakan_hover.webp'),
                'image_alt' => 'KAKAN Image',
                'description' => '“Kakan” is composed, refined, and timeless — an expression of quiet confidence as the evening unfolds. Saffron, vanilla, and olibanum open with a bold, leathery smoke, softened by subtle sweetness. “Kakan” is the scent of those who value precision and authenticity, a modern classic defined by confidence and leathery nuances.',
                'insight_images' => [
                    asset('img/product/kakan/web_image-1.jpg'),
                    asset('img/product/kakan/web_image-2.jpg'),
                    asset('img/product/kakan/web_image-3.jpg')
                ],
                'price' => 398000,
                'product_features' => [
                    [
                        'title' => 'Best for',
                        'description' => $this->getMdContent('kakan', 'best-for'),
                    ],
                    [
                        'title' => 'Signature & Moment',
                        'description' => $this->getMdContent('kakan', 'signature-moment')
                    ],
                    [
                        'title' => 'Notes & Accords',
                        'description' => $this->getMdContent('kakan', 'notes')
                    ],
                    [
                        'title' => 'Ingredients',
                        'description' => $this->getMdContent('kakan', 'ingredients')
                    ]
                ]
            ],
            [
                'name' => 'KOHAKU',
                'slug' => 'kohaku',
                'image' => asset('img/product/product_Kohaku.webp'),
                'image_hover' => asset('img/product/product_Kohaku_hover.webp'),
                'image_alt' => 'KOHAKU Image',
                'description' => '“Kohaku” is smooth, subtle, and sensual — best worn during the day to evenings into the depths of the night. Fresh aldehydic and green nuances blend with the gentle fruitiness of pear, leading into a delicate floral heart of muguet, rose, and jasmine. A balanced scent that leaves a delicately alluring impression.',
                'insight_images' => [
                    asset('img/product/kohaku/web_image-1.jpg'),
                    asset('img/product/kohaku/web_image-2.jpg'),
                    asset('img/product/kohaku/web_image-3.jpg')
                ],
                'price' => 398000,
                'product_features' => [
                    [
                        'title' => 'Best for',
                        'description' => $this->getMdContent('kohaku', 'best-for'),
                    ],
                    [
                        'title' => 'Signature Moments',
                        'description' => $this->getMdContent('kohaku', 'signature-moment'),
                    ],
                    [
                        'title' => 'Notes & Accords',
                        'description' => $this->getMdContent('kohaku', 'notes'),
                    ],
                    [
                        'title' => 'Ingredients',
                        'description' => $this->getMdContent('kohaku', 'ingredients')
                    ]
                ]
            ],
            [
                'name' => 'KI',
                'slug' => 'ki',
                'image' => asset('img/product/product_Ki.webp'),
                'image_hover' => asset('img/product/product_Ki_hover.webp'),
                'image_alt' => 'KI Image',
                'description' => 'Strong yet elegant — “Ki” is bold, rich, and earthy. Fresh and subtly sweet with mirabelle and pear, it unfolds into the warmth of date layered with jasmine and freesia, before deepening into earthy oakmoss and patchouli. “Ki” embodies the balance between strength and serenity, crafted for active days that extend into composed evenings.',
                'insight_images' => [
                    asset('img/product/ki/web_image-1.jpg'),
                    asset('img/product/ki/web_image-2.jpg'),
                    asset('img/product/ki/web_image-3.jpg')
                ],
                'price' => 398000,
                'product_features' => [
                    [
                        'title' => 'Best for',
                        // 'description' => 'Individuals who lead with consistency rather than spectacle. Thoughtful in their decisions and measured in their pace, they balance ambition with composure and leave a lasting impression through their character rather than their volume.'
                        'description' => $this->getMdContent('ki', 'best-for'),
                    ],
                    [
                        'title' => 'Signature & Moment',
                        'description' => $this->getMdContent('ki', 'signature-moment'),
                    ],
                    [
                        'title' => 'Notes & Accords',
                        'description' => $this->getMdContent('ki', 'notes'),
                    ],
                    [
                        'title' => 'Ingredients',
                        'description' => $this->getMdContent('ki', 'ingredients'),
                    ]
                ]
            ],
        ];
    }
}
