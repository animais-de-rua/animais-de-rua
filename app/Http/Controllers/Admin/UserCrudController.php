<?php

namespace App\Http\Controllers\Admin;

use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as OriginalUserCrudController;
use App\Helpers\EnumHelper;
use Illuminate\Http\Request;
use App\Models\Headquarter;
use App\User;

class UserCrudController extends OriginalUserCrudController
{
    public function setup()
    {
        parent::setup();

        // Fields
        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone',
            'type' => 'text'
        ])->afterField('email');

        $this->crud->addField([
            'name' => 'status',
            'label' => __("Status"),
            'type' => 'select_from_array',
            'options' => EnumHelper::translate('user.status'),
            'allows_null' => false,
        ])->afterField('phone');

        $this->crud->addField([
            'label' => ucfirst(__("headquarter")),
            'name' => 'headquarter_id',
            'type' => 'select2',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => 'App\Models\Headquarter'
        ])->afterField('status');

        $this->crud->addColumn([
            'label' => ucfirst(__('headquarter')),
            'type' => "select",
            'entity' => 'headquarter',
            'attribute' => "name",
            'model' => "App\Models\Headquarter"
        ])->afterColumn('email');

        $this->crud->addColumn([
            'label' => __("Status"),
            'name' => 'status',
            'type' => "check"
        ]);

        // Filters
        $this->crud->addFilter([
            'name' => 'headquarter_id',
            'type' => 'select2_multiple',
            'label'=> ucfirst(__("headquarter")),
            'placeholder' => __('Select a headquarter')
        ],
        api()->headquarterList(),
        function($values) {
            $this->crud->addClause('whereIn', 'headquarter_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'roles',
            'type' => 'select2_multiple',
            'label'=> ucfirst(__('backpack::permissionmanager.roles')),
            'placeholder' => __('Select a role')
        ],
        EnumHelper::translate('user.roles'),
        function($values) {
            $this->crud->query->whereHas('roles', function ($query) use ($values) {
                $query
                    ->selectRaw("role_id")
                    ->whereIn('role_id', json_decode($values));
            });
        });

        $this->crud->addFilter([
            'name' => 'permissions',
            'type' => 'select2_multiple',
            'label'=> ucfirst(__('backpack::permissionmanager.permission_plural')),
            'placeholder' => __('Select a permission')
        ],
        EnumHelper::translate('user.permissions'),
        function($values) {
            $this->crud->query->whereHas('permissions', function ($query) use ($values) {
                $query
                    ->selectRaw("permission_id")
                    ->whereIn('permission_id', json_decode($values));
            });
        });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'select2',
            'label'=> __("Status"),
            'placeholder' => __('Select a status')
        ],
        EnumHelper::translate('user.status'),
        function($value) {
            $this->crud->addClause('where', 'status', $value);
        });

        $this->crud->enableExportButtons();
    }

    public function terminal()
    {
        $this->data['title'] = trans('Terminal');
        $this->data['user'] = \Auth::user();

        return view('auth.terminal', $this->data);
    }

    public function terminal_run(Request $request)
    {
        if(admin()) {
            echo shell_exec($request->input('cmd'));
        }
    }

}
