<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\AdoptionRequest as StoreRequest;
use App\Http\Requests\AdoptionRequest as UpdateRequest;
use App\Models\Adoption;
use App\User;
use Carbon\Carbon;

/**
 * Class AdoptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdoptionCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Adoption');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/adoption');
        $this->crud->setEntityNameStrings(__('adoption'), __('adoptions'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD FIELDS
        $this->crud->addFields(['process_id', 'fat_id', 'name', 'name_after', 'age', 'gender', 'sterilized', 'vaccinated', 'processed', 'individual', 'docile', 'abandoned', 'foal', 'images', 'features', 'history', 'adopter_id', 'adoption_date', 'status']);

        $this->crud->addField([
            'label' => ucfirst(__('process')),
            'name' => 'process_id',
            'type' => 'select2_from_ajax',
            'entity' => 'process',
            'attribute' => 'detail',
            'model' => '\App\Models\Process',
            'data_source' => url('admin/process/ajax/search'),
            'placeholder' => __('Select a process'),
            'minimum_input_length' => 2,
            'default' => \Request::get('process') ?: false,
        ]);

        if (is('admin')) {
            $this->crud->addField([
                'label' => ucfirst(__('volunteer')),
                'name' => 'user_id',
                'type' => 'select2_from_ajax',
                'entity' => 'user',
                'attribute' => 'name',
                'model' => '\App\User',
                'placeholder' => '',
                'minimum_input_length' => 2,
                'data_source' => null,
                'attributes' => [
                    'disabled' => 'disabled',
                ],
            ], 'update');
        }

        $this->crud->addField([
            'label' => __('FAT'),
            'name' => 'fat_id',
            'type' => 'select2_from_ajax',
            'entity' => 'fat',
            'attribute' => 'name',
            'model' => '\App\Models\Fat',
            'data_source' => url('admin/fat/ajax/search/'),
            'placeholder' => __('Select a fat'),
            'minimum_input_length' => 2,
            'default' => \Request::get('fat') ?: false,
        ]);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('Name after adoption'),
            'name' => 'name_after',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('age')),
            'name' => 'age',
            'type' => 'age',
            'default' => [0, 0],
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('gender')),
            'name' => 'gender',
            'type' => 'enum',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('sterilized')),
            'name' => 'sterilized',
            'type' => 'checkbox',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('vaccinated')),
            'name' => 'vaccinated',
            'type' => 'checkbox',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('processed')) . '<br /><i>Activar se o animal já tiver sido tratado na AdR.</i>',
            'name' => 'processed',
            'type' => 'checkbox',
        ]);

        $this->crud->addField([
            'label' => __('Individual Animal'),
            'name' => 'individual',
            'type' => 'checkbox',
        ]);

        $this->crud->addField([
            'label' => __('Docile cat of colony'),
            'name' => 'docile',
            'type' => 'checkbox',
        ]);

        $this->crud->addField([
            'label' => __('Abandoned cat of colony'),
            'name' => 'abandoned',
            'type' => 'checkbox',
        ]);

        $this->crud->addField([
            'label' => __('Foal'),
            'name' => 'foal',
            'type' => 'checkbox',
        ]);

        $this->crud->addField([
            'name' => 'images',
            'label' => __('Images'),
            'type' => 'dropzone',
            'upload-url' => '/admin/dropzone/images/adoptions',
            'thumb' => 340,
            'size' => 800,
            'quality' => 82,
        ]);

        $this->crud->addField([
            'label' => __('Features'),
            'type' => 'wysiwyg',
            'name' => 'features',
        ]);

        $this->crud->addField([
            'label' => __('History'),
            'type' => 'wysiwyg',
            'name' => 'history',
        ]);

        $this->separator()->afterField('history');

        $this->crud->addField([
            'label' => __('Adoption Date'),
            'name' => 'adoption_date',
            'type' => 'date',
            'default' => Carbon::today()->toDateString(),
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('adopter')),
            'name' => 'adopter_id',
            'type' => 'select2_from_ajax',
            'entity' => 'adopter',
            'attribute' => 'name',
            'model' => '\App\Models\Adopter',
            'data_source' => url('admin/adopter/ajax/search'),
            'placeholder' => __('Select an adopter'),
            'minimum_input_length' => 2,
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'type' => 'enum',
            'name' => 'status',
            'attributes' => is('admin', 'adoptions') ? [] : [
                'disabled' => 'disabled',
            ],
        ]);

        // ------ CRUD COLUMNS
        $this->crud->addColumns(['id', 'name', 'process_id', 'fat_id', 'age', 'gender', 'sterilized', 'vaccinated', 'processed', 'adoption_date', 'status', 'user_id']);

        $this->crud->setColumnDetails('id', [
            'label' => 'ID',
        ]);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getNameLinkAttribute',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('name', 'like', "%$searchTerm%");
            },
        ]);

        $this->crud->setColumnDetails('process_id', [
            'name' => 'process',
            'label' => ucfirst(__('process')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getProcessLinkAttribute',
        ]);

        if (is('admin') || count(restrictToHeadquarters()) > 1) {
            $this->crud->addColumn([
                'name' => 'headquarter',
                'label' => ucfirst(__('headquarter')),
                'type' => 'model_function',
                'limit' => 120,
                'function_name' => 'getHeadquarterAttribute',
            ])->afterColumn('process_id');
        }

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->setColumnDetails('fat_id', [
            'name' => 'fat',
            'label' => __('FAT'),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getFatLinkAttribute',
        ]);

        $this->crud->setColumnDetails('age', [
            'name' => 'age',
            'label' => ucfirst(__('age')),
            'type' => 'model_function',
            'function_name' => 'getAgeValueAttribute',
        ]);

        $this->crud->setColumnDetails('gender', [
            'type' => 'trans',
            'label' => ucfirst(__('gender')),
        ]);

        $this->crud->setColumnDetails('sterilized', [
            'type' => 'boolean',
            'label' => ucfirst(__('sterilized')),
        ]);

        $this->crud->setColumnDetails('vaccinated', [
            'type' => 'boolean',
            'label' => ucfirst(__('vaccinated')),
        ]);

        $this->crud->setColumnDetails('processed', [
            'type' => 'boolean',
            'label' => ucfirst(__('processed')),
        ]);

        $this->crud->setColumnDetails('adopter_id', [
            'name' => 'adopter',
            'label' => ucfirst(__('adopter')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getAdopterLinkAttribute',
        ]);

        $this->crud->setColumnDetails('adoption_date', [
            'label' => __('Adoption Date'),
            'type' => 'date',
        ]);

        $this->crud->setColumnDetails('status', [
            'type' => 'trans',
            'label' => __('Status'),
        ]);

        // Filtrers
        $this->crud->addFilter([
            'name' => 'process',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('process')),
            'placeholder' => __('Select a process'),
        ],
            url('admin/process/ajax/filter'),
            function ($value) {
                $this->crud->addClause('where', 'process_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'fat',
            'type' => 'select2_ajax',
            'label' => __('FAT'),
            'placeholder' => __('Select a FAT'),
        ],
            url('admin/user/ajax/filter/' . User::FAT),
            function ($value) {
                $this->crud->addClause('where', 'user_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'user',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('volunteer')),
            'placeholder' => __('Select a volunteer'),
        ],
            url('admin/user/ajax/filter/' . User::VOLUNTEER),
            function ($value) {
                $this->crud->addClause('where', 'user_id', $value);
            });

        $this->crud->addFilter([
            'name' => 'gender',
            'type' => 'select2',
            'label' => ucfirst(__('gender')),
            'placeholder' => __('Select a gender'),
        ],
            EnumHelper::translate('animal.gender'),
            function ($value) {
                $this->crud->addClause('where', 'gender', $value);
            });

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'sterilized',
            'label' => ucfirst(__('sterilized')),
        ],
            EnumHelper::translate('general.boolean'),
            function ($value) {
                $this->crud->addClause('where', 'sterilized', $value);
            });

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'vaccinated',
            'label' => ucfirst(__('vaccinated')),
        ],
            EnumHelper::translate('general.boolean'),
            function ($value) {
                $this->crud->addClause('where', 'vaccinated', $value);
            });

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'processed',
            'label' => __('processed'),
        ],
            EnumHelper::translate('general.boolean'),
            function ($value) {
                $this->crud->addClause('where', 'processed', $value);
            });

        $this->crud->addFilter([
            'name' => 'number',
            'type' => 'range',
            'label' => sprintf('%s (%s)', ucfirst(__('age')), ucfirst(__('months'))),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            false,
            function ($value) {
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'age', '>=', (float) $range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'age', '<=', (float) $range->to);
                }
            });

        $this->crud->addFilter([
            'name' => 'adopter',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('adopter')),
            'placeholder' => __('Select an adopter'),
        ],
            url('admin/adopter/ajax/filter'),
            function ($value) {
                $this->crud->addClause('where', 'adopter_id', $value);
            });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'from_to',
            'label' => __('Adoption Date'),
            'format' => 'DD/MM/YYYY',
            'firstDay' => 1,
        ],
            false,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->query->whereRaw('adoption_date >= ? AND adoption_date <= DATE_ADD(?, INTERVAL 1 DAY)', [$dates->from, $dates->to]);
            });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2_multiple',
            'label' => __('Status'),
            'placeholder' => __('Select a status'),
        ],
            EnumHelper::translate('adoption.status'),
            function ($values) {
                $this->crud->addClause('whereIn', 'status', json_decode($values));
            });

        // ------ CRUD ACCESS
        $this->crud->allowAccess('show');
        $this->crud->removeButton('show');

        if (!is(['admin', 'volunteer'])) {
            $this->crud->denyAccess(['list', 'show']);
        }

        if (!is('admin', 'adoptions')) {
            $this->crud->denyAccess(['create', 'update']);
        }

        if (!is('admin')) {
            $this->crud->addClause('whereHas', 'process', function ($query) {
                $headquarters = restrictToHeadquarters();
                $query->whereIn('headquarter_id', $headquarters ?: []);
            })->get();

            $this->crud->denyAccess(['delete']);
        }

        // ------ ADVANCED QUERIES
        $this->crud->query->with(['process.headquarter', 'user', 'fat']);

        $this->crud->addClause('orderBy', 'id', 'DESC');

        // Buttons
        if (is('admin', 'adoptions')) {
            $this->crud->addButtonFromModelFunction('line', 'add_adopter', 'addAdopter', 'beginning');
        }

        // add asterisk for fields that are required in AdoptionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->setColumnDetails('history', [
            'label' => __('History'),
        ]);

        $this->crud->addColumn([
            'name' => 'features',
            'label' => __('Features'),
            'type' => 'textarea',
        ]);

        $this->crud->setColumnDetails('images', [
            'name' => 'images',
            'label' => __('Images'),
            'type' => 'upload_multiple_image',
        ]);

        $this->crud->addColumn([
            'name' => 'individual',
            'label' => __('Individual Animal'),
            'type' => 'boolean',
        ]);

        $this->crud->addColumn([
            'name' => 'docile',
            'label' => __('Docile cat of colony'),
            'type' => 'boolean',
        ]);

        $this->crud->addColumn([
            'name' => 'abandoned',
            'label' => __('Abandoned cat of colony'),
            'type' => 'boolean',
        ]);

        $this->crud->addColumn([
            'name' => 'foal',
            'label' => __('Foal'),
            'type' => 'boolean',
        ])->afterColumn('abandoned');

        return $content;
    }

    public function store(StoreRequest $request)
    {
        // Add user
        $request->merge(['user_id' => backpack_user()->id]);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }

    public function sync()
    {
        \Cache::forget('adoptions_count');
        \Cache::forget('adoptions_districts_adoption');
    }
}
