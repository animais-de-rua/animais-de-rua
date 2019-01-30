<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;

class Appointment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'appointments';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'user_id', 'vet_id_1', 'date_1', 'vet_id_2', 'date_2', 'amount_males', 'amount_females', 'amount_other', 'notes', 'status'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function addTreatment()
    {
        switch ($this->status) {
            case 'approved_option_1':
                $date = $this->date_1;
                break;
            case 'approved_option_2':
                $date = $this->date_2;
                break;
            default:
                $date = null;
                break;
        }

        $future = $date && Carbon::parse($date) > Carbon::today();

        $disabled = $this->status == 'approving' || $future || (is('admin', ['accountancy']) ? false : ($this->user_id != backpack_user()->id));
        $btn_color = $future || $disabled ? 'btn-default' : ($this->getTreatmentsCountValue() ? 'btn-primary' : 'btn-warning');

        return '
        <a class="btn btn-xs ' . $btn_color . ' ' . ($disabled ? 'disabled' : '') . '" href="/admin/treatment/create?appointment=' . $this->id . '" title="' . __('Add treatment') . '">
        <i class="fa fa-plus"></i> ' . ucfirst(__('treatment')) . '
        </a>';
    }

    public function approveAppointment()
    {
        $disabled = $this->status != 'approving';
        $btn_color = $disabled ? 'btn-default' : 'btn-primary';

        return '
    <div class="btn-group">
      <button type="button" ' . ($disabled ? 'disabled' : '') . ' class="btn btn-xs ' . $btn_color . ' dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-check"></i> ' . __('approve') . '
        <span class="caret" style="margin-left:2px"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-right">
        <li><a href="#" onclick="return approveAppointment(this, ' . $this->id . ', 1)">' . ucfirst(__('option')) . ' 1</a></li>
        <li><a href="#" onclick="return approveAppointment(this, ' . $this->id . ', 2)">' . ucfirst(__('option')) . ' 2</a></li>
      </ul>
    </div>';

    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function process()
    {
        return $this->belongsTo('App\Models\Process', 'process_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function vet1()
    {
        return $this->belongsTo('App\Models\Vet', 'vet_id_1');
    }

    public function vet2()
    {
        return $this->belongsTo('App\Models\Vet', 'vet_id_2');
    }

    public function treatments()
    {
        return $this->hasMany('App\Models\Treatment', 'appointment_id');
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

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->process, true, '');
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getVet1LinkAttribute()
    {
        return $this->getLink($this->vet1, is('admin', 'vet'));
    }

    public function getVet2LinkAttribute()
    {
        return $this->getLink($this->vet2, is('admin', 'vet'));
    }

    public function getStatusWithClassAttribute()
    {
        return "<span class='status'>" . __($this->status) . '</span>';
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

    public function getTreatmentsCountValue()
    {
        return data_get_first($this, 'treatments', 'treatments_count', 0);
    }

    public function getDetailAttribute()
    {
        return "{$this->id} - " . ($this->process ? "{$this->process->name} ({$this->process->id}) - " : '') . ($this->user ? $this->user->name : '') . " ({$this->date_1} / {$this->date_2})";
    }

    public function getApprovedDate()
    {
        switch ($this->status) {
            case 'approved_option_1':return $this->date_1;
            case 'approved_option_2':return $this->date_2;
            default:return null;
        }
    }

    public function getApprovedVet()
    {
        switch ($this->status) {
            case 'approved_option_1':return $this->vet1;
            case 'approved_option_2':return $this->vet2;
            default:return null;
        }
    }

    public function getApprovedVetID()
    {
        return $this->getApprovedVet() ? $this->getApprovedVet()->id : null;
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

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($appointment) {
            if (!is('admin') && $appointment->status != 'approving') {
                abort(403);
            }
        });
    }
}
