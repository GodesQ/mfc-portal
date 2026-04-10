# Early Bird Discount
The Early Bird feature allow the event organizer or admin to add an Early Bird Discount in Event. This will be applied when there's a registration fee in the event.

## Specs to Implement
1. Make a migration to add new column in events table for status and amount of early bird discount.
2. In, event registration when there's a registration fee, implement a discount if the early bird is enabled in the event.
3. For event_registration and transaction table, add column (make migration) for early_bird_discount.
5. If there's a multiple attendee in one booking, only apply the discount to a primary attendee and not all attendee.
4. Add email service to notify the attendee that claim the the early bird discount to congratulate and thank you the attendee.
