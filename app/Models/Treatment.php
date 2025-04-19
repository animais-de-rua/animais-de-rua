<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\TreatmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    use CrudTrait;
    /** @use HasFactory<TreatmentFactory> */
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'treatment_type_id',
        'vet_id',
        'affected_animals',
        'affected_animals_new',
        'user_id',
        'expense',
        'date',
        'status',
        'notes',
    ];

    public function customUpdateButton()
    {
        $disabled = ! is('admin', 'treatments') || (is('volunteer', 'treatments') && $this->status == 'approved');
        if (is('admin')) {
            $disabled = false;
        }

        return '<a href='.url("admin/treatment/{$this->id}/edit")." class='btn btn-xs btn-default ".($disabled ? 'disabled' : '')."'><i class='fa fa-edit'></i> ".__('backpack::crud.edit').'</a>';
    }

    public function approveTreatment()
    {
        $disabled = $this->status == 'approved';
        $btn_color = $disabled ? 'btn-default' : 'btn-primary';

        return '
      <button type="button" '.($disabled ? 'disabled' : '').' class="btn btn-xs '.$btn_color.' dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="return approveTreatment(this, '.$this->id.')">
        <i class="fa fa-check"></i> '.__('approve').'
      </button>';

    }

    /**
     * @return BelongsTo<Appointment, $this>
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    /**
     * @return BelongsTo<Vet, $this>
     */
    public function vet(): BelongsTo
    {
        return $this->belongsTo(Vet::class, 'vet_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<TreatmentType, $this>
     */
    public function treatment_type(): BelongsTo
    {
        return $this->belongsTo(TreatmentType::class, 'treatment_type_id');
    }

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->appointment ? $this->appointment->process : null, true, '', 'id_name');
    }

    public function getVetLinkAttribute()
    {
        return $this->getLink($this->vet, is('admin', 'vet'));
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getFullExpenseAttribute()
    {
        return $this->expense.'€';
    }

    public function getTreatmentTypeNameAttribute()
    {
        return $this->treatment_type->name;
    }

    public function getFullStatusAttribute()
    {
        return __($this->status);
    }

    public function getStatusWithClassAttribute()
    {
        return "<span class='status'>".__($this->status).'</span>';
    }

    public function getAffectedAnimalsNew($appointment_id)
    {
        return Treatment::selectRaw('SUM(affected_animals_new) as total')->where('appointment_id', $appointment_id)->first()->total;
    }
}
