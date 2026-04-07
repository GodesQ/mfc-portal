# Public Event Registration

## Goal

Implement a public event registration experience for public users, guest users, and authenticated users, starting from the public event details page and ending at the payment success page.

The feature must support:
* authenticated member registration
* guest registration without creating a `users` row
* payment via the existing transaction and PayMaya/Maya flow
* safe rendering of guest-backed registrations in admin and payment pages

This spec is organized into implementation phases so each phase can be developed independently while still following one complete flow.

---

## End-to-End Flow

### 1. Public Event Details Page

* Entry page: public event details page at `GET /events/show/{identifier}`
* Source view: `resources/views/pages/events/show.blade.php`
* Only events with `is_enable_event_registration = true` should show the registration CTA.
* Registration CTA behavior:
  + if the visitor is not authenticated, redirect to a public registration entry page
  + if the visitor is authenticated, redirect directly to the event registration form

### 2. Public Registration Entry Page

* New public page for choosing how to continue registration
* Recommended route: `GET /events/{event}/register/public`
* Purpose:
  + explain that the visitor can continue as a member or as a guest
  + show the event summary
  + show the registration fee
  + show two clear choices:
    - `Register as Member`
    - `Register as Guest`

#### Behavior

* If user is already authenticated:
  + skip this page and redirect directly to the authenticated registration form
* If user selects `Register as Member`:
  + redirect to login page
  + after successful login, redirect back to the event registration form for the same event
* If user selects `Register as Guest`:
  + redirect to the public guest registration form

### 3. Authenticated Event Registration Form

* Existing page: `resources/views/pages/events/register.blade.php`
* Existing route shape: `GET /dashboard/events/{event_id}/register`
* This page remains for authenticated users only.

#### Scope

* allow logged-in users to register themselves
* allow logged-in users to register additional attendees based on current app rules
* preserve the current donation and convenience fee calculation
* continue to create transaction + event registrations + event user details

#### Required updates

* save `event_registrations.user_id`
* save payer snapshot fields in `transactions`
* save `event_user_details.user_type`
* ensure one attendee per registration row remains the current behavior

### 4. Guest Event Registration Form

* New public page for non-authenticated users
* Recommended route: `GET /events/{event}/register/guest`
* New form POST route: `POST /events/{event}/register/guest`

#### Form sections

* Primary registrant / payer details
  + first name
  + last name
  + email
  + contact number
  + address
* Attendee list
  + at minimum Phase 1 of the public UI may support one attendee only
  + later phase may support multiple attendees
* Donation field
* Order summary
  + registration fee
  + convenience fee
  + donation
  + total amount
* Event summary card

#### Guest form rules

* guest registration must not create a `users` row
* guest registration stores attendee details in `event_user_details`
* primary registrant row must be marked with `user_type = primary`
* additional attendees must be marked with `user_type = normal`
* transaction payer snapshot fields must be filled from the guest payer form
* transaction `received_from_id` must remain `null` for guest payer flow

### 5. Payment Redirect

* After successful submission of authenticated or guest registration:
  + create transaction
  + create event registration row(s)
  + create event user detail row(s)
  + call Maya checkout
  + redirect to Maya hosted payment page

### 6. Payment Success Page

* Existing route: `GET /redirect/payment/success`
* Existing controller: `RedirectController@payment_success`
* Existing view: `resources/views/pages/payments/redirect-success.blade.php`

#### Requirements

* must render for both member-backed and guest-backed transactions
* `Received From` section:
  + use member data if `received_from_id` exists
  + otherwise use payer snapshot fields
* line items:
  + use `event_registrations.user` when present
  + otherwise use `event_user_details`
* payment success page must not assume all registrations have a linked `users` row

### 7. Payment Failed / Cancelled

* Existing routes:
  + `GET /redirect/payment/failed`
  + `GET /redirect/payment/cancelled`
* These pages should remain public and usable for both guest and authenticated transactions.

---

## Functional Requirements

### Event Availability Rules

* Public registration is only available when:
  + event exists
  + event is active enough to be shown publicly
  + `is_enable_event_registration = true`
* If event registration is disabled:
  + return `404` or a dedicated unavailable page

### Authentication Rules

* Guests can access:
  + public event details page
  + registration entry page
  + guest registration form
  + payment redirect pages
* Authenticated users can access:
  + public event details page
  + authenticated registration form
  + payment redirect pages
* Authenticated users visiting guest-only public entry steps may be redirected forward to the authenticated flow.

### Registration Rules

* Every registration must belong to one event.
* Every registration must belong to one transaction.
* Every attendee must have one `event_registrations` row.
* Every attendee must have one `event_user_details` row.
* Every registration set must have one primary customer/registrant.
* Duplicate registration must be blocked.

#### Duplicate handling

* Authenticated attendee:
  + duplicate check should use `event_registrations.user_id`
* Guest attendee:
  + duplicate check should use event + normalized attendee identity, minimum:
    - first name
    - last name
    - email
  + exact duplicate matching policy can be tightened later if needed

### Payment Rules

* Payment type must remain `event_registration`.
* Convenience fee calculation must be consistent between authenticated and guest flows.
* Transaction amount must equal:
  + registration subtotal
  + plus donation
  + plus total convenience fee
* Both guest and authenticated flows must use the same Maya integration service.

### Attendance Rules

* Attendance remains tied to real `users` in the current system.
* Guest attendance scanning is out of scope for the initial public registration rollout.
* Guest registration attendance access must fail safely and never throw a null relationship error.

---

## Routes

### Existing Routes Used

* `GET /events/show/{identifier}` -> public event details page
* `GET /redirect/payment/success`
* `GET /redirect/payment/failed`
* `GET /redirect/payment/cancelled`

### New Routes Required

* `GET /events/{event}/register/public`
  + public registration entry page
* `GET /events/{event}/register/guest`
  + guest registration form page
* `POST /events/{event}/register/guest`
  + guest registration submit action
* `GET /events/{event}/register/member`
  + optional helper route that redirects to login or to authenticated event registration form

### Route Updates Required

* Public event details page CTA must stop posting to `/dashboard/events/register`.
* Public CTA should redirect using normal browser navigation to the new public registration entry route.
* Authenticated redirect-back after login should preserve the intended event ID.

---

## Data Model Requirements

### event_registrations

* support authenticated attendee:
  + `user_id` populated
  + `mfc_id_number` optional for compatibility
* support guest attendee:
  + `user_id = null`
  + attendee details resolved through `event_user_details`

### event_user_details

* add `user_type`
  + allowed values:
    - `primary`
    - `normal`
* store attendee snapshot fields for both guest and authenticated flows

### transactions

* support authenticated payer:
  + `received_from_id` populated
  + payer snapshot fields also populated
* support guest payer:
  + `received_from_id = null`
  + payer snapshot fields populated

### Related Read Behavior

* all payment, transaction, and event registration pages must use safe fallbacks:
  + member relationship first
  + guest snapshot second

---

## UI / Page Requirements

### Public Event Details Page

* CTA text: `Register Now`
* If event registration is enabled:
  + CTA should navigate, not AJAX post to dashboard-only route
* Event details should include:
  + title
  + description
  + poster
  + date
  + time
  + location
  + registration fee

### Public Registration Entry Page

* Must clearly explain the two paths:
  + member registration
  + guest registration
* Must show:
  + event title
  + event date/time
  + location
  + registration fee

### Guest Registration Form

* Must be mobile-friendly
* Must show validation errors clearly
* Must preserve entered values on validation failure
* Must show total summary before submit

### Payment Success Page

* Must show:
  + payer name
  + payer identifier if available
  + transaction reference
  + event registration items
  + total amounts
* Must render correctly for:
  + member payer + member attendees
  + guest payer + guest attendees
  + member payer + mixed attendee list if future business rules allow it

---

## Validation Rules

### Public Entry

* event must exist
* event must be open for registration

### Guest Registration Submit

* `event_id` required and valid
* primary payer first name required
* primary payer last name required
* payer email required and valid
* payer contact number required
* attendee list required
* donation optional numeric minimum `0`

### Authenticated Registration Submit

* preserve existing validation
* update duplicate checks to use `user_id`

---

## Error Handling

### Expected User Errors

* event not found
* registration disabled
* duplicate registration
* missing required guest fields
* invalid payment session creation

### System Safety Requirements

* rollback transaction if any registration row or payment setup fails
* never leave partial event registration rows without intended transaction linkage
* failed guest reads must degrade gracefully in UI

---

## Implementation Phases

## Phase 1 - Guest-Capable Registration Schema

### Goal
Allow event registration records and transactions to support guest/non-authenticated users.

### Scope

* `event_registrations`
* `event_user_details`
* `transactions`
* models and guest-safe read paths
* minimal controller and view hardening for guest-safe rendering

### Deliverables

* migration files for schema changes
* model updates
* guest-safe rendering in admin and payment pages
* fallback-safe transaction and registration reads

### Status Notes

* This phase is the database and compatibility foundation.
* It does not yet complete the public guest UI flow by itself.

---

## Phase 2 - Public Registration Entry and Routing

### Goal
Create the public registration entry point from the public event details page.

### Scope

* public route for registration entry
* update public event details CTA behavior
* login redirect intent preservation
* member-vs-guest selection page

### Deliverables

* `GET /events/{event}/register/public`
* public entry page UI
* public event page CTA update
* redirect rules:
  + guest -> guest form
  + unauthenticated member -> login
  + authenticated member -> authenticated registration form

### Acceptance Criteria

* guest visitor clicking `Register Now` no longer hits a dashboard-only route
* authenticated user reaches the member registration form directly
* unauthenticated user can choose guest or member path

---

## Phase 3 - Guest Registration Form and Submit Flow

### Goal
Allow guest users to complete registration and create a payable transaction.

### Scope

* guest registration form page
* guest submit controller action
* validation
* transaction creation
* event registration creation
* event user detail creation
* Maya checkout redirect

### Deliverables

* `GET /events/{event}/register/guest`
* `POST /events/{event}/register/guest`
* new request validation class for guest registration
* guest registration service/controller logic

### Acceptance Criteria

* guest can submit form without authentication
* guest registration creates:
  + transaction
  + event registration row(s)
  + event user detail row(s)
* guest is redirected to Maya payment page

---

## Phase 4 - Payment Redirect Completion

### Goal
Ensure success, failed, and cancelled payment pages work for both guest and member flows.

### Scope

* success redirect rendering
* failed and cancelled messaging review
* item display fallback logic
* payer display fallback logic

### Deliverables

* guest-safe receipt rendering on success page
* consistent item labels for attendee and payer identity
* safe rendering when no linked `users` row exists

### Acceptance Criteria

* guest-paid transaction renders successfully after payment
* no null errors on success page
* failed/cancelled pages still work for guest transactions

---

## Phase 5 - Duplicate Checks, Testing, and Cleanup

### Goal
Harden the registration flow for production use.

### Scope

* duplicate registration rules for guest and member paths
* feature tests
* regression tests
* controller cleanup or service extraction if needed
* copy and UX refinement

### Deliverables

* automated tests for:
  + public event CTA flow
  + member registration redirect flow
  + guest registration submit flow
  + payment success rendering
  + duplicate prevention
* technical cleanup for maintainability

### Acceptance Criteria

* no duplicate attendee registration for the same event
* tests cover both member and guest flows
* public flow is ready for further enhancements

---

## Optional Future Phases

## Phase 6 - Multi-Attendee Guest Registration

* allow guest payer to register multiple attendees in one checkout
* support one primary attendee plus multiple normal attendees
* enhance summary calculations and duplicate checks

## Phase 7 - Guest Attendance Support

* add attendance model support for non-user attendees
* enable guest QR or registration code scanning
* update reports and admin attendance UI

## Phase 8 - Guest Registration Management

* resend receipt
* registration lookup by email/reference code
* self-service guest registration status page

---

## Test Scenarios

### Public Routing

* guest opens public event details page and clicks register
* authenticated user opens public event details page and clicks register
* registration CTA hidden when event registration is disabled

### Member Flow

* unauthenticated visitor chooses member path and is redirected to login
* after login, user returns to the correct event registration form
* authenticated registration still creates valid transaction and event registration data

### Guest Flow

* guest opens guest form and submits valid data
* guest sees validation errors for incomplete form
* guest receives payment redirect URL
* guest success page shows correct payer and attendee details

### Edge Cases

* invalid event ID
* disabled event registration
* duplicate attendee
* Maya payment setup failure
* success page for guest registration without user relationship

---

## Summary

This feature should be developed as a public-to-payment journey, not just as a form page. The recommended build order is:

1. Phase 1: schema and guest-capable data foundation
2. Phase 2: public entry routing and selection page
3. Phase 3: guest registration form and submit flow
4. Phase 4: payment completion and receipt rendering
5. Phase 5: duplicate prevention, tests, and polish

That order keeps the system stable while enabling incremental delivery of the full public event registration experience.
