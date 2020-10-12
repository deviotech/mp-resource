<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $table = "payment_statuses";
    protected $fillable = ['id','customer_name','order_id','description','payment_id','payment_amount','user_id','status'];
}
