<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;

class Process extends Model
{
    use CrudTrait;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'processes';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'contact', 'phone', 'email', 'address', 'territory_id', 'headquarter_id', 'specie', 'amount_males', 'amount_females', 'amount_other', 'status', 'urgent', 'history', 'notes', 'latlong', 'images', 'user_id'];
    protected $casts = ['images' => 'array'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $translatable = ['history'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function addAppointment()
    {
        $disabled = !in_array($this->status, ['waiting_godfather', 'waiting_capture', 'open']);

        return '
        <a class="btn btn-xs btn-' . ($disabled ? 'default' : 'primary') . ' ' . ($disabled ? 'disabled' : '') . '" href="/admin/appointment/create?process=' . $this->id . '" title="' . __('Add appointment') . '">
        <i class="fa fa-plus"></i> ' . ucfirst(__('appointment')) . '
        </a>';
    }

    public function toggleContacted()
    {
        return '
        <a href="#"
            title="' . ($this->contacted ? __('Contacted') : __('Not yet contacted')) . '"
            class="btn btn-xs btn-' . ($this->contacted ? 'success' : 'default') . '"
            onclick="return toggleContacted(this, ' . $this->id . ')">
        <i class="fa fa-phone"></i>
        </a>';
    }

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
        return $this->hasManyThrough('App\Models\Treatment', 'App\Models\Appointment', 'process_id', 'appointment_id');
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

    public function getNameLinkAttribute()
    {
        return $this->getLink($this, true, '');
    }

    public function getDateAttribute()
    {
        return $this->created_at ? explode(' ', $this->created_at)[0] : '';
    }

    public function getDetailAttribute()
    {
        $headquarter = (isset($this->headquarter) ? $this->headquarter['name'] . ', ' : '');

        return "{$this->id} - {$this->name} ({$headquarter}{$this->date})";
    }

    public function getIdNameAttribute()
    {
        return "{$this->id} — {$this->name}";
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

    public function getTotalAffectedAnimalsValue()
    {
        $total = data_get_first($this, 'treatments', 'total_affected_animals', 0);

        return $total.' / '.$this->getAnimalsCountValue();
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
        return $this->colorizeValue($this->getBalance());
    }

    public function getBalance()
    {
        $donations = data_get_first($this, 'donations', 'total_donations', 0);
        $expenses = data_get_first($this, 'treatments', 'total_expenses', 0);
        $balance = $donations - $expenses;

        return round($balance, 2);
    }

    public function getAnimalsValue()
    {
        $result = '';
        if ($this->amount_males || $this->amount_females) {
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

    public function getAnimalsCountValue()
    {
        return $this->amount_males + $this->amount_females + $this->amount_other;
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
        return $this->treatments->reduce(function ($carry, $item) {return $carry + $item->affected_animals;});
    }

    public function getTotalAnimals()
    {
        return $this->amount;
    }

    public function getTotalAffectedAnimalsNew()
    {
        return $this->treatments->reduce(function ($carry, $item) {return $carry + $item->affected_animals_new;});
    }

    public function getBalanceStats()
    {
        $balance = round($this->getDonated() - $this->getExpenses(), 2);
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

    public function getApplicantAttribute()
    {
        return "$this->contact, $this->phone, $this->address";
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
        $data['thumb'] = $this->images ? thumb_image($this->images[0]) : null;

        return $data;
    }
}
