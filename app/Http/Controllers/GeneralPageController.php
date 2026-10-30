<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralPageController extends Controller
{
    function about() {
        return view('about');
    }

    function faq() {
        $locale = app()->getLocale();

        $faqSections = collect(['my-account', 'products', 'payment', 'shipping', 'stores', 'return-policy', 'refund'])
            ->map(function (string $section) use ($locale) {
                $path = resource_path("contents/faq/{$locale}/{$section}.md");

                if (! file_exists($path)) {
                    $path = resource_path("contents/faq/en/{$section}.md");
                }

                if (! file_exists($path)) {
                    return null;
                }

                $markdown = file_get_contents($path);

                preg_match('/^##\s+(.+)$/m', $markdown, $titleMatch);
                $title = $titleMatch[1] ?? $section;

                $parts = preg_split('/^###\s+/m', $markdown, -1, PREG_SPLIT_NO_EMPTY);
                $items = [];

                foreach (array_slice($parts, 1) as $part) {
                    $lines = explode("\n", trim($part), 2);

                    $items[] = [
                        'question' => trim($lines[0]),
                        'answer' => trim($lines[1] ?? ''),
                    ];
                }

                return ['title' => $title, 'items' => $items];
            })
            ->filter();

        return view('faq', compact('faqSections'));
    }

    function terms_and_privacy() {
        $locale = app()->getLocale();
        $path = resource_path("contents/terms/{$locale}/privacy-policy.md");

        if (! file_exists($path)) {
            $path = resource_path("contents/terms/en/privacy-policy.md");
        }

        $termsContent = file_exists($path) ? file_get_contents($path) : null;

        return view('terms-and-privacy', compact('termsContent'));
    }

    function track_order() {
        $locale = app()->getLocale();
        $path = resource_path("contents/track-order/{$locale}/shipping-delivery.md");

        if (! file_exists($path)) {
            $path = resource_path("contents/track-order/en/shipping-delivery.md");
        }

        $trackContent = file_exists($path) ? file_get_contents($path) : null;

        return view('track-my-order', compact('trackContent'));
    }
}
