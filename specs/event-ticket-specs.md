# Event Ticket Specs

The current implementation of Event Ticket is more than just what I need. I want to refactor the whole process of tickets in event.

Requirements:
- Remove unnecessary columns and fields when creating and editing tickets. Below are the actual fields that I need.
    - id
    - ticket_name
    - price
    - description
    - is_unlimited
    - total_number_of_tickets
    - created_at
    - updated_at