<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\PartnerRequest as StoreRequest;
use App\Http\Requests\PartnerRequest as UpdateRequest;
use App\Models\Partner;
use App\Models\Territory;
use App\User;
use DB;
use Illuminate\Http\Request;

/**
 * Class PartnerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PartnerCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Partner');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/partner');
        $this->crud->setEntityNameStrings(__('partner'), __('partners'));

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        $this->crud->setColumns(['image', 'name', 'url', 'categories', 'territories', 'promo_code', 'status', 'user_id']);

        $this->crud->setColumnDetails('image', [
            'name' => 'image',
            'label' => __('Image'),
            'type' => 'image',
            'prefix' => 'uploads/',
            'height' => '50px',
            'width' => '50px',
        ]);

        $this->crud->setColumnDetails('name', [
            'label' => __('Name'),
        ]);

        $this->crud->setColumnDetails('url', [
            'label' => __('Website'),
            'type' => 'url',
            'limit' => 36,
        ]);

        $this->crud->setColumnDetails('status', [
            'label' => __('Status'),
            'type' => 'check',
        ]);

        $this->crud->setColumnDetails('user_id', [
            'name' => 'user',
            'label' => ucfirst(__('volunteer')),
            'type' => 'model_function',
            'limit' => 120,
            'function_name' => 'getUserLinkAttribute',
        ]);

        $this->crud->setColumnDetails('categories', [
            'label' => ucfirst(__('categories')),
            'type' => 'select_multiple',
            'name' => 'categories',
            'entity' => 'categories',
            'attribute' => 'name',
            'model' => "App\Models\PartnerCategory",
        ]);

        $this->crud->setColumnDetails('territories', [
            'label' => ucfirst(__('territories')),
            'type' => 'select_multiple',
            'name' => 'territories',
            'entity' => 'territories',
            'attribute' => 'name',
            'model' => "App\Models\Territory",
        ]);

        $this->crud->setColumnDetails('promo_code', [
            'label' => __('Promo Code'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'benefit',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'phone1',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'phone1_info',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'phone2',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'phone2_info',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'facebook',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'instagram',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'address',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'address_info',
            'visibleInTable' => false,
        ]);

        $this->crud->addColumn([
            'name' => 'notes',
            'visibleInTable' => false,
        ]);

        // ------ CRUD FIELDS
        $this->crud->addFields(['name', 'benefit', 'categories', 'territories', 'email', 'phone1', 'phone1_info', 'phone2', 'phone2_info', 'url', 'facebook', 'instagram', 'address', 'address_info', 'image', 'notes', 'promo_code', 'status']);

        $this->crud->addField([
            'label' => __('Name'),
            'name' => 'name',
        ]);

        $this->crud->addField([
            'label' => __('Benefit'),
            'name' => 'benefit',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('categories')),
            'type' => 'select2_multiple',
            'name' => 'categories',
            'entity' => 'categories',
            'attribute' => 'name',
            'model' => "App\Models\PartnerCategory",
            'pivot' => true,
        ]);

        $this->crud->addField([
            'label' => ucfirst(__('territories')),
            'type' => 'select2_multiple_data_source',
            'name' => 'territories',
            'attribute' => 'name',
            'model' => api()->territorySearch(Territory::DISTRITO, new Request()),
            'pivot' => true,
        ]);

        $this->crud->addField([
            'label' => __('Email'),
            'name' => 'email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone1',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Phone') . ' info',
            'name' => 'phone1_info',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Phone') . ' 2',
            'name' => 'phone2',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Phone') . ' 2 info',
            'name' => 'phone2_info',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Website'),
            'name' => 'url',
            'type' => 'url',
        ]);

        $this->crud->addField([
            'label' => 'Facebook',
            'name' => 'facebook',
            'type' => 'url',
        ]);

        $this->crud->addField([
            'label' => 'Instagram',
            'name' => 'instagram',
            'type' => 'url',
        ]);

        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'label' => __('Address'),
            'name' => 'address_info',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Image'),
            'name' => 'image',
            'type' => 'image',
            'upload' => true,
            'crop' => true,
            'disk' => 'uploads',
        ]);

        $this->crud->addField([
            'label' => __('Notes'),
            'name' => 'notes',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'label' => __('Promo Code'),
            'name' => 'promo_code',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => __('Status'),
            'name' => 'status',
            'type' => 'checkbox',
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

        // Filters
        $this->crud->addFilter([
            'name' => 'territory',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('territory')),
            'placeholder' => __('Select a territory'),
        ],
            api()->territoryList(Territory::DISTRITO | Territory::CONCELHO),
            function ($values) {
                $ids = DB::table('partners_territories')->select('partner_id');
                foreach (json_decode($values) as $value) {
                    $ids->orWhere('territory_id', 'LIKE', "$value%");
                }
                $this->crud->query->whereIn('id', $ids->pluck('partner_id')->toArray());
            });

        $this->crud->addFilter([
            'name' => 'category',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('category')),
            'placeholder' => __('Select a category'),
        ],
            api()->partnerCategoryList(),
            function ($values) {
                $ids = DB::table('partners_categories')->select('partner_id');
                foreach (json_decode($values) as $value) {
                    $ids->orWhere('partner_category_list_id', 'LIKE', "$value%");
                }
                $this->crud->query->whereIn('id', $ids->pluck('partner_id')->toArray());
            });

        $this->crud->addFilter([
            'name' => 'user',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('volunteer')),
            'placeholder' => __('Select a volunteer'),
        ],
            url('admin/user/ajax/filter/' . User::ROLE_VOLUNTEER),
            function ($value) {
                $this->crud->addClause('where', 'user_id', $value);
            });

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'status',
            'label' => __('Status'),
        ],
            EnumHelper::translate('general.check'),
            function ($value) {
                $this->crud->addClause('where', 'status', $value);
            });

        // ------ CRUD ACCESS
        if (!is(['admin', 'friend card'])) {
            $this->crud->denyAccess(['list', 'create', 'update']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['delete']);
        }

        // ------ ADVANCED QUERIES
        $this->crud->query->with(['categories', 'territories', 'user']);

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // add asterisk for fields that are required in PartnerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Add user to the partner
        $request->merge(['user_id' => backpack_user()->id]);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }

    public function sync()
    {
        \Cache::forget('partners');
        \Cache::forget('partners_territories');
    }
}
