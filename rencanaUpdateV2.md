# Implementation Plan - Blade to Vue Migration 🚀

## Goal

Transition the Tunggal Jaya Transport frontend from Laravel Blade to a modern Single Page Application (SPA) using Vue 3 and Inertia.js to improve interactivity, performance, and user experience.

## Phase 1: Setup & Infrastructure (Completed)

-   [x] **Install Dependencies**: Install `vue`, `@vitejs/plugin-vue`, `@inertiajs/vue3`.
-   [x] **Vite Configuration**: Update `vite.config.js` to handle `.vue` files and setup aliases (`@` for `resources/js`).
-   [x] **Entry Point**: Configure `resources/js/app.js` to initialize the Inertia application with `createInertiaApp`.
-   [x] **Root View**: Create `resources/views/app.blade.php` containing the `@inertia` directive and `@vite` assets.
-   [x] **Middleware**: Setup `HandleInertiaRequests` to share common data (User, Flash messages, Ziggy routes).
-   [x] **Ziggy Integration**: Install Ziggy Vue plugin to use Laravel's named routes (`route()`) inside Vue components.

## Phase 2: Layout & Component Migration

### Layouts

-   **FrontendLayout.vue** (Replaces `layouts/frontend.blade.php`):
    -   [x] **Structure**: Create a persistent layout wrapper.
    -   [x] **Navbar**: Implement responsive navigation, mobile menu toggle, and active state logic.
    -   [x] **Footer**: informative footer with social links and contact info.
    -   [x] **Slots**: Use `<slot />` for dynamic page content.

### Components

-   **UI Elements**:
    -   [x] Buttons (`btn-premium`).
    -   [x] Form Inputs (`input-premium`).
    -   [x] Cards (`card-premium`).
    -   [x] Icons (FontAwesome integration).

## Phase 3: Page Migration (Home & Core)

### Home Page (`Home.vue`)

-   [x] **Hero Section**: Full-screen background, animated text, CTA buttons.
-   [x] **Search Widget**: Reactive form for Origin, Destination, Date, and Class selection using `v-model`.
-   [x] **Features Section**: Display service highlights with hover effects.
-   [x] **Routes Section**: Dynamic list of popular routes passed as props.
-   [x] **Fleet Section**: Showcase of bus fleet with images and specs.
-   [x] **News Section**: Latest updates grid.

### Remaining Pages (To Do)

-   [ ] **Fleet Page** (`Fleet/Index.vue`): Filterable list of all buses.
-   [ ] **Booking Flow**:
    -   [ ] `Booking/Search.vue`: Search results page.
    -   [ ] `Booking/SeatSelection.vue`: Interactive seat map.
    -   [ ] `Booking/Payment.vue`: Checkout/Payment confirmation.
-   [ ] **Static Pages**:
    -   [ ] `About.vue`: Company profile and history.
    -   [ ] `Contact.vue`: Contact form and map.
-   [ ] **Auth Pages**: Login, Register, Forgot Password (customized for Inertia).

## Phase 4: Backend Integration

-   [x] **Routes**: Update `routes/web.php` to use `Inertia::render('PageName', [props])` instead of `view()`.
-   [x] **Controllers**: Refactor controllers to pass data as JSON props (Arrays/Collections) rather than View variables.
-   [x] **Assets**: Move static images to `public/img` or import dynamically in Vue.

## Phase 5: Verification & Polish

-   [x] **Build Process**: specific `npm run build` workflow.
-   [x] **Responsiveness**: Verify layout on Mobile (iPhone SE/XR), Tablet (iPad), and Desktop.
-   [x] **Interactivity**: Test all dropdowns, form submissions, and buttons.
-   [x] **Performance**: Ensure lazy loading of components where applicable.
