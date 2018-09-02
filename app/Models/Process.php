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
    protected $fillable = ['name', 'contact', 'phone', 'email', 'address', 'territory_id', 'headquarter_id', 'specie', 'amount_males', 'amount_females', 'amount_other', 'status', 'history', 'notes', 'latlong', 'images'];
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

    public function treatments()
    {
        return $this->hasMany('App\Models\Treatment', 'process_id');
    }

    public function appointments()
    {
        return $this->hasMany('App\Models\Appointment', 'process_id');
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

    public function getDateAttribute()
    {
        return $this->created_at ? explode(' ', $this->created_at)[0] : '';
    }

    public function getDetailAttribute()
    {
        $headquarter = (isset($this->headquarter) ? $this->headquarter['name'] . ', ' : '');

        return "{$this->name} ({$headquarter}{$this->date})";
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

        if (!$balance) {
            return '-';
        } else if ($balance > 0) {
            return "+{$balance}€";
        } else {
            return "<span style='color:#A00'>{$balance}€</span>";
        }

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
        $donations = $this->donations->reduce(function ($carry, $item) {return $carry + $item->value;});
        return $donations != 0 ? $donations . '€' : '-';
    }

    public function getTotalDonationsStats()
    {
        return sizeof($this->donations);
    }

    public function getTotalExpensesStats()
    {
        $expenses = $this->treatments->reduce(function ($carry, $item) {return $carry + $item->expense;});
        return $expenses != 0 ? $expenses . '€' : '-';
    }

    public function getTotalOperationsStats()
    {
        return sizeof($this->treatments);
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
