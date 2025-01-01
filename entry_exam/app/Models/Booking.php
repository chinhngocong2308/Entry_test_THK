<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Booking
 * @package App\Models
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'bookings';

    /**
     * @var string
     */
    protected $primaryKey = 'booking_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'hotel_id',
        'customer_name',
        'customer_contact',
        'checkin_time',
        'checkout_time',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }
}
