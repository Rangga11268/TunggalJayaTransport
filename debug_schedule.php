<?php

// Create a simple debug script to test what's being passed
// This would be added to the controller to debug the issue

/*
Add this to the store method to debug:

\Log::info('Debug schedule creation', [
    'departure_date' => $request->departure_date,
    'departure_time' => $request->departure_time,
    'combined_departure' => $request->departure_date . ' ' . $request->departure_time,
    'is_weekly' => $request->is_weekly
]);

*/

echo "This is a placeholder for debugging the schedule creation issue.\n";
echo "The issue might be related to how the date is being passed from the form.\n";

?>