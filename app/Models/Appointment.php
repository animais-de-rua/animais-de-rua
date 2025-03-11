<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use CrudTrait;
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'appointments';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'user_id', 'vet_id_1', 'date_1', 'vet_id_2', 'date_2', 'amount_males', 'amount_females', 'amount_other', 'notes', 'notes_deliver', 'notes_collect', 'notes_contact', 'notes_godfather', 'notes_info', 'status'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function addTreatment(): string
    {
        $date = match ($this->status) {
            'approved_option_1' => $this->date_1,
            'approved_option_2' => $this->date_2,
            default => null,
        };

        $future = $date && Carbon::parse($date) > Carbon::today();

        // Disabled by default
        $disabled = true;

        // Enable in case of own appointment or treatments or appointments permission
        if (is('admin', ['treatments', 'appointments']) || $this->user_id == backpack_user()->id) {
            $disabled = false;
        }

        // Always disable in case of not yet approved or future appointment
        if ($this->status == 'approving' || $future) {
            $disabled = true;
        }

        $btn_color = $disabled ? 'btn-default' : ($this->getTreatmentsCountValue() ? 'btn-primary' : 'btn-warning');

        return '
        <a class="btn btn-xs '.$btn_color.' '.($disabled ? 'disabled' : '').'" href="/admin/treatment/create?appointment='.$this->id.'" title="'.__('Add treatment').'">
        <i class="fa fa-plus"></i> '.ucfirst(__('treatment')).'
        </a>';
    }

    public function approveAppointment(): string
    {
        $disabled = $this->status != 'approving';
        $btn_color = $disabled ? 'btn-default' : 'btn-primary';

        return '
    <div class="btn-group">
      <button type="button" '.($disabled ? 'disabled' : '').' class="btn btn-xs '.$btn_color.' dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-check"></i> '.__('approve').'
        <span class="caret" style="margin-left:2px"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-right">
        <li><a href="#" onclick="return approveAppointment(this, '.$this->id.', 1)">'.ucfirst(__('option')).' 1</a></li>
        <li><a href="#" onclick="return approveAppointment(this, '.$this->id.', 2)">'.ucfirst(__('option')).' 2</a></li>
      </ul>
    </div>';

    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * @return BelongsTo<Process>
     */
    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Vet>
     */
    public function vet1(): BelongsTo
    {
        return $this->belongsTo(Vet::class, 'vet_id_1');
    }

    /**
     * @return BelongsTo<Vet>
     */
    public function vet2(): BelongsTo
    {
        return $this->belongsTo(Vet::class, 'vet_id_2');
    }

    /**
     * @return HasMany<Treatment>
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'appointment_id');
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
        return "<span class='status'>".__($this->status).'</span>';
    }

    public function getAnimalsValue()
    {
        $result = '';
        if ($this->amount_males || $this->amount_females) {
            $result .= $this->amount_males.' / '.$this->amount_females;
            if ($this->amount_other) {
                $result .= ' | '.$this->amount_other;
            }

        } elseif ($this->amount_other) {
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
        return "{$this->id} - ".($this->process ? "{$this->process->name} ({$this->process->id}) - " : '').($this->user ? $this->user->name : '')." ({$this->date_1} / {$this->date_2})";
    }

    public function getApprovedDate()
    {
        return match ($this->status) {
            'approved_option_1' => $this->date_1,
            'approved_option_2' => $this->date_2,
            default => null,
        };
    }

    public function getApprovedVet()
    {
        return match ($this->status) {
            'approved_option_1' => $this->vet1,
            'approved_option_2' => $this->vet2,
            default => null,
        };
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

    public function toArray(): array
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
    public static function boot(): void
    {
        parent::boot();
        static::deleting(function ($appointment) {
            /*if (!is('admin') && $appointment->status != 'approving') {
                abort(403);
            }*/
        });
    }
}
