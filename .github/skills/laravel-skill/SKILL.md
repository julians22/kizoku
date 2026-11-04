---
name: laravel-lean
description: Token-efficient Laravel 12 guidance for Blade, Livewire 3, Alpine.js, Vite, and Tailwind work. Use whenever the task touches a Laravel project (views, Livewire components, routes, controllers, config, assets), even if the user does not mention the version. Keeps edits targeted, reads minimal, and verification narrow to save usage limit. Defer to the project's own instruction file for project boundaries and conventions.
---

# Laravel 12 (lean)

Project instruction files win over this skill. Do not restate them; apply this skill only for efficiency and Laravel 12 correctness.

## Efficiency rules (highest priority)

**Reading**
- Do not re-read files already in context. Read line ranges, not whole files, when the target is known.
- Locate with search (grep / file search) first. Never list or read `vendor/`, `node_modules/`, `storage/`, `public/build/`, `bootstrap/cache/`, or lock files.
- Read sibling components/views only to copy the local pattern, one example is enough.
- Batch independent reads and edits in a single step.

**Writing**
- Modify the existing code in place with targeted edits. Never rewrite a whole file for a small change.
- Match existing naming, markup, and class conventions. No new abstractions, packages, or files unless required.
- Do not add tests, docs, comments, or refactors that were not requested.
- Output only the changed hunk when showing code; no full-file dumps.

**Running commands**
- One narrowest check, once: `php artisan test --filter=Name` or a single test file. Run `pnpm run build` only if assets/Vite modules changed.
- Skip `composer install/update`, `pnpm install`, `--coverage`, and the full test suite unless asked or no narrower check exists.
- Limit noisy output: `php artisan route:list --path=x` / `--name=x`, and trim long output (`| Select-Object -First 40` on PowerShell, `| head -n 40` on bash).
- Use `--no-interaction` on `php artisan make:*`.

**Replying**
- If unclear, state one assumption and proceed; ask at most one question.
- Final reply: what changed and which check passed, in a few lines. No diff recap, no restating the request.

## Laravel 12 structure (do not generate old patterns)

- No `Http/Kernel.php`, `Console/Kernel.php`, `Exceptions/Handler.php`, or `RouteServiceProvider`. Middleware, exceptions, and routing live in `bootstrap/app.php`; extra providers in `bootstrap/providers.php`; schedules in `routes/console.php`.
- `routes/api.php` exists only after `php artisan install:api`.
- Casts: `protected function casts(): array`. No `$dates`.
- Carbon 3: `diffIn*()` returns floats; cast when an integer is needed.
- Queued jobs use `Illuminate\Foundation\Queue\Queueable`.
- Read config with `config()`; `env()` only inside `config/*.php`.
- Eager load relations (`with()`); avoid queries in Blade.

## Livewire 3

- One root element per component. Add `wire:key` to looped items.
- `wire:model` is deferred by default; use `.live` (and `.live.debounce.300ms`) only for real-time needs.
- Use `$this->dispatch('event')` and `#[On('event')]`, not `emit`. Use `#[Computed]` for derived data, `#[Validate]` or `rules()` for validation.
- Livewire 3 bundles Alpine. Never load a second Alpine instance; use `Alpine.data()` / `x-data` for browser-only state.
- Provide `wire:loading`, empty, error, and validation states for user-facing changes.
- Use `wire:navigate` only where the app already uses it.

## Blade, Vite, Tailwind

- Markup in Blade (`<x-...>` components, `@props`), server state in Livewire, small browser-only state in Alpine.
- Assets via `@vite([...])` using existing entry points only.
- Check `package.json` before writing Tailwind code. v3 uses `tailwind.config.js` and `@tailwind` directives; v4 uses `@import "tailwindcss"` and `@theme` in CSS. Follow whichever the project has.

## Never

- Raw SQL with interpolated input; hard-coded secrets or env values in views/assets.
- Business logic in controllers or Blade.
- Skipping validation on user input.
- Inventing data to fill a missing field: verify the contract in the owning project first.
