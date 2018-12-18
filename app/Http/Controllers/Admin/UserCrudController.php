<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\User;
use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as OriginalUserCrudController;
use Illuminate\Http\Request;

class UserCrudController extends OriginalUserCrudController
{
    use Permissions;

    public function setup()
    {
        parent::setup();

        // Fields
        $this->crud->addField([
            'label' => __('Phone'),
            'name' => 'phone',
            'type' => 'text',
        ])->afterField('email');

        $this->crud->addField([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'select_from_array',
            'options' => EnumHelper::translate('user.status'),
            'allows_null' => false,
        ])->afterField('phone');

        $this->crud->addField([
            'label' => ucfirst(__('headquarter')),
            'name' => 'headquarter_id',
            'type' => 'select2',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => 'App\Models\Headquarter',
        ])->afterField('status');

        $this->crud->addField([
            'label' => ucfirst(__('friend card')),
            'name' => 'friend_card_modality_id',
            'type' => 'select2',
            'entity' => 'friend_card_modality',
            'attribute' => 'fullname',
            'model' => 'App\Models\FriendCardModality',
        ])->afterField('headquarter_id');

        $this->crud->addColumn([
            'label' => ucfirst(__('headquarter')),
            'name' => 'headquarter',
            'type' => 'select',
            'entity' => 'headquarter',
            'attribute' => 'name',
            'model' => "App\Models\Headquarter",
        ])->afterColumn('email');

        $this->crud->addColumn([
            'label' => ucfirst(__('friend card')),
            'name' => 'friend_card_modality',
            'type' => 'select',
            'entity' => 'friend_card_modality',
            'attribute' => 'value',
            'model' => "App\Models\FriendCardModality",
        ])->afterColumn('headquarter');

        $this->crud->addColumn([
            'label' => __('Status'),
            'name' => 'status',
            'type' => 'check',
        ]);

        // Filters
        $this->crud->addFilter([
            'name' => 'headquarter_id',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('headquarter')),
            'placeholder' => __('Select a headquarter'),
        ],
            api()->headquarterList(),
            function ($values) {
                $this->crud->addClause('whereIn', 'headquarter_id', json_decode($values));
            });

        if (is('admin')) {
            $this->crud->addFilter([
                'name' => 'roles',
                'type' => 'select2_multiple',
                'label' => ucfirst(__('backpack::permissionmanager.roles')),
                'placeholder' => __('Select a role'),
            ],
                EnumHelper::translate('user.roles'),
                function ($values) {
                    $this->crud->query->whereHas('roles', function ($query) use ($values) {
                        $query
                            ->selectRaw('role_id')
                            ->whereIn('role_id', json_decode($values));
                    });
                });

            $this->crud->addFilter([
                'name' => 'permissions',
                'type' => 'select2_multiple',
                'label' => ucfirst(__('backpack::permissionmanager.permission_plural')),
                'placeholder' => __('Select a permission'),
            ],
                EnumHelper::translate('user.permissions'),
                function ($values) {
                    $this->crud->query->whereHas('permissions', function ($query) use ($values) {
                        $query
                            ->selectRaw('permission_id')
                            ->whereIn('permission_id', json_decode($values));
                    });
                });

            $this->crud->addFilter([
                'name' => 'status',
                'type' => 'select2',
                'label' => __('Status'),
                'placeholder' => __('Select a status'),
            ],
                EnumHelper::translate('user.status'),
                function ($value) {
                    $this->crud->addClause('where', 'status', $value);
                });

            $this->crud->addFilter([
                'name' => 'friend_card_modality2',
                'type' => 'simple',
                'label' => ucfirst(__('friend card')),
                'placeholder' => __('Select a modality'),
            ],
                1,
                function ($value) {
                    $this->crud->addClause('whereNotNull', 'friend_card_modality_id');
                });
        }

        $this->crud->addFilter([
            'name' => 'friend_card_modality',
            'type' => 'select2_multiple',
            'label' => ucfirst(__('friend card modality')),
            'placeholder' => __('Select a modality'),
        ],
            api()->friendCardModalitiesList(),
            function ($values) {
                $this->crud->addClause('whereIn', 'friend_card_modality_id', json_decode($values));
            });

        // ------ DATATABLE EXPORT BUTTONS
        $this->crud->enableExportButtons();

        // ------ CRUD ACCESS
        if (!is('admin', 'friend card')) {
            $this->crud->denyAccess(['list']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['create', 'update', 'delete']);

            if (is([], 'friend card')) {
                $this->crud->addClause('whereNotNull', 'friend_card_modality_id');
                $this->crud->removeColumn('roles');
                $this->crud->removeColumn('permissions');
            }
        }
    }

    public function terminal()
    {
        $this->data['title'] = trans('Terminal');
        $this->data['user'] = \Auth::user();

        return view('auth.terminal', $this->data);
    }

    public function terminal_run(Request $request)
    {
        if (admin()) {
            echo shell_exec($request->input('cmd'));
        }
    }

}
