<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Application;
use App\Models\Document;
use App\Models\Location;
use App\Models\Payment;
use App\Models\Premises;
use App\Models\Resident;
use App\Models\RentalAgreement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // ADMIN ACCOUNTS
        // ============================================================
        $admin = Admin::create([
            'admin_first_name' => 'Sarah',
            'admin_last_name' => 'Abdullah',
            'admin_email' => 'admin@mdch.gov.my',
            'admin_password' => Hash::make('password'),
            'created_at' => now(),
        ]);

        Admin::create([
            'admin_first_name' => 'Rashid',
            'admin_middle_name' => 'bin',
            'admin_last_name' => 'Hamid',
            'admin_email' => 'rashid@mdch.gov.my',
            'admin_password' => Hash::make('password'),
            'created_at' => now(),
        ]);

        // ============================================================
        // LOCATIONS (Cameron Highlands towns)
        // ============================================================
        $tanah = Location::create(['location_name' => 'Tanah Rata', 'location_description' => 'Main town of Cameron Highlands', 'created_at' => now()]);
        $brinchang = Location::create(['location_name' => 'Brinchang', 'location_description' => 'Northern highland town', 'created_at' => now()]);
        $ringlet = Location::create(['location_name' => 'Ringlet', 'location_description' => 'Gateway to Cameron Highlands', 'created_at' => now()]);

        // ============================================================
        // PREMISES
        // ============================================================
        $p1 = Premises::create([
            'location_id' => $tanah->location_id,
            'premises_name' => 'Lot 12 Tanah Rata Market',
            'premises_type' => 'market_stall',
            'premises_description' => 'Ground floor market stall near bus terminal',
            'rental_fee' => 250.0,
            'premises_status' => 'available',
            'created_at' => now(),
        ]);

        $p2 = Premises::create([
            'location_id' => $tanah->location_id,
            'premises_name' => 'Tanah Rata Business Unit B3',
            'premises_type' => 'business_premises',
            'premises_description' => '2-storey shophouse in Tanah Rata commercial area',
            'rental_fee' => 850.0,
            'premises_status' => 'available',
            'created_at' => now(),
        ]);

        $p3 = Premises::create([
            'location_id' => $brinchang->location_id,
            'premises_name' => 'Brinchang Food Court Stall F4',
            'premises_type' => 'food_stall',
            'premises_description' => 'Corner stall in Brinchang night market food court',
            'rental_fee' => 320.0,
            'premises_status' => 'available',
            'created_at' => now(),
        ]);

        $p4 = Premises::create([
            'location_id' => $brinchang->location_id,
            'premises_name' => 'Brinchang Handicraft Corner H1',
            'premises_type' => 'handicraft',
            'premises_description' => 'Heritage handicraft display space',
            'rental_fee' => 180.0,
            'premises_status' => 'occupied',
            'created_at' => now(),
        ]);

        $p5 = Premises::create([
            'location_id' => $ringlet->location_id,
            'premises_name' => 'Ringlet Market Table MT-07',
            'premises_type' => 'market_table',
            'premises_description' => 'Market table in Ringlet weekly produce market',
            'rental_fee' => 90.0,
            'premises_status' => 'available',
            'created_at' => now(),
        ]);

        $p6 = Premises::create([
            'location_id' => $tanah->location_id,
            'premises_name' => 'Tanah Rata Auto Workshop W2',
            'premises_type' => 'workshop',
            'premises_description' => 'Covered workshop bay with drainage',
            'rental_fee' => 650.0,
            'premises_status' => 'available',
            'created_at' => now(),
        ]);

        $p7 = Premises::create([
            'location_id' => $ringlet->location_id,
            'premises_name' => 'Ringlet Multipurpose Lot V1',
            'premises_type' => 'various',
            'premises_description' => 'General commercial lot, flexible use',
            'rental_fee' => 400.0,
            'premises_status' => 'unavailable',
            'created_at' => now(),
        ]);

        // ============================================================
        // RESIDENTS
        // ============================================================
        $resident1 = Resident::create([
            'resident_first_name' => 'Ahmad',
            'resident_last_name' => 'Hassan',
            'resident_ic_number' => '900101145678',
            'resident_phone' => '0123456789',
            'resident_address' => '12 Jalan Besar, Tanah Rata, Cameron Highlands',
            'resident_email' => 'ahmad@example.com',
            'resident_password' => Hash::make('password'),
            'residency_duration' => 10,
            'marital_status' => 'married',
            'mdch_license_holder' => 0,
            'business_experience' => 1,
            'business_type' => 'Food & Beverage',
            'created_at' => now(),
        ]);

        $resident2 = Resident::create([
            'resident_first_name' => 'Siti',
            'resident_last_name' => 'Rahimah',
            'resident_ic_number' => '850612025432',
            'resident_phone' => '0134567890',
            'resident_address' => '5 Lorong Damai, Brinchang, Cameron Highlands',
            'resident_email' => 'siti@example.com',
            'resident_password' => Hash::make('password'),
            'residency_duration' => 7,
            'marital_status' => 'single',
            'mdch_license_holder' => 0,
            'business_experience' => 0,
            'business_type' => null,
            'created_at' => now(),
        ]);

        $resident3 = Resident::create([
            'resident_first_name' => 'Rajan',
            'resident_last_name' => 'Munusamy',
            'resident_ic_number' => '780320088765',
            'resident_phone' => '0198765432',
            'resident_address' => '3 Jalan Ringlet, Ringlet, Cameron Highlands',
            'resident_email' => 'rajan@example.com',
            'resident_password' => Hash::make('password'),
            'residency_duration' => 15,
            'marital_status' => 'married',
            'mdch_license_holder' => 1,
            'business_experience' => 1,
            'business_type' => 'Retail',
            'created_at' => now(),
        ]);

        // Extra dummy residents
        $dummyResidents = [['Lim', 'Wei Kiat', '920305101234', '0111234567', 'married'], ['Norida', 'Zakaria', '940820045678', '0129876543', 'single'], ['Kumar', 'Selvam', '881215087654', '0167654321', 'widowed'], ['Fatimah', 'Ismail', '760901025432', '0133219876', 'divorced'], ['David', 'Tan', '990715141234', '0125432167', 'single']];

        $icBase = 800000000000;
        foreach ($dummyResidents as $i => $d) {
            Resident::create([
                'resident_first_name' => $d[0],
                'resident_last_name' => $d[1],
                'resident_ic_number' => $d[2],
                'resident_phone' => $d[3],
                'resident_address' => 'Lot ' . ($i + 10) . ', Cameron Highlands',
                'resident_email' => strtolower(str_replace(' ', '', $d[1])) . $i . '@example.com',
                'resident_password' => Hash::make('password'),
                'residency_duration' => rand(1, 20),
                'marital_status' => $d[4],
                'mdch_license_holder' => 0,
                'business_experience' => rand(0, 1),
                'business_type' => null,
                'created_at' => now()->subDays(rand(1, 60)),
            ]);
        }

        // ============================================================
        // APPLICATIONS & PAYMENTS (demo data)
        // ============================================================

        // Application 1: Approved + Paid (triggers create rental agreement & occupied status for p4)
        $app1 = Application::create([
            'resident_id' => $resident3->resident_id,
            'premises_id' => $p4->premises_id,
            'intended_business_type' => 'Orang Asli Handicraft',
            'financial_position' => 5000.0,
            'application_status' => 'approved',
            'application_date' => now()->subDays(30),
            'reviewed_by' => $admin->admin_id,
            'reviewed_at' => now()->subDays(25),
            'remarks' => 'Approved. Applicant meets all criteria.',
            'created_at' => now()->subDays(30),
        ]);

        // Insert payment as completed → trigger fires → rental_agreement created automatically
        $pay1 = Payment::create([
            'application_id' => $app1->application_id,
            'amount' => $p4->rental_fee,
            'card_number' => '4111111111111111',
            'card_expiry_date' => '2028-12-01',
            'payment_date' => now()->subDays(24),
            'payment_status' => 'pending',
            'created_at' => now()->subDays(24),
        ]);
        // Update to completed — fires trigger
        DB::table('payments')
            ->where('payment_id', $pay1->payment_id)
            ->update(['payment_status' => 'completed']);

        // Application 2: Pending
        $app2 = Application::create([
            'resident_id' => $resident1->resident_id,
            'premises_id' => $p3->premises_id,
            'intended_business_type' => 'Nasi Lemak & Local Cuisine',
            'financial_position' => 8000.0,
            'application_status' => 'pending',
            'application_date' => now()->subDays(5),
            'created_at' => now()->subDays(5),
        ]);

        // Application 3: Approved, awaiting payment
        $app3 = Application::create([
            'resident_id' => $resident2->resident_id,
            'premises_id' => $p1->premises_id,
            'intended_business_type' => 'Fresh Produce & Vegetables',
            'financial_position' => 3000.0,
            'application_status' => 'approved',
            'application_date' => now()->subDays(15),
            'reviewed_by' => $admin->admin_id,
            'reviewed_at' => now()->subDays(10),
            'remarks' => 'Priority given as resident of Pahang > 5 years.',
            'created_at' => now()->subDays(15),
        ]);

        // Application 4: Rejected
        Application::create([
            'resident_id' => $resident1->resident_id,
            'premises_id' => $p2->premises_id,
            'intended_business_type' => 'Tourism Souvenirs',
            'financial_position' => 1500.0,
            'application_status' => 'rejected',
            'application_date' => now()->subDays(45),
            'reviewed_by' => $admin->admin_id,
            'reviewed_at' => now()->subDays(40),
            'remarks' => 'Insufficient financial position for business premises.',
            'created_at' => now()->subDays(45),
        ]);

        // Application 5: Cancelled
        Application::create([
            'resident_id' => $resident2->resident_id,
            'premises_id' => $p6->premises_id,
            'intended_business_type' => 'Auto Accessories',
            'financial_position' => 6000.0,
            'application_status' => 'cancelled',
            'application_date' => now()->subDays(20),
            'created_at' => now()->subDays(20),
        ]);

        // ============================================================
        // SUMMARY OUTPUT
        // ============================================================
        $this->command->info('✓ BPRMS database seeded with Cameron Highlands data!');
        $this->command->info('  Admin:     admin@mdch.gov.my / password');
        $this->command->info('  Resident:  ahmad@example.com / password');
        $this->command->info('  Resident:  siti@example.com  / password');
        $this->command->info('  Resident:  rajan@example.com / password');
        $this->command->info('  Locations: 3 (Tanah Rata, Brinchang, Ringlet)');
        $this->command->info('  Premises:  7');
        $this->command->info('  Residents: 8');
        $this->command->info('  Applications: 5 (1 approved+paid, 1 pending, 1 approved, 1 rejected, 1 cancelled)');
        $this->command->info('  Rental Agreements: ' . RentalAgreement::count() . ' (auto-created by trigger)');
    }
}
