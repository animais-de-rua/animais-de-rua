<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Process extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'processes';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'contact', 'phone', 'email', 'address', 'territory_id', 'headquarter_id', 'specie', 'amount_males', 'amount_females', 'amount_other', 'status', 'urgent', 'history', 'notes', 'latlong', 'images'];
    protected $casts = ['images' => 'array'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function headquarter()
    {
        return $this->belongsTo('App\Models\Headquarter', 'headquarter_id');
    }

    public function territory()
    {
        return $this->belongsTo('App\Models\Territory', 'territory_id');
    }

    public function donations()
    {
        return $this->hasMany('App\Models\Donation', 'process_id');
    }

    public function appointments()
    {
        return $this->hasMany('App\Models\Appointment', 'process_id');
    }

    public function adoptions()
    {
        return $this->hasMany('App\Models\Adoption', 'process_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function treatments()
    {
        return $this->hasManyThrough('App\Models\Treatment', 'App\Models\Appointment');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getLinkAttribute()
    {
        return $this->getLink($this);
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getDateAttribute()
    {
        return $this->created_at ? explode(' ', $this->created_at)[0] : '';
    }

    public function getDetailAttribute()
    {
        $headquarter = (isset($this->headquarter) ? $this->headquarter['name'] . ', ' : '');

        return "{$this->name} ({$headquarter}{$this->date})";
    }

    public function getAmountAttribute()
    {
        return $this->amount_males + $this->amount_females + $this->amount_other;
    }

    public function getTotalDonatedValue()
    {
        $donations = data_get_first($this, 'donations', 'total_donations', 0);

        return $donations != 0 ? $donations . '€' : '-';
    }

    public function getTotalExpensesValue()
    {
        $expenses = data_get_first($this, 'treatments', 'total_expenses', 0);

        return $expenses != 0 ? $expenses . '€' : '-';
    }

    public function getTotalOperationsValue()
    {
        $operations = data_get_first($this, 'treatments', 'total_operations', 0);

        return $operations;
    }

    public function getBalanceValue()
    {
        $donations = data_get_first($this, 'donations', 'total_donations', 0);
        $expenses = data_get_first($this, 'treatments', 'total_expenses', 0);
        $balance = $donations - $expenses;

        return $this->colorizeValue($balance);
    }

    public function getAnimalsValue()
    {
        $result = '';
        if ($this->amount_males && $this->amount_females) {
            $result .= $this->amount_males . ' / ' . $this->amount_females;
            if ($this->amount_other) {
                $result .= ' | ' . $this->amount_other;
            }

        } else if ($this->amount_other) {
            $result = $this->amount_other;
        } else {
            $result = '-';
        }

        return $result;
    }

    // Stats
    public function getTotalDonatedStats()
    {
        $donations = $this->getDonated();
        return $donations != 0 ? $donations . '€' : '-';
    }

    public function getTotalDonationsStats()
    {
        return count($this->donations);
    }

    public function getTotalExpensesStats()
    {
        $expenses = $this->getExpenses();
        return $expenses != 0 ? $expenses . '€' : '-';
    }

    public function getTotalOperationsStats()
    {
        return count($this->treatments);
    }

    public function getBalanceStats()
    {
        $balance = $this->getDonated() - $this->getExpenses();
        return $this->colorizeValue($balance);
    }

    private function getDonated()
    {
        return $this->donations->reduce(function ($carry, $item) {return $carry + $item->value;});
    }

    private function getExpenses()
    {
        return $this->treatments->reduce(function ($carry, $item) {return $carry + $item->expense;});
    }

    private function colorizeValue($value)
    {
        if (!$value) {
            return '-';
        } else if ($value > 0) {
            return "<span style='color:#0A0'>+{$value}€</span>";
        } else {
            return "<span style='color:#A00'>{$value}€</span>";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function toArray()
    {
        $data = parent::toArray();

        $data['detail'] = $this->detail;

        return $data;
    }
}
