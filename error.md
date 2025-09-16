FIXED: SQLSTATE[01000]: Warning: 1265 Data truncated for column 'payment_status' at row 1 (Connection: mysql, SQL: update `bookings` set `payment_status` = completed, `bookings`.`updated_at` = 2025-09-16 13:36:45 where `id` = 10)

The issue was that the code was trying to set the payment_status to 'completed', but this is not a valid value for the ENUM column. The valid values are: 'pending', 'paid', 'failed', 'refunded'. This has been fixed by updating:
1. Frontend\BookingController.php - Changed 'completed' to 'paid' when setting payment_status

PREVIOUSLY FIXED:
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'seat_number' in 'field list' (Connection: mysql, SQL: update `bookings` set `payment_status` = failed, `seat_number` = 1, `bookings`.`updated_at` = 2025-09-16 13:19:27 where `id` = 8)

The issue was that the code was referencing 'seat_number' (without the 's') instead of 'seat_numbers' (with the 's'). This has been fixed by updating:
1. Admin\BookingController.php - Changed 'seat_number' to 'seat_numbers' in validation rules and assignments
2. Resources\views\admin\bookings\create.blade.php - Updated form field name and label
3. Resources\views\admin\bookings\edit.blade.php - Updated form field name and label
4. Resources\views\admin\bookings\show.blade.php - Updated display label
5. Database\migrations\2025_09_14_051954_create_bookings_table.php - Fixed column name in original migration

Additionally, fixed a logical issue where seats remained occupied even after payment failure:
1. Updated Schedule model's getBookedSeatNumbers() and getBookedSeatsCount() methods to exclude bookings with failed payments