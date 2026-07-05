# mojahed/spa

A Laravel SPA package powered by **Alpine.js**, **Axios**, and **Bootstrap** — no build step, no npm, no Vite. Load blade fragments dynamically, manage modals, offcanvases, and independent page sections with clean declarative components.

---

## Requirements

- PHP >= 7.4
- Laravel >= 7.x (components & directives)
- Laravel >= 9.36 (for the `SpaRequest::fragmentIf()` controller helper)
- Bootstrap 5
- Alpine.js 3.x
- Axios
- SweetAlert2 (for confirm dialogs and toasts)

---

## Installation

```bash
composer require mojahed/spa
```

Publish and copy assets to your public directory:

```bash
php artisan spa:setup
```

Optionally publish config and views for customization:

```bash
php artisan spa:publish
```

---

## Layout Setup

Add directives to your main layout file:

```html
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    @spacss
    @stack('styles')
</head>

<body @spadata>

    @include('partials.sidebar')

    <div class="main-content">
        @yield('content')
    </div>

    @spajs
    @stack('scripts')

</body>
</html>
```

Use a custom Alpine component name:

```html
<body @spadata('myAppData')>
```

Then define it in your scripts:

```javascript
function myAppData() {
    return {
        ...spa(),
        // your custom properties and methods
    };
}
```

---

## Directives

| Directive | Output |
|---|---|
| `@spacss` | CSS link tags (Bootstrap + spa.css) |
| `@spajs` | JS script tags (Alpine, Axios, Bootstrap, SweetAlert2, spa.js) + CSRF headers |
| `@spadata` | `x-data="spa()"` on body |
| `@spadata('name')` | `x-data="name()"` on body |

---

## Components

### `<x-spa-target>` — Inline Fragment Zone

Loads a blade fragment into an inline div. Optionally auto-loads on page ready.

```html
<x-spa-target
    id="table-wrapper"
    url="{{ route('employees.index') }}"
    auto-load="true"
    loader-type="table"
    loader-rows="6"
    loader-cols="5"
/>
```

**Props**

| Prop | Default | Description |
|---|---|---|
| `id` | required | Unique ID |
| `url` | `''` | URL to fetch |
| `auto-load` | `false` | Load on page ready |
| `loader-type` | config default | `spinner` \| `skeleton` \| `table` \| `card` |
| `loader-rows` | `5` | Skeleton/table rows |
| `loader-cols` | `4` | Table columns |
| `method` | `get` | HTTP method |
| `params` | `[]` | Query params array |

---

### `<x-spa-modal>` — Bootstrap Modal Container

```html
<x-spa-modal id="form-modal" size="xl" />
```

**Props**

| Prop | Default | Description |
|---|---|---|
| `id` | required | Unique ID |
| `size` | `lg` | `sm` \| `md` \| `lg` \| `xl` \| `fullscreen` |
| `title` | `''` | Modal title |
| `loader-type` | config default | Loader type while loading |
| `static-backdrop` | `true` | Prevent close on outside click |
| `scrollable` | `false` | Scrollable modal body |

---

### `<x-spa-offcanvas>` — Bootstrap Offcanvas Container

```html
<x-spa-offcanvas id="details-offcanvas" title="Details" width="500px" />
```

**Props**

| Prop | Default | Description |
|---|---|---|
| `id` | required | Unique ID |
| `title` | `''` | Header title |
| `placement` | `end` | `start` \| `end` \| `top` \| `bottom` |
| `width` | `450px` | Width (end/start only) |
| `loader-type` | config default | Loader type while loading |

---

### `<x-spa-btn>` — Button Trigger

```html
{{-- Load into modal --}}
<x-spa-btn modal="form-modal" url="{{ route('employees.create') }}" class="btn btn-success btn-sm">
    New Employee
</x-spa-btn>

{{-- Delete with confirm --}}
<x-spa-btn
    url="{{ route('employees.destroy', $emp->id) }}"
    method="DELETE"
    confirm="true"
    confirm-title="Delete this employee?"
    confirm-text="This cannot be undone."
    on-success-reload="#table-wrapper"
    on-success-toast="Employee deleted."
    class="btn btn-danger btn-xs">
    Delete
</x-spa-btn>
```

**Props**

| Prop | Default | Description |
|---|---|---|
| `url` | `''` | Target URL |
| `method` | `get` | HTTP method |
| `target` | `''` | Load into `#spa-target` id |
| `modal` | `''` | Load into modal id |
| `offcanvas` | `''` | Load into offcanvas id |
| `confirm` | `false` | Show confirm dialog first |
| `confirm-title` | config | Confirm dialog title |
| `confirm-text` | config | Confirm dialog body text |
| `confirm-type` | `warning` | SweetAlert icon type |
| `confirm-ok` | config | Confirm button label |
| `confirm-cancel` | config | Cancel button label |
| `on-success-reload` | `''` | Reload target by `#id` |
| `on-success-close` | `''` | Close modal/offcanvas by `#id` |
| `on-success-toast` | `''` | Show success toast message |
| `on-success-redirect` | `''` | Redirect to URL |
| `on-success-emit` | `''` | Dispatch custom Alpine event |

---

### `<x-spa-link>` — Anchor Trigger

Same props as `<x-spa-btn>`, renders as an `<a>` tag.

```html
<x-spa-link
    offcanvas="details-offcanvas"
    url="{{ route('employees.show', $emp->id) }}"
    class="text-info">
    View Details
</x-spa-link>
```

---

### `<x-spa-form>` — Axios Form Submit

Handles form submission with error binding and submitting state. Pass initial
field values through `:data` and bind inputs with `x-model="{model}.{field}"`.

```html
<x-spa-form
    url="{{ route('items.store') }}"
    method="POST"
    model="form"
    :data="['name' => '', 'status' => 'active']"
    on-success-reload="#table-wrapper"
    on-success-close="#form-modal"
    on-success-toast="Saved!"
>
    <input type="text" x-model="form.name" :class="{ 'is-invalid': errors.name }">
    <div class="invalid-feedback" x-text="errors.name"></div>

    <button type="button" @click="submit()" :disabled="submitting">
        <span x-show="submitting" class="spinner-border spinner-border-sm"></span>
        Save
    </button>
</x-spa-form>
```

**Props**

| Prop | Default | Description |
|---|---|---|
| `url` | required | Submit URL |
| `method` | `post` | HTTP method |
| `model` | `form` | Name of the Alpine data object holding the fields |
| `:data` | `[]` | Initial field values, e.g. `:data="['name' => $item->name]"` |

---

### `<x-spa-loader>` — Standalone Loader

```html
<x-spa-loader type="spinner" />
<x-spa-loader type="skeleton" rows="5" />
<x-spa-loader type="table" rows="6" cols="4" />
<x-spa-loader type="card" rows="3" />
```

---

## Dashboard — Independent Sections

Each section loads independently with its own loader, just like the AWS dashboard:

```html
<div class="row g-3">
    <div class="col-md-4">
        <x-spa-target id="widget-revenue"    url="{{ route('dashboard.revenue') }}"    auto-load="true" loader-type="card" />
    </div>
    <div class="col-md-4">
        <x-spa-target id="widget-attendance" url="{{ route('dashboard.attendance') }}" auto-load="true" loader-type="skeleton" />
    </div>
    <div class="col-md-4">
        <x-spa-target id="widget-notices"    url="{{ route('dashboard.notices') }}"    auto-load="true" loader-type="spinner" />
    </div>
</div>
```

---

## Controller — Fragment Detection

Use Laravel's built-in fragment system with the `X-SPA-Request` header automatically sent by the package:

```php
use Mojahed\Spa\SpaRequest;

public function index(Request $request)
{
    $employees = Employee::all();

    return SpaRequest::fragmentIf($request,
        view('employees.index', compact('employees')),
        'list'
    );
}
```

Or directly with Laravel's `fragmentIf`:

```php
return view('employees.index', compact('employees'))
    ->fragmentIf($request->hasHeader('X-SPA-Request'), 'list');
```

---

## JS Utilities

All utilities are globally available:

```javascript
// Toasts
spaToast({ type: 'success', title: 'Saved!', seconds: 3 });
spaToast({ type: 'error',   title: 'Something went wrong.' });

// URL params
spaSetParam('page', 2);
spaGetParam('page', 1);
spaRemoveParam('page');
spaGetAllParams();

// Script execution from fragment HTML
spaRunScriptCode(html);

// Trigger an action programmatically
spaAction({
    url:      '/employees/1',
    method:   'delete',
    confirm:  { enabled: true, title: 'Delete?' },
    onSuccess: { toast: 'Deleted!', reload: 'table-wrapper' }
});
```

---

## Configuration

Publish and edit `config/spa.php`:

```php
return [
    'asset_path'       => 'assets/spa',
    'asset_version'    => null,         // e.g. '1.0' for cache busting
    'default_loader'   => 'spinner',    // spinner | skeleton | table | card
    'auto_run_scripts' => true,
    'confirm' => [
        'title'  => 'Are you sure?',
        'text'   => "You won't be able to revert this!",
        'type'   => 'warning',
        'ok'     => 'Yes, proceed!',
        'cancel' => 'Cancel',
    ],
];
```

---

## Legacy Compatibility

If you are migrating from existing code that uses `toast()`, `setParam()`, `getParam()`, `runScriptCode()` — these are aliased to the new `spa*` functions so your existing code continues to work without changes.

---

## License

MIT — [Md. Mojahedul Islam](https://github.com/md-mojahed)
