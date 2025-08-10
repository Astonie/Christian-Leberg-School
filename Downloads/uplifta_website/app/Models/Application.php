<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'address', 'country', 'business_name', 'business_type', 'years_in_business', 'employees', 'monthly_revenue', 'business_description', 'loan_amount', 'loan_term', 'loan_purpose', 'loan_description', 'repayment_plan', 'status'
    ];
}
