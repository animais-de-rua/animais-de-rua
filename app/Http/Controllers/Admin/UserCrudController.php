<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\Permissions;
use App\Http\Requests\UserStoreRequest as StoreRequest;
use App\Http\Requests\UserUpdateRequest as UpdateRequest;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserCrudController extends CrudController
{
    use Permissions;

    public function setup()
    {
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.user'), trans('backpack::permissionmanager.users'));
        $this->crud->setRoute(backpack_url('user'));

        // Original Columns
        $this->crud->setColumns([
            [
                'name' => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type' => 'email',
            ],
            [
                'label' => trans('backpack::permissionmanager.roles'),
                'type' => 'select_multiple',
                'name' => 'roles',
                'entity' => 'roles',
                'attribute' => 'name',
                'model' => config('permission.models.role'),
            ],
            [
                'label' => trans('backpack::permissionmanager.extra_permissions'),
                'type' => 'select_multiple',
                'name' => 'permissions',
                'entity' => 'permissions',
                'attribute' => 'name',
                'model' => config('permission.models.permission'),
            ],
        ]);

        // Original Fields
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type' => 'email',
            ],
            [
                'name' => 'password',
                'label' => trans('backpack::permissionmanager.password'),
                'type' => 'password',
            ],
            [
                'name' => 'password_confirmation',
                'label' => trans('backpack::permissionmanager.password_confirmation'),
                'type' => 'password',
            ],
            [
                'label' => trans('backpack::permissionmanager.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type' => 'checklist_dependency',
                'name' => 'roles_and_permissions',
                'subfields' => [
                    'primary' => [
                        'label' => trans('backpack::permissionmanager.roles'),
                        'name' => 'roles',
                        'entity' => 'roles',
                        'entity_secondary' => 'permissions',
                        'attribute' => 'name',
                        'model' => config('permission.models.role'),
                        'pivot' => true,
                        'number_columns' => 3,
                    ],
                    'secondary' => [
                        'label' => ucfirst(trans('backpack::permissionmanager.permission_singular')),
                        'name' => 'permissions',
                        'entity' => 'permissions',
                        'entity_primary' => 'roles',
                        'attribute' => 'name',
                        'model' => config('permission.models.permission'),
                        'pivot' => true,
                        'number_columns' => 3,
                    ],
                ],
            ],
        ]);

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
            'type' => 'select2_multiple_data_source',
            'name' => 'headquarters',
            'attribute' => 'name',
            'model' => api()->headquarterSearch(),
            'pivot' => true,
        ])->afterField('status');

        $this->crud->addField([
            'label' => ucfirst(__('friend card')),
            'name' => 'friend_card_modality_id',
            'type' => 'select2',
            'entity' => 'friend_card_modality',
            'attribute' => 'fullname',
            'model' => 'App\Models\FriendCardModality',
        ])->afterField('headquarters');

        if (is('admin')) {
            $this->crud->addField([
                'label' => __('Notes'),
                'type' => 'textarea',
                'name' => 'notes',
            ])->afterField('friend_card_modality_id');
        }

        $this->crud->addColumn([
            'label' => ucfirst(__('headquarter')),
            'name' => 'headquarter',
            'type' => 'select',
            'entity' => 'headquarters',
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
                $this->crud->addClause('whereHas', 'headquarters', function ($query) use ($values) {
                    $query->whereIn('headquarter_id', json_decode($values) ?: []);
                })->get();
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

        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        // ------ CRUD ACCESS
        if (!is(['admin', 'friend card'])) {
            $this->crud->denyAccess(['list', 'create', 'update']);
        }

        if (!is('admin')) {
            $this->crud->denyAccess(['delete']);

            $this->crud->removeField('roles_and_permissions');

            $hiddenAttr = [
                'type' => 'hidden',
            ];

            $disabledAttr = [
                'attributes' => [
                    'disabled' => 'disabled',
                ],
            ];

            $this->crud->modifyField('name', $disabledAttr, 'update');
            $this->crud->modifyField('email', $disabledAttr, 'update');
            $this->crud->modifyField('phone', $disabledAttr, 'update');
            $this->crud->modifyField('headquarters', $disabledAttr, 'update');
            $this->crud->modifyField('password', $disabledAttr, 'update');
            $this->crud->modifyField('password_confirmation', $disabledAttr, 'update');

            if (is('friend card')) {
                $this->crud->addClause('whereNotNull', 'friend_card_modality_id');
                $this->crud->removeColumn('roles');
                $this->crud->removeColumn('permissions');
            }
        }
    }

    public function showDetailsRow($id)
    {
        $user = User::select(['notes'])->find($id);

        return "<div style='margin:5px 8px'>
                <p style='white-space: pre-wrap;'><i>" . __('Notes') . "</i> : $user->notes</p>
            </div>";
    }

    public function terminal()
    {
        return view('auth.terminal', []);
    }

    public function terminal_run(Request $request)
    {
        if (admin()) {
            echo shell_exec($request->input('cmd'));
        }
    }

    public function symlink()
    {
        return view('auth.symlink', []);
    }

    public function symlink_run(Request $request)
    {
        if (admin()) {
            echo symlink(base_path() . $request->input('target'), base_path() . $request->input('link')) ? 'Success' : 'Error';
        }
    }

    public function store(StoreRequest $request)
    {
        $this->handleInputs($request);

        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        $this->handleInputs($request);

        return parent::updateCrud($request);
    }

    protected function handleInputs(Request $request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }
    }
}
