# Event Registration Fee Using Tickets

## Summary

Use `tickets` as the pricing source for private/authenticated event registration.
The private registration form lists the event tickets, requires one ticket selection,
stores that ticket on the transaction and each attendee registration, and sends the
ticket-based total to PayMaya through the existing transaction total.

Guest/public registration remains on the existing event registration fee flow.

## Phase 1: Data Model

- Add nullable `ticket_id` foreign keys to `transactions` and `event_registrations`.
- Keep historical records valid by allowing `ticket_id = null`.
- Add relationships:
  - `Transaction belongsTo Ticket`
  - `EventRegistration belongsTo Ticket`
  - `Ticket hasMany Transaction`
  - `Ticket hasMany EventRegistration`

## Phase 2: Private Registration UI

- Load an event with its tickets in `showPrivateRegistration`.
- Display each ticket with name, description, price/free label, and availability.
- Require one selected ticket using `ticket_id`.
- Disable unavailable/sold-out ticket options.
- Block registration from the page when the event has no available tickets.
- Recalculate the order summary from:
  - selected ticket price x attendee count
  - one early bird discount applied to the primary attendee
  - convenience fee x attendee count
  - donation

## Phase 3: Validation And Capacity

- Validate `ticket_id` in `EventRegistration\StoreRequest`.
- In `savePrivateRegistration`, verify the ticket belongs to the submitted event.
- Enforce ticket capacity before creating payment records:
  - unlimited tickets skip the capacity check
  - limited tickets count existing `event_registrations.ticket_id`
  - block registration when requested attendee count exceeds remaining slots

## Phase 4: Pricing And Persistence

- Keep guest registration pricing unchanged.
- Use a private ticket-pricing helper for authenticated registration.
- Treat free tickets as `0.00`; otherwise use `tickets.price`.
- Cap early bird discount against the selected ticket price.
- Save `ticket_id` on the transaction and every event registration row.
- Store each attendee's `amount` after any attendee-specific early bird discount.
- PayMaya continues to use `Transaction::total_amount`.

## Phase 5: Display Updates

- Event registration details show ticket name and ticket price when available.
- Legacy rows without a ticket fall back to the event registration fee.
- Transaction details continue to show each registration amount and include ticket name when available.

## Test Plan

- Private registration page lists all tickets for the event.
- Submitting without `ticket_id` fails validation.
- Submitting a ticket from another event fails server-side validation.
- Paid ticket with multiple attendees creates one transaction and multiple registrations with the same `ticket_id`.
- Free ticket creates a zero ticket subtotal while still applying donation and convenience fee.
- Early bird discount applies once and is capped by selected ticket price.
- Limited ticket blocks over-capacity registration.
- Unlimited ticket allows registration regardless of count.
- PayMaya request total matches the saved transaction total.

## Assumptions

- One selected ticket applies to all attendees in the transaction.
- This phase only changes private/authenticated registration pricing.
- Existing guest/public registration can be migrated to tickets in a separate phase.
- Historical registrations may have no ticket and must continue rendering safely.
