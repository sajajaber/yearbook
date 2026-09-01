<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        // Get the admin user (or create a test user if needed)
        $admin = User::where('role_id', 1)->first() ?? User::first();
        
        if (!$admin) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        // Create sample media items with placeholder images
        $mediaItems = [
            [
                'file_name' => 'graduation-ceremony-2026.jpg',
                'caption' => 'Main graduation ceremony hall',
                'alt_text' => 'Students in caps and gowns during graduation ceremony',
                'credit' => 'School Photography Team',
                'tags' => ['ceremony', 'graduation', '2026'],
            ],
            [
                'file_name' => 'student-life-campus.jpg',
                'caption' => 'Student activities on campus',
                'alt_text' => 'Students gathering in the main campus courtyard',
                'credit' => 'Campus Media',
                'tags' => ['campus', 'student-life', 'outdoor'],
            ],
            [
                'file_name' => 'lecture-hall-event.jpg',
                'caption' => 'Academic symposium at the main auditorium',
                'alt_text' => 'Lecture hall during an academic event',
                'credit' => 'Academic Affairs',
                'tags' => ['academic', 'event', 'auditorium'],
            ],
            [
                'file_name' => 'portrait-placeholder.jpg',
                'caption' => 'Portrait photography session',
                'alt_text' => 'Professional portrait setup for graduate photos',
                'credit' => 'Professional Photographer',
                'tags' => ['portrait', 'photography', 'graduate'],
            ],
            [
                'file_name' => 'campus-library.jpg',
                'caption' => 'Main library building entrance',
                'alt_text' => 'Modern library architecture with students entering',
                'credit' => 'Facilities Team',
                'tags' => ['campus', 'library', 'architecture'],
            ],
        ];

        foreach ($mediaItems as $item) {
            // Create placeholder image (1x1 transparent PNG)
            $imagePath = 'media/' . date('Y/m/d') . '/' . time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '', $item['file_name']);
            
            // Create a simple placeholder image
            $placeholder = base64_decode(
                'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
            );
            
            Storage::disk('public')->put($imagePath, $placeholder);

            // Create media record
            Media::create([
                'file_name' => $item['file_name'],
                'path' => $imagePath,
                'type' => 'image',
                'caption' => $item['caption'],
                'alt_text' => $item['alt_text'],
                'credit' => $item['credit'],
                'tags' => $item['tags'],
                'uploaded_by' => $admin->id,
                'checksum' => md5($placeholder),
            ]);
        }

        $this->command->info('Media seeder completed. ' . count($mediaItems) . ' media items created.');
    }
}
