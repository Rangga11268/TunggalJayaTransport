<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WeeklyScheduleTemplate;
use App\Models\Schedule;
use Carbon\Carbon;

class GenerateSchedulesFromTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedules:generate-from-templates 
                            {--weeks=4 : Number of weeks to generate schedules for} 
                            {--template-id= : Specific template ID to generate schedules from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate schedules from weekly schedule templates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $weeks = (int) $this->option('weeks');
        $templateId = $this->option('template-id');
        
        $this->info("Generating schedules for {$weeks} weeks...");
        
        // Get templates
        $query = WeeklyScheduleTemplate::active();
        
        if ($templateId) {
            $query->where('id', $templateId);
        }
        
        $templates = $query->get();
        
        if ($templates->isEmpty()) {
            $this->error('No active templates found.');
            return 1;
        }
        
        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addWeeks($weeks);
        
        $totalSchedulesCreated = 0;
        
        foreach ($templates as $template) {
            $this->info("Processing template: {$template->name}");
            
            $createdSchedules = $template->createSchedulesForDateRange($startDate, $endDate);
            $totalSchedulesCreated += count($createdSchedules);
            
            $this->line("  Created " . count($createdSchedules) . " schedules");
        }
        
        $this->info("Successfully created {$totalSchedulesCreated} schedules from templates.");
        
        return 0;
    }
}
