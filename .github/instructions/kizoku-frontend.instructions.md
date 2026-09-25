---
name: kizoku-frontend
description: "Use when building or changing Kizoku's Laravel frontend, Livewire UI, Blade views, assets, or frontend-facing adapters that consume data owned by the Karuizawa project."
applyTo: "resources/**, app/Livewire/**, app/Http/Controllers/**, routes/**, config/**"
---

# Kizoku Frontend Instructions

## Project Boundary

- Kizoku is the frontend subproject of the Karuizawa Project.
- Build the user-facing experience in Kizoku using Laravel, Blade, Livewire, Vite, Tailwind CSS, Alpine.js, and the existing frontend dependencies.
- Treat `C:\laragon\www\karuizawa` as the owning project for domain models, migrations, schema changes, seeders, and database-backed business rules.
- Use Karuizawa's existing models, migrations, APIs, and database contract as the source of truth. Inspect that project when the data shape or integration contract is unclear.
- Do not create or duplicate domain models, migrations, seeders, or schema changes in Kizoku unless the user explicitly changes this project boundary.
- Do not modify files in Karuizawa as part of a Kizoku frontend task unless the user explicitly asks for a cross-project change.

## Implementation Rules

- Prefer existing Kizoku components, layouts, Livewire components, services, translations, icons, and styles before introducing new abstractions.
- Keep frontend behavior in the owning layer: Blade for markup, Livewire for server-backed interactive state, Alpine for small browser-only interactions, and JavaScript modules for client-side integrations.
- Keep controllers and routes thin. They may adapt or proxy data for the frontend, but they must not become a second domain layer.
- Preserve existing authentication, checkout, localization, toast, and payment integration contracts unless the task explicitly requires a contract change.
- Use the existing Vite entry points and asset pipeline. Avoid adding a second build system or a parallel styling framework.
- Keep responsive behavior, loading states, empty states, validation feedback, error states, and accessible interaction states complete for user-facing changes.
- Reuse the existing icon package and project visual language. Do not add a new icon or UI library without a clear need.
- Keep secrets, credentials, and environment-specific values in environment/configuration files; never hard-code them in views or client assets.

## Data and Integration

- Treat data returned by Karuizawa or its API as an external contract. Validate and normalize it at the integration boundary instead of spreading shape assumptions through views.
- When a backend field, endpoint, or relationship is missing, first verify the contract in Karuizawa and then make the smallest frontend adapter change needed.
- Do not silently fall back to invented records or fake persistence in production-facing flows.
- Keep database access and API calls out of Blade templates. Use Livewire components, services, or controllers according to the existing local pattern.

## Verification

- For frontend changes, run the narrowest relevant check first, then `pnpm run build` when assets or Vite modules are involved.
- For Livewire, Blade, route, or PHP adapter changes, run the relevant Laravel test or `php artisan test` when no narrower test exists.
- Check desktop and mobile layouts for visual changes and verify that interactive states do not overlap or shift unexpectedly.
- Before finishing, review the diff for accidental schema/model work, hard-coded secrets, duplicated business logic, and unrelated formatting changes.
