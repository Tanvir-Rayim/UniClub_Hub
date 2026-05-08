<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Club;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'university_id' => 'ADMIN001',
            'email' => 'admin@uniclubhub.local',
            'password' => 'password', // Will be hashed by mutator
            'phone' => '555-0000',
            'role' => 'admin',
            'is_active' => true
        ]);

        // Create faculty advisors
        $advisor1 = User::create([
            'name' => 'Dr. Smith',
            'university_id' => 'FAC001',
            'email' => 'smith@university.edu',
            'password' => 'password',
            'phone' => '555-0001',
            'role' => 'advisor',
            'is_active' => true
        ]);

        $advisor2 = User::create([
            'name' => 'Dr. Johnson',
            'university_id' => 'FAC002',
            'email' => 'johnson@university.edu',
            'password' => 'password',
            'phone' => '555-0002',
            'role' => 'advisor',
            'is_active' => true
        ]);

        // Create club executives
        $exec1 = User::create([
            'name' => 'John Executive',
            'university_id' => 'STU001',
            'email' => 'john.exec@university.edu',
            'password' => 'password',
            'phone' => '555-0003',
            'role' => 'executive',
            'is_active' => true
        ]);

        $exec2 = User::create([
            'name' => 'Sarah Leader',
            'university_id' => 'STU002',
            'email' => 'sarah.leader@university.edu',
            'password' => 'password',
            'phone' => '555-0004',
            'role' => 'executive',
            'is_active' => true
        ]);

        // Create student users
        $student1 = User::create([
            'name' => 'Alice Student',
            'university_id' => 'STU003',
            'email' => 'alice@university.edu',
            'password' => 'password',
            'phone' => '555-0005',
            'role' => 'student',
            'is_active' => true
        ]);

        $student2 = User::create([
            'name' => 'Bob Scholar',
            'university_id' => 'STU004',
            'email' => 'bob@university.edu',
            'password' => 'password',
            'phone' => '555-0006',
            'role' => 'student',
            'is_active' => true
        ]);

        // Create clubs
        $club1 = Club::create([
            'name' => 'Computer Science Club',
            'description' => 'A club dedicated to computer science enthusiasts, coding competitions, and tech discussions.',
            'faculty_advisor_id' => $advisor1->id,
            'created_by' => $exec1->id,
            'is_active' => true
        ]);

        $club2 = Club::create([
            'name' => 'Debate Society',
            'description' => 'Join us for exciting debates, public speaking workshops, and competitive debating events.',
            'faculty_advisor_id' => $advisor2->id,
            'created_by' => $exec2->id,
            'is_active' => true
        ]);

        $club3 = Club::create([
            'name' => 'Photography Club',
            'description' => 'For photography enthusiasts - workshops, photo walks, and exhibitions.',
            'faculty_advisor_id' => $advisor1->id,
            'created_by' => $exec1->id,
            'is_active' => true
        ]);

        // Add club members
        $club1->members()->attach($exec1->id, ['status' => 'approved', 'joined_at' => now()]);
        $club1->members()->attach($student1->id, ['status' => 'approved', 'joined_at' => now()]);
        $club1->members()->attach($student2->id, ['status' => 'pending']);

        $club2->members()->attach($exec2->id, ['status' => 'approved', 'joined_at' => now()]);
        $club2->members()->attach($student1->id, ['status' => 'pending']);

        $club3->members()->attach($exec1->id, ['status' => 'approved', 'joined_at' => now()]);
    }
}
