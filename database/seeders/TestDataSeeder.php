<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Note;
use App\Models\Reminder;
use App\Models\Routine;
use App\Models\File;
use App\Models\ChecklistItem;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (admin)
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Create test projects
        $project1 = Project::firstOrCreate(
            ['name' => 'Website Development', 'user_id' => $user->id],
            [
                'description' => 'Building a new company website',
                'status' => 'in_progress',
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(20),
                'budget' => 5000.00,
            ]
        );

        $project2 = Project::firstOrCreate(
            ['name' => 'Mobile App', 'user_id' => $user->id],
            [
                'description' => 'Developing mobile application',
                'status' => 'not_started',
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(30),
                'budget' => 8000.00,
            ]
        );

        // Create test tasks with different statuses and dates for productivity chart
        $taskData = [
            ['title' => 'Design homepage', 'status' => 'completed', 'priority' => 'high', 'days_ago' => 6],
            ['title' => 'Setup database', 'status' => 'completed', 'priority' => 'high', 'days_ago' => 5],
            ['title' => 'Create API endpoints', 'status' => 'completed', 'priority' => 'medium', 'days_ago' => 4],
            ['title' => 'Build user authentication', 'status' => 'completed', 'priority' => 'high', 'days_ago' => 3],
            ['title' => 'Implement dashboard', 'status' => 'completed', 'priority' => 'medium', 'days_ago' => 2],
            ['title' => 'Add charts functionality', 'status' => 'completed', 'priority' => 'medium', 'days_ago' => 1],
            ['title' => 'Write documentation', 'status' => 'in_progress', 'priority' => 'low', 'days_ago' => 0],
            ['title' => 'Testing phase', 'status' => 'to_do', 'priority' => 'high', 'days_ago' => 0],
            ['title' => 'Deploy to production', 'status' => 'to_do', 'priority' => 'high', 'days_ago' => 0],
            ['title' => 'Mobile app wireframes', 'status' => 'in_progress', 'priority' => 'medium', 'days_ago' => 0],
            ['title' => 'UI/UX design', 'status' => 'to_do', 'priority' => 'medium', 'days_ago' => 0],
        ];

        foreach ($taskData as $data) {
            $task = Task::firstOrCreate(
                [
                    'title' => $data['title'],
                    'project_id' => $project1->id,
                    'user_id' => $user->id,
                ],
                [
                    'description' => 'Task description for ' . $data['title'],
                    'status' => $data['status'],
                    'priority' => $data['priority'],
                    'due_date' => now()->addDays(rand(1, 30)),
                    'created_at' => now()->subDays($data['days_ago']),
                    'updated_at' => $data['status'] === 'completed' ? now()->subDays($data['days_ago']) : now(),
                ]
            );
        }

        // Add checklist items for some tasks
        $sampleTask = Task::where('title', 'Write documentation')->first();
        if ($sampleTask) {
            ChecklistItem::firstOrCreate([
                'task_id' => $sampleTask->id,
                'name' => 'Create user guide'
            ], ['completed' => true]);

            ChecklistItem::firstOrCreate([
                'task_id' => $sampleTask->id,
                'name' => 'Write API documentation'
            ], ['completed' => true]);

            ChecklistItem::firstOrCreate([
                'task_id' => $sampleTask->id,
                'name' => 'Add code examples'
            ], ['completed' => false]);

            ChecklistItem::firstOrCreate([
                'task_id' => $sampleTask->id,
                'name' => 'Review and proofread'
            ], ['completed' => false]);
        }

        // Create some notes
        Note::firstOrCreate(
            ['title' => 'Project Ideas', 'user_id' => $user->id],
            [
                'content' => 'Ideas for future projects and improvements',
                'category' => 'ideas',
                'is_favorite' => true,
            ]
        );

        Note::firstOrCreate(
            ['title' => 'Meeting Notes', 'user_id' => $user->id],
            [
                'content' => 'Notes from the team meeting about project progress',
                'category' => 'meetings',
                'is_favorite' => false,
            ]
        );

        // Create some reminders
        Reminder::firstOrCreate(
            ['title' => 'Team standup', 'user_id' => $user->id],
            [
                'description' => 'Daily team standup meeting',
                'date' => now()->addHours(2)->format('Y-m-d'),
                'time' => now()->addHours(2)->format('H:i'),
                'priority' => 'medium',
                'is_completed' => false,
            ]
        );

        Reminder::firstOrCreate(
            ['title' => 'Project deadline', 'user_id' => $user->id],
            [
                'description' => 'Website project deadline reminder',
                'date' => now()->addDays(20)->format('Y-m-d'),
                'time' => '09:00',
                'priority' => 'high',
                'is_completed' => false,
            ]
        );

        // Create some routines
        Routine::firstOrCreate(
            ['title' => 'Morning Workout', 'user_id' => $user->id],
            [
                'description' => 'Daily morning exercise routine',
                'frequency' => 'daily',
                'days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
                'start_time' => '07:00',
                'end_time' => '08:00',
            ]
        );

        // Create some files
        File::firstOrCreate(
            ['name' => 'Project Documentation.pdf', 'user_id' => $user->id],
            [
                'path' => 'files/project-docs.pdf',
                'type' => 'pdf',
            ]
        );

        $this->command->info('Test data created successfully!');
    }
}
