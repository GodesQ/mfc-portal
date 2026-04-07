<?php

namespace Tests\Feature;

use App\Enum\PaymentType;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventUserDetail;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PaymayaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EventRegistrationGuestSupportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_support_schema_and_relationships_are_available(): void
    {
        $this->assertTrue(Schema::hasColumns('event_registrations', ['user_id', 'mfc_id_number']));
        $this->assertTrue(Schema::hasColumn('event_user_details', 'user_type'));
        $this->assertTrue(Schema::hasColumns('transactions', [
            'payer_first_name',
            'payer_last_name',
            'payer_email',
            'payer_contact_number',
        ]));

        $user = $this->createUser();
        $event = $this->createEvent();
        $transaction = $this->createTransaction([
            'received_from_id' => $user->id,
            'payer_first_name' => $user->first_name,
            'payer_last_name' => $user->last_name,
            'payer_email' => $user->email,
            'payer_contact_number' => $user->contact_number,
        ]);

        $authenticatedRegistration = EventRegistration::create([
            'registration_code' => 'REG-AUTH-001',
            'transaction_id' => $transaction->id,
            'event_id' => $event->id,
            'user_id' => $user->id,
            'mfc_id_number' => $user->mfc_id_number,
            'amount' => 1500,
            'registered_by' => $user->id,
            'registered_at' => now(),
        ]);

        $authenticatedDetail = EventUserDetail::create([
            'event_registration_id' => $authenticatedRegistration->id,
            'user_type' => 'primary',
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'address' => 'Makati City',
        ]);

        $guestRegistration = EventRegistration::create([
            'registration_code' => 'REG-GUEST-001',
            'transaction_id' => $transaction->id,
            'event_id' => $event->id,
            'amount' => 1500,
            'registered_at' => now(),
        ]);

        $guestDetail = EventUserDetail::create([
            'event_registration_id' => $guestRegistration->id,
            'user_type' => 'normal',
            'first_name' => 'Guest',
            'last_name' => 'Attendee',
            'email' => 'guest@example.test',
            'contact_number' => '639111111111',
            'address' => 'Quezon City',
        ]);

        $this->assertTrue($authenticatedRegistration->user->is($user));
        $this->assertTrue($authenticatedRegistration->event_user_detail->is($authenticatedDetail));
        $this->assertSame('primary', $authenticatedRegistration->event_user_detail->user_type);

        $this->assertNull($guestRegistration->user);
        $this->assertTrue($guestRegistration->event_user_detail->is($guestDetail));
        $this->assertSame('Guest Attendee', $guestRegistration->display_name);
        $this->assertSame('guest@example.test', $guestRegistration->display_email);
    }

    public function test_transaction_detail_page_renders_guest_event_registration(): void
    {
        $viewer = $this->createUser([
            'email' => 'viewer@example.test',
            'mfc_id_number' => 'MFCPH000010',
        ]);
        $event = $this->createEvent();
        $transaction = $this->createTransaction([
            'received_from_id' => null,
            'payer_first_name' => 'Guest',
            'payer_last_name' => 'Buyer',
            'payer_email' => 'buyer@example.test',
            'payer_contact_number' => '639222222222',
        ]);

        $registration = EventRegistration::create([
            'registration_code' => 'REG-GUEST-002',
            'transaction_id' => $transaction->id,
            'event_id' => $event->id,
            'amount' => 1500,
            'registered_at' => now(),
        ]);

        EventUserDetail::create([
            'event_registration_id' => $registration->id,
            'user_type' => 'primary',
            'first_name' => 'Guest',
            'last_name' => 'Attendee',
            'email' => 'guest-attendee@example.test',
            'contact_number' => '639333333333',
            'address' => 'Cebu City',
        ]);

        $response = $this->actingAs($viewer)->get(route('transactions.show', ['transaction' => $transaction->id]));

        $response->assertOk();
        $response->assertSee('Guest Buyer');
        $response->assertSee('buyer@example.test');
        $response->assertSee('Guest Attendee');
    }

    public function test_payment_success_page_renders_guest_backed_event_registration_transaction(): void
    {
        $event = $this->createEvent();
        $transaction = $this->createTransaction([
            'transaction_code' => 'TRX-GUEST-SUCCESS',
            'received_from_id' => null,
            'payer_first_name' => 'Guest',
            'payer_last_name' => 'Payer',
            'payer_email' => 'payer@example.test',
            'payer_contact_number' => '639444444444',
        ]);

        $registration = EventRegistration::create([
            'registration_code' => 'REG-GUEST-003',
            'transaction_id' => $transaction->id,
            'event_id' => $event->id,
            'amount' => 1500,
            'registered_at' => now(),
        ]);

        EventUserDetail::create([
            'event_registration_id' => $registration->id,
            'user_type' => 'primary',
            'first_name' => 'Guest',
            'last_name' => 'Registrant',
            'email' => 'registrant@example.test',
            'contact_number' => '639555555555',
            'address' => 'Davao City',
        ]);

        $response = $this->get(route('payments.success', ['transaction_id' => $transaction->transaction_code]));

        $response->assertOk();
        $response->assertSee('Guest Payer');
        $response->assertSee('payer@example.test');
        $response->assertSee('Guest Registrant');
    }

    public function test_event_registration_show_page_renders_guest_registration_without_user_relation(): void
    {
        $viewer = $this->createUser([
            'email' => 'registration-viewer@example.test',
            'mfc_id_number' => 'MFCPH000020',
        ]);
        $event = $this->createEvent();
        $transaction = $this->createTransaction();

        $registration = EventRegistration::create([
            'registration_code' => 'REG-GUEST-004',
            'transaction_id' => $transaction->id,
            'event_id' => $event->id,
            'amount' => 1500,
            'registered_at' => now(),
        ]);

        EventUserDetail::create([
            'event_registration_id' => $registration->id,
            'user_type' => 'primary',
            'first_name' => 'Guest',
            'last_name' => 'Viewer',
            'email' => 'guest-viewer@example.test',
            'contact_number' => '639666666666',
            'address' => 'Pasig City',
        ]);

        $response = $this->actingAs($viewer)->get(route('events.registrations.show', ['id' => $registration->id]));

        $response->assertOk();
        $response->assertSee('Guest Viewer');
        $response->assertSee('guest-viewer@example.test');
    }

    public function test_authenticated_save_registration_populates_user_id_and_payer_snapshot(): void
    {
        $payer = $this->createUser([
            'email' => 'payer@example.test',
            'mfc_id_number' => 'MFCPH000030',
            'first_name' => 'Paid',
            'last_name' => 'Member',
        ]);
        $attendee = $this->createUser([
            'email' => 'attendee@example.test',
            'mfc_id_number' => 'MFCPH000031',
            'first_name' => 'Registered',
            'last_name' => 'Member',
        ]);
        $event = $this->createEvent([
            'reg_fee' => 1700,
        ]);

        $this->mock(PaymayaService::class, function ($mock) {
            $mock->shouldReceive('createRequestModel')->once()->andReturn(['mock' => true]);
            $mock->shouldReceive('pay')->once()->andReturn([
                'checkoutId' => 'checkout-test-1',
                'redirectUrl' => 'https://example.test/pay',
            ]);
        });

        $response = $this->actingAs($payer)->post(route('events.register.post'), [
            'event_id' => $event->id,
            'users' => [$attendee->id],
            'donation' => 0,
        ]);

        $response->assertRedirect('https://example.test/pay');

        $transaction = Transaction::firstOrFail();
        $registration = EventRegistration::firstOrFail();
        $registrationDetail = EventUserDetail::firstOrFail();

        $this->assertSame($payer->id, $transaction->received_from_id);
        $this->assertSame($payer->first_name, $transaction->payer_first_name);
        $this->assertSame($payer->last_name, $transaction->payer_last_name);
        $this->assertSame($payer->email, $transaction->payer_email);
        $this->assertSame($attendee->id, $registration->user_id);
        $this->assertSame($attendee->mfc_id_number, $registration->mfc_id_number);
        $this->assertSame('primary', $registrationDetail->user_type);
    }

    public function test_public_event_page_uses_public_registration_entry_link(): void
    {
        $event = $this->createEvent([
            'title' => 'Phase Two Conference',
        ]);

        $response = $this->get(route('events.show', ['identifier' => $event->title]));

        $response->assertOk();
        $response->assertSee(route('events.register.public', ['event' => $event->id]), false);
        $response->assertDontSee('/dashboard/events/register', false);
        $response->assertSee('Register Now');
    }

    public function test_public_event_page_hides_registration_cta_when_registration_is_disabled(): void
    {
        $event = $this->createEvent([
            'title' => 'Closed Registration Event',
            'is_enable_event_registration' => false,
        ]);

        $response = $this->get(route('events.show', ['identifier' => $event->title]));

        $response->assertOk();
        $response->assertDontSee('Register Now');
    }

    public function test_guest_can_open_public_registration_entry_page_and_choose_paths(): void
    {
        $event = $this->createEvent([
            'title' => 'Public Registration Event',
        ]);

        $response = $this->get(route('events.register.public', ['event' => $event->id]));

        $response->assertOk();
        $response->assertSee('Register as Member');
        $response->assertSee('Register as Guest');
        $response->assertSee(route('events.register.member', ['event' => $event->id]), false);
        $response->assertSee(route('events.register.guest', ['event' => $event->id]), false);
    }

    public function test_authenticated_user_skips_public_entry_and_reaches_member_registration_form(): void
    {
        $user = $this->createUser([
            'email' => 'member-skip@example.test',
        ]);
        $event = $this->createEvent([
            'title' => 'Direct Member Registration',
        ]);

        $response = $this->actingAs($user)->get(route('events.register.public', ['event' => $event->id]));

        $response->assertRedirect(route('events.register', ['event_id' => $event->id]));
    }

    public function test_unauthenticated_member_choice_redirects_to_login_and_back_to_event_registration(): void
    {
        $user = $this->createUser([
            'email' => 'member-choice@example.test',
            'password' => Hash::make('secret123'),
        ]);
        $event = $this->createEvent([
            'title' => 'Member Login Redirect Event',
        ]);

        $memberEntryResponse = $this->get(route('events.register.member', ['event' => $event->id]));
        $memberEntryResponse->assertRedirect(route('login'));

        $loginResponse = $this->followingRedirects()->post(route('login'), [
            'login' => $user->email,
            'password' => 'secret123',
        ]);

        $loginResponse->assertOk();
        $loginResponse->assertSee('Event Registration');
        $loginResponse->assertSee('Member Login Redirect Event');
    }

    public function test_public_registration_entry_returns_404_for_disabled_event_registration(): void
    {
        $event = $this->createEvent([
            'is_enable_event_registration' => false,
        ]);

        $response = $this->get(route('events.register.public', ['event' => $event->id]));

        $response->assertNotFound();
    }

    public function test_guest_registration_form_page_renders_for_public_visitors(): void
    {
        $event = $this->createEvent([
            'title' => 'Guest Form Event',
        ]);

        $response = $this->get(route('events.register.guest', ['event' => $event->id]));

        $response->assertOk();
        $response->assertSee('Guest Registration');
        $response->assertSee('Proceed to Payment');
        $response->assertSee('Order Summary');
    }

    public function test_authenticated_user_visiting_guest_registration_page_is_redirected_to_member_flow(): void
    {
        $user = $this->createUser([
            'email' => 'guest-route-member@example.test',
        ]);
        $event = $this->createEvent();

        $response = $this->actingAs($user)->get(route('events.register.guest', ['event' => $event->id]));

        $response->assertRedirect(route('events.register', ['event_id' => $event->id]));
    }

    public function test_guest_registration_requires_payer_and_attendee_fields(): void
    {
        $event = $this->createEvent();

        $response = $this->from(route('events.register.guest', ['event' => $event->id]))
            ->post(route('events.register.guest.post', ['event' => $event->id]), []);

        $response->assertRedirect(route('events.register.guest', ['event' => $event->id]));
        $response->assertSessionHasErrors([
            'payer_first_name',
            'payer_last_name',
            'payer_email',
            'payer_contact_number',
            'payer_address',
            'attendees',
        ]);
    }

    public function test_guest_registration_creates_transaction_registration_and_redirects_to_payment(): void
    {
        $event = $this->createEvent([
            'reg_fee' => 1750,
        ]);

        $this->mock(PaymayaService::class, function ($mock) {
            $mock->shouldReceive('createRequestModel')->once()->andReturn(['mock' => true]);
            $mock->shouldReceive('pay')->once()->andReturn([
                'checkoutId' => 'guest-checkout-1',
                'redirectUrl' => 'https://example.test/guest-pay',
            ]);
        });

        $payload = $this->guestRegistrationPayload([
            'payer_first_name' => 'Grace',
            'payer_last_name' => 'Guest',
            'payer_email' => 'grace@example.test',
            'payer_contact_number' => '639991234567',
            'payer_address' => 'Pasig City',
            'donation' => 150,
            'attendees' => [[
                'first_name' => 'Grace',
                'last_name' => 'Guest',
                'email' => 'grace.attendee@example.test',
                'contact_number' => '639991234568',
                'address' => 'Pasig City',
            ]],
        ]);

        $response = $this->post(route('events.register.guest.post', ['event' => $event->id]), $payload);

        $response->assertRedirect('https://example.test/guest-pay');

        $transaction = Transaction::firstOrFail();
        $registration = EventRegistration::firstOrFail();
        $detail = EventUserDetail::firstOrFail();

        $this->assertNull($transaction->received_from_id);
        $this->assertSame('Grace', $transaction->payer_first_name);
        $this->assertSame('Guest', $transaction->payer_last_name);
        $this->assertSame('grace@example.test', $transaction->payer_email);
        $this->assertSame('639991234567', $transaction->payer_contact_number);
        $this->assertSame(1750.0, (float) $transaction->sub_amount);
        $this->assertSame(10.0, (float) $transaction->convenience_fee);
        $this->assertSame(1910.0, (float) $transaction->total_amount);

        $this->assertNull($registration->user_id);
        $this->assertNull($registration->mfc_id_number);
        $this->assertSame($event->id, $registration->event_id);
        $this->assertSame('primary', $detail->user_type);
        $this->assertSame('Grace', $detail->first_name);
        $this->assertSame('grace.attendee@example.test', $detail->email);
    }

    public function test_guest_registration_blocks_duplicate_attendee_for_same_event(): void
    {
        $event = $this->createEvent();
        $transaction = $this->createTransaction([
            'received_from_id' => null,
            'payer_first_name' => 'Existing',
            'payer_last_name' => 'Guest',
            'payer_email' => 'existing@example.test',
            'payer_contact_number' => '639881111111',
        ]);

        $existingRegistration = EventRegistration::create([
            'registration_code' => 'REG-GUEST-DUPE-1',
            'transaction_id' => $transaction->id,
            'event_id' => $event->id,
            'amount' => $event->reg_fee,
            'registered_at' => now(),
        ]);

        EventUserDetail::create([
            'event_registration_id' => $existingRegistration->id,
            'user_type' => 'primary',
            'first_name' => 'Repeat',
            'last_name' => 'Guest',
            'email' => 'repeat@example.test',
            'contact_number' => '639881111112',
            'address' => 'Makati City',
        ]);

        $payload = $this->guestRegistrationPayload([
            'payer_first_name' => 'New',
            'payer_last_name' => 'Payer',
            'payer_email' => 'newpayer@example.test',
            'payer_contact_number' => '639881111113',
            'payer_address' => 'Taguig City',
            'attendees' => [[
                'first_name' => ' Repeat ',
                'last_name' => 'GUEST',
                'email' => 'REPEAT@example.test',
                'contact_number' => '639881111114',
                'address' => 'Makati City',
            ]],
        ]);

        $response = $this->from(route('events.register.guest', ['event' => $event->id]))
            ->post(route('events.register.guest.post', ['event' => $event->id]), $payload);

        $response->assertRedirect(route('events.register.guest', ['event' => $event->id]));
        $response->assertSessionHasErrors('guest_registration');
        $this->assertCount(1, Transaction::all());
        $this->assertCount(1, EventRegistration::all());
        $this->assertCount(1, EventUserDetail::all());
    }

    private function createUser(array $overrides = []): User
    {
        static $sequence = 1;

        $defaults = [
            'first_name' => 'User' . $sequence,
            'last_name' => 'Test',
            'email' => 'user' . $sequence . '@example.test',
            'password' => Hash::make('password'),
            'avatar' => 'avatar-1.jpg',
            'country_code' => 63,
            'contact_number' => '6390000000' . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT),
            'area' => 'ncr_north',
            'chapter' => User::$chapter[0],
            'gender' => User::$gender[0],
            'status' => User::$status[1],
            'mfc_id_number' => 'MFCPH' . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT),
            'email_verified_at' => now(),
        ];

        $sequence++;

        return User::create(array_merge($defaults, $overrides));
    }

    private function createEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'title' => 'Global Conference',
            'type' => 1,
            'section_ids' => [1],
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'time' => '13:00:00',
            'location' => 'Mandaluyong',
            'latitude' => '14.5794',
            'longitude' => '121.0359',
            'reg_fee' => 1500,
            'description' => 'Conference event',
            'is_open_for_non_community' => true,
            'is_enable_event_registration' => true,
            'status' => 'Active',
        ], $overrides));
    }

    private function createTransaction(array $overrides = []): Transaction
    {
        return Transaction::create(array_merge([
            'transaction_code' => 'TRX-' . uniqid(),
            'reference_code' => 'REF-' . uniqid(),
            'donation' => 0,
            'convenience_fee' => 10,
            'sub_amount' => 1500,
            'total_amount' => 1510,
            'payment_mode' => 'maya',
            'payment_type' => PaymentType::EVENT_REGISTRATION,
            'status' => 'pending',
        ], $overrides));
    }

    private function guestRegistrationPayload(array $overrides = []): array
    {
        return array_merge([
            'payer_first_name' => 'Guest',
            'payer_last_name' => 'Payer',
            'payer_email' => 'guestpayer@example.test',
            'payer_contact_number' => '639771234567',
            'payer_address' => 'Quezon City',
            'donation' => 0,
            'attendees' => [[
                'first_name' => 'Guest',
                'last_name' => 'Attendee',
                'email' => 'guestattendee@example.test',
                'contact_number' => '639771234568',
                'address' => 'Quezon City',
            ]],
        ], $overrides);
    }
}
